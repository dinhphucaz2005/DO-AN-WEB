# Ứng dụng Giao Đồ Ăn Nhanh (FastFood Delivery)

Mô tả: dự án Laravel tối giản cho một doanh nghiệp giao đồ ăn nhanh.

Thư mục chính được thêm/ổn định trong repository này:

- `app/Http/Controllers/HomeController.php` — controller cho trang chính
- `app/Models/Product.php` — model hàng hoá/menu
- `database/migrations/2025_11_08_000000_create_products_table.php` — migration khởi tạo bảng products
- `public/css/app.css` — file CSS cơ bản
- `public/js/app.js` — mã JS (dùng jQuery)
- `resources/views/layouts/app.blade.php` — layout chính
- `resources/views/home.blade.php` — view trang chủ
- `routes/web.php` — route cho trang chủ

Hướng dẫn nhanh (yêu cầu: PHP, Composer, MySQL/sqlite):

1. Cài phụ thuộc:

   composer install

2. Tạo file môi trường và khóa ứng dụng:

   cp .env.example .env
   php artisan key:generate

3. Chạy migration:

   php artisan migrate

4. Chạy server dev:

   php artisan serve

Mở http://127.0.0.1:8000

Ghi chú: Đây là scaffold ban đầu. Bạn có thể thêm controllers, models, views, và API endpoints theo nhu cầu.
