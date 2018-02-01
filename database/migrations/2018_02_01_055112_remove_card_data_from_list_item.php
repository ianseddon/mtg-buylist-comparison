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
            // Defaults are to keep SQLite happy.
            $table->string('name')->default('default_value');
            $table->string('set')->default('default_value');
        });
    }
}
