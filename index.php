<?php
require 'inc/db.php';
require 'inc/functions.php';
// Fetch 8 products
$stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC LIMIT 8');
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Yoga Store - Demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="index.php">🧘 Yoga Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">☰</button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#yoga">Yoga Collection</a></li>
        <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="py-5 text-center bg-light">
  <div class="container">
    <h1 class="display-5">Find peace, balance & comfort</h1>
    <p class="lead">Premium yoga gear for your daily practice.</p>
    <a class="btn btn-primary" href="#yoga">Shop Yoga Collection</a>
  </div>
</header>

<section id="yoga" class="py-5">
  <div class="container">
    <h2 class="mb-4">Yoga Collection</h2>
    <div class="row">
      <?php foreach($products as $p): ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="assets/img/<?= h($p['image']?:'placeholder.png') ?>" class="card-img-top" alt="<?= h($p['name']) ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= h($p['name']) ?></h5>
            <p class="card-text"><?= h(substr($p['description'],0,80)) ?>...</p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <strong><?= number_format($p['price'],2) ?> BDT</strong>
              <a class="btn btn-sm btn-outline-primary" href="product.php?id=<?= $p['id'] ?>">View</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-light py-5">
  <div class="container text-center">
    <div class="row">
      <div class="col-md-4"><div class="p-3 border rounded">Free shipping over 3000 BDT</div></div>
      <div class="col-md-4"><div class="p-3 border rounded">Quality guaranteed</div></div>
      <div class="col-md-4"><div class="p-3 border rounded">Secure payments (bKash / Nagad)</div></div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6"><img src="assets/img/hero-600x400.jpg" class="img-fluid rounded"></div>
      <div class="col-md-6">
        <h3>Practice with comfort</h3>
        <p>Products curated for all levels — from beginners to advanced practitioners.</p>
        <a class="btn btn-success" href="#yoga">Explore Collection</a>
      </div>
    </div>
  </div>
</section>

<section class="bg-primary text-white text-center py-5">
  <div class="container">
    <h2>Limited time: 10% off first order</h2>
    <p>Use code <strong>YOGA10</strong></p>
  </div>
</section>

<section id="blog" class="py-5">
  <div class="container">
    <h2 class="mb-4">From our blog</h2>
    <div class="row">
      <div class="col-md-4"><h5>5 Morning Routines</h5><p>Short excerpt...</p></div>
      <div class="col-md-4"><h5>Beginner Tips</h5><p>Short excerpt...</p></div>
      <div class="col-md-4"><h5>Meditation Guide</h5><p>Short excerpt...</p></div>
    </div>
  </div>
</section>

<section class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4">Testimonials</h2>
    <div class="row text-center">
      <div class="col-md-4"><blockquote>"Great quality"</blockquote><p>- A. Rahman</p></div>
      <div class="col-md-4"><blockquote>"Fast delivery"</blockquote><p>- Sima</p></div>
      <div class="col-md-4"><blockquote>"Highly recommend"</blockquote><p>- Joy</p></div>
    </div>
  </div>
</section>

<section id="contact" class="py-5">
  <div class="container">
    <h2 class="mb-4">Contact</h2>
    <form method="post" action="inc/contact_submit.php" class="mx-auto" style="max-width:600px;">
      <div class="mb-3"><input name="name" required class="form-control" placeholder="Your name"></div>
      <div class="mb-3"><input name="email" type="email" required class="form-control" placeholder="Email"></div>
      <div class="mb-3"><textarea name="message" required class="form-control" rows="4" placeholder="Message"></textarea></div>
      <button class="btn btn-primary">Send</button>
    </form>
  </div>
</section>

<footer class="bg-dark text-white py-4 text-center">
  <div class="container">&copy; <?= date('Y') ?> Yoga Store - Demo</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
