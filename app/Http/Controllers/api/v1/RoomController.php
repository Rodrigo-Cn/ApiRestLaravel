<?php

namespace App\Http\Controllers\api\v1;

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
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attributes = $request->has('attributes') ? explode(',', $request->query('attributes')) : null;
        $rooms = $this->roomRepository->getAllRooms($attributes);

        return response()->json($rooms, 200);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRequest $request)
    {
        $validated = $request->validated();
        $room = $this->roomRepository->createRoom($validated);

        return response()->json($room, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $room = $this->roomRepository->getRoomById($id);
    
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }
    
        return response()->json($room, 200);
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRequest $request, string $id)
    {
        $room = $this->roomRepository->getRoomById($id);
        
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $validatedData = $request->validated();
        $this->roomRepository->updateRoom($id, $validatedData);

        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = $this->roomRepository->getRoomById($id);
     
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $this->roomRepository->deleteRoom($id);

        return response()->json(['success' => 'Quarto deletado com sucesso'], 200);
    }
}
