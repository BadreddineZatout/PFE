<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('structure_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('place_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('state', ['non traité', 'accepté', 'refusé'])->default('non traité');
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
        Schema::dropIfExists('accommodations');
    }
}
