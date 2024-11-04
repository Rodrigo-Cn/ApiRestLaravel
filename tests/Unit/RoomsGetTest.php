<?php

namespace Tests\Unit;

use App\Models\Hotel;
use Tests\TestCase;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomsGetTest extends TestCase
{
    use RefreshDatabase;

    public function testGetRooms()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create();

        $response = $this->get('/api/rooms');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'hotel_id'=>$room->hotel_id,
            'room_id' => $room->room_id,
            'name' => $room->name,
        ]);

        $hotel->delete();
        $room->delete();
    }
}
