@extends('layouts.app')

@section('title', 'Meme Gallery - Meme Templates')

@section('content')
<div class="meme-gallery-page">
    <!-- Header -->
    <div class="rainbow-box">
        <div class="rainbow-inner">
            <h1>📷 Meme Gallery</h1>
            <p>Khám phá bộ sưu tập meme có sẵn. Chọn meme yêu thích và sửa đổi theo ý của bạn!</p>
        </div>
    </div>

    <div class="gallery-container">
        @auth
            @if(auth()->user()->is_admin)
            <div style="margin-bottom:24px; text-align:right;">
                <a href="#" class="btn btn-success" id="add-template-btn">➕ Thêm Template</a>
            </div>
            @endif
        @endauth
        <!-- Templates Grid -->
        <div class="templates-showcase">
            <!-- Template cards with data attributes for JS -->
            <div class="template-card" data-template-title="Gold Meme" data-template-bg="linear-gradient(45deg, #FFD700, #FFA500)" data-template-icon="👑">
                <div class="template-preview" style="background: linear-gradient(45deg, #FFD700, #FFA500);">
                    <div class="template-placeholder">👑</div>
                </div>
                <h3>Gold Meme</h3>
                <p>Classic gold gradient template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
                @auth
                @if(auth()->user()->is_admin)
                <button class="btn btn-danger btn-sm delete-template-btn" style="margin-top:6px;">Xóa</button>
                @endif
                @endauth
            </div>
            <div class="template-card" data-template-title="Pink Love" data-template-bg="linear-gradient(45deg, #FFB6C1, #FFC0CB)" data-template-icon="💝">
                <div class="template-preview" style="background: linear-gradient(45deg, #FFB6C1, #FFC0CB);">
                    <div class="template-placeholder">💝</div>
                </div>
                <h3>Pink Love</h3>
                <p>Cute pink themed template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
                @auth
                @if(auth()->user()->is_admin)
                <button class="btn btn-danger btn-sm delete-template-btn" style="margin-top:6px;">Xóa</button>
                @endif
                @endauth
            </div>
            <div class="template-card" data-template-title="Blue Drama" data-template-bg="linear-gradient(45deg, #4169E1, #1E90FF)" data-template-icon="🎭">
                <div class="template-preview" style="background: linear-gradient(45deg, #4169E1, #1E90FF);">
                    <div class="template-placeholder">🎭</div>
                </div>
                <h3>Blue Drama</h3>
                <p>Deep blue theatrical template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
                @auth
                @if(auth()->user()->is_admin)
                <button class="btn btn-danger btn-sm delete-template-btn" style="margin-top:6px;">Xóa</button>
                @endif
                @endauth
            </div>
            <div class="template-card" data-template-title="Purple Magic" data-template-bg="linear-gradient(45deg, #9370DB, #BA55D3)" data-template-icon="💫">
                <div class="template-preview" style="background: linear-gradient(45deg, #9370DB, #BA55D3);">
                    <div class="template-placeholder">💫</div>
                </div>
                <h3>Purple Magic</h3>
                <p>Mystical purple template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
                @auth
                @if(auth()->user()->is_admin)
                <button class="btn btn-danger btn-sm delete-template-btn" style="margin-top:6px;">Xóa</button>
                @endif
                @endauth
            </div>
            <div class="template-card" data-template-title="Fire Hot" data-template-bg="linear-gradient(45deg, #FF6347, #FF4500)" data-template-icon="🔥">
                <div class="template-preview" style="background: linear-gradient(45deg, #FF6347, #FF4500);">
                    <div class="template-placeholder">🔥</div>
                </div>
                <h3>Fire Hot</h3>
                <p>Hot orange red template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
                @auth
                @if(auth()->user()->is_admin)
                <button class="btn btn-danger btn-sm delete-template-btn" style="margin-top:6px;">Xóa</button>
                @endif
                @endauth
            </div>
            <div class="template-card" data-template-title="Ocean Wave" data-template-bg="linear-gradient(45deg, #20B2AA, #48D1CC)" data-template-icon="🌊">
                <div class="template-preview" style="background: linear-gradient(45deg, #20B2AA, #48D1CC);">
                    <div class="template-placeholder">🌊</div>
                </div>
                <h3>Ocean Wave</h3>
                <p>Cool turquoise template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Electric Gold" data-template-bg="linear-gradient(45deg, #DAA520, #FFD700)" data-template-icon="⚡">
                <div class="template-preview" style="background: linear-gradient(45deg, #DAA520, #FFD700);">
                    <div class="template-placeholder">⚡</div>
                </div>
                <h3>Electric Gold</h3>
                <p>Bright electric template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Forest Green" data-template-bg="linear-gradient(45deg, #228B22, #32CD32)" data-template-icon="🌿">
                <div class="template-preview" style="background: linear-gradient(45deg, #228B22, #32CD32);">
                    <div class="template-placeholder">🌿</div>
                </div>
                <h3>Forest Green</h3>
                <p>Natural green template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Crimson Heart" data-template-bg="linear-gradient(45deg, #DC143C, #FF1493)" data-template-icon="💖">
                <div class="template-preview" style="background: linear-gradient(45deg, #DC143C, #FF1493);">
                    <div class="template-placeholder">💖</div>
                </div>
                <h3>Crimson Heart</h3>
                <p>Deep red love template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Circus Dark" data-template-bg="linear-gradient(45deg, #2F4F4F, #696969)" data-template-icon="🎪">
                <div class="template-preview" style="background: linear-gradient(45deg, #2F4F4F, #696969);">
                    <div class="template-placeholder">🎪</div>
                </div>
                <h3>Circus Dark</h3>
                <p>Dark gray template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Earthy Brown" data-template-bg="linear-gradient(45deg, #8B4513, #D2691E)" data-template-icon="🎨">
                <div class="template-preview" style="background: linear-gradient(45deg, #8B4513, #D2691E);">
                    <div class="template-placeholder">🎨</div>
                </div>
                <h3>Earthy Brown</h3>
                <p>Warm brown template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
            <div class="template-card" data-template-title="Hot Pink" data-template-bg="linear-gradient(45deg, #FF69B4, #FF1493)" data-template-icon="✨">
                <div class="template-preview" style="background: linear-gradient(45deg, #FF69B4, #FF1493);">
                    <div class="template-placeholder">✨</div>
                </div>
                <h3>Hot Pink</h3>
                <p>Vibrant pink template</p>
                <button class="btn btn-primary btn-sm use-template-btn">Use Template</button>
            </div>
        </div>

        <!-- Modal chọn nơi sử dụng template -->
        <div id="choose-destination-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
            <div style="background:white; border-radius:12px; padding:32px 24px; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.2); text-align:center;">
                <h2>Chọn nơi sử dụng template</h2>
                <div id="template-preview-modal" style="margin:16px auto 24px auto; width:120px; height:120px; display:flex; align-items:center; justify-content:center; border-radius:12px; font-size:48px;"></div>
                <div style="display:flex; gap:16px; justify-content:center;">
                    <button id="go-meme-create" class="btn btn-primary" style="flex:1;">Meme Create</button>
                    <button id="go-gif-create" class="btn btn-primary" style="flex:1;">GIF Creator</button>
                </div>
                <button id="close-modal-btn" class="btn btn-secondary" style="margin-top:20px;">Đóng</button>
            </div>
        </div>

        <script>
        // Tạo preview template thành ảnh base64
        function renderTemplateToImage(bg, icon, callback) {
            var canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = 800;
            var ctx = canvas.getContext('2d');
            // Vẽ background gradient
            var grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            var match = bg.match(/#([0-9a-fA-F]{3,6})/g);
            if (match && match.length >= 2) {
                grad.addColorStop(0, match[0]);
                grad.addColorStop(1, match[1]);
            } else {
                grad.addColorStop(0, '#fff');
                grad.addColorStop(1, '#eee');
            }
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            // Vẽ icon vào giữa
            ctx.font = '400px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(icon, canvas.width/2, canvas.height/2);
            callback(canvas.toDataURL('image/png'));
        }

        // Xử lý nút Use Template
        function openChooseModal(title, bg, icon) {
            var modal = document.getElementById('choose-destination-modal');
            var preview = document.getElementById('template-preview-modal');
            preview.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:'+bg+';border-radius:12px;">'+icon+'</div>';
            modal.style.display = 'flex';
            modal.dataset.templateTitle = title;
            modal.dataset.templateBg = bg;
            modal.dataset.templateIcon = icon;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // bind use buttons
            document.querySelectorAll('.use-template-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    var card = btn.closest('.template-card');
                    var title = card.getAttribute('data-template-title');
                    var bg = card.getAttribute('data-template-bg');
                    var icon = card.getAttribute('data-template-icon');
                    openChooseModal(title, bg, icon);
                });
            });

            // close
            var closeBtn = document.getElementById('close-modal-btn');
            if (closeBtn) closeBtn.addEventListener('click', function(){ document.getElementById('choose-destination-modal').style.display = 'none'; });

            // go handlers
            var goMeme = document.getElementById('go-meme-create');
            var goGif = document.getElementById('go-gif-create');
            if (goMeme) goMeme.addEventListener('click', function(){ goToEditor('meme'); });
            if (goGif) goGif.addEventListener('click', function(){ goToEditor('gif'); });

            // admin actions: delete
            document.querySelectorAll('.delete-template-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Bạn có chắc muốn xóa template này?')) return;
                    var card = btn.closest('.template-card');
                    var id = card.getAttribute('data-template-id');
                    fetch('/templates/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        if (data.success) card.remove();
                        else alert('Lỗi xóa template!');
                    });
                });
            });

            // admin add
            var addBtn = document.getElementById('add-template-btn');
            if (addBtn) {
                addBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var title = prompt('Tên template?');
                    if (!title) return;
                    var bg = prompt('CSS background (vd: linear-gradient(45deg, #FFD700, #FFA500))?');
                    if (!bg) return;
                    var icon = prompt('Emoji/icon?');
                    if (!icon) return;
                    fetch('/templates', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({title, bg, icon})
                    }).then(r => r.json()).then(data => {
                        if (data.success) location.reload();
                        else alert('Lỗi thêm template!');
                    });
                });
            }
        });

        // Chuyển hướng khi chọn Meme Create hoặc GIF Creator
        function goToEditor(type) {
            var modal = document.getElementById('choose-destination-modal');
            var title = modal.dataset.templateTitle;
            var bg = modal.dataset.templateBg;
            var icon = modal.dataset.templateIcon;
            renderTemplateToImage(bg, icon, function(imgData) {
                // Lưu vào localStorage (dùng key tạm thời)
                localStorage.setItem('selected_template_image', imgData);
                localStorage.setItem('selected_template_title', title);
                // Chuyển hướng
                if(type === 'meme') {
                    window.location.href = '/meme-editor?from_template=1';
                } else {
                    window.location.href = '/gif-creator?from_template=1';
                }
            });
        }
        </script>
        </div>
    </div>
</div>

<style>
.meme-gallery-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.gallery-container {
    padding: 40px 0;
}

.templates-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}

.template-card {
    background: white;
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.template-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.template-preview {
    width: 100%;
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.template-placeholder {
    font-size: 60px;
    line-height: 1;
}

.template-card h3 {
    margin: 16px 16px 8px 16px;
    font-size: 1.1rem;
    color: #333;
}

.template-card p {
    margin: 0 16px 16px 16px;
    font-size: 0.9rem;
    color: #666;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
    margin: 0 16px 16px 16px;
    width: calc(100% - 32px);
}

.btn {
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e55a2b 0%, #e0861b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255,107,53,0.3);
}

.rainbow-box {
    background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3);
    border-radius: 12px;
    padding: 2px;
    margin-bottom: 30px;
}

.rainbow-inner {
    background: white;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
}

.rainbow-inner h1 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 2rem;
}

.rainbow-inner p {
    margin: 0;
    color: #666;
}

@media (max-width: 768px) {
    .templates-showcase {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }
}
</style>
@endsection
