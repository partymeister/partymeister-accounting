<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateAccountsTable
 */
return new class extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('has_card_payments')
                  ->after('has_pos')
                  ->default(false);
            $table->boolean('has_coupon_payments')
                  ->after('has_pos')
                  ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('has_card_payments');
            $table->dropColumn('has_coupon_ayments');
        });
    }
};
