# Shopping Cart & Order Workflow (Laravel 12 + React)

This project implements a **shopping cart and order management system** using Laravel 12 (backend) and React (frontend).  
It enforces **one active cart per user**, supports checkout into `orders`, and triggers **low stock notifications**.

---

# Setup

- Pre-requisites:
PHP 8+, MySQL, Composer, NPM

Clone the repository onto local directory


- Copy `.env.example` to `.env`
$ cp .env.example .env

- Set application key
$ php artisan key:generate

- Create a database in MySQL
- Set database credentials

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=ecom-cart-project

DB_USERNAME=root

DB_PASSWORD=


- Set APP_URL in `.env`

APP_URL=http://127.0.0.1:8000


- Set `SESSION_DOMAIN` in `.env`

SESSION_DOMAIN=127.0.0.1


- Set `QUEUE_CONNECTION` in `.env`

QUEUE_CONNECTION=database


- Configure e-mail settings in `.env`

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="admin@example.com"
MAIL_FROM_NAME="Ecom Admin"
ADMIN_EMAIL=admin@example.com


- Set `SANCTUM_STATEFUL_DOMAINS` in `.env`

SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8000,localhost:5173


- Set `LOW_STOCK_THRESHOLD` in `.env`

LOW_STOCK_THRESHOLD=5


- Create Job/Queue Table

$ php artisan queue:table


- Run database migrations

$ php artisan migrate


- Seed sample products

$ php artisan db:seed


---

# Run application

- Navigate to project directory
- Open CMD/Terminal and run `npm` command to start front-end

$ npm run dev


- Open another CMD/Terminal and run Job/Queue

$ php artisan queue:work


- Open one more CMD/Terminal and run `serve` command

$ php artisan serve


- Use this command for daily sales report

$ php artisan report:daily-sales
