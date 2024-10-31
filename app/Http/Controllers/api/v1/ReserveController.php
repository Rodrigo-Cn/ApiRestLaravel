<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Guest, Payment, Reserve, Daily, ReserveGuest};
use Illuminate\Http\Request;

class ReserveController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'room_id' => 'required|exists:rooms,room_id',
            'guest.first_name' => 'required|string|max:60',
            'guest.last_name' => 'required|string|max:60',
            'guest.phone' => 'required|string|max:11',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'daily' => 'required|array',
            'daily.*.date' => 'required|date',
            'daily.*.value' => 'required|numeric|min:0',
            'payments' => 'nullable|array',
            'payments.*.method' => 'required|integer',
            'payments.*.value' => 'required|numeric|min:0',
        ]);

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

        return response()->json($reserve, 200);
    }

}
