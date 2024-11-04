<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ReserveRepository;
use App\Http\Requests\{ReserveRequest, GuestRequest, DailyRequest, PaymentRequest};
use App\Models\Daily;
use App\Models\Reserve;
use App\Repositories\Eloquent\{GuestRepository, PaymentRepository, DailyRepository};

class ReserveController extends Controller
{
    protected $reserveRepository;

    public function __construct(ReserveRepository $reserveRepository)
    {
        $this->reserveRepository = $reserveRepository;
        $this->middleware('auth:sanctum');
    }
    
    /**
     * Store a newly created resource in storage.
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
            $dailies = Daily::where('reserve_id', $currentReserve->reserve_id)->get();
            
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

        if ($validatedDaily['date'] < $reserve['check_in'] || $validatedDaily['date'] > $reserve['check_out']) {
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
