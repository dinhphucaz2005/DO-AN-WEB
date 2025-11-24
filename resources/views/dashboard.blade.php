<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="card mb-6 animate-scale-in">
                <h1 style="font-size: 2.5rem; font-weight: 800; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 10px;">
                    👋 Xin chào, {{ Auth::user()->name }}!
                </h1>
                <p style="color: #666; font-size: 1.1rem;">Chào mừng bạn đến với Meme Creator - nơi sáng tạo không giới hạn!</p>
            </div>

            <!-- Quick Actions Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 40px;">
                <!-- Create Meme Card -->
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <div class="card animate-slide-in" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: 2px solid rgba(102, 126, 234, 0.3); cursor: pointer; animation-delay: 0.1s;">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎨</div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: #667eea; margin-bottom: 10px;">Tạo Meme</h3>
                        <p style="color: #666; margin-bottom: 20px;">Sáng tạo meme độc đáo với công cụ chỉnh sửa mạnh mẽ</p>
                        <div class="btn btn-primary" style="width: 100%;">Bắt đầu ngay →</div>
                    </div>
                </a>

                <!-- Create GIF Card -->
                <a href="{{ route('gif.creator') }}" style="text-decoration: none;">
                    <div class="card animate-slide-in" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border: 2px solid rgba(79, 172, 254, 0.3); cursor: pointer; animation-delay: 0.2s;">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎬</div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: #4facfe; margin-bottom: 10px;">Tạo GIF</h3>
                        <p style="color: #666; margin-bottom: 20px;">Tạo GIF động từ nhiều hình ảnh một cách dễ dàng</p>
                        <div class="btn btn-success" style="width: 100%;">Tạo GIF →</div>
                    </div>
                </a>

                <!-- My Creations Card -->
                <a href="{{ route('memes.index') }}" style="text-decoration: none;">
                    <div class="card animate-slide-in" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%); border: 2px solid rgba(240, 147, 251, 0.3); cursor: pointer; animation-delay: 0.3s;">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🖼️</div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: #f093fb; margin-bottom: 10px;">Tác phẩm của tôi</h3>
                        <p style="color: #666; margin-bottom: 20px;">Xem và quản lý tất cả các meme & GIF đã tạo</p>
                        <div class="btn btn-danger" style="width: 100%;">Xem ngay →</div>
                    </div>
                </a>
            </div>

            <!-- Features Section -->
            <div class="card animate-fade-in" style="animation-delay: 0.4s;">
                <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 25px; color: #333;">✨ Tính năng nổi bật</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div style="padding: 20px; background: rgba(102, 126, 234, 0.05); border-radius: 15px; border-left: 4px solid #667eea;">
                        <h4 style="font-weight: 600; color: #667eea; margin-bottom: 8px;">🚀 Nhanh chóng</h4>
                        <p style="color: #666; font-size: 0.9rem;">Tạo meme chỉ trong vài giây với giao diện trực quan</p>
                    </div>
                    <div style="padding: 20px; background: rgba(79, 172, 254, 0.05); border-radius: 15px; border-left: 4px solid #4facfe;">
                        <h4 style="font-weight: 600; color: #4facfe; margin-bottom: 8px;">🎯 Dễ sử dụng</h4>
                        <p style="color: #666; font-size: 0.9rem;">Không cần kỹ năng thiết kế, ai cũng có thể sử dụng</p>
                    </div>
                    <div style="padding: 20px; background: rgba(240, 147, 251, 0.05); border-radius: 15px; border-left: 4px solid #f093fb;">
                        <h4 style="font-weight: 600; color: #f093fb; margin-bottom: 8px;">💾 Lưu trữ an toàn</h4>
                        <p style="color: #666; font-size: 0.9rem;">Tất cả tác phẩm được lưu trữ bảo mật trong database</p>
                    </div>
                    <div style="padding: 20px; background: rgba(118, 75, 162, 0.05); border-radius: 15px; border-left: 4px solid #764ba2;">
                        <h4 style="font-weight: 600; color: #764ba2; margin-bottom: 8px;">🎨 Sáng tạo</h4>
                        <p style="color: #666; font-size: 0.9rem;">Công cụ chỉnh sửa mạnh mẽ với nhiều tùy chọn</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
