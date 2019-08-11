<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class RemoveUnusedAttributesFromItemsTable
 */
class RemoveUnusedAttributesFromItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign([ 'pos_earnings_account_id' ]);
            $table->dropIndex([ 'pos_earnings_account_id' ]);
            $table->dropColumn('pos_earnings_account_id');
            $table->dropColumn('is_visible_in_pos');
            $table->dropColumn('pos_sort_position');
            $table->dropColumn('pos_do_break');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
