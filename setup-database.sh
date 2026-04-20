#!/bin/bash

echo "========================================"
echo "  LAPTOP ECOMMERCE - DATABASE SETUP"
echo "========================================"
echo ""

echo "[1/4] Checking environment..."
if [ ! -f ".env" ]; then
    echo "ERROR: .env file not found!"
    echo "Please copy .env.example to .env and configure database settings."
    exit 1
fi

echo "[2/4] Dropping existing tables and running migrations..."
php artisan migrate:fresh
if [ $? -ne 0 ]; then
    echo "ERROR: Migration failed!"
    exit 1
fi

echo ""
echo "[3/4] Seeding database with sample data..."
php artisan db:seed
if [ $? -ne 0 ]; then
    echo "ERROR: Seeding failed!"
    exit 1
fi

echo ""
echo "[4/4] Database setup completed successfully!"
echo ""
echo "========================================"
echo "  DATABASE READY TO USE"
echo "========================================"
echo ""
echo "You can now run: php artisan serve"
echo ""
