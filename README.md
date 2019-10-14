
  

  

  

# Challenge for Invillia.

  

  

  

This is a small project that provides an XML file upload screen, writes the information to the database, and provides a REST API for consuming the information.

  

  

  

# Requirements

  

  

  

- Composer https://getcomposer.org/

  

  

- Symfony2 https://symfony.com/download (version: 2.8.x)

  

  

- PHP 5.3+

  

  

- MySQL

  

  

  

# Install

  

  

  

Clone the project

  

  

  

`git clone https://github.com/rafaelhbarreto/challenge.git`

  

  

  

go to the project directory and install the dependencies with composer

  

  

  

`composer install`

  

  

###### Parameters to install

  

- database_driver pdo_mysql

- database_host 127.0.0.1

- database_port null

- database_name **challenge**

- database_user root

- database_password null

  

- mailer_transport smtp

- mailer_host 127.0.0.1

- mailer_user null

- mailerpassword null

- locale en

- debug_toolbar true

- debug_redirects false

- use_assetic_controller true

  

  

Create database

  

  

  

`php app/console doctrine:database:create`

  

  

  

Create the tables

  

  

  

`php app/console doctrine:schema:create`

  

  

  

Run the server

  

  

  

`php app/console server:run`


 
  **ATTENTION**
 The emailed shiporders.xml file contained a parser error at line 37 and 61. Following is the download link for the correct files. :)  [click here to download](https://www.sendspace.com/file/wbuj79)
 
**Now open the browser of your preference and access** http://localhost:8000

  

  
  
  
  

  

# API

  

  

  

After the upload files, the API will be available. If you use [Insomnia client](https://insomnia.rest/), you can import the [insomnia requests.json](https://github.com/rafaelhbarreto/challenge/blob/master/insomnia%20requests.json  "insomnia requests.json") file. The environment is ready to use

  

  

  

##### Documentation

  

You can view the documentation in http://localhost:8000/docs

  

  

  

### Available routes

  
Ex.: http://localhost:8000/api/people
  

  

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

  

  

  

# Technologies

  

  

  

- Symfony2 (2.8.x)

  

  

- Composer

  

  

- Doctrine

  

  

- MySQL

  

  

- Git

  

  

- XML