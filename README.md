

# Challenge for Invillia.

This is a small project that provides an XML file upload screen, writes the information to the database, and provides a REST API for consuming the information.

# Requirements

- Composer  https://getcomposer.org/
- Symfony https://symfony.com/download
- PHP 5.3+ 
- MySQL 

# Install

Clone the project 

`git clone https://github.com/rafaelhbarreto/challenge.git`

go to the project directory and install the dependencies with composer

`composer install`

Create database

`php app/console doctrine:database:create`

Create the tables 

`php app/console doctrine:schema:create`

Run the server

`php app/console server:run`

Now open the browser of your preference and access http://localhost:8000

# API 

After the upload files, the API will be available. If you use [Insomnia client](https://insomnia.rest/), you can import the [insomnia requests.json](https://github.com/rafaelhbarreto/challenge/blob/master/insomnia%20requests.json "insomnia requests.json") file. The environment is ready to use

### Available routes

##### People
- /api/people 
- /api/people/{personId} 
- /api/people/{personId}/phones
- /api/people/{personId}/orders 

##### Orders
- /api/orders
- /api/order/{orderId} 
- /api/orders/{orderId}/ship
- /api/orders/{personId}/items 


# Technologies used 

  - Symfony2
- Composer
- Doctrine
- MySQL
- Git
- XML
