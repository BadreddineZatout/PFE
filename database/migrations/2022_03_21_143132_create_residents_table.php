<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('structure_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('place_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('state', ['renouvlé', 'non renouvlé']);
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
        Schema::dropIfExists('residents');
    }
}
