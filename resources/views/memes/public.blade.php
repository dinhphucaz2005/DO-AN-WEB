@extends('layouts.app')

@section('title', 'Community Gallery')

@section('content')
<div class="memes-gallery">
    <div class="gallery-header">
        <h1>🌍 Community Gallery</h1>
        <p>Khám phá những meme và GIF tuyệt vời từ cộng đồng</p>
    </div>

    @if($memes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h2>Chưa có bài đăng nào</h2>
            <p>Hãy là người đầu tiên chia sẻ tác phẩm của bạn!</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                <a href="{{ route('home') }}" class="btn btn-primary">🎨 Tạo Meme</a>
            </div>
        </div>
    @else
        <div class="gallery-grid">
            @foreach($memes as $meme)
                <div class="gallery-item" data-type="{{ $meme->type }}">
                    <div class="item-header">
                        <div class="user-info">
                            <span class="avatar">👤</span>
                            <span class="username">{{ $meme->user->name }}</span>
                        </div>
                        <span class="item-type">{{ $meme->type === 'gif' ? '🎬 GIF' : '🖼️ Meme' }}</span>
                    </div>

                    <div class="item-image">
                        <img src="{{ route('memes.image', $meme->id) }}" alt="{{ $meme->title }}">
                    </div>

                    <div class="item-info">
                        <h3>{{ $meme->title }}</h3>
                        @if($meme->description)
                            <p class="description">{{ Str::limit($meme->description, 100) }}</p>
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
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.gallery-item {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s;
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

.pagination {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}
</style>

<script>
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
</script>
@endsection
