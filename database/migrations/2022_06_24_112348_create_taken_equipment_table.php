<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTakenEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taken_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained();
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->integer('quantity')->nullable();
            $table->date('take_date')->nullable();
            $table->date('return_date')->nullable();
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
        Schema::dropIfExists('taken_equipment');
    }
}
