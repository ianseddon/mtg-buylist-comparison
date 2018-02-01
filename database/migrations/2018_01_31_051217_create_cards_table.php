<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->primary('multiverse_id');
            $table->integer('multiverse_id')
                ->unsigned();
            $table->integer('set_id')
                ->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('set_id')
                ->references('id')
                ->on('sets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
