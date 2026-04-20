# Hướng dẫn Setup Database

## Yêu cầu
- PHP >= 8.1
- MySQL/MariaDB
- Composer

## Cách 1: Sử dụng Script Tự Động (Khuyến nghị)

### Windows
```bash
# Chạy file setup-database.bat
setup-database.bat
```

### Linux/Mac
```bash
# Cấp quyền thực thi
chmod +x setup-database.sh

# Chạy script
./setup-database.sh
```

## Cách 2: Chạy Thủ Công

### Bước 1: Cấu hình .env
```bash
# Copy file .env.example
cp .env.example .env

# Chỉnh sửa thông tin database trong .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laptop_shop
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 2: Tạo Database
```sql
-- Mở MySQL và chạy lệnh:
CREATE DATABASE laptop_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Bước 3: Generate Application Key
```bash
php artisan key:generate
```

### Bước 4: Chạy Migrations
```bash
# Xóa tất cả bảng cũ và tạo lại từ đầu
php artisan migrate:fresh
```

### Bước 5: Chạy Seeders
```bash
# Seed tất cả dữ liệu mẫu
php artisan db:seed
```

## Cách 3: Reset Database (Khi cần làm lại)

```bash
# Xóa tất cả và tạo lại database với dữ liệu mẫu
php artisan migrate:fresh --seed
```

## Dữ liệu mẫu sau khi seed

### Users
- **Admin**: admin@example.com / password
- **Customer**: customer@example.com / password

### Database bao gồm:
- ✅ Categories (Danh mục sản phẩm)
- ✅ Brands (Thương hiệu)
- ✅ Laptops (Sản phẩm laptop với đầy đủ thông tin)
- ✅ Customers (Khách hàng)
- ✅ Employees (Nhân viên)
- ✅ Promotions (Mã khuyến mãi)
- ✅ Orders (Đơn hàng mẫu)

## Troubleshooting

### Lỗi: "Access denied for user"
- Kiểm tra lại username/password trong file `.env`
- Đảm bảo MySQL đang chạy

### Lỗi: "Database does not exist"
- Tạo database thủ công: `CREATE DATABASE laptop_shop;`

### Lỗi: "Class not found"
- Chạy: `composer dump-autoload`

### Lỗi: "SQLSTATE[42000]: Syntax error"
- Kiểm tra phiên bản MySQL >= 5.7
- Kiểm tra charset trong .env: `DB_CHARSET=utf8mb4`

## Chạy ứng dụng

```bash
# Start development server
php artisan serve

# Truy cập:
# - Frontend: http://localhost:8000
# - Admin: http://localhost:8000/admin
```

## Lưu ý
- Script sẽ **XÓA TẤT CẢ** dữ liệu cũ trong database
- Backup database trước khi chạy nếu cần giữ dữ liệu
- Đảm bảo file `.env` đã được cấu hình đúng
