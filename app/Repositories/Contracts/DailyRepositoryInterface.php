<?php

namespace App\Repositories\Contracts;

interface DailyRepositoryInterface
{
    public function getAllDailies();
    public function getDailyById($id);
    public function createDaily(array $data);
    public function updateDaily($id, array $data);
    public function deleteDaily($id);
}
