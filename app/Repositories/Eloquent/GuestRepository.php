<?php

namespace App\Repositories\Eloquent;
use App\Models\Guest;
use App\Repositories\Contracts\GuestRepositoryInterface;

class GuestRepository implements GuestRepositoryInterface
{
    public function getAllGuests()
    {
        return Guest::all();
    }

    public function getGuestById($id)
    {
        return Guest::find($id);
    }

    public function createGuest(array $data)
    {
        return Guest::create($data);
    }

    public function updateGuest($id, array $data)
    {
        $guest = $this->getGuestById($id);
        $guest ->update($data);
        return $guest;
    }

    public function deleteGuest($id)
    {
        $guest = $this->getGuestById($id);
        $guest->delete();
        return $guest;
    }
}
