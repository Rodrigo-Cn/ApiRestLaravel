<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ReserveRepository;
use App\Http\Requests\ReserveRequest;

class ReserveController extends Controller
{
    protected $reserveRepository;

    public function __construct(ReserveRepository $reserveRepository)
    {
        $this->reserveRepository = $reserveRepository;
        $this->middleware('auth:sanctum')->only('store');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReserveRequest $request)
    {
        $validatedReserve = $request->validated();
        $reserve = $this->reserveRepository->createReserve($validatedReserve);

        return response()->json($reserve, 201);
    }
}
