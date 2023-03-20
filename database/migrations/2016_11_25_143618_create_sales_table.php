<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateSalesTable
 */
class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('earnings_booking_id')->unsigned()->index();
            $table->integer('cost_booking_id')->unsigned()->nullable()->index();
            $table->integer('item_id')->unsigned()->index();
            $table->integer('quantity')->default(0);
            $table->decimal('price_with_vat', 15, 4);
            $table->decimal('price_without_vat', 15, 4);
            $table->decimal('vat_percentage', 4, 2);
            $table->string('currency_iso_4217')->default('EUR');

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('earnings_booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('cost_booking_id')->references('id')->on('bookings')->onDelete('set null');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('sale_id')->after('id')->unsigned()->index()->nullable();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropColumn('sale_id');
        });

        Schema::drop('sales');
    }
}
