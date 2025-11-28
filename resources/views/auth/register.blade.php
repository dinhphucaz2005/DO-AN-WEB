<x-guest-layout>
    <x-slot name="title">
        Tạo tài khoản mới
    </x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">👤 Tên của bạn</label>
            <input 
                id="name" 
                class="form-input" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Nguyễn Văn A"
            >
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">📧 Email</label>
            <input 
                id="email" 
                class="form-input" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="username"
                placeholder="your@email.com"
            >
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">🔒 Mật khẩu</label>
            <input 
                id="password" 
                class="form-input" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            >
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">🔐 Xác nhận mật khẩu</label>
            <input 
                id="password_confirmation" 
                class="form-input" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            >
            @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a class="form-link" href="{{ route('login') }}">
                Đã có tài khoản?
            </a>

            <button type="submit" class="auth-button">
                Đăng ký
            </button>
        </div>
    </form>
</x-guest-layout>
