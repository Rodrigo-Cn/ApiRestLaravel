<?php

namespace App\Repositories\Contracts;

interface GuestRepositoryInterface
{
    public function getAllGuests();
    public function getGuestById($id);
    public function createGuest(array $data);
    public function updateGuest($id, array $data);
    public function deleteGuest($id);
}
