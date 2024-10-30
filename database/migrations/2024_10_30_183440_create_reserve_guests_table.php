<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reserve_guests', function (Blueprint $table) {
            $table->unsignedBigInteger('reserve_id');
            $table->unsignedBigInteger('guest_id');

            $table->primary(['reserve_id', 'guest_id']);
    
            $table->foreign('reserve_id')->references('reserve_id')->on('reserves')->onDelete('cascade');
            $table->foreign('guest_id')->references('guest_id')->on('guests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserve_guests');
    }
};