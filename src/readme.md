## SALEX

### Developed and maintained by Gain HQ

### Introduction 

SaleX - is a robust piece of online software which aims to make inventory management as effortless as possible.
If your business requires for precise regulation of sale & purchase, customer & supplier, product stock distribution, company expenses, and frequent reference of overall or branch/warehouse-wise reports of the aforementioned elements, then this is the application for you.
SaleX is developed using VueJS along with Laravel 9 and MySQL.
The installation process is seamless and, you will be provided with juicy updates packed with a ton of useful features for free.

# Installation

Run composer

```composer install```

Run npm

```npm install```

Build CSS and Js by running 

```npm run watch```

Migrate database 

```php artisan migrate```

Seed database with dummy data

```php artisan db:seed```

Or you can run migration and seed together 

```php artisan migrate --seed```

##Application modules:
Dashboard:

The dashboard section provides the user with a quick and high-level overview of the business as a whole or branch/warehouse-specific details.

Product

The product module keeps track of the products and their respective details that the company keeps in stock.
These details mostly consist of product attributes such as categories, brands, groups along with a few other pieces of product-specific information.

Inventory

As the name implies, this module is responsible for keeping track of the company inventory.
Users can make use of the features provided in this module to add, remove, adjust and transfer product stock across branches and warehouses.

POS

This module consists of the application POS menu and descriptive invoice and return lists.

Expenses

Company expenses and area of expenses are to be regulated from this module.

Contacts

Customers & Suppliers (collectively called 'Contacts') are to be registered into the application from here.
Along with registration, users are provided with descriptive detail pages for the respective contacts.

Reports

As the name suggests, the report module provides the user with an overall the summary of the business as a whole, or of the provided branch or warehouse.

Users & Roles

The admin is to register and assign other users to various tasks that can be performed via this application from here.
The admin also has the privilege of constraining other users with various roles that fit their position in the business accordingly.

Settings

Contains application settings

###Installation in root directory
####Please follow the procedure.

Download the zipped file from CodeCanyon.
Upload the zipped file into cPanel / server.
Unzip the zipped file. Inside the unzipped folder you will get two folders "Documentation" and "upload".
Move all the items from the "upload" folder to the root directory. Please remember to show hidden files. There are 2 files named .env and .htaccess which are hidden by default. Remember to move all files.
Make sure you have correct access permission for these directories and files.

'src/env' => '644'
'src/config/' => '644'
'src/bootstrap/' => '755'
'src/bootstrap/cache/' => '755'
'src/storage/' => '755'
'src/storage/logs/' => '755'
'src/storage/framework/' => '755'

Create the database and remember these things for future usage.
Database name.
Username.
Password.
Database name, username and Password must not contain # or white space.

Make sure that your server must meet our Server Requirements .
Go to URL your_domain/install/. Follow the easy installer procedures step by step and install the application.
Set Up Wizard
Please follow the procedure.

01. Required Environment
You will find the installer using this URL: your_domain/install/.


02. Additional Requirement
Make sure you have symlink permission enabled otherwise you won't able change any images.


03. Purchase Code
Make sure you use correct purchase code. On success you will get "Purchase code verified successfully" message and redirection on next step.

Possible Error Cases response are "Invalid Purchase code", "Request Failed" etc.

"Invalid Purchase code" : Re check you purchase code again.

"Request Failed": Your server could able to communicate with another server to verify purchase code.

If there is any problem occurs in this section then please check Error log and copy error log and contact support@gainhq.com to further assistance.


04. Database Configuration
Make sure you use correct credentials.

Database Host usually used localhost. (Some VPS Server it might differs).

Database Port usually used 3306. (It might differs in some case and if the port is occupied use different port).

Database name, username and Password must not contain # or white space.


05. Admin Login Details
This email and password will be used as admin credentials to log in.

Fill up necessary information and click Save and Next.


06. Login page
Login using admin email and password.


After Installation
Configure your email delivery service from Settings > App Settings > Email Setup

Make sure that your email delivery service credentials is correct. Otherwise, you will get this type of message alert "Configure email setup properly" / "Server error"

Development Guide
Language Setting
If you would like to change any language, please copy the text from default.php to custom.php.
Please note, we may replace all other files except the custom.php in future updates.
Set Settings
Go to "resources/lang" directory of the project.

Add a new directory named on your new language's code name. Like, for Spanish language your directory name would be 'es', for Arabic 'ar' etc.

For getting more language's code name you can go to "config/language.php" of the project. Here you can find all the possible language name and their code.

N:B: Make sure the directory name is same as the valid code of your language.

Copy all the files from "resources/lang/en" directory.

Change "resources/lang/your-new-language-name/default.php" file. Just make the changes to the right sided text in your language. Suppose, you are adding a new language "Portuguese" in this app. So add a new directory named "portuguese" in "resources/lang". Copy all files from "resources/lang/en" directory to "resources/lang/pr". Then change the "default.php" file in this directory. There can be translations from English (such as, "add"=>"Add"). You should change this to portuguese (such as, "add" => "Adicionar").

Update language
Go to "Settings">"Application Settings".

Select your preferred language from "Language Settings" section. Save the changes.

If you would like to change any language, please copy the text from default.php to custom.php.

Please note, we may replace all other files except the custom.php in future updates.

After changing any text, please remember to clear cache from the Settings > Application settings by login as admin.

Environment configuration
Open your project on an editor. Open terminal and run "npm i". Make sure that Nodejs is installed on your PC. If Nodejs is not installed on your PC Click here to download and install Nodejs.

After successfully installed node_modules, To change something in vue component you need to go through the following steps -
Steps : Go to resources/js/tenant/Components/View (You will find all the component here with specific folder) > Change something in vue component > run "npm run watch" command from terminal (You must have Nodejs install first) > Refresh browser to see the change

Add New Page
Go to "resources/views/tenant/" directory of the project.

Create a directory and name it similar to your feature.

Add a new blade file with the following code. Make sure to change "default .preferred_title_of_this_page" to your page title which is added in default.php of your language file and change the "app-component-name" to your component name related to feature. (component create process described below) for app level use @extends('layout.app'), for brand level use@extends('layout.brand')


Go to "app/Http/Controllers/Tenant" make a directory similar to your feature name. Create a controller class like "YourFeatureNameController", which is extends "App\Http\Controllers\Controller". create a function that returns view of the page you created before. Like for example:


Go to "routes/" directory, check route files and subdirectories. You can create a .php file or you also can add in existing file which is similar to your feature. and create a route for your page like that-


Now go to "resources/js/tenant/Components/View" directory. make a directory related to your feature.

Add a new .vue file. Like for example-


Write your desired HTML code inside the component.

Go to "resources/js/tenant/tenantComponent.js" file and register your vue component


Save your changes. Make sure that your component is compiling successfully.

Now hit your route in the browser and see the new page.

How to install Locally-
Make sure you have met our server requirements.
PHP Version = 7.4.x
MySql Version >= 5.6+
Maria DB Version >= 10.2+
Node Version >= 14.17.6
npm Version >= 7.22.0
Make sure that node.js and composer are installed in your local environment.
And we also recommend to use Valet to run the application.
Now, download the zipped file from CodeCanyon.
Unzip the zipped file. Inside the unzipped folder you will get two folders "Documentation" and "upload"
Move all the items from the "upload" folder to local directory from where you want to run the application
Open the folder in the Editor (phpstorm, vscode etc.)
Go to src folder and open .env file: update APP_INSTALLED=true

Update webpack.mix.js as

const mix = require("laravel-mix");

/*
|--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
|
| Mix provides a clean, fluent API for defining some Webpack build steps
| for your Laravel application. By default, we are compiling the Sass
| file for the application as well as bundling up all the JS files.
|
*/


mix.options({
    autoprefixer: {remove: false}
});

mix.setPublicPath("./")
.setResourceRoot("../") // Turns assets paths in css relative to css file
    .sass("src/resources/sass/core/core.scss", "css/core.css")
    .sass("node_modules/dropzone/src/dropzone.scss", "css/dropzone.css")
    .sass("src/resources/sass/_global.scss", "css/fontawesome.css")
    .js("src/resources/js/mainApp.js", "js/core.js").vue()
.extract([
    // Extract packages from node_modules to vendor.js
    "jquery",
    "bootstrap",
    "popper.js",
    "axios",
    "sweetalert2",
    "lodash"
]).sourceMaps();

if (mix.inProduction()) {
    mix.version().options({
        // Optimize JS minification process
        terser: {}
    });
}else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: "inline-source-map",
        stats: {
            warnings: false,
        }
    });
}
Move src\package.json One folder up.

Replace line No. 11 and 12 of src\resources\sass\core\core.scss file with these lines

@import url("../../../../node_modules/nouislider/distribute/nouislider.min.css");
@import url("../../../../node_modules/animate.css/animate.min.css");

Open a terminal on the application folder and run bellow commands
npm install
npm run dev

Now navigate to src folder in command line and run the below commands
composer install
php artisan storage:link

Create a local database from your local machine.

Update the database credentials in the .env file.
DB_CONNECTION=mysql
DB_HOST=127.0.0.1/localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user_name
DB_PASSWORD=your_database_password
After configure your database credentials. You have to run
php artisan optimize:clear
php artisan migrate:fresh --seed

If you want some demo data in the application, please run
php artisan db:demo

In this src folder open another terminal and run the below command in order to complete all the queued jobs generated in the application
php artisan schedule:work

Now, complete the installation process and your application should be ready to use locally.

If you are not using Valet then please run the below command on a terminal of the root directory of your application
php -S localhost:8000 -t ./

Then you will get a URL in terminal: ex (http://127.0.0.1:8000)

Copy and paste the url on a browser.

After that, complete the installation process and your application should be ready to use locally.

Prepare local changes for server
Go to src folder and open .env file: update APP_INSTALLED=false

Zip all project files without node_modules folder.

Upload this zip on the server and extract

N.B: If you change anything or improve to that specific area you might not able to get our future release. Because once you update then your changes will be gone.
Change Color & Design
After installing the application locally you can modify and change any design by changing the files from resources/sass folder.
Change Brand Color
Install the app locally.
Go to resources/sass/core/_variables.scss file.
On line 22 you can see $brand-color: #019AFF;
Change the color code with your new color code.
Build the css with npm.
Your application should appear with new brand color.


### License 
All copyrights reserved by Gain HQ. You will need a corporate license agreement to use this product. 
