<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(CreateProductsVouchersTable::TABLE_NAME, function(Blueprint $table) {
            $table->unique(['product_id', 'voucher_id'], 'products-vouchers-uniq-index');
        });
        
        Schema::table(CreateVouchersTable::TABLE_NAME, function(Blueprint $table) {
            $table->index(['start_date', 'end_date'], 'vauchers-date-index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(CreateProductsVouchersTable::TABLE_NAME, function(Blueprint $table) {
            $table->dropUnique('products-vouchers-uniq-index');
        });
        
        Schema::table(CreateVouchersTable::TABLE_NAME, function(Blueprint $table) {
            $table->dropIndex('vauchers-date-index');
        });
    }
}
