# Website Bán Laptop - Laravel MVC

Website bán máy tính xách tay được xây dựng theo mô hình MVC với Laravel Framework.

## Công nghệ sử dụng

- **Backend**: Laravel 13 (PHP 8.3)
- **Frontend**: HTML5, CSS3, JavaScript, jQuery, Bootstrap 5
- **Database**: MySQL/SQLite
- **Kiến trúc**: MVC (Model-View-Controller)

## Tính năng

- Hiển thị danh sách laptop theo danh mục
- Tìm kiếm và lọc sản phẩm
- Xem chi tiết sản phẩm
- Giỏ hàng (Session-based)
- Đặt hàng
- Responsive design với Bootstrap

## Cấu trúc MVC

### Models
- `Category.php` - Quản lý danh mục
- `Laptop.php` - Quản lý sản phẩm laptop
- `Order.php` - Quản lý đơn hàng
- `OrderItem.php` - Quản lý chi tiết đơn hàng

### Controllers
- `HomeController.php` - Trang chủ
- `LaptopController.php` - Danh sách và chi tiết sản phẩm
- `CartController.php` - Giỏ hàng
- `OrderController.php` - Đặt hàng

### Views
- `layouts/app.blade.php` - Layout chính
- `home.blade.php` - Trang chủ
- `laptops/index.blade.php` - Danh sách sản phẩm
- `laptops/show.blade.php` - Chi tiết sản phẩm
- `cart/index.blade.php` - Giỏ hàng
- `orders/checkout.blade.php` - Thanh toán
- `orders/success.blade.php` - Đặt hàng thành công

## Cài đặt

1. Cài đặt dependencies:
```bash
composer install
npm install
```

2. Cấu hình database trong file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laptop_shop
DB_USERNAME=root
DB_PASSWORD=
```

3. Chạy migration và seeder:
```bash
php artisan migrate
php artisan db:seed
```

4. Khởi động server:
```bash
php artisan serve
npm run dev
```

5. Truy cập: `http://localhost:8000`

## Database Schema

- **categories**: id, name, slug, description
- **laptops**: id, category_id, name, slug, description, brand, processor, ram, storage, display, graphics, price, stock, image
- **orders**: id, customer_name, customer_email, customer_phone, customer_address, total_amount, status
- **order_items**: id, order_id, laptop_id, quantity, price

## Routes

- `GET /` - Trang chủ
- `GET /laptops` - Danh sách laptop
- `GET /laptops/{slug}` - Chi tiết laptop
- `GET /cart` - Giỏ hàng
- `POST /cart/add/{id}` - Thêm vào giỏ
- `DELETE /cart/remove/{id}` - Xóa khỏi giỏ
- `GET /checkout` - Trang thanh toán
- `POST /order` - Tạo đơn hàng
- `GET /order/success` - Đặt hàng thành công
