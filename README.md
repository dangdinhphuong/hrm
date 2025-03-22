# HRM Project

## Introduction

This project is a Human Resource Management (HRM) system built with Laravel. It helps manage employee data, leave requests, payroll, and other HR-related tasks.

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 20.x
- npm >= 6.x
- Docker (optional, for containerized deployment)

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/dangdinhphuong/hrm.git
    cd hrm
    ```

2. Install PHP dependencies:
    ```sh
    composer install
    ```

3. Install JavaScript dependencies:
    ```sh
    npm install
    ```

4. Copy the example environment file and configure the environment variables:
    ```sh
    cp .env.example .env
    ```

5. Generate the application key:
    ```sh
    php artisan key:generate
    ```

6. Run database migrations:
    ```sh
    php artisan migrate
    ```

7. Seed the database with initial data:
    ```sh
    php artisan db:seed
    ```

## Running the Project

1. Start the local development server:
    ```sh
    php artisan serve
    ```

2. Compile the assets:
    ```sh
    npm run dev
    ```

3. Access the application in your browser at `http://localhost:8000`.

## Running Tests

To run the tests, use the following command:
```sh
php artisan test
