<?php

namespace App\Repositories\Contracts;

interface RoomRepositoryInterface
{
    public function getAllRooms($atributtes = null);
    public function getRoomById($id);
    public function createRoom(array $data);
    public function updateRoom($id, array $data);
    public function deleteRoom($id);
}
