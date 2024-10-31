<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'name' => 'required|string|max:100',
        ]);

        $room = Room::create($validate);

        return response()->json($room, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Room::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room =  Room::findOrFail($id);

        $validate = $request->validate([
            'name' => 'sometimes|string|max:100',
        ]);

        $room->update($validate);

        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room =  Room::findOrFail($id);

        $room->delete();

        return response()->json(['message' => 'Quarto deletado com sucesso'], 200);
    }
}
