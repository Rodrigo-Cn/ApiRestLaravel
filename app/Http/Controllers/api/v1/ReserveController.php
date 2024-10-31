<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserve;
use App\Models\Guest;
use App\Models\Daily;
use App\Models\Payment;
use App\Models\ReserveGuest;

class ReserveController extends Controller
{
    protected $reserve;

    public function __construct(Reserve $reserve)
    {
        $this->reserve = $reserve;
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->reserve->rules(), $this->reserve->messages());

        $reservationExists = Reserve::where('room_id', $validatedData['room_id'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('check_in', [$validatedData['check_in'], $validatedData['check_out']])
                      ->orWhereBetween('check_out', [$validatedData['check_in'], $validatedData['check_out']]);
            })
            ->exists();

        if ($reservationExists) {
            return response()->json(['error' => 'Quarto não disponível para as datas selecionadas.'], 409);
        }

        $guest = Guest::updateOrCreate(
            ['phone' => $validatedData['guest']['phone']],
            [
                'first_name' => $validatedData['guest']['first_name'],
                'last_name' => $validatedData['guest']['last_name'],
            ]
        );

        $reserve = Reserve::create([
            'hotel_id' => $validatedData['hotel_id'],
            'room_id' => $validatedData['room_id'],
            'guest_id' => $guest->id,
            'check_in' => $validatedData['check_in'],
            'check_out' => $validatedData['check_out'],
            'total' => 0,
        ]);

        ReserveGuest::create([
            'reserve_id' => $reserve->reserve_id,
            'guest_id' => $guest->id,
        ]);

        $total = 0;
        foreach ($validatedData['daily'] as $dailyData) {
            Daily::create([
                'reserve_id' => $reserve->reserve_id,
                'date' => $dailyData['date'],
                'value' => $dailyData['value'],
            ]);
            $total += $dailyData['value'];
        }

        if (isset($validatedData['payments'])) {
            foreach ($validatedData['payments'] as $paymentData) {
                Payment::create([
                    'reserve_id' => $reserve->reserve_id,
                    'method' => $paymentData['method'],
                    'value' => $paymentData['value'],
                ]);
                $total -= $paymentData['value'];
            }
        }

        $reserve->update(['total' => $total]);

        return response()->json($reserve, 201);
    }
}
