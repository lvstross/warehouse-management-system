<h1 align="center">Dataentry System</h1>
<h3 align="center">A system built from the group up for companies with product driven warehouses.</h3>
This project is currently in session. The Dataentry System is capable of managing product and customer information as well as tracking of travelers, purchase orders, 
invoices, shipments, company vendor purchases and more. See the full list of features below.
<img src="/public/img/frontpage.png">
<img src="/public/img/dashboard.png">

### The System currently includes
1. A simple navigation dashboard leading you to all the different areas of the Dataentry System.
2. The system saves company information for use on printables and allows you to import and export your system data at anytime.
3. The users interface makes it easy to create upto four different levels of users all of which have verying roles in the system.
4. The system allows you to store and use customer, vendor and product data with ease.
5. You can also manage purchase orders with a sortable interface allowing you to keep track of which orders are more important. This interface also allows you to print four different reports (all for differenct purposes) one of which gives you your on time delivery percentage.
6. The Dataentry Systems finest features is it's ability to track work orders through your warehouse with a simple drag and drop interface where you can move work orders between departments and submit them to inventory. On that note, keeping track of what you have in inventory has never been easier. With the Dataentry System you are able to keep an eye on your product quantities either shop wide or directly in inventory.
7. One important entity in the system is the Inventory Ship Ticket. These ship tickets are what keep track of your shipment data. With each ship ticket made, your inventory is automaticly updated for you so that you can spend less time worrying about writing down what shipped and when it shipped. The Dataentry System tracks all of that for you.
8. The system makes it simple and easy to record and track your invoices, print shippers as well as monthly and yearly sales reports.
9. The Dataentry System also keeps track of who does what in the system at all times. With live reloading, you can watch in real time the goings on in your warehouse.
10. Lastly, the system allows you to create and print Certificates of Confirmation for every sale you make.

#### Other features include:
1. Error and Success Messages.
2. Mobile Responsiveness with Bootstrap.
3. Warning messages along with input validation.
4. pdf printables
6. Database Importing and Exporting
5. Information cards for every input area.
6. Icons, smooth animations and page loaders
7. full screen mode

#### Invoices Interface
<img src="/public/gifs/invoices.gif">

#### Partflow Interface
<img src="/public/gifs/partflow.gif">

#### Product and Customers Interface
<img src="/public/gifs/product-customer.gif">

#### Purchase Orders Interface
<img src="/public/gifs/purchase-orders.gif">

#### Inventory Shipticket Interface
<img src="/public/gifs/shipticket.gif">

### General SetUp
This applications was built on Laravel 5.4 and developed in the Laravel Valet LEMP environment.
1. Rename the .env.example file to .env.
2. Fill in the following in the .env file.
```
   DB_DATABASE=dbname
   DB_USERNAME=root
   DB_PASSWORD=
```
3. Create a database with the name you put for the DB_DATABASE config
4. Run these commands to install all the needed packages.
```bash
~ composer install
~ npm install
~ npm run dev
```
5. Migrate the database files.
```bash
~ php artisan migrate
```
6. Run these commands to generate the nessesary keys
```bash
~ php artisan key:generate
~ php artisan passport:keys
```
7. Serve up the application.
```bash
~ php artisan serve
```
8. If you are making changes to any of the javascript files, run
```bash
~ npm run watch
```

Once you have successfully opened the home page on the local host server, go to '/register' and make an account.
This initial registration is a one time registrations as all other users are supposed to be created in the admin area of the 
application in '/users'. All future visits to '/register' will be redirected to the '/login' page.