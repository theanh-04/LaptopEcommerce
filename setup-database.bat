@echo off
echo ========================================
echo   LAPTOP ECOMMERCE - DATABASE SETUP
echo ========================================
echo.

echo [1/4] Checking environment...
if not exist ".env" (
    echo ERROR: .env file not found!
    echo Please copy .env.example to .env and configure database settings.
    pause
    exit /b 1
)

echo [2/4] Dropping existing tables and running migrations...
php artisan migrate:fresh
if %errorlevel% neq 0 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)

echo.
echo [3/4] Seeding database with sample data...
php artisan db:seed
if %errorlevel% neq 0 (
    echo ERROR: Seeding failed!
    pause
    exit /b 1
)

echo.
echo [4/4] Database setup completed successfully!
echo.
echo ========================================
echo   DATABASE READY TO USE
echo ========================================
echo.
echo You can now run: php artisan serve
echo.
pause
