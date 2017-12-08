<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('signed_in')->default(false);
            $table->dateTimeTz('sign_in_time')->nullable();
            $table->timestamps();

            $table->unsignedInteger('competition_id');
            $table->unsignedInteger('team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_teams');
    }
}
