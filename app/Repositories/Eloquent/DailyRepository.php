<?php

namespace App\Repositories\Eloquent;
use App\Models\Daily;
use App\Repositories\Contracts\DailyRepositoryInterface;

class DailyRepository implements DailyRepositoryInterface
{
    public function getAllDailies()
    {
        return Daily::all();
    }

    public function getDailyById($id)
    {
        return Daily::find($id);
    }

    public function createDaily(array $data)
    {
        return Daily::create($data);
    }

    public function updateDaily($id, array $data)
    {
        $daily = $this->getDailyById($id);
        $daily ->update($data);
        return $daily;
    }

    public function deleteDaily($id)
    {
        $daily = $this->getDailyById($id);
        $daily ->delete();
        return $daily;
    }
}
