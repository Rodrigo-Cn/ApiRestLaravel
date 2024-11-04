<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use App\Models\Guest;
use App\Models\Daily;
use App\Models\Reserve;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataBaseTest extends TestCase
{
    use RefreshDatabase;

    public function testEntityDeletion()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $reserve = Reserve::factory()->create();
        $guest = Guest::factory()->create();
        $daily = Daily::factory()->create();
        $payment = Payment::factory()->create();

        $payment->delete();
        $daily->delete();
        $guest->delete();
        $reserve->delete();
        $room->delete();
        $hotel->delete();
        $user->delete();

        $this->assertModelMissing($payment);
        $this->assertModelMissing($reserve);
        $this->assertModelMissing($daily);
        $this->assertModelMissing($guest);
        $this->assertModelMissing($room);
        $this->assertModelMissing($hotel);
        $this->assertModelMissing($user);

        Log::info('Database e Model funcionando');
    }
}
