

### 1. Clone the Repository & Install Dependencies

```bash
git clone https://github.com/your-org/your-repo.git
cd your-repo
composer update
````

### 2. Configure Environment

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Then update your `.env` file with correct database credentials:

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

* Drop existing tables (if any)
* Recreate tables from migrations
* Seed demo data (e.g., users, products)

### 4. Start the Development Server

```bash
php artisan serve
```

Then open your browser and navigate to:

```
http://127.0.0.1:8000/sales
```

This will load the **Sales Management Dashboard**.
