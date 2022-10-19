# Local setup
## Requirements
You will need PHP 7.1 at least
You will node and NPM
You will need to have a local database setup
You will need PHP composer

If you have a local MySQl server then just login and create a new database called tenancystream.  You will need to put the credentials for your database in a local .env file, more of that later.

Once you have the repo go to the root of the project and run composer install.  This will show you if you need to have any more PHP extensions etc.  Once all of that is done you are ready for the next step.

Run npm run dev in the root to get the JS and CSS working.

To get this project working locally - and by that I mean with the following command php artisan serve - you will need to have the .env file sent to you.  For obvious reasons this is not part of the git repo.  However, the .env file will be setup to run against a local Postgres Database - I Do not know why -. So just set the database to point at the test rds instance.

