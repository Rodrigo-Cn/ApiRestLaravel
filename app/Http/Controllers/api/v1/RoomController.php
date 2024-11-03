<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Http\Requests\RoomRequest;

class RoomController extends Controller
{
    protected $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->middleware('auth:sanctum')->only('store', 'update', 'delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attributes = $request->has('attributes') ? explode(',', $request->query('attributes')) : null;
        $rooms = $this->roomRepository->getAllRooms($attributes);

        Log::info('Listagem de quartos concluída.', ['total_quartos' => count($rooms)]);

        return response()->json($rooms, 200);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRequest $request)
    {
        $validatedRoom = $request->validated();
        $room = $this->roomRepository->createRoom($validatedRoom);

        Log::info('Novo quarto criado com sucesso.', ['room_id' => $room->id]);

        return response()->json($room, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $room = $this->roomRepository->getRoomById($id);
    
        if (!$room) {
            Log::warning('Quarto não encontrado.', ['room_id' => $id]);
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        Log::info('Quarto encontrado.', ['room_id' => $room->id]);
    
        return response()->json($room, 200);
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRequest $request, string $id)
    {
        Log::info('Iniciando update de quarto.', ['room_id' => $id, 'user_id' => $request->user()->id]);

        $room = $this->roomRepository->getRoomById($id);
        
        if (!$room) {
            Log::warning('Tentativa de update falhou. Quarto não encontrado.', ['room_id' => $id]);
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $validatedRoom = $request->validated();
        $this->roomRepository->updateRoom($id, $validatedRoom);

        Log::info('Quarto atualizado com sucesso.', ['room_id' => $id]);

        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = $this->roomRepository->getRoomById($id);
     
        if (!$room) {
            Log::warning('Tentativa de exclusão falhou. Quarto não encontrado.', ['room_id' => $id]);
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $this->roomRepository->deleteRoom($id);

        Log::info('Quarto deletado com sucesso.', ['room_id' => $id]);

        return response()->json(['success' => 'Quarto deletado com sucesso'], 200);
    }
}
