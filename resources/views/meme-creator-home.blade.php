@extends('layouts.app')

@section('title', 'Meme Creator - Tạo Meme Online Miễn Phí')

@section('content')
<div class="meme-creator-home" style="--page-type:meme-creator;">
    <script>document.body.classList.add('meme-creator-page');</script>

    <!-- Quick Action Buttons -->
    <div class="quick-actions">
        <a href="{{ route('meme.editor') }}" class="action-card active" title="Make a meme with our editor">
            <div class="action-icon">✏️</div>
            <div class="action-label">Make a meme</div>
        </a>
        <a href="{{ route('gif.creator') }}" class="action-card" title="Create animated GIFs">
            <div class="action-icon">🎬</div>
            <div class="action-label">Make a GIF</div>
        </a>
        <div class="action-card disabled" title="Coming soon">
            <div class="action-icon">🤖</div>
            <div class="action-label">AI Meme</div>
            <div class="coming-soon">coming soon...</div>
        </div>
    </div>

    <!-- My Creations Section -->
    @auth
    <div class="section-block">
        <div class="section-header">
            <h2>My Creations</h2>
            <a href="{{ route('memes.index') }}" class="btn btn-small">View All</a>
        </div>
        <div class="creations-placeholder" style="text-align:center; padding:30px; color:#999;">
            You haven't created any memes yet. Start creating! 🎨
        </div>
    </div>
    @else
    <div class="section-block">
        <div class="section-header">
            <h2>My Creations</h2>
            <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
        </div>
    </div>
    @endauth

    <!-- Community Gallery Section -->
    <div class="section-block">
        <div class="section-header">
            <h2>🌍 Community Gallery</h2>
            <a href="{{ route('memes.public') }}" class="btn btn-small">All</a>
        </div>
        <div class="community-placeholder" style="text-align:center; padding:30px; color:#999;">
            Check out amazing memes created by the community! <a href="{{ route('memes.public') }}" style="color:#007bff; text-decoration:none;">Visit Community Gallery →</a>
        </div>
    </div>
</div>

<style>
.meme-creator-home {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
    background: linear-gradient(135deg, #1f1f3d, #2a2a4e);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #fff;
    position: relative;
    min-height: 120px;
}

.action-card:hover:not(.disabled) {
    background: linear-gradient(135deg, #2a2a4e, #3a3a5e);
    border-color: rgba(255,255,255,0.2);
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}

.action-card.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.action-card.active {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
}

.action-icon {
    font-size: 40px;
    margin-bottom: 8px;
}

.action-label {
    font-weight: 600;
    text-align: center;
    margin-bottom: 4px;
}

.coming-soon {
    font-size: 11px;
    color: #ffc107;
    margin-top: 4px;
    font-style: italic;
}

/* Section Blocks */
.section-block {
    margin-bottom: 40px;
    background: linear-gradient(135deg, #f5f5f5, #fafafa);
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 24px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    margin: 0;
    font-size: 1.3rem;
    color: #333;
}

.btn-small {
    padding: 6px 12px;
    font-size: 0.9rem;
    border-radius: 6px;
    background: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-small:hover {
    background: #0056b3;
}

.creations-placeholder,
.community-placeholder {
    background: #fff;
    border: 1px dashed #ddd;
    border-radius: 8px;
}

.btn-primary {
    padding: 8px 16px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-primary:hover {
    background: #0056b3;
}

/* Hide audio control on Meme Creator home page */
body.meme-creator-page #audio-toggle,
body.meme-creator-page #global-audio-control {
    display: none !important;
}
</style>

@endsection
