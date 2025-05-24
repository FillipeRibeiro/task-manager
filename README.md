<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About the Application

This application showcases a task manager based on projects (boards), focusing on clear and efficient organization.
Tasks are grouped by project to simplify separation and tracking.
A Kanban-style board is used to provide an intuitive view of each task’s current status.

## Project Structure

The application was built as a monolith, with the frontend developed using Vue 3, Tailwind CSS, and Inertia.js. The backend is powered by Laravel with a PostgreSQL database.
The development environment is containerized with Docker through Laravel Sail, including services for the server, database, and cache (Redis planned but not yet implemented). Vite is used on the frontend to compile TypeScript and Vue files.

The architecture follows a simple and idiomatic Laravel structure, with well-defined layers: routes → Form Requests (validation) → Controllers → Actions for business logic.

## Setup

To setup the enviroment is necessary to have installed a few tools:

 - Composer (v2.7.*)
 - PHP (v8.2.*)
 - Node (v18.*)
 - NPM (v10.*)
 - Docker

First step is to install the packages running the command `composer install`.
Then you can run `npm install` to insatall all Javascript dependencies.

Create the `.env` file on the root of the project and copy the intire content of `.env.example`.

Next step is to run `sail up` or `sail up -d` to run detached.
In other terminal if you choose the non detached option, you must run `npm run dev` to build Vite and compile the Javascript files.

For the last just run the command `sail artisan migrate` to run the migrations on the container.

## API Documentation

This application has API support, check the endpoints on the file `routes/api.php`.

The API documentation is accesable by the url `http://localhost/api/documentation`.

## To do

A few ideas of what can be implemented in the future.

- A validation to limit the amout of task for each project.
    To keep each project (board) simple and clear and improve performance.
- Implement cache with redis also to improve performance
- Add feature tests for API
- Add pinia to improve management state
