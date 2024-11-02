<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleXMLElement;
use Exception;
use App\Models\{Hotel, Room, Guest, Reserve, ReserveGuest, Daily, Payment};

class ImportDatabaseXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-database-xml {path? : Caminho para o XML}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transformar XML em dados para persistir no banco.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path') ?? storage_path('app/data.xml');

        if (!file_exists($path)) {
            $this->error('Caminho do arquivo não encontrado.');
            return Command::FAILURE;
        }

        try {
            $xml = new SimpleXMLElement(file_get_contents($path));

            if (isset($xml->Hotel)) {
                $this->createHotels($xml->Hotel);
            } else {
                $this->createHotels([]);
            }

            if (isset($xml->Room)) {
                $this->createRooms($xml->Room);
            } else {
                $this->createRooms([]);
            }

            if (isset($xml->Reserve)) {
                $this->createReserves($xml->Reserve);
            } else {
                $this->createReserves([]);
            }

        } catch (Exception $e) {
            $this->error($e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Importação concluída com sucesso.');
        return Command::SUCCESS;
    }

    private function createHotels($hotels)
    {
        foreach ($hotels as $hotelData) {
            Hotel::updateOrCreate(
                ['hotel_id' => (int) $hotelData['id']],
                [
                    'name' => (string) $hotelData->Name
                ]
            );
        }
    }

    private function createRooms($rooms)
    {
        foreach ($rooms as $roomData) {
            Room::updateOrCreate(
                ['room_id' => (int) $roomData['id']],
                [
                    'hotel_id' => (int) $roomData['hotelCode'],
                    'name' => (string) $roomData->Name
                ]
            );
        }
    }

    private function createReserves($reserves)
    {
        foreach ($reserves as $reserveData) {

            $reserve = Reserve::updateOrCreate(
                ['reserve_id' => (int) $reserveData['id']],
                [
                    'hotel_id' => (int) $reserveData['hotelCode'],
                    'room_id' => (int) $reserveData['roomCode'],
                    'check_in' => (string) $reserveData->CheckIn,
                    'check_out' => (string) $reserveData->CheckOut,
                    'total' => (float) $reserveData->Total
                ]
            );


            if (isset($reserveData->Guests->Guest)) {
                foreach ($reserveData->Guests->Guest as $guestData) {
                    Guest::updateOrCreate(
                        [
                            'reserve_id' => $reserve->reserve_id,
                            'first_name' => (string) $guestData->Name,
                            'last_name' => (string) $guestData->LastName,
                            'phone' => (string) $guestData->Phone
                        ]
                    );

                }
            } else {
                Guest::insert([]);
            }

            if (isset($reserveData->Dailies->Daily)) {
                foreach ($reserveData->Dailies->Daily as $dailyData) {
                    Daily::updateOrCreate(
                        [
                            'date' => (string) $dailyData->Date,
                            'reserve_id' => $reserve->reserve_id
                        ],
                        [
                            'value' => (float) $dailyData->Value
                        ]
                    );
                }
            } else {
                Daily::insert([]);
            }

            if (isset($reserveData->Payments->Payment)) {
                foreach ($reserveData->Payments->Payment as $paymentData) {
                    Payment::updateOrCreate(
                        [
                            'reserve_id' => $reserve->reserve_id,
                            'method' => (int) $paymentData->Method,
                            'value' => (float) $paymentData->Value
                        ]
                    );
                }
            } else {
                Payment::insert([]);
            }
        }
    }
}
