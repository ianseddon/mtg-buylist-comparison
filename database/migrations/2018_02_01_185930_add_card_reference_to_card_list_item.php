<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardReferenceToCardListItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_list_items', function (Blueprint $table) {
            $table->integer('card_id')
                ->unsigned();
        });

        Schema::table('card_list_items', function (Blueprint $table) {
            $table->foreign('card_id')
                ->references('multiverse_id')
                ->on('cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_list_items', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
            $table->dropColumn('card_id');
        });
    }
}
