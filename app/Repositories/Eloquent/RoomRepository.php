<?php

namespace App\Repositories\Eloquent;
use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAllRooms($attributes = null)
    {
        if ($attributes != null) {
            $rooms = Room::select($attributes)->get();
        } else {
            $rooms = Room::all();
        }

        return $rooms;
    }

    public function getRoomById($id)
    {
        return Room::find($id);
    }

    public function createRoom(array $data)
    {
        return Room::create($data);
    }

    public function updateRoom($id, array $data)
    {
        $room = $this->getRoomById($id);
        $room->update($data);
        return $room;
    }

    public function deleteRoom($id)
    {
        $room = $this->getRoomById($id);
        $room->delete();
        return $room;
    }
}
