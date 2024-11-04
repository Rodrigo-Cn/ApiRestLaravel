<?php

namespace Database\Seeders;

use App\Models\Daily;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HotelSeeder::class,
            RoomSeeder::class,
            ReserveSeeder::class,
            GuestSeeder::class,
            DailySeeder::class,
            PaymentSeeder::class,
            UserSeeder::class,
        ]);
    }
}
