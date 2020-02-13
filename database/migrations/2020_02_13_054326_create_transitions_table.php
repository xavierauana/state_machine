<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transitions',
            function(Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->unsignedBigInteger('state_machine_id');
                $table->foreign('state_machine_id')
                      ->references('id')
                      ->on('state_machines')
                      ->onDelete('cascade');
                $table->unsignedBigInteger('to_state_id');
                $table->foreign('to_state_id')
                      ->references('id')
                      ->on('states')
                      ->onDelete('cascade');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transitions');
    }
}
