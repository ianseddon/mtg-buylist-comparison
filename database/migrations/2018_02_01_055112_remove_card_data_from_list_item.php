<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCardDataFromListItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_list_items', function (Blueprint $table) {
            $table->dropColumn(['name', 'set']);
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
            $table->addColumn('string', 'name');
            $table->addColumn('set', 'string');
        });
    }
}
