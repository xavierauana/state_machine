<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transition_log',
            function(Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('subject');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('transition_id')->default(0);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transition_log');
    }
}
