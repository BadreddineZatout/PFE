<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lieu_id')->nullable()->constrained();
            $table->foreignId('structure_id')->nullable()->constrained();
            $table->foreignId('establishment_id')->nullable()->constrained();
            $table->string('description');
            $table->date('date');
            $table->enum('state', ['traité', 'non traité']);
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
        Schema::dropIfExists('incidents');
    }
}
