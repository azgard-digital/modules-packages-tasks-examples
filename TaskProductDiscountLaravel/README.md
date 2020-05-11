Task Description
===================================
A web page is needed where a user will see a list of products available in the system and their prices with or without discount vouchers associated 

Consider the following entities:
    • Products that have:
        ◦ ID
        ◦ name 
        ◦ price
    • Vouchers that have:
        ◦ IDs 
        ◦ start date
        ◦ end date 
    • Discount tiers (10%, 15%, 20% and 25% discounts applicable to product price)

A product can have one or more vouchers associated with it (or none). Each voucher, once introduced to the database, might be assigned to several products. Each voucher must be associated with one discount tier. The task is to produce REST API service that would allow:
    • To create a voucher and associate it with a discount tier
    • To add a product
    • To add a voucher bind to a certain product 
    • To remove voucher bind from a certain product
    • To buy a product

The goal is to produce a table of products with names and associated prices and a button (or a link or whatever control element is convenient) to buy a certain product. Prices in this table are calculated in the following way:

	Price of the product * sum of discounts by all associated vouchers

Please note that the vouchers that are outdated (if today is earlier than start date or today is later than end date) should not be applied in the formula. Also, if the sum of all discounts is more than 60% then the summary discount is equal to 60% regardless of how many vouchers are associated with the product.

Clicking on “buying a product” should make the product and all associated vouchers unavailable via REST API and the web page.

You are completely free to design the database structure and project architecture at your will. Please note that aside from testing the project via web page you provide, the REST API will be also accessed multiple times separately from the web page by third party tools in order to test the application with additional data.

Result
================================

Requirements (PHP 7.*, Laravel 5.*)
-------------------------------------------------------
Add files to the framework according to their position.
Example folder contains frontend part which should be moved in public directory.

Add a provider to app.php 
------------------------------------------------
'providers' => [
-App\Modules\Shop\ShopServiceProvider::class,
]

Run commands
----------------------------
```bash
php artisan migrate
composer dump-autoload
php artisan db:seed
```
Http REST example
-------------------------
```bash
CURL REQUEST
Add product
curl -X POST -F 'name=product7' -F 'price=122' http://testtask.local/product
Add voucher
curl -X POST -F 'start_date=12-12-12' -F 'end_date=15-12-12' -F 'discount_id=1' http://testtask.local/voucher
Add related product with voucher
curl -X POST -F 'voucher_id=1' -F 'product_id=2' http://testtask.local/product-voucher
Remove product voucher relation
curl -X "DELETE" http://testtal/product-voucher/1/1
Delete all products associated with the voucher
curl -X "DELETE" http://testtal/product-voucher/1
```