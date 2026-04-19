# Hướng dẫn Setup Dự án Laptop Ecommerce

## Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM

## Các bước cài đặt

### 1. Clone dự án
```bash
git clone https://github.com/theanh-04/LaptopEcommerce.git
cd LaptopEcommerce
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình database trong file .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laptop_shop
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Tạo database
Tạo database tên `laptop_shop` trong MySQL

### 6. Chạy migration và seeder
```bash
php artisan migrate
php artisan db:seed
php artisan db:seed --class=UpdateLaptopsSeeder
```

### 7. Chạy server
```bash
php artisan serve
```

## Các link quan trọng

### Giao diện người dùng
- Trang chủ: http://localhost:8000
- Danh sách laptop: http://localhost:8000/laptops
- Giỏ hàng: http://localhost:8000/cart
- Thanh toán: http://localhost:8000/checkout

### Giao diện Admin
- Dashboard: http://localhost:8000/admin/dashboard
- Quản lý nhân viên: http://localhost:8000/admin/employees
- Quản lý đơn hàng (Kanban): http://localhost:8000/admin/orders
- Quản lý kho: http://localhost:8000/admin/inventory
- POS (Bán hàng): http://localhost:8000/admin/pos

## Tính năng đã hoàn thành

### Nhánh `main` - Giao diện người dùng (theanh-04)
✅ Trang chủ với danh sách sản phẩm
✅ Trang chi tiết sản phẩm
✅ Giỏ hàng (session-based)
✅ Thanh toán và tạo đơn hàng
✅ Trang thành công sau thanh toán

### Nhánh `khanh/feat-them-giao-dien-admin` - Admin cơ bản (khanhh2404)
✅ Layout admin với Neon Kinetic theme
✅ Sidebar navigation
✅ Top navigation bar
✅ Dashboard với stats và biểu đồ
✅ Quản lý nhân viên (Employees)
✅ Components tái sử dụng (head, sidebar, topbar)

### Nhánh `theanh204/feat-them-man-hinh-quan-ly` - Admin nâng cao (Theeanh204)
✅ **Quản lý đơn hàng (Orders)**
  - Kanban board với 3 cột: Chờ duyệt, Đang xử lý, Đã giao
  - Hiển thị chi tiết đơn hàng
  - Cập nhật trạng thái đơn hàng
  - Tích hợp database thật

✅ **Quản lý kho (Inventory)**
  - Danh sách sản phẩm với SKU
  - Hiển thị tồn kho với progress bar
  - Filter theo thương hiệu và trạng thái
  - Cảnh báo sắp hết hàng
  - Cập nhật stock

✅ **POS (Point of Sale)**
  - Giao diện bán hàng trực tiếp
  - Grid sản phẩm với search
  - Giỏ hàng realtime
  - Xử lý thanh toán
  - Tự động trừ tồn kho
  - Tạo đơn hàng tự động

## Database Schema

### Bảng `laptops`
- id, sku, category_id, name, slug, description
- brand, processor, ram, storage, display, graphics
- price, stock, image, timestamps

### Bảng `orders`
- id, order_number, customer_name, customer_email
- customer_phone, customer_address, total_amount
- status (pending/processing/shipped/completed/cancelled)
- notes, timestamps

### Bảng `order_items`
- id, order_id, laptop_id, quantity, price, timestamps

### Bảng `categories`
- id, name, slug, description, timestamps

## API Endpoints

### Admin APIs
- POST `/admin/orders/{id}/status` - Cập nhật trạng thái đơn hàng
- POST `/admin/inventory/{id}/stock` - Cập nhật tồn kho
- POST `/admin/pos/payment` - Xử lý thanh toán POS

## Troubleshooting

### Lỗi database connection
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra thông tin trong file .env
- Đảm bảo database `laptop_shop` đã được tạo

### Lỗi migration
```bash
php artisan migrate:fresh --seed
```

### Lỗi không có dữ liệu
```bash
php artisan db:seed
php artisan db:seed --class=UpdateLaptopsSeeder
```

## Contributors
- **theanh-04** (Theang1405@gmail.com) - Giao diện người dùng
-    khanh2404              - Admin dashboard & employees
- **Theeanh204** (20224145@eaut.edu.vn) - Orders, Inventory, POS

## Tech Stack
- Laravel 11
- Tailwind CSS
- MySQL
- Blade Templates
- Material Symbols Icons
- Space Grotesk & Manrope Fonts
