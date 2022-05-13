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
            $table->date('date');
            $table->foreignId('structure_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['breakfast', 'lunch', 'dinner']);
            $table->string('main_dish');
            $table->string('secondary_dish');
            $table->string('dessert');
            $table->integer('quantity');
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
