<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image');
            $table->string('type')->nullable();
            $table->string('region');
            $table->string('born_year')->nullable();
            $table->string('death_year')->nullable();
            $table->string('born_location');
            $table->text('about_artist')->nullable();
            $table->string('website')->nullable();
            $table->text('quote')->nullable();
            $table->text('quote_by')->nullable();
            $table->text('represented_by')->nullable();
            $table->text('collections')->nullable();
            $table->text('major_shows')->nullable();
            $table->bigInteger('author_id')->unsigned()->index()->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artists', function(Blueprint $table)
        {
            $table->dropForeign('author_id'); //
        });
    }
}
