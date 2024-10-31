<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    protected $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('attributes')) {
            $attributes = explode(',', $request->query('attributes'));
            $rooms = Room::select($attributes)->get();
        } else {
            $rooms = Room::all();
        }
    
        return response()->json($rooms, 200);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate($this->room->rules(), $this->room->messages());

        $room = Room::create($validate);

        return response()->json($room, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $room = Room::find($id);
    
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }
    
        return response()->json($room, 200);
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);
        
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $validate = $request->validate($room->rules(true), $room->messages());

        $room->update($validate);

        return response()->json($room, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);
     
        if (!$room) {
            return response()->json(["error" => "Quarto não registrado"], 404);
        }

        $room->delete();

        return response()->json(['success' => 'Quarto deletado com sucesso'], 200);
    }
}