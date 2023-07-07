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
Run 'php artisan migrate --seed' command
Its important to seed the database because thats where the data from third party API for star wars movies is called

step-6
Import API collection (smartWorldTest.postman_collection.json) in postman which is provided in project root

step-7
Update base_url variable in postman collection varibale named base_url with local url

step-8
Create user by executing signup API
OR
Sign in by executing sign in API

step-9
Update postman variable 'token' with token from signup/Sign in API response.

step-7
Check all apis

---Testing---
This project is developed using TDD approach,Therefore you can also run test by executing 'php artisan test' command.
