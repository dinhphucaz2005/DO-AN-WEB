<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Meme Creator - Tạo Meme Online')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container">
      <header class="header">
        <h1><a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">🎨 Meme Creator</a></h1>
        <nav style="display: flex; gap: 15px; align-items: center;">
          <a href="{{ route('home') }}" style="text-decoration: none; color: #666;">🏠 Trang chủ</a>
          @auth
            <a href="{{ route('profile.edit') }}" style="text-decoration: none; color: #666;">{{ Auth::user()->name }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="text-decoration: none; color: #666;">Log Out</a>
            </form>
          @else
            <a href="{{ route('login') }}" style="text-decoration: none; color: #666;">Log in</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" style="text-decoration: none; color: #666;">Register</a>
            @endif
          @endauth
        </nav>
      </header>

      <main>
        @yield('content')
      </main>

      <footer style="margin-top:30px; color:#666; font-size:0.9rem;">
        &copy; {{ date('Y') }} Meme Creator - Tạo meme online miễn phí
      </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+Yt6G6j5J9u6b3Zr6YV9vR5lQvYQf5c5g3I=" crossorigin="anonymous"></script>
    @stack('scripts')
  </body>
</html>