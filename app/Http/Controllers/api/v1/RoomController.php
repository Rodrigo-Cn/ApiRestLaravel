<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Http\Requests\RoomRequest;
use OpenApi\Annotations as OA;

class RoomController extends Controller
{
    protected $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        #$this->middleware('auth:sanctum')->only('store', 'update', 'destroy');
    }

    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     summary="Retorna todos os quartos",
     *     tags={"Rooms"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de quartos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Room"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $attributes = $request->has('attributes') ? explode(',', $request->query('attributes')) : null;
        $rooms = $this->roomRepository->getAllRooms($attributes);

        Log::info('Listagem de quartos concluída.', ['total_quartos' => count($rooms)]);

        return response()->json($rooms, 200);
    }    

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     summary="Cria um novo quarto",
     *     tags={"Rooms"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quarto criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     )
     * )
     */
    public function store(RoomRequest $request)
    {
        $validatedRoom = $request->validated();
        $room = $this->roomRepository->createRoom($validatedRoom);

        Log::info('Novo quarto criado com sucesso.', ['room_id' => $room->id]);

        return response()->json($room, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     summary="Retorna um quarto específico",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do quarto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quarto encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quarto não encontrado"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     summary="Atualiza um quarto existente",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do quarto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quarto atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quarto não encontrado"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     summary="Remove um quarto",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do quarto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quarto deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quarto não encontrado"
     *     )
     * )
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
