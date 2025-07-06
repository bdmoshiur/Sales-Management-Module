
## Installation & Setup

### 1. Clone the Repository & Install Dependencies

```bash
git clone https://github.com/bdmoshiur/Sales-Management-Module.git
cd Sales-Management-Module
composer update
````

### 2. Configure Environment

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

This will:

* Drop all tables
* Recreate them from migrations
* Seed demo data (users, products, etc.)

### 4. Start the Development Server

```bash
php artisan serve
```

Now open your browser and visit:

```
http://127.0.0.1:8000/sales
```

This will take you to the **Sales Management Module** dashboard.

