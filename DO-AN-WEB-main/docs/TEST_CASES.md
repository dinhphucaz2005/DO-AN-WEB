# TEST CASES (Ngắn gọn)

Tài liệu này chứa các test case thủ công ngắn gọn, dễ đọc, dùng cho QA nhanh.

---

## TC-001: Tạo meme (upload + canvas JSON)
- Tiền đề: Người dùng đã đăng nhập.
- Bước:
  1. Mở editor (/meme/create).
  2. Upload ảnh, nhập tiêu đề `adsaaa`, mô tả `saaaaaa`.
  3. Thêm sticker, nhấn `Save`.
- Kết quả mong đợi:
  - Redirect tới trang bài viết; DB có `memes.title = 'adsaaa'`.
  - Ảnh được lưu và hiển thị.
- Cleanup: xóa bản ghi & file test.

---

## TC-002: Validation (thiếu tiêu đề hoặc ảnh)
- Tiền đề: Người dùng đã đăng nhập.
- Bước:
  1. Gửi form tạo meme mà không có `title` hoặc `image`.
- Kết quả mong đợi:
  - Hiển thị lỗi validation cho trường bị thiếu (session errors hoặc 422 JSON).

---

## TC-003: Lưu / Xuất ảnh (Export)
- Tiền đề: Có một meme đã tạo sẵn với `image` và `canvas_json`.
- Bước:
  1. Mở trang bài viết và nhấn nút Export/Download nếu có.
- Kết quả mong đợi:
  - Trả về content-type image/* và file ảnh có thể tải về.

---

## TC-004: Hiển thị gallery / tiêu đề & mô tả
- Tiền đề: Có nhiều meme public trong DB.
- Bước:
  1. Mở trang gallery (/memes/public).
- Kết quả mong đợi:
  - Tiêu đề (`h3`) hiển thị màu trắng.
  - Mô tả hiển thị màu trắng và kích thước đã điều chỉnh.

---

Ghi chú ngắn:
- Mỗi test nên kèm thông tin môi trường (browser, phiên bản PHP, Docker nếu dùng).
- Với các test UI phức tạp (kéo/thả, undo/redo), dùng Dusk/Cypress cho E2E.

