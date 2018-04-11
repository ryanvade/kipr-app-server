<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->string('location');
            $table->dateTimeTz('start_date');
            $table->dateTimeTz('end_date');
            $table->integer('ruleset_id')->unsigned()->nullable();
            $table->foreign('ruleset_id')->references('id')->on('rulesets')->onDelete('set null');
            $table->integer('tables')->unsigned();
            $table->json('schedule')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions');
    }
}
