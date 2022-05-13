<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines_stops', function (Blueprint $table) {
            $table->foreignId('line_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('stop_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('order');
            $table->primary(['line_id', 'stop_id']);
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
        Schema::dropIfExists('lines_stops');
    }
}
