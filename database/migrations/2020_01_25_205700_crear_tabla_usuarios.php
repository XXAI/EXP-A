<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('id',12);
            $table->string('username');
            $table->string('password');
            $table->string('api_token', 80)
                        ->unique()
                        ->nullable()
                        ->default(null);
            $table->rememberToken();
            $table->boolean('su')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->unique(['username', 'servidor_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
