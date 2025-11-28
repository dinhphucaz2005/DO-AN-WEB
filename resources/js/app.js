import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/* ========== CUSTOM: Điều khiển âm thanh nền (Audio Control System) ==========
   - Bạn đã thêm chức năng bật/tắt âm thanh cho web
   - Mục đích: Phát file âm thanh từ public/audio/nyan_audio.mp3 (hoặc tệp khác)
   - Tính năng chính:
     1. Nút toggle (bật/tắt) trong header
     2. Lưu trạng thái vào localStorage (nhớ lựa chọn người dùng)
     3. Xử lý chính sách autoplay của trình duyệt
     4. Hiển thị biểu tượng khác nhau (🔊 / 🔈) tùy theo trạng thái
   =============================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    try {
        /* === BƯỚC 1: Tạo thẻ <audio> (Audio Element) === */
        var audio = document.createElement('audio');
        audio.id = 'site-audio';                    // ID để lấy element này nếu cần sau
        audio.src = '/audio/nyan_audio.mp3';        // Đường dẫn file âm thanh (LƯU Ý: THAY ĐỔI nếu file khác)
        audio.loop = true;                          // Lặp lại âm thanh vô hạn khi phát
        audio.preload = 'auto';                     // Tự động tải file âm thanh khi trang tải
        audio.style.display = 'none';               // Ẩn player (không hiển thị control mặc định của trình duyệt)
        document.body.appendChild(audio);            // Thêm vào DOM để có thể phát

        /* === BƯỚC 2: Lấy reference nút toggle === */
        var toggle = document.getElementById('audio-toggle');
        if (!toggle) return;                        // Nếu nút không tồn tại, dừng thực thi

        /* === BƯỚC 3: Đọc trạng thái đã lưu từ localStorage === */
        // localStorage: lưu trữ dữ liệu trên máy tính người dùng, tồn tại ngay cả khi đóng tab/trình duyệt
        // 'siteAudioPlaying': Khóa để lưu trạng thái phát (true/false)
        var isPlaying = localStorage.getItem('siteAudioPlaying') === 'true';

        /* === BƯỚC 4: Hàm cập nhật biểu tượng nút === */
        function updateButton() {
            // Nếu đang phát: 🔊 (loa phát), Nếu tắt: 🔈 (loa tắt tiếng)
            toggle.textContent = isPlaying ? '🔊' : '🔈';
        }

        /* === BƯỚC 5: Hàm thử phát âm thanh === */
        // Lý do: Nhiều trình duyệt (Chrome, Safari, Firefox) chặn autoplay âm thanh
        // Chỉ có thể phát sau khi người dùng tương tác (click, scroll, etc.)
        function tryPlay() {
            if (isPlaying) {
                audio.play().catch(function(err){
                    // Nếu phát không thành công (do chính sách autoplay), tắt trạng thái
                    console.warn('Autoplay bị chặn:', err);
                    isPlaying = false;
                    localStorage.setItem('siteAudioPlaying', 'false');
                    updateButton();
                });
            }
        }

        /* === BƯỚC 6: Khởi tạo nút và thiết lập event listener === */
        updateButton();  // Cập nhật icon khi trang tải dựa trên trạng thái đã lưu
        
        // Bắt sự kiện click đầu tiên (dấu hiệu người dùng tương tác) để thử phát âm
        // Điều này giúp vượt qua chính sách autoplay của trình duyệt
        document.addEventListener('click', function oncePlay() {
            tryPlay();
            // Chỉ chạy hàm này 1 lần, sau đó xóa listener để tránh gọi nhiều lần
            document.removeEventListener('click', oncePlay);
        });

        /* === BƯỚC 7: Xử lý sự kiện click nút toggle === */
        toggle.addEventListener('click', function (e) {
            e.preventDefault();  // Ngăn hành động mặc định
            
            if (!isPlaying) {
                // ===== NẾU CHƯA PHÁT: Bật âm thanh =====
                audio.play()
                    .then(function(){
                        // Phát thành công
                        isPlaying = true;
                        localStorage.setItem('siteAudioPlaying', 'true');  // Lưu trạng thái
                        updateButton();  // Cập nhật icon thành 🔊
                        console.log('Phát âm thanh thành công');
                    })
                    .catch(function(err){
                        // Phát thất bại (do chính sách autoplay hoặc lỗi khác)
                        console.warn('Lỗi phát âm thanh:', err);
                        isPlaying = false;
                        localStorage.setItem('siteAudioPlaying', 'false');
                        updateButton();  // Cập nhật icon thành 🔈
                        alert('Trình duyệt chặn tự động phát âm thanh. Vui lòng click lần nữa để bật âm.');
                    });
            } else {
                // ===== NẾU ĐANG PHÁT: Tắt âm thanh =====
                audio.pause();  // Dừng phát
                isPlaying = false;
                localStorage.setItem('siteAudioPlaying', 'false');  // Lưu trạng thái
                updateButton();  // Cập nhật icon thành 🔈
                console.log('Đã tắt âm thanh');
            }
        });
    } catch (e) {
        // Bắt lỗi nếu quá trình khởi tạo audio gặp vấn đề
        console.error('Lỗi khởi tạo âm thanh:', e);
    }
});