<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');

            // Scheduling information
            $table->timestamps();
            $table->dateTimeTz('match_time')->nullable();
            $table->unsignedInteger('match_table')->nullable();

            $table->unsignedInteger('match_type');
            $table->unsignedInteger('round');
            $table->unsignedInteger('competition_id');
            $table->unsignedInteger('team_A');
            $table->unsignedInteger('team_B')->nullable();
            $table->unsignedInteger('match_A')->nullable();
            $table->unsignedInteger('match_B')->nullable();
            $table->json('results')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
