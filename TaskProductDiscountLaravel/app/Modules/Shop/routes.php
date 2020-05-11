<?php
/**
 * Buy product controller
 */
Route::get('product/buy/{productId}', 'ProductController@buyProduct');

/**
 * Product controller
 */
Route::resource('product', 'ProductController');

/**
 * Voucher controller
 */
Route::resource('voucher', 'VoucherController');

/**
 * Discount controller
 */
Route::resource('discount', 'DiscountController');

/**
 * Delete all products associated with voucher
 */
Route::delete('product-voucher/{voucherId}', 'ProductVoucherController@voucherDestroy');

/**
 * Delete specific product and voucher
 */
Route::delete('product-voucher/{voucherId}/{productId}', 'ProductVoucherController@voucherProductDestroy');

/**
 * Associate voucher controller
 */
Route::resource('product-voucher', 'ProductVoucherController');
