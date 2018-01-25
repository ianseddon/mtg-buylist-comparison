<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_list_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_list_id')->unsigned();
            $table->foreign('card_list_id')->references('id')->on('card_lists');
            $table->integer('quantity');
            $table->string('set');
            $table->string('name');
            $table->boolean('foil')->default(false);
            $table->string('language')->default('en');
            $table->string('condition')->default('NM/M');
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
        Schema::dropIfExists('card_list_items');
    }
}
