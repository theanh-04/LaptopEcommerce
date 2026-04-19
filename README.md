# NEON KINETIC - Laptop E-commerce

Hệ thống quản lý và bán hàng laptop với giao diện hiện đại.

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (cho Tailwind CSS)

## Cài đặt nhanh (Quick Setup)

### 1. Clone repository

```bash
git clone https://github.com/theanh-04/Mua-b-n-laptop.git
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

Cập nhật thông tin database trong file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laptop_shop
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Tạo database

Tạo database `laptop_shop` trong MySQL:

```sql
CREATE DATABASE laptop_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Chạy migration và seeder (1 lệnh duy nhất)

```bash
php artisan migrate:fresh --seed
```

**Lệnh này sẽ:**
- Xóa toàn bộ tables cũ (nếu có)
- Tạo lại tất cả tables từ migrations
- Chạy tất cả seeders để tạo dữ liệu mẫu:
  - Categories (Gaming, Business, Creative, Student)
  - Brands (Apple, Dell, HP, Lenovo, Asus, MSI, Acer, Razer)
  - Laptops (30+ sản phẩm mẫu)
  - Employees (12 nhân viên)
  - Customers (10 khách hàng với loyalty tiers)
  - Promotions (6 mã khuyến mãi)
  - Orders (dữ liệu đơn hàng mẫu)
  - Admin user

### 6. Chạy ứng dụng

```bash
php artisan serve
```

Truy cập: **http://localhost:8000**

## Tài khoản mặc định

### Admin
- Email: `admin@neonkinetic.com`
- Password: `admin123`
- Truy cập: http://localhost:8000/admin/dashboard

### Customer
- Tự đăng ký tại: http://localhost:8000/register
- Hoặc dùng các customer từ seeder

## Cấu trúc Database

### Tables chính:

#### 1. `users` - Tài khoản người dùng
- id, name, email, password, role (admin/customer), phone, address

#### 2. `categories` - Danh mục sản phẩm
- id, name, slug, description

#### 3. `brands` - Thương hiệu
- id, name, slug, description, logo, website

#### 4. `laptops` - Sản phẩm laptop
- id, sku, category_id, brand_id, name, slug
- description, cpu, ram, storage, display, graphics
- price, stock, image, is_featured

#### 5. `orders` - Đơn hàng
- id, order_number, user_id, employee_id
- customer_name, customer_email, customer_phone, customer_address
- total_amount, status, promo_code, discount_amount, shipping_fee

#### 6. `order_items` - Chi tiết đơn hàng
- id, order_id, laptop_id, quantity, price

#### 7. `employees` - Nhân viên
- id, name, email, phone, position, department
- hire_date, salary, is_active, address, avatar

#### 8. `customers` - Khách hàng (Loyalty Program)
- id, name, email, phone, address, date_of_birth, gender
- loyalty_points, tier (bronze/silver/gold/platinum)
- total_spent, total_orders, last_purchase_at

#### 9. `promotions` - Mã khuyến mãi
- id, code, name, description, type (percentage/fixed)
- value, min_order_amount, max_discount
- usage_limit, used_count, start_date, end_date, is_active

## Tính năng

### 🛍️ Khách hàng (Customer):
- ✅ Xem danh sách sản phẩm với bộ lọc nâng cao
- ✅ Tìm kiếm theo tên, giá, CPU, RAM, Storage
- ✅ Lọc theo danh mục, thương hiệu, khoảng giá
- ✅ Sắp xếp (mới nhất, giá, tên)
- ✅ Xem chi tiết sản phẩm
- ✅ Thêm vào giỏ hàng
- ✅ Đặt hàng với mã khuyến mãi
- ✅ Tính phí ship tự động (miễn phí nếu >= 10M)
- ✅ Xem lịch sử đơn hàng
- ✅ Quản lý thông tin cá nhân (Profile)
- ✅ Đổi mật khẩu
- ✅ Đăng ký/Đăng nhập

### 👨‍💼 Admin:
- ✅ **Dashboard** - Thống kê real-time với dữ liệu thực
  - Tổng doanh thu với % tăng trưởng
  - Đơn hàng đang xử lý
  - Sản phẩm bán chạy nhất
  - Biểu đồ doanh thu 7 ngày
  - Hoạt động gần đây

- ✅ **Quản lý sản phẩm (Inventory)**
  - CRUD sản phẩm
  - Upload ảnh
  - Quản lý tồn kho
  - Đánh dấu sản phẩm nổi bật (featured)
  - Filter theo brand, category

- ✅ **Quản lý đơn hàng (Orders)**
  - Xem danh sách đơn hàng
  - Xem chi tiết đơn hàng
  - Cập nhật trạng thái (pending → processing → completed)
  - Gán nhân viên xử lý
  - In hóa đơn (invoice)
  - Hủy đơn hàng
  - Tìm kiếm và lọc

- ✅ **Quản lý nhân viên (Employees)**
  - CRUD nhân viên
  - Theo dõi số đơn hàng đã xử lý
  - Tìm kiếm và lọc theo phòng ban

- ✅ **Quản lý khách hàng (Customers)**
  - CRUD khách hàng
  - Loyalty program (Bronze/Silver/Gold/Platinum)
  - Theo dõi điểm thưởng
  - Xem lịch sử mua hàng
  - Tìm kiếm và lọc

- ✅ **Quản lý thương hiệu (Brands)**
  - CRUD thương hiệu
  - Upload logo

- ✅ **Quản lý khuyến mãi (Promotions)**
  - CRUD mã khuyến mãi
  - Loại giảm giá: % hoặc số tiền cố định
  - Giới hạn sử dụng
  - Đơn hàng tối thiểu
  - Giảm giá tối đa
  - Thời gian hiệu lực

- ✅ **POS (Point of Sale)**
  - Bán hàng trực tiếp
  - Tìm kiếm sản phẩm nhanh
  - Áp dụng mã khuyến mãi
  - Tự động trừ tồn kho

- ✅ **Báo cáo (Reports)**
  - Lọc theo thời gian (7 ngày, 30 ngày, 1 năm)
  - Tổng quan: doanh thu, đơn hàng, hoàn thành, hủy
  - Biểu đồ doanh thu theo ngày
  - Biểu đồ đơn hàng theo trạng thái
  - Top 10 sản phẩm bán chạy
  - Top 10 khách hàng
  - Doanh thu theo danh mục
  - In báo cáo

## Công nghệ sử dụng

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL 8.0
- **Authentication**: Laravel custom auth
- **Icons**: Material Symbols
- **Fonts**: Space Grotesk, Manrope

## Git Workflow

### Branches:
- `main` - Production
- `khanh2404/*` - Features by khanh2404
- `theanh-04/*` - Features by theanh-04
- `theanh204/*` - Features by theanh204

### Commit format:
```
username/type: Description

Examples:
- khanh2404/feat: Thêm quản lý nhân viên
- theanh-04/fix: Sửa lỗi checkout
- theanh204/feat: Tạo trang profile
```

### Các nhánh đã hoàn thành:
1. `khanh2404/feat-hoan-thien-quan-ly-nhan-vien` - Quản lý nhân viên
2. `khanh2404/feat-quan-ly-khuyen-mai` - Quản lý khuyến mãi
3. `theanh-04/feat-quan-ly-khach-hang` - Quản lý khách hàng
4. `theanh-04/feat-auth-system` - Hệ thống đăng ký/đăng nhập
5. `theanh-04/feat-checkout-process` - Quy trình đặt hàng
6. `khanh2404/feat-hoan-thien-dashboard` - Dashboard với dữ liệu thực
7. `khanh2404/feat-hoan-thien-orders` - Quản lý đơn hàng + Tìm kiếm/lọc sản phẩm
8. `khanh2404/feat-trang-profile-khach-hang` - Trang profile khách hàng

## Troubleshooting

### Lỗi migration
```bash
php artisan migrate:fresh --seed
```

### Lỗi permission (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Lỗi "Class not found"
```bash
composer dump-autoload
```

### Reset database hoàn toàn
```bash
php artisan migrate:fresh --seed
```

## API Endpoints

### Public Routes
- GET `/` - Trang chủ
- GET `/laptops` - Danh sách sản phẩm
- GET `/laptops/{slug}` - Chi tiết sản phẩm
- GET `/cart` - Giỏ hàng
- POST `/cart/add/{id}` - Thêm vào giỏ
- DELETE `/cart/remove/{id}` - Xóa khỏi giỏ
- GET `/checkout` - Trang thanh toán
- POST `/order` - Tạo đơn hàng
- POST `/order/check-promo` - Kiểm tra mã khuyến mãi

### Auth Routes
- GET `/login` - Trang đăng nhập
- POST `/login` - Xử lý đăng nhập
- GET `/register` - Trang đăng ký
- POST `/register` - Xử lý đăng ký
- POST `/logout` - Đăng xuất

### Profile Routes (Auth required)
- GET `/profile` - Trang profile
- GET `/profile/edit` - Chỉnh sửa profile
- PUT `/profile` - Cập nhật profile
- GET `/profile/change-password` - Đổi mật khẩu
- POST `/profile/change-password` - Xử lý đổi mật khẩu
- GET `/my-orders` - Lịch sử đơn hàng

### Admin Routes (Auth + Admin required)
- GET `/admin/dashboard` - Dashboard
- GET `/admin/inventory` - Quản lý sản phẩm
- GET `/admin/orders` - Quản lý đơn hàng
- POST `/admin/orders/{id}/status` - Cập nhật trạng thái
- POST `/admin/orders/{id}/assign` - Gán nhân viên
- GET `/admin/orders/{id}/invoice` - In hóa đơn
- GET `/admin/employees` - Quản lý nhân viên
- GET `/admin/customers` - Quản lý khách hàng
- GET `/admin/brands` - Quản lý thương hiệu
- GET `/admin/promotions` - Quản lý khuyến mãi
- GET `/admin/pos` - POS
- GET `/admin/reports` - Báo cáo

## Team

- **khanh2404** (khanhthui2404@gmail.com)
  - Dashboard với dữ liệu thực
  - Báo cáo chi tiết
  - Quản lý đơn hàng (gán nhân viên, in hóa đơn)
  - Tìm kiếm và lọc sản phẩm nâng cao

- **theanh-04** (Theang1405@gmail.com)
  - Hệ thống đăng ký/đăng nhập
  - Quy trình đặt hàng với promo code
  - Quản lý khách hàng với loyalty program
  - Featured products

- **theanh204** (20224145@eaut.edu.vn)
  - Trang profile khách hàng
  - Chỉnh sửa thông tin cá nhân
  - Đổi mật khẩu
  - UI/UX improvements

## License

Private project for educational purposes.

---

**Phiên bản**: 1.0.0  
**Cập nhật**: 19/04/2026
