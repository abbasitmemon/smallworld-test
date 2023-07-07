Project Setup
Step-1
clone this project to your xamp wamp mamp (htdocs or wwww) folder

step-2
run composer install

step-3
run php artisan migrate --seed
its important to seed your database because i call third party for star wars movie api from MovieSeeder

step-4
import api collection which i provided in project root

step-5
update base_url variable in collection varibale named base_url

step-6
create user by executing login api from postman or any api testing tool
then update access_token to token variable in collection varibale named token

step-7
check all apis

---Testing---
I build this test project according to your requirement using TDD approach.
So you can also run test by executing
php artisan test
