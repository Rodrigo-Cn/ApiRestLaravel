<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ReserveRepository;
use App\Http\Requests\{ReserveRequest, GuestRequest, DailyRequest, PaymentRequest};
use App\Models\Daily;
use App\Models\Reserve;
use App\Repositories\Eloquent\{GuestRepository, PaymentRepository, DailyRepository};
use OpenApi\Annotations as OA;

class ReserveController extends Controller
{
    protected $reserveRepository;

    public function __construct(ReserveRepository $reserveRepository)
    {
        $this->reserveRepository = $reserveRepository;
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Post(
     *     path="/api/reserves",
     *     summary="Cria uma nova reserva",
     *     tags={"Reserves"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_id", "check_in", "check_out"},
     *             @OA\Property(property="room_id", type="integer", example=1, description="ID do quarto"),
     *             @OA\Property(property="check_in", type="string", format="date", example="2024-11-01", description="Data de check-in"),
     *             @OA\Property(property="check_out", type="string", format="date", example="2024-11-05", description="Data de check-out"),
     *             @OA\Property(property="total", type="number", format="float", example=500.00, description="Valor total da reserva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID da reserva"),
     *             @OA\Property(property="room_id", type="integer", example=1, description="ID do quarto"),
     *             @OA\Property(property="check_in", type="string", format="date", example="2024-11-01", description="Data de check-in"),
     *             @OA\Property(property="check_out", type="string", format="date", example="2024-11-05", description="Data de check-out"),
     *             @OA\Property(property="total", type="number", format="float", example=500.00, description="Valor total da reserva")
     *         )
     *     ),
     * )
     */
    public function store(ReserveRequest $request)
    {
        Log::info('Iniciando processo de criação de reserva.', ['user_id' => $request->user()->id]);

        try {
            $validatedReserve = $request->validated();
            Log::info('Dados da reserva validados com sucesso.', ['data' => $validatedReserve]);

            $reserve = $this->reserveRepository->createReserve($validatedReserve);
            Log::info('Reserva criada com sucesso.', ['reserve_id' => $reserve->id]);

            return response()->json($reserve, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar a reserva.', [
                'user_id' => $request->user()->id,
                'message' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro ao criar a reserva.'], 500);
        }
    }

        /**
     * @OA\Post(
     *     path="/api/reserves/{id}/guests",
     *     summary="Adiciona um hóspede a uma reserva",
     *     tags={"Reserves"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "document"},
     *             @OA\Property(property="name", type="string", example="João Silva", description="Nome do hóspede"),
     *             @OA\Property(property="document", type="string", example="12345678900", description="Documento do hóspede")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Hóspede adicionado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID do hóspede"),
     *             @OA\Property(property="name", type="string", example="João Silva", description="Nome do hóspede"),
     *             @OA\Property(property="document", type="string", example="12345678900", description="Documento do hóspede"),
     *             @OA\Property(property="reserve_id", type="integer", example=1, description="ID da reserva associada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva não registrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Reserva não registrada")
     *         )
     *     )
     * )
     */
    public function storeGuest(GuestRequest $request, string $id)
    {
        $validatedGuest = $request->validated();
        $reserve = $this->reserveRepository->getReserveById($id);
    
        if (!$reserve) {
            Log::warning('Tentativa de adicionar hóspede a uma reserva inexistente.', ['reserve_id' => $id]);
            return response()->json(["error" => "Reserva não registrada"], 404);
        }
    
        $validatedGuest['reserve_id'] = $id;
        $guestRepository = new GuestRepository;
        $guest = $guestRepository->createGuest($validatedGuest);
    
        Log::info('Hóspede criado com sucesso.', ['guest_id' => $guest->id, 'reserve_id' => $id]);

        return response()->json($guest, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/reserves/{id}/payments",
     *     summary="Adiciona um pagamento a uma reserva",
     *     tags={"Reserves"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value"},
     *             @OA\Property(property="value", type="number", format="float", example=150.00, description="Valor do pagamento")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pagamento adicionado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID do pagamento"),
     *             @OA\Property(property="value", type="number", format="float", example=150.00, description="Valor do pagamento"),
     *             @OA\Property(property="reserve_id", type="integer", example=1, description="ID da reserva associada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva não registrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Reserva não registrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="O valor do pagamento não pode ser maior que o total da reserva",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="O valor do pagamento não pode ser maior que o total da reserva.")
     *         )
     *     )
     * )
     */
    public function storePayment(PaymentRequest $request, string $id)
    {
        $validatedPayment = $request->validated();
        $reserve = $this->reserveRepository->getReserveById($id);
    
        if (!$reserve) {
            Log::warning('Tentativa de adicionar pagamento a uma reserva inexistente.', ['reserve_id' => $id]);
            return response()->json(["error" => "Reserva não registrada"], 404);
        }
    
        if ($reserve->total < $validatedPayment['value']) {
            Log::warning('Tentativa de pagamento com valor superior ao total da reserva.', [
                'reserve_id' => $id,
                'total' => $reserve->total,
                'payment_value' => $validatedPayment['value'],
            ]);
            return response()->json(["error" => "O valor do pagamento não pode ser maior que o total da reserva."], 400);
        }
    
        $validatedPayment['reserve_id'] = $id;
    
        $paymentRepository = new PaymentRepository;
        $payment = $paymentRepository->createPayment($validatedPayment);
    
        $reserve->total -= $validatedPayment['value'];
        $reserve->save();

        Log::info('Pagamento criado com sucesso.', ['payment_id' => $payment->id, 'reserve_id' => $id]);

        return response()->json($payment, 201);
    }
    
    /**
     * @OA\Post(
     *     path="/api/reserves/{id}/dailies",
     *     summary="Adiciona uma diária a uma reserva",
     *     tags={"Reserves"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da reserva",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "value"},
     *             @OA\Property(property="date", type="string", format="date", example="2024-11-15", description="Data da diária"),
     *             @OA\Property(property="value", type="number", format="float", example=100.00, description="Valor da diária")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Diária adicionada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID da diária"),
     *             @OA\Property(property="date", type="string", format="date", example="2024-11-15", description="Data da diária"),
     *             @OA\Property(property="value", type="number", format="float", example=100.00, description="Valor da diária"),
     *             @OA\Property(property="reserve_id", type="integer", example=1, description="ID da reserva associada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva não registrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Reserva não registrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflito ao adicionar diária já existente",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Já existe uma diária registrada para essa data em uma das reservas desse quarto.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="A data da diária deve estar entre o check-in e check-out da reserva",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="A data da diária deve estar entre o check-in e check-out da reserva.")
     *         )
     *     )
     * )
     */
    public function storeDaily(DailyRequest $request, string $id)
    {
        $validatedDaily = $request->validated();
        $reserve = $this->reserveRepository->getReserveById($id);

        if (!$reserve) {
            Log::warning('Tentativa de adicionar diária a uma reserva inexistente.', ['reserve_id' => $id]);
            return response()->json(["error" => "Reserva não registrada"], 404);
        }

        $reserves = Reserve::where('room_id', $reserve->room_id)->get();

        foreach ($reserves as $currentReserve) {
            $dailies = Daily::where('reserve_id', $currentReserve->id)->get();
            
            foreach ($dailies as $daily) {
                if ($daily->date === $validatedDaily['date']) {
                    Log::warning('Tentativa de adicionar uma diária já existente para essa data.', [
                        'reserve_id' => $id,
                        'date' => $validatedDaily['date']
                    ]);
                    return response()->json(['error' => 'Já existe uma diária registrada para essa data em uma das reservas desse quarto.'], 409);
                }
            }
        }

        if ($validatedDaily['date'] < $reserve->check_in || $validatedDaily['date'] > $reserve->check_out) {
            Log::warning('Tentativa de adicionar diária fora do intervalo de check-in e check-out.');
            return response()->json(['error' => 'A data da diária deve estar entre o check-in e check-out da reserva.'], 400);
        }

        $validatedDaily['reserve_id'] = $id;
        $dailyRepository = new DailyRepository;
        $daily = $dailyRepository->createDaily($validatedDaily);

        $reserve->total += $validatedDaily['value'];
        $reserve->save();

        Log::info('Diária criada com sucesso.', ['daily_id' => $daily->id, 'reserve_id' => $id]);

        return response()->json($daily, 201);
    }
}
