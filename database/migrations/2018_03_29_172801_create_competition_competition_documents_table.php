<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionCompetitionDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_competition_document', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('competition_id')->nullable();
            $table->unsignedInteger('document_id')->nullable();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('set null');
            $table->foreign('document_id')->references('id')->on('competition_documents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_competition_documents');
    }
}
