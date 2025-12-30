# 🛒 E-Commerce Shopping Cart Application

A modern, full-featured e-commerce shopping cart application built with Laravel 12, Vue 3, Inertia.js, and Tailwind CSS.

## 📋 Table of Contents

-   [Features](#features)
-   [Technology Stack](#technology-stack)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Running the Application](#running-the-application)
-   [Default Credentials](#default-credentials)
-   [Project Structure](#project-structure)
-   [Key Features Explained](#key-features-explained)
-   [Testing](#testing)
-   [Troubleshooting](#troubleshooting)
-   [Support](#support)

---

## ✨ Features

### Core E-Commerce Features

-   ✅ **User Authentication** - Complete registration, login, and profile management
-   ✅ **Product Catalog** - Browse products with real-time stock information
-   ✅ **Shopping Cart** - Add, update, and remove items (user-specific, database-backed)
-   ✅ **Checkout System** - Complete order processing with stock management
-   ✅ **Order History** - View past orders and order details
-   ✅ **Responsive Design** - Works on desktop, tablet, and mobile devices

### Advanced Features

-   ✅ **Low Stock Notifications** - Automated email alerts when product stock drops below 5 units
-   ✅ **Daily Sales Reports** - Automated daily email reports sent at 11 PM
-   ✅ **Stock Management** - Real-time stock tracking and validation
-   ✅ **Price Snapshots** - Order prices are preserved at time of purchase

---

## 🛠️ Technology Stack

### Backend

-   **Laravel 12.x** - PHP framework
-   **MySQL** - Database
-   **Laravel Breeze** - Authentication scaffolding
-   **Inertia.js** - Modern monolith architecture (no API needed)

### Frontend

-   **Vue 3** - Progressive JavaScript framework
-   **Tailwind CSS** - Utility-first CSS framework
-   **Vite** - Next-generation frontend tooling
-   **Inertia.js** - SPA without building an API

### Additional Tools

-   **Laravel Queue** - Background job processing
-   **Laravel Scheduler** - Automated task scheduling
-   **Laravel Mail** - Email notifications

---

## 📦 Requirements

Before you begin, ensure you have the following installed:

-   **PHP** 8.2 or higher
-   **Composer** (PHP package manager)
-   **Node.js** 22.x or higher
-   **NPM** (comes with Node.js)
-   **MySQL** 5.7 or higher
-   **Git** (optional, for version control)

### Verify Installation

```bash
php -v          # Should show PHP 8.2+
composer -v     # Should show Composer version
node -v         # Should show Node.js 22.x+
npm -v          # Should show NPM version
mysql --version # Should show MySQL version
```

---

## 🚀 Installation

### Step 1: Clone or Download the Project

If using Git:

```bash
git clone <repository-url>
cd ecommerce-cart
```

Or extract the project files to your desired directory.

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all Laravel and PHP packages.

### Step 3: Install JavaScript Dependencies

```bash
npm install --legacy-peer-deps
```

**Note**: The `--legacy-peer-deps` flag is required due to Vite 7.x compatibility.

### Step 4: Configure Environment

Copy the example environment file:

```bash
copy .env.example .env
```

Or on Linux/Mac:

```bash
cp .env.example .env
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Configure Database

Open the `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_cart
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

**Important**: Create the database in MySQL before proceeding:

```sql
CREATE DATABASE ecommerce_cart;
```

### Step 7: Run Migrations and Seed Database

```bash
php artisan migrate:fresh --seed
```

This will:

-   Create all database tables
-   Create test users
-   Create 10 sample products
-   Create sample orders and carts

### Step 8: Configure Email (Optional)

For email notifications to work, configure mail settings in `.env`:

**For Development (Log Driver - Emails saved to log file):**

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**For Production (SMTP):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🎯 Running the Application

### Development Mode

You need to run **two terminal windows**:

**Terminal 1 - Laravel Server:**

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

**Terminal 2 - Vite Dev Server (Hot Reload):**

```bash
npm run dev
```

This enables hot module replacement for instant updates.

### Access the Application

1. Open your browser and navigate to: `http://localhost:8000`
2. You'll see the welcome page
3. Click **Login** or **Register** to get started

### Alternative: All-in-One Command

If you have `concurrently` installed, you can run:

```bash
composer dev
```

This starts Laravel server, queue worker, logs, and Vite dev server together.

---

## 👤 Default Credentials

After seeding the database, you can use these test accounts:

### Admin User

-   **Email**: `vishal.s@yopmail.com`
-   **Password**: `password`
-   **Purpose**: Receives low stock alerts and daily sales reports

### Test User

-   **Email**: `test@example.com`
-   **Password**: `password`
-   **Purpose**: Regular user for testing shopping features

### Additional Users

-   5 randomly generated users
-   All passwords: `password`

---

## 📁 Project Structure

```
ecommerce-cart/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendDailyReport.php      # Daily sales report command
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CartController.php      # Cart operations
│   │   │   ├── OrderController.php     # Order processing
│   │   │   └── ProductController.php    # Product listing
│   │   └── Middleware/
│   ├── Jobs/
│   │   └── SendLowStockNotification.php # Low stock alert job
│   ├── Mail/
│   │   ├── DailySalesReport.php         # Sales report email
│   │   └── LowStockAlert.php            # Low stock email
│   └── Models/
│       ├── Cart.php                      # Cart model
│       ├── CartItem.php                  # Cart item model
│       ├── Order.php                     # Order model
│       ├── OrderItem.php                 # Order item model
│       ├── Product.php                   # Product model
│       └── User.php                      # User model
│
├── database/
│   ├── migrations/                       # Database migrations
│   └── seeders/                          # Database seeders
│
├── resources/
│   ├── js/
│   │   ├── Components/                   # Reusable Vue components
│   │   ├── Layouts/                      # Page layouts
│   │   └── Pages/
│   │       ├── Cart/                     # Cart pages
│   │       ├── Orders/                   # Order pages
│   │       └── Shop.vue                  # Product catalog
│   └── views/
│       └── emails/                       # Email templates
│
├── routes/
│   ├── web.php                           # Web routes
│   └── console.php                       # Scheduled tasks
│
└── .env                                  # Environment configuration
```

---

## 🔑 Key Features Explained

### Shopping Cart System

-   Each user has their own cart stored in the database
-   Cart is automatically created when user first adds an item
-   All cart operations (add, update, remove) are tied to the authenticated user
-   No session or local storage - everything is database-backed

### Order Processing

-   When user checks out:
    1. Order is created with total price
    2. Order items are created with price snapshots
    3. Product stock is decremented
    4. Cart is emptied
    5. Low stock check triggers notification if needed

### Low Stock Notifications

-   Automatically triggered when product stock drops below 5 units
-   Uses Laravel Queue for asynchronous processing
-   Email sent to: `vishal.s@yopmail.com`
-   Includes product details and current stock level

### Daily Sales Reports

-   Scheduled to run every day at 11:00 PM (23:00)
-   Queries all orders created that day
-   Calculates statistics:
    -   Total orders
    -   Total revenue
    -   Items sold
    -   Top selling products
-   Email sent to: `vishal.s@yopmail.com`

---

## 🧪 Testing

### Test Shopping Flow

1. **Login** as test user: `test@example.com` / `password`
2. **Browse Products** - Go to Shop page
3. **Add to Cart** - Click "Add to Cart" on products
4. **View Cart** - Click "Cart" in navigation
5. **Update Quantities** - Change quantity and press Enter
6. **Remove Items** - Click "Remove" button
7. **Checkout** - Click "Proceed to Checkout"
8. **View Order** - See order confirmation
9. **Order History** - View all past orders

### Test Low Stock Notification

1. Add products to cart that will reduce stock below 5
2. Complete checkout
3. Check queue: `php artisan queue:work` (if running)
4. Check email at `vishal.s@yopmail.com`

### Test Daily Sales Report

Run manually:

```bash
php artisan app:send-daily-report
```

Check scheduled tasks:

```bash
php artisan schedule:list
```

---

## 🔧 Common Commands

### Database

```bash
# Run migrations
php artisan migrate

# Reset database and seed
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder
```

### Development

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server
npm run dev

# Build for production
npm run build
```

### Queue (for notifications)

```bash
# Start queue worker
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Cache

```bash
# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize
```

---

## 🐛 Troubleshooting

### Issue: "Database connection error"

**Solution:**

1. Check MySQL is running
2. Verify database credentials in `.env`
3. Ensure database exists: `CREATE DATABASE ecommerce_cart;`
4. Test connection: `php artisan db:show`

### Issue: "Vite manifest not found"

**Solution:**

```bash
npm run build
```

### Issue: "Port 8000 already in use"

**Solution:**

```bash
php artisan serve --port=8080
```

### Issue: "Class not found" or "Route not found"

**Solution:**

```bash
composer dump-autoload
php artisan optimize:clear
```

### Issue: "Permission denied" (Linux/Mac)

**Solution:**

```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: "NPM install fails"

**Solution:**

```bash
npm install --legacy-peer-deps
```

### Issue: "Email not sending"

**Solution:**

1. Check `.env` mail configuration
2. For development, use `MAIL_MAILER=log` (emails saved to `storage/logs/laravel.log`)
3. For production, configure SMTP settings

---

## 📧 Email Configuration

### Development Setup (Log Driver)

Emails will be saved to log file instead of being sent:

```env
MAIL_MAILER=log
```

View emails in: `storage/logs/laravel.log`

### Production Setup (SMTP)

Configure with your email provider:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="E-Commerce Store"
```

### Testing with Mailtrap

1. Sign up at https://mailtrap.io
2. Get SMTP credentials
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

---

## 🚀 Production Deployment

### Build Assets

```bash
npm run build
```

### Optimize Application

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Set Up Cron Job

Add to server crontab:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Set Up Queue Worker

For production, use a process manager like Supervisor to keep queue worker running:

```bash
php artisan queue:work --tries=3
```

### Environment Variables

Ensure `.env` has:

-   `APP_ENV=production`
-   `APP_DEBUG=false`
-   Proper database credentials
-   Proper mail configuration
-   `APP_URL` set correctly

---

## 📊 Database Schema

### Main Tables

-   **users** - User accounts
-   **products** - Product catalog
-   **carts** - User shopping carts
-   **cart_items** - Items in carts
-   **orders** - Completed orders
-   **order_items** - Items in orders (with price snapshots)

### Relationships

-   User → hasOne → Cart
-   User → hasMany → Orders
-   Cart → hasMany → CartItems
-   CartItem → belongsTo → Product
-   Order → hasMany → OrderItems
-   OrderItem → belongsTo → Product

---

## 🔐 Security Features

-   ✅ **Authentication Required** - All cart/order routes protected
-   ✅ **CSRF Protection** - Automatic with Inertia.js
-   ✅ **User Ownership Verification** - Users can only access their own data
-   ✅ **Password Hashing** - Bcrypt encryption
-   ✅ **SQL Injection Prevention** - Eloquent ORM
-   ✅ **XSS Protection** - Vue.js automatic escaping

---

## 📝 Important Notes

### Cart Persistence

-   Carts are stored in the database, not in session or local storage
-   Each user has exactly one cart
-   Cart persists across browser sessions
-   Cart is automatically created when user first adds an item

### Stock Management

-   Stock is validated before adding to cart
-   Stock is validated before checkout
-   Stock is decremented after successful checkout
-   Low stock alerts trigger when stock < 5

### Order Processing

-   Orders are created with price snapshots
-   Stock is decremented atomically (within transaction)
-   Cart is emptied after successful checkout
-   Orders are permanent records

---

## 🎯 Quick Start Summary

```bash
# 1. Install dependencies
composer install
npm install --legacy-peer-deps

# 2. Configure environment
copy .env.example .env
php artisan key:generate

# 3. Update .env with database credentials

# 4. Create database in MySQL
CREATE DATABASE ecommerce_cart;

# 5. Run migrations and seed
php artisan migrate:fresh --seed

# 6. Start servers (2 terminals)
php artisan serve
npm run dev

# 7. Open browser
http://localhost:8000

# 8. Login
Email: test@example.com
Password: password
```

---

## 📞 Support & Resources

### Documentation

-   **Laravel**: https://laravel.com/docs
-   **Vue 3**: https://vuejs.org/
-   **Inertia.js**: https://inertiajs.com/
-   **Tailwind CSS**: https://tailwindcss.com/docs

### Application Routes

| Route          | Description       |
| -------------- | ----------------- |
| `/`            | Welcome page      |
| `/shop`        | Product catalog   |
| `/cart`        | Shopping cart     |
| `/orders`      | Order history     |
| `/orders/{id}` | Order details     |
| `/login`       | Login page        |
| `/register`    | Registration page |
| `/profile`     | User profile      |

---

## ✅ System Status

**Current Version**: 1.0.0  
**Laravel Version**: 12.x  
**Vue Version**: 3.4+  
**PHP Version**: 8.2+  
**Status**: ✅ Production Ready

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

Built with:

-   Laravel Framework
-   Vue.js
-   Inertia.js
-   Tailwind CSS
-   And many other open-source packages

---

**Happy Shopping! 🛒**

For questions or issues, please refer to the troubleshooting section or check the Laravel/Vue documentation.
