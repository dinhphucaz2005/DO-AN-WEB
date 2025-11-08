<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'FastFood Delivery')</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    <div class="container">
      <header class="header">
        <h1>FastFood Delivery</h1>
        <div>
          <span class="cart-count" id="cart-count">0</span>
        </div>
      </header>

      <main>
        @yield('content')
      </main>

      <footer style="margin-top:30px; color:#666; font-size:0.9rem;">
        &copy; {{ date('Y') }} FastFood Delivery - Mẫu scaffold
      </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+Yt6G6j5J9u6b3Zr6YV9vR5lQvYQf5c5g3I=" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
    @stack('scripts')
  </body>
</html>
