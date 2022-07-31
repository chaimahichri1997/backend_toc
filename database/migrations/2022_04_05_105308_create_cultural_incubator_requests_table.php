<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCulturalIncubatorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cultural_incubator_requests', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('company');
            $table->string('country');
            $table->string('full_name');
            $table->string('occupation');
            $table->string('email');
            $table->string('phone');
            $table->longText('message');
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
        Schema::dropIfExists('cultural_incubator_requests');
    }
}
