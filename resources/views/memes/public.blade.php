@extends('layouts.app')

@section('title', 'Community Gallery')

@section('content')
<div class="memes-gallery">
    <div class="rainbow-box">
        <div class="rainbow-inner">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                <div>
                    <h1>🌍 Community Gallery</h1>
                    <p>Khám phá những meme và GIF tuyệt vời từ cộng đồng</p>
                </div>
                @auth
                <div>
                    <button id="open-post-btn" class="btn" style="background:linear-gradient(90deg,#4caf50,#8bc34a);color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer;">➕ Đăng bài</button>
                </div>
                @endauth
            </div>
        </div>
        <!-- My Creations Modal -->
        <div id="my-creations-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:2100;padding:20px;">
            <div style="background:#fff;border-radius:12px;max-width:1000px;width:100%;padding:18px;box-shadow:0 12px 40px rgba(0,0,0,0.3);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <h3 style="margin:0">🗂️ My Creations</h3>
                    <button id="close-my-creations" style="background:none;border:none;font-size:18px;cursor:pointer">✖</button>
                </div>
                <div id="my-creations-list" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;max-height:60vh;overflow:auto;padding:6px;">
                    <!-- items loaded by JS -->
                </div>
            </div>
        </div>
        <!-- Modal: Create Post (moved here so it's available even when there are no posts) -->
        <div id="create-post-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:2000;padding:20px;">
            <div style="background:#fff;border-radius:12px;max-width:720px;width:100%;padding:18px;box-shadow:0 12px 40px rgba(0,0,0,0.3);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <h3 style="margin:0">➕ Đăng bài mới</h3>
                    <button id="close-post-modal" style="background:none;border:none;font-size:18px;cursor:pointer">✖</button>
                </div>

                <form id="create-post-form">
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <label>Tiêu đề <input type="text" name="title" id="post-title" placeholder="Tiêu đề bài đăng" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd" /></label>
                        <label>Mô tả (tùy chọn) <textarea name="description" id="post-desc" rows="3" placeholder="Mô tả ngắn" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd"></textarea></label>
                        <label>Chọn ảnh/GIF <input type="file" id="post-image" accept="image/*" /></label>
                        <div style="margin-top:6px;">
                            <button type="button" id="open-my-creations" style="background:#fff;border:1px solid #ddd;padding:6px 10px;border-radius:6px;cursor:pointer">📁 Chọn từ My Creations</button>
                        </div>
                        <div id="post-preview" style="display:none;border-radius:8px;overflow:hidden;border:1px solid #eee;padding:8px;background:#fafafa"></div>
                        <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" id="post-public" /> Công khai (hiển thị trong Community Gallery)</label>
                        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:6px;">
                            <button type="button" id="submit-post-btn" style="background:linear-gradient(90deg,#4caf50,#8bc34a);color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer">Đăng</button>
                            <button type="button" id="cancel-post-btn" style="background:#eee;border:none;padding:8px 12px;border-radius:6px;cursor:pointer">Hủy</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($memes->isEmpty())
        <div class="empty-state">
            <div class="empty-card">
                <div class="empty-icon">📭</div>
                <h2>Chưa có bài đăng nào</h2>
                <p>Hãy là người đầu tiên chia sẻ tác phẩm của bạn!</p>
                <div class="empty-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary">🎨 Tạo Meme</a>
                </div>
            </div>
        </div>
    @else
        
        <div class="gallery-grid">
            @foreach($memes as $meme)
                <div class="gallery-item rainbow-box" data-type="{{ $meme->type }}">
                    <div class="rainbow-inner">
                        <div class="item-header">
                            <div class="user-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($meme->user->name) }}&background=fff&color=007bff&size=32" class="avatar-img" alt="avatar" />
                                <span class="username">{{ $meme->user->name }}</span>
                                <span class="post-time">{{ $meme->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="item-type">{{ $meme->type === 'gif' ? '🎬 GIF' : '🖼️ Meme' }}</span>
                        </div>

                        <div class="item-image">
                            <div class="card-frame">
                                <div class="card-frame-inner">
                                    <img src="{{ route('memes.image', $meme->id) }}" alt="{{ $meme->title }}" class="card-img-centered">
                                </div>
                            </div>
                        </div>

                        <div class="item-info">
                            <h3>{{ $meme->title }}</h3>
                            @if($meme->description)
                                <p class="description">{{ $meme->description }}</p>
                            @endif
                            <div class="item-actions-bar">
                                <button class="btn-like {{ Auth::check() && $meme->isLikedBy(Auth::user()) ? 'liked' : '' }}" 
                                        data-id="{{ $meme->id }}"
                                        onclick="toggleLike(this)">
                                    <span class="heart-icon">❤️</span>
                                    <span class="like-count">{{ $meme->likes->count() }}</span>
                                </button>
                                <a href="{{ route('memes.image', $meme->id) }}" download class="btn-download">
                                    ⬇️
                                </a>

                                @auth
                                    @if(Auth::id() === $meme->user_id || Auth::user()->is_admin)
                                        <form method="POST" action="{{ route('memes.destroy', $meme->id) }}" style="display:inline;margin-left:8px;" onsubmit="return confirm('Bạn có chắc muốn xóa bài đăng này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Xóa bài">🗑️</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <!-- Bình luận -->
                        <div class="comments-section">
                            <div class="comments-list">
                                @foreach($meme->comments as $comment)
                                    <div class="comment-item">
                                        <span class="comment-avatar">👤</span>
                                        <span class="comment-user">{{ $comment->user->name }}</span>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                        <div class="comment-content">{{ $comment->content }}</div>
                                    </div>
                                @endforeach
                            </div>
                            @auth
                            <form class="comment-form" onsubmit="return submitComment(event, {{ $meme->id }})">
                                <input type="text" name="content" class="comment-input" placeholder="Viết bình luận..." maxlength="500" required />
                                <button type="submit" class="comment-btn">💬 Bình luận</button>
                            </form>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $memes->links() }}
        </div>
    @endif
</div>

<style>
.memes-gallery {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.gallery-header {
    text-align: center;
    margin-bottom: 40px;
}

.gallery-grid {
    display: flex;
    flex-direction: column;
    gap: 29px; /* increased ~15% from 25px */
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    align-items: stretch;
}

.gallery-item {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s;
    width: 70%;
    margin: 0 auto;
}

.avatar-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
    border: 2px solid #e91e63;
}
.post-time {
    font-size: 0.95em;
    color: #888;
    margin-left: 10px;
}
.comments-section {
    margin-top: 18px;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 12px 16px;
}
.comments-list {
    margin-bottom: 10px;
}
.comment-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    font-size: 1em;
}
.comment-avatar {
    font-size: 1.2em;
}
.comment-user {
    font-weight: 600;
    color: #007bff;
}
.comment-time {
    font-size: 0.9em;
    color: #aaa;
}
.comment-content {
    background: #fff;
    border-radius: 6px;
    padding: 4px 10px;
    margin-left: 5px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.comment-form {
    display: flex;
    gap: 8px;
    margin-top: 5px;
}
.comment-input {
    flex: 1;
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 6px 10px;
    font-size: 1em;
}
.comment-btn {
    background: linear-gradient(90deg,#ff9800,#e91e63,#3f51b5);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.comment-btn:hover {
    background: linear-gradient(90deg,#3f51b5,#e91e63,#ff9800);
}

.gallery-item:hover {
    transform: translateY(-5px);
}

.item-header {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
}

.item-image {
    width: 100%;
    aspect-ratio: 1;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Framed card for listing thumbnails (community) */
.card-frame {
    background: #fff;
    border: 3px solid #000;
    padding: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
}
.card-frame-inner { width: 100%; display:flex; justify-content:center; align-items:center }
.card-img-centered { max-width: 100%; height: auto; display:block }

.item-info {
    padding: 20px;
}

.item-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.btn-like {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 1.1rem;
    color: #666;
    transition: all 0.2s;
}

.btn-like:hover {
    transform: scale(1.1);
}

.btn-like.liked {
    color: #e91e63;
}

.btn-like.liked .heart-icon {
    animation: pulse 0.3s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

.btn-download {
    text-decoration: none;
    font-size: 1.2rem;
    color: #666;
    transition: color 0.2s;
}

.btn-download:hover {
    color: #007bff;
}

.btn-delete {
    background: #ff4d4f;
    color: #fff;
    border: none;
    padding: 6px 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.95rem;
}
.btn-delete:hover { opacity: 0.9; }

.pagination {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* Empty state card */
.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 0;
}
.empty-card {
    text-align: center;
    max-width: 560px;
    width: 100%;
    background: linear-gradient(180deg, #ffffff, #fbfbff);
    border: 1px solid #e6e9ee;
    border-radius: 12px;
    padding: 28px 24px;
    box-shadow: 0 8px 30px rgba(16,24,40,0.06);
}
.empty-card .empty-icon {
    font-size: 44px;
    margin-bottom: 12px;
}
.empty-card h2 { margin: 0 0 8px 0; font-size: 1.5rem; }
.empty-card p { margin: 0 0 16px 0; color: #666; }
.empty-actions { margin-top: 8px; }
.empty-actions .btn-primary { padding: 10px 16px; border-radius: 8px; }
</style>

<script>
// Create post modal logic
document.addEventListener('DOMContentLoaded', function(){
    const openBtn = document.getElementById('open-post-btn');
    const modal = document.getElementById('create-post-modal');
    const closeBtn = document.getElementById('close-post-modal');
    const cancelBtn = document.getElementById('cancel-post-btn');
    const fileInput = document.getElementById('post-image');
    const preview = document.getElementById('post-preview');
    const submitBtn = document.getElementById('submit-post-btn');

    if (openBtn) {
        openBtn.addEventListener('click', () => { modal.style.display = 'flex'; });
    }
    if (closeBtn) closeBtn.addEventListener('click', () => { modal.style.display = 'none'; resetCreatePost(); });
    if (cancelBtn) cancelBtn.addEventListener('click', () => { modal.style.display = 'none'; resetCreatePost(); });

    function resetCreatePost(){
        const form = document.getElementById('create-post-form');
        form.reset();
        preview.style.display = 'none';
        preview.innerHTML = '';
    }

    if (fileInput) {
        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev){
                const src = ev.target.result;
                preview.style.display = 'block';
                preview.innerHTML = `<img src="${src}" style="max-width:100%;height:auto;display:block;border-radius:6px" />`;
                preview.dataset.base64 = src;
            };
            reader.readAsDataURL(file);
        });
    }

    // My Creations picker
    const openMyBtn = document.getElementById('open-my-creations');
    const myModal = document.getElementById('my-creations-modal');
    const myClose = document.getElementById('close-my-creations');
    const myList = document.getElementById('my-creations-list');

    if (openMyBtn) {
        openMyBtn.addEventListener('click', async () => {
            // fetch user's creations
                try {
                myList.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:20px">Đang tải...</div>';
                myModal.style.display = 'flex';
                const resp = await fetch('{{ route('memes.myjson') }}', { credentials: 'same-origin' });
                // ensure we received JSON; if server redirected to login it may return HTML
                const contentType = resp.headers.get('content-type') || '';
                if (!resp.ok) {
                    const txt = await resp.text();
                    if (resp.status === 401) {
                        myList.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:20px">Bạn chưa đăng nhập hoặc phiên đã hết hạn. Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để xem My Creations.</div>`;
                    } else {
                        myList.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:20px">Lỗi server: ${resp.status} ${resp.statusText}<br/><small>${escapeHtml(txt.substring(0,200))}</small></div>`;
                    }
                    console.error('My creations fetch failed', resp.status, resp.statusText, txt);
                    return;
                }
                if (!contentType.includes('application/json')) {
                    const txt = await resp.text();
                    myList.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:20px">Không nhận được JSON từ server. Có thể đã bị chuyển hướng (phiên đăng nhập có thể đã hết hạn). Vui lòng đăng nhập lại.<br/><small>${escapeHtml(txt.substring(0,200))}</small></div>`;
                    console.error('Unexpected response for my-json (not JSON):', txt);
                    return;
                }
                const payload = await resp.json();
                if (!payload.success) {
                    myList.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:20px">Không tải được danh sách: ${escapeHtml(payload.message || 'Unknown error')}</div>`;
                    console.error('my-json returned success:false', payload);
                    return;
                }
                myList.innerHTML = '';
                payload.memes.forEach(m => {
                    const el = document.createElement('div');
                    el.style = 'background:#fff;border:1px solid #eee;border-radius:8px;padding:8px;display:flex;flex-direction:column;gap:8px;align-items:stretch;';
                    el.innerHTML = `
                        <div style="height:110px;overflow:hidden;display:flex;align-items:center;justify-content:center;background:#fafafa;border-radius:6px"><img src="${m.url}" style="max-width:100%;height:auto;display:block;" /></div>
                        <div style="font-weight:600;">${m.title}</div>
                        <div style="display:flex;gap:6px;justify-content:space-between;align-items:center">
                            <div style="font-size:0.85em;color:#666">${m.type.toUpperCase()}</div>
                            <div style="display:flex;gap:6px">
                                <button class="use-btn" data-id="${m.id}" style="padding:6px 8px;border-radius:6px;border:1px solid #ddd;background:#fff;cursor:pointer">Use</button>
                                ${m.is_public ? '<button disabled style="padding:6px 8px;border-radius:6px;background:#eee;border:none">Public</button>' : `<button class="publish-btn" data-id="${m.id}" style="padding:6px 8px;border-radius:6px;background:linear-gradient(90deg,#2196f3,#03a9f4);color:#fff;border:none;cursor:pointer">Publish</button>`}
                            </div>
                        </div>`;
                    myList.appendChild(el);
                });

                // attach handlers
                myList.querySelectorAll('.use-btn').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        const id = e.currentTarget.dataset.id;
                        // fetch image and convert to base64
                        try {
                            const resp2 = await fetch(`/memes/image/${id}`);
                            const blob = await resp2.blob();
                            const dataUrl = await blobToDataURL(blob);
                            preview.style.display = 'block';
                            preview.innerHTML = `<img src="${dataUrl}" style="max-width:100%;height:auto;display:block;border-radius:6px" />`;
                            preview.dataset.base64 = dataUrl;
                            // set title if empty
                            const titleInput = document.getElementById('post-title');
                            if (titleInput && !titleInput.value) titleInput.value = document.querySelector(`[data-id='${id}']`)?.closest('.gallery-item')?.querySelector('h3')?.textContent || '';
                            myModal.style.display = 'none';
                        } catch (err) {
                            console.error(err);
                            alert('Không thể lấy ảnh từ My Creations.');
                        }
                    });
                });

                myList.querySelectorAll('.publish-btn').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        const id = e.currentTarget.dataset.id;
                        try {
                            const resp3 = await fetch(`/memes/${id}/publish`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });
                            if (resp3.ok) {
                                e.currentTarget.textContent = 'Published';
                                e.currentTarget.disabled = true;
                            } else {
                                const err = await resp3.json();
                                alert(err.message || 'Lỗi khi publish');
                            }
                        } catch (err) {
                            console.error(err);
                            alert('Lỗi khi publish.');
                        }
                    });
                });

            } catch (err) {
                console.error(err);
                myList.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:20px">Lỗi khi tải danh sách. Xem console (F12) để biết chi tiết: ${escapeHtml(String(err).substring(0,200))}</div>`;
            }
        });
    }

    if (myClose) myClose.addEventListener('click', () => { myModal.style.display = 'none'; });

    function blobToDataURL(blob) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(blob);
        });
    }

    function escapeHtml(str){
        return String(str).replace(/[&<>\"'`]/g, function(s){
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;","`":"&#96;"})[s];
        });
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            const title = document.getElementById('post-title').value.trim();
            const desc = document.getElementById('post-desc').value.trim();
            const isPublic = document.getElementById('post-public').checked ? true : false;
            const imageBase = preview.dataset.base64;
            if (!title) { alert('Vui lòng nhập tiêu đề.'); return; }
            if (!imageBase) { alert('Vui lòng chọn ảnh hoặc GIF.'); return; }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang đăng...';

            try {
                const res = await fetch('{{ route('memes.saveImage') }}', {
                        method: 'POST',
                        credentials: 'same-origin', // ensure session cookie is sent
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            title: title,
                            type: 'meme',
                            image: imageBase,
                            description: desc,
                            is_public: isPublic
                        })
                    });

                    // If server redirected to login or returned non-JSON (e.g., 419 page), handle gracefully
                    const ct = res.headers.get('content-type') || '';
                    if (!res.ok) {
                        let errText = '';
                        try { errText = await res.text(); } catch(e){}
                        console.error('Save image failed', res.status, res.statusText, errText);
                        if (res.status === 419) {
                            alert('Session đã hết hạn. Vui lòng làm mới trang và đăng nhập lại.');
                        } else if (ct.includes('application/json')) {
                            const errJson = await res.json();
                            alert(errJson.message || 'Lỗi khi đăng bài.');
                        } else {
                            alert('Lỗi server khi đăng bài. Vui lòng thử lại.');
                        }
                        return;
                    }

                    let data = {};
                    if (ct.includes('application/json')) {
                        data = await res.json();
                    } else {
                        // unexpected content type
                        const txt = await res.text();
                        console.error('Unexpected response when saving image:', txt);
                        alert('Không nhận được phản hồi JSON từ server. Vui lòng thử làm mới trang.');
                        return;
                    }

                    if (data.success) {
                        // reload to show new post (simpler)
                        location.reload();
                    } else {
                        alert(data.message || 'Lỗi khi đăng bài.');
                    }
            } catch (err) {
                console.error(err);
                alert('Lỗi khi gửi yêu cầu.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Đăng';
            }
        });
    }
});

async function toggleLike(btn) {
    @auth
        const id = btn.dataset.id;
        const countSpan = btn.querySelector('.like-count');
        
        try {
            const response = await fetch(`/memes/${id}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                countSpan.textContent = data.count;
                if (data.liked) {
                    btn.classList.add('liked');
                } else {
                    btn.classList.remove('liked');
                }
            }
        } catch (error) {
            console.error('Error toggling like:', error);
        }
    @else
        window.location.href = '{{ route("login") }}';
    @endauth
}

// Xử lý gửi bình luận bằng AJAX
async function submitComment(event, memeId) {
    event.preventDefault();
    const form = event.target;
    const input = form.querySelector('input[name="content"]');
    const content = input.value.trim();
    if (!content) return false;

    try {
        const response = await fetch(`/memes/${memeId}/comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content })
        });
        const data = await response.json();
        if (data.success) {
            // Thêm bình luận mới vào danh sách
            const commentsList = form.closest('.comments-section').querySelector('.comments-list');
            const newComment = document.createElement('div');
            newComment.className = 'comment-item';
            newComment.innerHTML = `<span class='comment-avatar'>👤</span> <span class='comment-user'>${data.comment.user}</span> <span class='comment-time'>${data.comment.created_at}</span> <div class='comment-content'>${data.comment.content}</div>`;
            commentsList.appendChild(newComment);
            input.value = '';
        }
    } catch (error) {
        alert('Lỗi gửi bình luận!');
    }
    return false;
}
</script>
@endsection
