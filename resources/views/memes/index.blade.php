@extends('layouts.app')

@section('title', 'My Memes')

@section('content')
<div class="container" style="padding: 20px;">
    <h1 style="text-align: center; margin-bottom: 20px;">My Saved Memes</h1>

    @if(session('status'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    @if($memes->isEmpty())
        <p style="text-align: center;">You haven't saved any memes yet. <a href="{{ route('home') }}">Create one!</a></p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach($memes as $meme)
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center;">
                    <h3>{{ $meme->title }}</h3>
                    <canvas id="meme-canvas-{{ $meme->id }}" width="300" height="200"></canvas>
                    <p style="color: #666; font-size: 0.9rem;">Saved on: {{ $meme->created_at->format('d M Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const memes = @json($memes);
        memes.forEach(meme => {
            const canvas = new fabric.StaticCanvas(`meme-canvas-${meme.id}`);
            canvas.loadFromJSON(JSON.parse(meme.data), () => {
                canvas.renderAll();
            });
        });
    });
</script>
@endpush

