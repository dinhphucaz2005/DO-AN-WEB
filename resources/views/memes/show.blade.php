@extends('layouts.app')

@section('title', $meme->title)

@section('content')
<div class="meme-detail">
    <div class="detail-header">
        <a href="{{ route('memes.index') }}" class="btn btn-secondary">⬅️ Quay lại</a>
        <h1>{{ $meme->title }}</h1>
        <div class="header-actions">
            @if($meme->image_path)
                <a href="{{ Storage::url($meme->image_path) }}" download class="btn btn-primary">⬇️ Tải về</a>
            @endif
            <form action="{{ route('memes.destroy', $meme->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</button>
            </form>
        </div>
    </div>

    <div class="detail-content">
        <div class="main-view">
            <div class="meme-viewer">
                @if($meme->image_path)
                    <img src="{{ Storage::url($meme->image_path) }}" alt="{{ $meme->title }}" class="meme-image">
                @else
                    <canvas id="memeCanvas" width="800" height="600"></canvas>
                @endif
            </div>
        </div>

        <div class="sidebar">
            <div class="info-card">
                <h3>📋 Thông tin</h3>
                <div class="info-item">
                    <strong>Loại:</strong>
                    <span class="badge badge-{{ $meme->type }}">
                        {{ $meme->type === 'gif' ? '🎬 GIF' : '🖼️ Meme' }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Ngày tạo:</strong>
                    <span>{{ $meme->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($meme->description)
                    <div class="info-item">
                        <strong>Mô tả:</strong>
                        <p>{{ $meme->description }}</p>
                    </div>
                @endif
            </div>

            @php
                $settings = json_decode($meme->data, true)['settings'] ?? [];
            @endphp

            @if(!empty($settings) && $meme->type === 'gif')
                <div class="info-card">
                    <h3>⚙️ Cài đặt GIF</h3>
                    @if(isset($settings['width']))
                        <div class="info-item">
                            <strong>Kích thước:</strong>
                            <span>{{ $settings['width'] }} × {{ $settings['height'] }}px</span>
                        </div>
                    @endif
                    @if(isset($settings['delay']))
                        <div class="info-item">
                            <strong>Tốc độ:</strong>
                            <span>{{ $settings['delay'] }}ms/frame</span>
                        </div>
                    @endif
                    @if(isset($settings['quality']))
                        <div class="info-item">
                            <strong>Chất lượng:</strong>
                            <span>{{ $settings['quality'] }}</span>
                        </div>
                    @endif
                    @if(isset($settings['frameCount']))
                        <div class="info-item">
                            <strong>Số frames:</strong>
                            <span>{{ $settings['frameCount'] }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="info-card">
                <h3>🔗 Chia sẻ</h3>
                <div class="share-buttons">
                    <button class="btn btn-secondary btn-small" onclick="copyLink()">📋 Copy link</button>
                    @if($meme->image_path)
                        <a href="{{ Storage::url($meme->image_path) }}" target="_blank" class="btn btn-secondary btn-small">🔗 Xem trực tiếp</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.meme-detail {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.detail-header h1 {
    flex: 1;
    margin: 0;
    color: #333;
    font-size: 2rem;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.detail-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.main-view {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.meme-viewer {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    min-height: 400px;
}

.meme-image {
    max-width: 100%;
    max-height: 800px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.info-card h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.2rem;
}

.info-item {
    margin-bottom: 15px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item strong {
    display: block;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.info-item span,
.info-item p {
    color: #333;
    margin: 0;
}

.badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.badge-meme {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-gif {
    background: #f3e5f5;
    color: #7b1fa2;
}

.share-buttons {
    display: flex;
    flex-direction: column;
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

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

@media (max-width: 1024px) {
    .detail-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .detail-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .detail-header h1 {
        font-size: 1.5rem;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions button,
    .header-actions a {
        flex: 1;
    }
}
</style>

@if(!$meme->image_path && $meme->type === 'meme')
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = new fabric.StaticCanvas('memeCanvas');
            const data = @json(json_decode($meme->data));

            if (data && typeof data === 'object' && !data.saved_at) {
                canvas.loadFromJSON(data, () => {
                    canvas.renderAll();
                });
            }
        });
    </script>
    @endpush
@endif

<script>
function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('✅ Đã copy link vào clipboard!');
    }).catch(() => {
        alert('❌ Không thể copy link');
    });
}
</script>
@endsection

