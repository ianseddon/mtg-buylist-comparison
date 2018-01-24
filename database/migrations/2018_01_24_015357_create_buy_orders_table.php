<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_site_id')->unsigned();
            // TODO: Replace these with card ID.
            $table->string('card_name');
            $table->string('card_set');
            // END TODO.
            $table->integer('buying')->unsigned();
            $table->boolean('foil')->default(false);
            $table->double('price');
            $table->timestamps();

            $table->foreign('vendor_site_id')->references('id')->on('vendor_sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_orders');
    }
}
