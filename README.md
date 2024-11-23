Start Store POS using laravel jetstream  team 
  Concept 1. You Can Log In
          2. You Can Create Your Own Store
          3. You Can Add Product to Your Store 
          4. You Can Appoint Cashier to Your Store by email
          5. You Cashier Can See Your Product 
          6. Your Cashier Can't Add Product to Your Store or Delete or Update
          7. Your Cashier Only  Allow Some Operation which can Update Database.
          8. you or your cashier can operate sale. 
          9.see  detail of your store performance 
          10. add new instock to your existing products 
How to set up
    1.git clone https://github.com/yeo50/lightweight_pos.git 
    2.cd lightweight_pos 
    3.in terminal run composer install npm install
    4.set .env file in lightweight_pos folder 
    5.copy all from .env.example 
    6.terminal run php artisan key:generate
How to use 
    1.go to Products and in Products page add product  
    2install scan_It to Office tool in both your phone and computer 
    3.to use Scan_It tool . check Scan_It website https://www.tec-it.com/en/software/mobile-data-acquisition/scan-it-to-office/manual-app/Default.aspx#FORMS 
    4.to product barcode add cursor to barcode input 
    5.scan product barcode with scan_it app and it automatically enter barcode. if not , checked connection.
    6.in sales screen besure to check cursor in scan barcode input and scan as usual 
    7. whenever scan the product ,quantity will increase .
    8 you can manually increase quantity as desire by clicking your product quantity after that hit enter
    9. now you can complete payment. 
    10. in receipt just click back, print is not available as the moment.
   11.now move to products again . you can see your product , edit , search, increae price , decrease price and so on.
   12.You can only add product instock quantity for refilling in add instock only 
   13. in there search by name or by barcode and add new quantity
   14.finally in dashboard you can see your store statistics 
