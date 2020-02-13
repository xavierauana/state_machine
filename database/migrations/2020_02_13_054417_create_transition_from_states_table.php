<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionFromStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transition_from_states',
            function(Blueprint $table) {

                $table->unsignedBigInteger('state_id');
                $table->foreign('state_id')
                      ->references('id')
                      ->on('states')
                      ->onDelete('cascade');

                $table->unsignedBigInteger('transition_id');
                $table->foreign('transition_id')
                      ->references('id')
                      ->on('transitions')
                      ->onDelete('cascade');


            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transition_mappings');
    }
}
