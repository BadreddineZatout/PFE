<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('rotation_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_transported')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_reservations');
    }
}
