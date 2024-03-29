<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateBookingsTable
 */
class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from_account_id')->unsigned()->nullable()->index();
            $table->bigInteger('to_account_id')->unsigned()->nullable()->index();
            $table->text('description');
            $table->decimal('vat_percentage', 4, 2);
            $table->decimal('price_with_vat', 15, 4);
            $table->decimal('price_without_vat', 15, 4);
            $table->string('currency_iso_4217');
            $table->boolean('is_manual_booking')->default(false);

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->timestamps();

            $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
