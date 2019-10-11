**Challenge for Invillia. **

This is a small project that provides an XML file upload screen, writes the information to the database, and provides a REST API for consuming the information.

------------

# **How to install  **

##### Download Symfony2 and composer

To run, you must have Symfony version 2.x and composer installed on your machine. Below are the download links.

- Download Composer on the official website https://getcomposer.org/
- Download the Symfony on the official website https://symfony.com/download
- Verify the server requirements and if the extensions are installed:  Ctype, iconv, JSON, PCRE, Session, SimpleXML, and Tokenizer;

After theses steps,  clone this repository in a directory of your preference. E.g:

`git clone https://github.com/rafaelhbarreto/challenge.git`

After, go to the directory

` cd challenge`

Update the dependecies of project 

`composer update`

##### Database

Create a MySQL database named challenge on your localhost machine. Then, set the enconding to utf8_general_ci. 

In the root folder of project run the command to generate the database

`php app/console doctrine:schema:update --force`

##### Run the server

In the root folder of project run the following command 

`php app/console server:run`

Now open the browser of your preference and access http://localhost:8000

# **Technologies used  **

- Symfony2
- Composer
- Doctrine
- MySQL
- Git
- XML