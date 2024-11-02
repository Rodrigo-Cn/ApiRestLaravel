<?php

namespace App\Repositories\Eloquent;
use App\Models\Reserve;
use App\Repositories\Contracts\ReserveRepositoryInterface;

class ReserveRepository implements ReserveRepositoryInterface
{
    public function getAllReserves($attributes = null)
    {
        if ($attributes != null) {
            $reserves = Reserve::select($attributes)->get();
        } else {
            $reserves = Reserve::all();
        }

        return $reserves;
    }

    public function getReserveById($id)
    {
        return Reserve::find($id);
    }

    public function createReserve(array $data)
    {
        return Reserve::create($data);
    }

    public function updateReserve($id, array $data)
    {
        $reserve = $this->getReserveById($id);
        $reserve ->update($data);
        return $reserve;
    }

    public function deleteReserve($id)
    {
        $reserve = $this->getReserveById($id);
        $reserve ->delete();
        return $reserve ;
    }
}
