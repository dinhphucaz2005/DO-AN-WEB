@extends('layouts.app')

@section('title', 'My Creations')

@section('content')
<div class="memes-gallery">
    <div class="gallery-header">
        <h1>🎨 My Creations</h1>
        <p>Tất cả meme và GIF bạn đã tạo</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Filter tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">Tất cả ({{ $memes->count() }})</button>
        <button class="filter-tab" data-filter="meme">🖼️ Memes ({{ $memes->where('type', 'meme')->count() }})</button>
        <button class="filter-tab" data-filter="gif">🎬 GIFs ({{ $memes->where('type', 'gif')->count() }})</button>
    </div>

    @if($memes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h2>Chưa có tác phẩm nào</h2>
            <p>Bắt đầu tạo meme hoặc GIF ngay!</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                <a href="{{ route('home') }}" class="btn btn-primary">🎨 Tạo Meme</a>
                <a href="{{ route('gif.creator') }}" class="btn btn-success">🎬 Tạo GIF</a>
            </div>
        </div>
    @else
        <div class="gallery-grid">
            @foreach($memes as $meme)
                <div class="gallery-item" data-type="{{ $meme->type }}">
                    <div class="item-header">
                        <span class="item-type">{{ $meme->type === 'gif' ? '🎬 GIF' : '🖼️ Meme' }}</span>
                        <form action="{{ route('memes.destroy', $meme->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️</button>
                        </form>
                    </div>

                    <div class="item-image">
                        @if($meme->image_path)
                            <img src="{{ Storage::url($meme->image_path) }}" alt="{{ $meme->title }}">
                        @else
                            <canvas id="meme-canvas-{{ $meme->id }}" width="300" height="300"></canvas>
                        @endif
                    </div>

                    <div class="item-info">
                        <h3>{{ $meme->title }}</h3>
                        @if($meme->description)
                            <p class="description">{{ Str::limit($meme->description, 100) }}</p>
                        @endif
                        <div class="item-meta">
                            <span>📅 {{ $meme->created_at->format('d M Y') }}</span>
                            @if($meme->type === 'gif')
                                @php
                                    $settings = json_decode($meme->data, true)['settings'] ?? [];
                                @endphp
                                @if(isset($settings['frameCount']))
                                    <span>🖼️ {{ $settings['frameCount'] }} frames</span>
                                @endif
                            @endif
                        </div>
                        <div class="item-actions">
                            <a href="{{ route('memes.show', $meme->id) }}" class="btn btn-secondary btn-small">👁️ Xem</a>
                            @if($meme->image_path)
                                <a href="{{ Storage::url($meme->image_path) }}" download class="btn btn-primary btn-small">⬇️ Tải về</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.memes-gallery {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
}

.gallery-header {
    text-align: center;
    margin-bottom: 40px;
}

.gallery-header h1 {
    font-size: 2.5rem;
    margin: 0 0 10px 0;
    color: #333;
}

.gallery-header p {
    color: #666;
    font-size: 1.1rem;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 12px 24px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s;
}

.filter-tab:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.filter-tab.active {
    border-color: #007bff;
    background: #007bff;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 20px;
}

.empty-state h2 {
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    font-size: 1.1rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
}

.gallery-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.item-type {
    font-size: 0.9rem;
    font-weight: 600;
    color: #666;
}

.btn-delete {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 5px 10px;
    transition: all 0.2s;
}

.btn-delete:hover {
    transform: scale(1.2);
}

.item-image {
    width: 100%;
    aspect-ratio: 1;
    background: #f5f5f5;
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

.item-image canvas {
    max-width: 100%;
    max-height: 100%;
}

.item-info {
    padding: 20px;
}

.item-info h3 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.2rem;
}

.description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 15px;
    font-size: 0.85rem;
    color: #999;
}

.item-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
    text-align: center;
}

.btn-small {
    padding: 8px 16px;
    font-size: 0.85rem;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }

    .filter-tabs {
        flex-direction: column;
    }

    .filter-tab {
        width: 100%;
    }
}
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Load fabric.js memes (old format without image_path)
    const memes = @json($memes);
    memes.forEach(meme => {
        if (!meme.image_path && meme.type === 'meme') {
            try {
                const canvas = new fabric.StaticCanvas(`meme-canvas-${meme.id}`);
                const data = JSON.parse(meme.data);
                if (data && typeof data === 'object' && !data.saved_at) {
                    canvas.loadFromJSON(data, () => {
                        canvas.renderAll();
                    });
                }
            } catch (e) {
                console.log('Could not load meme:', meme.id);
            }
        }
    });

    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const filter = tab.dataset.filter;

            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.dataset.type === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
@endsection

