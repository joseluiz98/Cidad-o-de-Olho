<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerbasIndenizatoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verbas_indenizatorias', function (Blueprint $table) {
            $table->timestamps();
            $table->tinyInteger('month')->nullable(false);
            $table->float('value');
            $table->unsignedBigInteger('deputado_id');
            $table->string('type');
            
            $table->primary(['deputado_id', 'month', 'type']);

            $table->foreign('deputado_id')->references('id')->on('deputados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verbas_indenizatorias');
    }
}