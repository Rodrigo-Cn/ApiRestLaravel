<?php

namespace App\Repositories\Eloquent;

use App\Models\Reserve;
use App\Repositories\Contracts\ReserveRepositoryInterface;
use App\Models\{Hotel,Room};

class ReserveRepository implements ReserveRepositoryInterface
{
    private $guestRepository;
    private $dailyRepository;
    private $paymentRepository;

    public function __construct(GuestRepository $guestRepository, DailyRepository $dailyRepository, PaymentRepository $paymentRepository)
    {
        $this->guestRepository = $guestRepository;
        $this->dailyRepository = $dailyRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function getAllReserves($attributes = null)
    {
        if ($attributes != null) {
            return Reserve::select($attributes)->get();
        }
        
        return Reserve::all();
    }

    public function getReserveById($id)
    {
        return Reserve::find($id);
    }

    public function createReserve(array $data)
    {
        $hotelExists = Hotel::where('hotel_id', $data['hotel_id'])->exists();
        $roomExists = Room::where('room_id', $data['room_id'])->where('hotel_id', $data['hotel_id'])->exists();
    
        if (!$hotelExists) {
            return response()->json(['error' => 'Hotel não encontrado.'], 404);
        }
    
        if (!$roomExists) {
            return response()->json(['error' => 'Quarto não encontrado.'], 404);
        }
    
        $reservationExists = Reserve::where('room_id', $data['room_id'])->where(function ($query) use ($data) {
            $query->whereBetween('check_in', [$data['check_in'], $data['check_out']])
                ->orWhereBetween('check_out', [$data['check_in'], $data['check_out']]);
        })->exists();
    
        if ($reservationExists) {
            return response()->json(['error' => 'Quarto não disponível para as datas selecionadas.'], 409);
        }
    
        $reserve = Reserve::create([
            'hotel_id' => $data['hotel_id'],
            'room_id' => $data['room_id'],
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'total' => 0,
        ]);
    
        foreach ($data['guest'] as $guestData) {
            $this->guestRepository->createGuest([
                'phone' => $guestData['phone'],
                'reserve_id' => $reserve->reserve_id,
                'first_name' => $guestData['first_name'],
                'last_name' => $guestData['last_name'],
            ]);
        }
    
        $total = 0;
    
        if (isset($data['daily']) && is_array($data['daily'])) {
            foreach ($data['daily'] as $dailyData) {
                $this->dailyRepository->createDaily([
                    'reserve_id' => $reserve->reserve_id,
                    'date' => $dailyData['date'],
                    'value' => $dailyData['value'],
                ]);
                $total += $dailyData['value'];
            }
        }
    
        if (isset($data['discount']) && is_numeric($data['discount'])) {
            $total -= $data['discount'];
        }
    
        if (isset($data['interest']) && is_numeric($data['interest'])) {
            $total += $data['interest'];
        }
    
        if (isset($data['payments']) && is_array($data['payments'])) {
            foreach ($data['payments'] as $paymentData) {
                $this->paymentRepository->createPayment([
                    'reserve_id' => $reserve->reserve_id,
                    'method' => $paymentData['method'],
                    'value' => $paymentData['value'],
                ]);
                $total -= $paymentData['value'];
            }
        }
    
        $reserve->update(['total' => $total]);
    
        return $reserve;
    }
    

    public function updateReserve($id, array $data)
    {
        $reserve = $this->getReserveById($id);
        $reserve->update($data);
        return $reserve;
    }

    public function deleteReserve($id)
    {
        $reserve = $this->getReserveById($id);
        $reserve->delete();
        return $reserve;
    }
}
