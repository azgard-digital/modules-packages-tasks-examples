<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    const TABLE_NAME = 'vouchers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('start_date', false, true);
            $table->integer('end_date', false, true);
            $table->integer('discount_id', false, true);
            $table->softDeletes();
        });
        
        Schema::table(self::TABLE_NAME, function(Blueprint $table) {
            $table->foreign('discount_id')->references('id')->on(CreateDiscountTable::TABLE_NAME)->onDelete('cascade');
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
