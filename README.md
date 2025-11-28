# 🎨 Meme Creator - Tạo Meme Online Miễn Phí

Ứng dụng web tạo meme online đơn giản và hiệu quả. Upload ảnh, thêm text và emoji để tạo ra những meme hài hước!

## ✨ Tính năng chính

- 📁 **Upload ảnh**: Kéo thả hoặc click để tải ảnh lên
- ✏️ **Thêm text**: Text trên và dưới với nhiều tuỳ chọn
- 😀 **Thư viện emoji**: 16+ emoji và sticker phổ biến
- 🎨 **Tuỳ chỉnh**: Font size, màu chữ, viền text
- 💾 **Download**: Lưu meme dưới dạng PNG
- 📱 **Responsive**: Hoạt động tốt trên mọi thiết bị

## 🛠️ Cấu trúc dự án

- `app/Http/Controllers/MemeController.php` — controller cho meme editor
- `public/css/app.css` — CSS styling cho ứng dụng
- `public/js/app.js` — JavaScript functionality
- `resources/views/layouts/app.blade.php` — layout chính
- `resources/views/meme-editor.blade.php` — giao diện tạo meme
- `routes/web.php` — routing

## 🚀 Hướng dẫn chạy

### Trên Linux (dnf):
```bash
chmod +x run.sh
./run.sh
```

### Trên Windows (PowerShell):
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
.\windows.ps1
```

### Thủ công:
1. Cài dependencies:
   ```bash
   composer install
   ```

2. Tạo file môi trường:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Chạy server:
   ```bash
   php artisan serve
   ```

4. Mở http://127.0.0.1:8000

## 🎯 Cách sử dụng

1. **Upload ảnh**: Kéo thả ảnh vào vùng upload hoặc click để chọn
2. **Thêm text**: Nhập text trên/dưới và tuỳ chỉnh font, màu
3. **Thêm emoji**: Click emoji từ thư viện, sau đó click vào canvas để đặt
4. **Download**: Click nút "💾 Download" để lưu meme

## 🔧 Yêu cầu hệ thống

- PHP 8.0+
- Composer
- SQLite (hoặc MySQL/PostgreSQL)
- Extension: php-sqlite3, php-mbstring, php-xml

## 🌟 Tính năng nâng cao

- Phím tắt Ctrl+S để download nhanh
- Canvas tương tác với khả năng đặt emoji tại bất kỳ vị trí nào
- Responsive design cho mobile/tablet
- Hỗ trợ nhiều định dạng ảnh đầu vào
