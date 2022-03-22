<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained();
            $table->enum('type', ['breakfast', 'lunch', 'dinner']);
            $table->string('sunday_meal');
            $table->string('monday_meal');
            $table->string('tuesday_meal');
            $table->string('wednesday_meal');
            $table->string('thursday_meal');
            $table->string('friday_meal');
            $table->string('saturday_meal');
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
        Schema::dropIfExists('menus');
    }
}
