<x-guest-layout>
    <x-slot name="title">
        Đăng nhập vào tài khoản
    </x-slot>

    <!-- Session Status -->
    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                autofocus 
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
                autocomplete="current-password"
                placeholder="••••••••"
            >
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-checkbox">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Ghi nhớ đăng nhập</label>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            @if (Route::has('password.request'))
                <a class="form-link" href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>
            @endif

            <button type="submit" class="auth-button">
                Đăng nhập
            </button>
        </div>
    </form>

    <!-- Register Link -->
    @if (Route::has('register'))
        <div class="register-prompt">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    @endif
</x-guest-layout>
