<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtMunchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_munchies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image');
            $table->text('url');
            $table->text('body');
            $table->date('date');
            $table->string('category');
            $table->string('region');
            $table->bigInteger('author_id')->unsigned()->index()->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('art_munchies', function(Blueprint $table)
        {
            $table->dropForeign('author_id'); //
        });
    }
}
