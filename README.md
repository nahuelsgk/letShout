letShout
========

A Symfony project created on October 18, 2017, 8:20 am. This is a simple endpoint that given a Twitter username and a number N, returns a JSON response with user’s last N tweets in uppercase.

Requirements
============
It was developed under php 5.5.38. So ensure have installed 5.5.38 or bigger.

Installation and run on Macos or linux
==============================
By git
------
@WARNING: Accept default values for database, mailer and secret except for the twitter api credentials: 
````
git clone https://github.com/nahuelsgk/letShout.git
cd letShout
php ./composer.phar install
php bin/console server:run
````

By rar
------
Just unzip and run and execute:
````
php bin/console server:run
````

Run tests
=========
From the root directory:

All tests:
````
php vendor/bin/phpunit tests/
````

Unit tests:
````
php vendor/bin/phpunit tests/AppBundle/LastTweetsTest
````

Functional tests:
````
php vendor/bin/phpunit tests/AppBundle/Controller
````

Api endpoints
=============
> DOMAIN/api/{screen_name}/{count}?cache=false

Has a GET param so to use cache or not. The TTL of cache is 5 minutes. By default uses Cache.

Example: http://127.0.0.1:8001/api/nahuelsgk/4

Small background
================
I decided to use Symfony as the technical test suggested the symfony as a framework, but actually I wouldn't use it. Just simply because for an API Restful I found more useful and robust Laravel.

Do not use any DB and uses local filesystem cache. As dependencies selected:
- Mockery: to unit tests and mock twitter api connection.
- j7mbo/twitter-api-php: A really simple api client.
 
The part that I spend more time to understand how to inject on the controller the dependency so I can mock it on functional tests.