<?php

namespace App\OpenApi\Annotations;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Room",
 *     type="object",
 *     required={"room_id", "hotel_id", "name"},
 *     @OA\Property(property="room_id", type="integer", format="int64", example=1, description="ID do quarto"),
 *     @OA\Property(property="hotel_id", type="integer", format="int64", example=1, description="ID do hotel"),
 *     @OA\Property(property="name", type="string", example="Quarto 1", description="Nome do quarto"),
 *     
 * )
 */
class RoomSchema
{
    //
}
