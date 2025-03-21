# Laravel HTTP Testing

An example app with some HTTP tests for API routes

## Setup

1. Clone this repository into your `html` folder
2. CD into the repository `cd laravel-http-testing`
3. Run `composer install` to download laravel and all of it's other dependencies
4. Make a copy of `.env.example` called `.env` and enter your database connection details
5. Run `php artisan key:generate` to generate security that laravel uses behind the scenes to make things safe
6. Run `php artisan migrate` to setup the database
7. Run `php artisan serve --host=0.0.0.0` to get it running

Note: If you want to look at the web routes, not just the API ones, you'll also need to run
`npm run dev` to get tailwind working

