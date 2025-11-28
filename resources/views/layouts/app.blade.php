<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Meme Creator - Tạo Meme Online')</title>
    <link rel="stylesheet" href="https://meme-creator-3-0-0.onrender.com/build/assets/style.css">
    <script src="https://meme-creator-3-0-0.onrender.com/build/assets/script.js" defer></script>
  </head>
  <body>
    <div class="container">
      <header class="header">
        <!-- Left side: Title + Navigation -->
        <div style="display: flex; align-items: center; gap: 30px; flex: 1;">
          <h1 style="margin: 0;">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">
              🎨 Sigma
            </a>
          </h1>
          
          <nav class="nav-left">
            <a href="{{ route('meme.editor') }}" class="nav-link">
              <span class="nav-icon">🎨</span>
              <span class="nav-text">Meme Create</span>
            </a>
            <a href="{{ route('gif.creator') }}" class="nav-link">
              <span class="nav-icon">🎬</span>
              <span class="nav-text">GIF Creator</span>
            </a>
            <a href="{{ route('memes.public') }}" class="nav-link">
              <span class="nav-icon">🌍</span>
              <span class="nav-text">Meme Community</span>
            </a>
          </nav>
        </div>

        <!-- Right side: Auth buttons -->
        <nav class="nav-right">
          <!-- ========== CUSTOM: Nút điều khiển âm thanh (Audio Toggle Button) ==========
               - Bạn đã thêm nút bật/tắt âm nhạc vào header
               - Mục đích: Cho phép người dùng bật/tắt âm thanh nền của web
               - Biểu tượng: 🔊 (đang phát) hoặc 🔈 (đã tắt)
               - Trạng thái được lưu vào localStorage để nhớ tùy chọn người dùng
               ====================================================================== -->
          <div id="audio-control" style="display:flex; align-items:center; gap:10px;">
            <!-- 
              * id="audio-toggle": ID để lấy element này trong JavaScript
              * class="nav-link": Sử dụng style từ CSS để phù hợp với giao diện header
              * title: Tooltip (chữ hiện khi hover chuột)
              * style="padding:8px 12px;": Khoảng cách bên trong nút
              * 🔊: Biểu tượng loa phát âm (sẽ đổi thành 🔈 khi tắt)
            -->
            <button id="audio-toggle" class="nav-link" title="Bật/Tắt âm nhạc" style="padding:8px 12px;">🔊</button>
          </div>
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

    <!-- Global audio volume control (site-wide) -->
    <style>
    /* Floating global audio control */
    #global-audio-control {
      position: fixed;
      right: 18px;
      bottom: 18px;
      z-index: 1200;
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255,255,255,0.96);
      border: 1px solid rgba(0,0,0,0.08);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
      padding: 8px;
      border-radius: 999px;
      backdrop-filter: blur(4px);
    }
    #global-audio-btn {
      background: transparent;
      border: none;
      font-size: 18px;
      cursor: pointer;
      line-height: 1;
      padding: 6px;
    }
    #global-audio-slider-wrapper {
      width: 140px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    #global-audio-slider {
      width: 120px;
      appearance: none;
      height: 6px;
      border-radius: 6px;
      background: linear-gradient(90deg,#ff6b35,#f7931e);
      outline: none;
      cursor: pointer;
    }
    #global-audio-slider::-webkit-slider-thumb {
      appearance: none;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: white;
      border: 2px solid #ff6b35;
      box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }
    @media (max-width: 480px) {
      #global-audio-control { right: 12px; bottom: 12px; padding: 6px; }
      #global-audio-slider-wrapper { width: 110px; }
      #global-audio-slider { width: 90px; }
    }
    </style>

    <div id="global-audio-control" aria-label="Global audio control" title="Âm lượng" style="display:none;">
      <button id="global-audio-btn" aria-label="Bật/Tắt âm lượng">🔊</button>
      <div id="global-audio-slider-wrapper">
        <input id="global-audio-slider" type="range" min="0" max="100" value="100" aria-label="Âm lượng" />
      </div>
    </div>

    <script>
    (function(){
      // Global audio control: applies to audio elements with data-audio-control="global"
      const STORAGE_KEY = 'globalVolumeV1';
      const btn = document.getElementById('global-audio-btn');
      const slider = document.getElementById('global-audio-slider');
      let lastNonZero = 100;

      function getSaved() {
        try { const v = localStorage.getItem(STORAGE_KEY); return v === null ? 100 : parseInt(v,10); } catch(e) { return 100; }
      }

      function save(v) { try { localStorage.setItem(STORAGE_KEY, String(v)); } catch(e){} }

      function updateIcon(v){
        if (!btn) return;
        if (v <= 0) btn.textContent = '🔇';
        else if (v < 34) btn.textContent = '🔈';
        else if (v < 67) btn.textContent = '🔉';
        else btn.textContent = '🔊';
      }

      function applyVolumeToAll(v){
        const value = Math.max(0, Math.min(100, Number(v)))/100;
        // Find ALL audio elements on page (no attribute needed)
        const audios = Array.from(document.querySelectorAll('audio'));
        if (audios.length > 0) {
          console.log('[GlobalVolume] Found ' + audios.length + ' audio element(s), setting volume to ' + (value*100).toFixed(0) + '%');
        }
        audios.forEach((a, i) => {
          try { 
            a.volume = value; 
            a.muted = value === 0;
            console.log('[GlobalVolume] Audio[' + i + '] volume set to ' + (value*100).toFixed(0) + '%');
          } catch(e){
            console.error('[GlobalVolume] Error setting audio[' + i + '] volume:', e);
          }
        });
        // Also attempt to call Howler if present
        try {
          if (typeof Howler !== 'undefined' && Howler && typeof Howler.volume === 'function') {
            Howler.volume(value);
            console.log('[GlobalVolume] Howler volume set to ' + (value*100).toFixed(0) + '%');
          }
        } catch(e){}
      }

      function init(){
        if (!slider) return;
        const saved = getSaved();
        slider.value = saved;
        updateIcon(saved);
        applyVolumeToAll(saved);

        slider.addEventListener('input', (e) => {
          const v = parseInt(e.target.value,10);
          if (v > 0) lastNonZero = v;
          updateIcon(v);
          applyVolumeToAll(v);
          save(v);
        });

        // Toggle mute/unmute on button click
        btn.addEventListener('click', () => {
          const cur = parseInt(slider.value,10);
          if (cur > 0) {
            slider.value = 0;
            updateIcon(0);
            applyVolumeToAll(0);
            save(0);
          } else {
            const restore = lastNonZero || 100;
            slider.value = restore;
            updateIcon(restore);
            applyVolumeToAll(restore);
            save(restore);
          }
        });

        // Keyboard accessibility: arrow keys change value when slider focused
        slider.addEventListener('keydown', (e) => {
          const step = e.shiftKey ? 10 : 1;
          let v = parseInt(slider.value,10);
          if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') { v = Math.max(0, v - step); slider.value = v; slider.dispatchEvent(new Event('input')); e.preventDefault(); }
          if (e.key === 'ArrowRight' || e.key === 'ArrowUp') { v = Math.min(100, v + step); slider.value = v; slider.dispatchEvent(new Event('input')); e.preventDefault(); }
          if (e.key === 'Home') { slider.value = 0; slider.dispatchEvent(new Event('input')); e.preventDefault(); }
          if (e.key === 'End') { slider.value = 100; slider.dispatchEvent(new Event('input')); e.preventDefault(); }
        });

            // Observe DOM for audio elements added after page load and apply volume
            const mo = new MutationObserver((mutations) => {
              mutations.forEach(m => {
                m.addedNodes && m.addedNodes.forEach(node => {
                  if (node.tagName && node.tagName.toLowerCase() === 'audio') {
                    try { 
                      node.volume = parseInt(slider.value,10)/100; 
                      node.muted = parseInt(slider.value,10) === 0;
                      console.log('[GlobalVolume] New audio element detected, volume applied');
                    } catch(e){}
                  } else if (node.querySelectorAll) {
                    const inner = node.querySelectorAll('audio');
                    inner.forEach(a => { 
                      try { 
                        a.volume = parseInt(slider.value,10)/100; 
                        a.muted = parseInt(slider.value,10) === 0;
                        console.log('[GlobalVolume] New nested audio element detected, volume applied');
                      } catch(e){} 
                    });
                  }
                });
              });
            });
            mo.observe(document.body, { childList: true, subtree: true });
        }

      // Initialize after DOM ready
      if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
    })();
    </script>

    <script>
    (function(){
      // Show/hide the floating control only when header audio toggle is clicked
      const HEADER_BTN_ID = 'audio-toggle';
      const VIS_KEY = 'globalVolumeControlVisible';
      const headerBtn = document.getElementById(HEADER_BTN_ID);
      const ctrl = document.getElementById('global-audio-control');
      function setVisible(v){ if (!ctrl) return; ctrl.style.display = v ? 'flex' : 'none'; if (v){ const s = document.getElementById('global-audio-slider'); if (s) s.focus(); }}
      try {
        const saved = localStorage.getItem(VIS_KEY);
        if (saved === '1') setVisible(true); else setVisible(false);
      } catch(e) {}
      if (headerBtn) {
        headerBtn.addEventListener('click', function(){
          try {
            const isVisible = ctrl && ctrl.style.display !== 'none';
            setVisible(!isVisible);
            localStorage.setItem(VIS_KEY, !isVisible ? '1' : '0');
          } catch(e) {}
        });
      }
    })();
    </script>
  </body>
</html>