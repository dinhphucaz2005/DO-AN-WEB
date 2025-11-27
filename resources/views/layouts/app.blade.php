<!doctype html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Meme Creator - Tạo Meme Online')</title>
        <link rel="stylesheet" href="https://raw.githubusercontent.com/dinhphucaz2005/DO-AN-WEB/main/public/build/assets/style.css">
        <script src="https://raw.githubusercontent.com/dinhphucaz2005/DO-AN-WEB/refs/heads/main/public/build/assets/script.js" defer></script>
    </head>
  <body>
    <div class="container">
      <header class="header">
        <!-- Left side: Title + Navigation -->
        <div style="display: flex; align-items: center; gap: 30px; flex: 1;">
          <h1 style="margin: 0;">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">
              🎨 Meme Creator
            </a>
          </h1>
          
          <nav class="nav-left">
            <a href="{{ route('home') }}" class="nav-link">
              <span class="nav-icon">🏠</span>
              <span class="nav-text">Trang chủ</span>
            </a>
            <a href="{{ route('gif.creator') }}" class="nav-link">
              <span class="nav-icon">🎬</span>
              <span class="nav-text">GIF Creator</span>
            </a>
            <a href="{{ route('memes.public') }}" class="nav-link">
              <span class="nav-icon">🌍</span>
              <span class="nav-text">Community Gallery</span>
            </a>
          </nav>
        </div>

        <!-- Right side: Auth buttons -->
        <nav class="nav-right">
          @auth
            <div class="user-menu">
              <span class="user-name">{{ Auth::user()->name }}</span>
              <div class="user-dropdown">
                <a href="{{ route('memes.index') }}" class="dropdown-item">
                  <span>🎨</span> My Creations
                </a>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <span>🚪</span> Log Out
                  </button>
                </form>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" class="nav-link nav-link-secondary">
              <span class="nav-icon">🔑</span>
              <span class="nav-text">Login</span>
            </a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="nav-link nav-link-primary">
                <span class="nav-icon">✨</span>
                <span class="nav-text">Register</span>
              </a>
            @endif
          @endauth
        </nav>
      </header>

      <main>
        @yield('content')
      </main>

      <footer class="footer">
        <div style="text-align: center;">
          <p style="margin: 0; font-weight: 600; color: rgba(255, 255, 255, 0.9);">
            🎨 Meme Creator
          </p>
          <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: rgba(255, 255, 255, 0.7);">
            &copy; {{ date('Y') }} - Tạo meme online miễn phí
          </p>
        </div>
      </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+Yt6G6j5J9u6b3Zr6YV9vR5lQvYQf5c5g3I=" crossorigin="anonymous"></script>
    @stack('scripts')
  </body>
</html>