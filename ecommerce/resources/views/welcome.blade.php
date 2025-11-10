<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Minha Loja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar-brand {
      font-weight: bold;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .card-text small {
      display: block;
      color: #6c757d;
    }
  </style>
</head>
<body>

@php
  $cartCount = 0;
  if (isset($pedido) && $pedido && isset($pedido->itens)) {
    $cartCount = count($pedido->itens);
  } elseif(session()->has('carrinho')) {
    $carrinho = session('carrinho');
    if (is_array($carrinho)) {
      $sum = 0;
      foreach($carrinho as $it) { $sum += isset($it['quantidade']) ? (int)$it['quantidade'] : 0; }
      $cartCount = $sum;
    }
  }
@endphp

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">Minha Loja</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        @auth
          <li class="nav-item"><a class="nav-link active" href="/login">Login</a></li>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
        @endauth
        <li class="nav-item"><a class="nav-link" href="#">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
        @auth
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/carrinho') }}">
              <span aria-hidden="true">🛒</span>
              <span class="badge bg-danger ms-1">{{ $cartCount }}</span>
            </a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/login') }}">
              <span aria-hidden="true">🛒</span>
              <span class="badge bg-danger ms-1">{{ $cartCount }}</span>
            </a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<header class="bg-light py-5 text-center">
  <div class="container">
    <h1 class="fw-bold">Bem-vindo à Minha Loja!</h1>
    <p class="lead">Os melhores produtos com os melhores preços</p>
    <a href="#produtos" class="btn btn-primary btn-lg">Ver Ofertas</a>
  </div>
</header>

<!-- Produtos -->
<section id="produtos" class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center fw-semibold">Nossos Produtos</h2>

    @if(isset($produtos) && count($produtos))
      <div class="row g-4" id="products-grid">
        @foreach($produtos as $p)
          <div class="col-sm-6 col-md-4 col-lg-3 {{ $loop->index >= 4 ? 'more-product d-none' : '' }}">
            <div class="card h-100 shadow-sm">
              @php
                $images = is_array($p->imagem) ? $p->imagem : ($p->imagem ? [$p->imagem] : []);
              @endphp
              <div class="product-image-wrapper position-relative" data-product-id="{{ $p->id }}">
                @if(count($images))
                  <div class="product-images">
                    @foreach($images as $i => $img)
                      <img src="{{ $img }}" class="card-img-top product-image" alt="Imagem do produto" data-index="{{ $i }}" style="display: {{ $i === 0 ? 'block' : 'none' }};">
                    @endforeach
                  </div>
                  @if(count($images) > 1)
                    <button type="button" class="img-nav btn-prev" onclick="prevImage({{ $p->id }})">‹</button>
                    <button type="button" class="img-nav btn-next" onclick="nextImage({{ $p->id }})">›</button>
                  @endif
                @else
                  <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Imagem do produto">
                @endif
              </div>
              <div class="card-body text-center">
                <h5 class="card-title">Produto #{{ $p->id }}</h5>
                <p class="card-text text-muted mb-1">R${{ number_format($p->Valor ?? 0, 2, ',', '.') }}</p>
                <p class="card-text">
                  <small>Tamanho: {{ $p->Tamanho ?? 'N/A' }}</small>
                  <small>Cor: {{ $p->Cor ?? 'N/A' }}</small>
                  <small>Gênero: {{ $p->Genero ?? 'N/A' }}</small>
                  <small>Marca: {{ $p->marca->Nome ?? 'N/A' }}</small>
                  <small>Tipo: {{ $p->tipo->Nome ?? 'N/A' }}</small>
                </p>
                @auth
                  <a href="/carrinho/add/{{ $p->id }}" class="btn btn-outline-primary mt-2">Comprar</a>
                @else
                  <a href="{{ url('/login') }}" class="btn btn-outline-primary mt-2">Comprar</a>
                @endauth
              </div>
            </div>
          </div>
        @endforeach
      </div>

      @if(count($produtos) > 4)
        <div class="text-center mt-4">
          <button id="show-more-btn" class="btn btn-outline-secondary">Mostrar mais</button>
        </div>
      @endif
    @else
      <p class="text-center text-muted">Nenhum produto disponível no momento.</p>
    @endif

  </div>
</section>

<!-- Rodapé -->
<footer class="bg-dark text-light py-4 text-center">
  <div class="container">
    <p class="mb-0">&copy; {{ date('Y') }} Minha Loja. Todos os direitos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
  .product-image-wrapper { overflow: hidden; }
  .product-image { width: 100%; height: auto; display: block; }
  .img-nav { position: absolute; top: 50%; transform: translateY(-50%); width:28px; height:28px; border-radius:50%; border:0; background: rgba(0,0,0,0.45); color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; line-height:1; }
  .img-nav.btn-prev { left:8px; }
  .img-nav.btn-next { right:8px; }
</style>
<script>
  function showImageFor(productId, index) {
    const wrapper = document.querySelector('[data-product-id="'+productId+'"]');
    if (!wrapper) return;
    const imgs = wrapper.querySelectorAll('.product-image');
    if (!imgs || imgs.length === 0) return;
    imgs.forEach((img, i) => img.style.display = (i === index ? 'block' : 'none'));
  }
  function nextImage(productId) {
    const wrapper = document.querySelector('[data-product-id="'+productId+'"]');
    if (!wrapper) return;
    const imgs = wrapper.querySelectorAll('.product-image');
    let active = 0;
    imgs.forEach((img, i) => { if (img.style.display !== 'none') active = i; });
    const next = (active + 1) % imgs.length;
    showImageFor(productId, next);
  }
  function prevImage(productId) {
    const wrapper = document.querySelector('[data-product-id="'+productId+'"]');
    if (!wrapper) return;
    const imgs = wrapper.querySelectorAll('.product-image');
    let active = 0;
    imgs.forEach((img, i) => { if (img.style.display !== 'none') active = i; });
    const prev = (active - 1 + imgs.length) % imgs.length;
    showImageFor(productId, prev);
  }
</script>
<script>
  (function(){
    const btn = document.getElementById('show-more-btn');
    if (!btn) return;
    btn.addEventListener('click', function(){
      const hidden = document.querySelectorAll('.more-product.d-none');
      if (hidden.length > 0) {
        document.querySelectorAll('.more-product').forEach(el => el.classList.remove('d-none'));
        btn.textContent = 'Mostrar menos';
      } else {
        document.querySelectorAll('.more-product').forEach(el => el.classList.add('d-none'));
        btn.textContent = 'Mostrar mais';
        // scroll back to products grid top
        const grid = document.getElementById('products-grid');
        if (grid) grid.scrollIntoView({behavior: 'smooth'});
      }
    });
  })();
</script>
</body>
</html>
