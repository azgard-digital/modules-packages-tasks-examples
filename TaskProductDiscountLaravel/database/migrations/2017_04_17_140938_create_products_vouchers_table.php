<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsVouchersTable extends Migration
{
    const TABLE_NAME = 'products_vouchers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id', false, true);
            $table->integer('voucher_id', false, true);
        });
        
        Schema::table(self::TABLE_NAME, function(Blueprint $table) {
            $table->foreign('voucher_id')->references('id')->on(CreateVouchersTable::TABLE_NAME)->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on(CreateProductsTable::TABLE_NAME)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE_NAME);
    }
}
