<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('states',
            function(Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->unsignedBigInteger('state_machine_id');
                $table->foreign('state_machine_id')
                      ->references('id')
                      ->on('state_machines')
                      ->onDelete('cascade');
                $table->timestamps();

                $table->unique(['name', 'state_machine_id']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('states');
    }
}
