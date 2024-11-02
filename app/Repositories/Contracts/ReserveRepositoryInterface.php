<?php

namespace App\Repositories\Contracts;

interface ReserveRepositoryInterface
{
    public function getAllReserves($atributtes = null);
    public function getReserveById($id);
    public function createReserve(array $data);
    public function updateReserve($id, array $data);
    public function deleteReserve($id);
}
