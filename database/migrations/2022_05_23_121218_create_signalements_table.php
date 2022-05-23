<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignalementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('establishment_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('structure_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('place_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('description');
            $table->date('date');
            $table->boolean('is_treated');
            $table->boolean('is_anonymous')->default(false);
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
        Schema::dropIfExists('signalements');
    }
}
