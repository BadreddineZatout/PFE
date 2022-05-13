<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr');
            $table->string('name_arabe');
            $table->date('creation_date');
            $table->foreignId('wilaya_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('commune_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['université', 'école superieure', 'institue', 'résidence']);
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
        Schema::dropIfExists('establishments');
    }
}
