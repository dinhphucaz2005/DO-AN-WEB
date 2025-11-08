@extends('layouts.app')

@section('title', 'Trang Chủ - FastFood')

@section('content')
  <h2>Thực đơn hôm nay</h2>

  <section class="menu-grid">
    @forelse($products ?? [] as $p)
      <article class="card">
        @if($p->image)
          <img src="{{ $p->image }}" alt="{{ $p->name }}">
        @endif
        <h3>{{ $p->name }}</h3>
        <p>{{ $p->description }}</p>
        <p><strong>{{ number_format($p->price, 0, ',', '.') }}₫</strong></p>
        <button class="btn add-to-cart">Thêm vào giỏ</button>
      </article>
    @empty
      <p>Chưa có món ăn nào. Thêm một vài products bằng seeder hoặc php artisan tinker.</p>
    @endforelse
  </section>

@endsection
