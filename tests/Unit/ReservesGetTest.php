<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Models\Reserve;
use App\Models\Room;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservesGetTest extends TestCase
{
    use RefreshDatabase;

    public function testGetRooms()
    {
        $hotel = Hotel::factory()->create();

        $room = Room::factory()->create([
            'hotel_id' => $hotel->hotel_id 
        ]);

        $reserve = Reserve::factory()->create([
            'hotel_id' => $hotel->hotel_id,
            'room_id' => $room->room_id,
            'check_in' => '2025-08-10',
            'check_out' => '2025-09-08',
            'total' => '10000.00',
        ]);

        $response = $this->get('/api/reserves');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'hotel_id' => $hotel->hotel_id,
            'room_id' => $room->room_id,
            'check_in' => '2025-08-10',
            'check_out' => '2025-09-08',
            'total' => '10000.00',
        ]);
        
        $hotel->delete();
        $room->delete();
        $reserve->delete();
    }
}
