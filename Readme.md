# Simple Url Shortener

A web app that was created using the beauiful Symfony 5 PHP framework.
It Contains:
  - Home & main redirect routes
  - API endpoints to handle the CRUD operations on the system
  - A command line tool to delete urls that are older than X days
  - Visits counter

## Assumptions
 - Only authenticated users can access the API and modify the resources
 - Each user can only modify his own created urls
 - Only one route is ananymous which is the redirect route which redirects to the original URL
 - Short URLs are generated using a smart sequence of AlphaNumeric characters
 - A short URL's alias cannot exceed 16 chars in length
 - The original URL cannot exceed 2048 chars

## How to get started?

  - Clone this repo using:
    ` git clone https://github.com/MohamedHassanWD/UrlShortener.git `
  - Move to the directory:
  ` cd UrlShortener/app `
  - And Run `composer install` to install all the dependencies
  - Then Run `docker-compose up -d` to install all neccessary images and run the containers in the background.
  - Now we need to execute the following commands in the same order:
  ```sh
  $ docker exec -it {CONTAINER_ID} sh 
  $ php ./bin/console doctrine:database:create
  $ php ./bin/console doctrine:migrations:migrate
  $ php ./bin/console doctrine:fixtures:load
  ```
  
  The previous four command done the following:
   - We logged in the shell of the PHP container
   - Created the database
   - Migrated the tables
   - Loaded some dump data into the tables in case you wanted to run some tests

## What data was dumped?
The data inserted are `three users in the users table` and `three urls in the short_url table`.
The three users have the same password which is `123`.

## The Authentication
for sake of simplicity, I used the Http Basic Auth. So when you're testing the API endpoints or in the browser, you have to provide the credentials.

**The users are:**
```
Username                password
hassan                  123
tsuy                    123
franco                  123
```

Now you can access the application in your browser on port 8888:
[`http://localhost:8888`](http://localhost:8888)

Give it a test and try to access this url:
[`http://localhost:8888/6K1`](http://localhost:8888/6K1)
You should be redirected to `http://php.net`

## The API Endpoints
The app has seven routes as follows:
```
Name                    Method    Scheme   Host   Path
app_default_index       GET|GET   ANY      ANY    /                         
redirect_to_short_url   GET|GET   ANY      ANY    /{alias}                  
app_url_index           GET       ANY      ANY    /api/urls                 
url_by_alias            GET       ANY      ANY    /api/urls/{alias}         
create_short_url        POST      ANY      ANY    /api/urls                 
update_short_url        PUT       ANY      ANY    /api/urls/{alias}         
delete_short_url        DELETE    ANY      ANY    /api/urls/{alias}   
```

You can easily test and access the endpoints using any client you prefer, if you prefer the easy way, you can go with [Postman](https://www.getpostman.com/) or [Insomnia](https://insomnia.rest/)

Each endpoint is self explainatory, just specify the method according to the action you want (take a look at the table above) and send your request.

`Don't forget to send the credentials along with the request, Basic Auth  :)`

## The command line tool
I built a console command which you can use to delete the old generated urls that exceed a number of days, which you specify as the first parameter.

Take this example:
`$ php ./bin/console delete:urls 100`

This command will delete all the URLs that are older than 100 days.
## Tests
I have written some basic test cases using PHPSpec & PHPUnit, you can run the tests using the following commands:
``./bin/phpunit`` for PHPUnit
``./vendor/bin/phpspec run`` for PHPSpec


