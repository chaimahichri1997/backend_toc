<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_works', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title');
            $table->string('reference')->nullable();
            $table->string('date')->nullable();
            $table->string('category');
            $table->string('medium')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('edition')->nullable();
            $table->string('provenance')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->integer('depth')->nullable();
            $table->string('framing')->nullable();
            $table->string('makers_marks')->nullable();
            $table->string('production_place')->nullable();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->bigInteger('artist_id')->unsigned()->index()->nullable();
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->bigInteger('author_id')->unsigned()->index()->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('subcollection_id')->unsigned()->index()->nullable();
            $table->string('InExplore');
            $table->string('price');
            $table->string('InBasket');
            $table->string('etat');


        

            $table->softDeletes();
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
        Schema::dropIfExists('art_works');
    }
}
