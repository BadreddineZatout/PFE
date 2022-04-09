<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLieusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lieux', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['chambre', 'amphi', 'class', 'sanitaire']);
            $table->foreignId('structure_id')->nullable()->constrained();
            $table->foreignId('establishment_id')->nullable()->constrained();
            $table->integer('capacity')->nullable();
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
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
        Schema::dropIfExists('lieux');
    }
}
