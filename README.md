Project Setup

Step-1
Clone this project to your xamp wamp mamp (htdocs or wwww) folder

Step-2
Generate .env file by running 'cp .env.example .env' command

Step-3
Run 'php artisan key:generate' command

Step-4
Run 'composer install' command

Step-5
Run 'php artisan migrate' command

Step-6
Update QUEUE_CONNECTION settings in .env
replace REDIS_CLIENT to this
QUEUE_CONNECTION=database

Step-7
Run 'php artisan queue:work' command
OR
if you're using linux then supervisor will work great
Its important to run queue worker because i created queue job and thats where the data from Redis cache is stored in database

step-8
Import API collection (smartWorldTest.postman_collection.json) in postman which is provided in project root

step-9
Update redis settings in config/database.php
replace cluster value to this
'cluster' => env('REDIS_CLUSTER', 'redis'),

step-10
Update redis settings in .env
replace REDIS_CLIENT to this
REDIS_CLIENT=predis

step-11
Make sure you have redis-server and running if not then install it according to your operating system

step-12
Update base_url variable in postman collection varibale named base_url with local url

step-13
Create user by executing signup API
OR
Sign in by executing sign in API

step-14
Update postman variable 'token' with token from signup/Sign in API response.

step-15
Check all apis

---Testing---
This project is developed using TDD approach,Therefore you can also run test by executing 'php artisan test' command.
