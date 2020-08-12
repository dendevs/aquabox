<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->string('hour');
            $table->string('minute');
            $table->string('day');
            $table->string('week');
            $table->string('month');
            $table->string('description')->nullable();
            $table->string('formated')->comment("Concaténation de hour, minute, day, week, month dans le but d'une comparaison accelérer");
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
        Schema::dropIfExists('crons');
    }
}
