<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ReserveRepository;
use App\Http\Requests\ReserveRequest;

class ReserveController extends Controller
{
    protected $reserveRepository;

    public function __construct(ReserveRepository $reserveRepository)
    {
        $this->reserveRepository = $reserveRepository;
        $this->middleware('auth:sanctum')->only('store');
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
    public function storeDaily(string $id){

    }
    public function storePayment(string $id){

    }
    public function storeGuest(string $id){

    }
}
