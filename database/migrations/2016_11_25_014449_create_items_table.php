<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateItemsTable
 */
class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pos_earnings_account_id')->unsigned()->nullable()->index();
            $table->bigInteger('pos_cost_account_id')->unsigned()->nullable()->index();
            $table->bigInteger('item_type_id')->unsigned()->nullable()->index();
            $table->string('name');
            $table->text('description');
            $table->text('internal_description');
            $table->decimal('vat_percentage', 4, 2);
            $table->decimal('price_with_vat', 15, 4);
            $table->decimal('price_without_vat', 15, 4);
            $table->decimal('cost_price_with_vat', 15, 4);
            $table->decimal('cost_price_without_vat', 15, 4);
            $table->string('currency_iso_4217')->default('EUR');
            $table->boolean('can_be_ordered');
            $table->boolean('is_visible');
            $table->integer('sort_position')->unsigned()->nullable();
            $table->boolean('is_visible_in_pos');
            $table->bigInteger('pos_create_booking_for_item_id')->unsigned()->nullable();
            $table->boolean('pos_can_book_negative_quantities');
            $table->integer('pos_sort_position')->unsigned()->nullable();
            $table->integer('pos_do_break');

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->timestamps();

            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('set null');
            $table->foreign('pos_create_booking_for_item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('pos_earnings_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('pos_cost_account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
