<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaPermisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->string('id',16);
            $table->string("descripcion");
            $table->integer('grupo_id')->unsigned();
            $table->boolean('su')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('grupo_id')
                  ->references('id')->on('grupo_permisos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permisos');
    }
}
