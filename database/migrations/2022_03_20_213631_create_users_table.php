<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            // $table->string('name')->storedAs("firstname || ' ' || lastname"); //Postgres db
            $table->string('name')->storedAs("concat(firstname, ' ', lastname)"); //Mysql db
            $table->date('birthday')->nullable();
            $table->string('nin')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('establishment_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('wilaya_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('commune_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_resident')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
