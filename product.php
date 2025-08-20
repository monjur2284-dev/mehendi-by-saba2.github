<?php
require 'inc/db.php';
require 'inc/functions.php';
session_start();

if (!isset($_GET['id'])){ header('Location: index.php'); exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { header('Location: index.php'); exit; }

// Quick add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='add_to_cart'){
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
    $_SESSION['cart'][$id] += $qty;
    header('Location: cart.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($product['name']) ?> - Yoga Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light">
  <div class="container"><a href="index.php" class="navbar-brand">🧘 Yoga Store</a></div>
</nav>

<div class="container py-5">
  <div class="row">
    <div class="col-md-6"><img src="assets/img/<?= h($product['image']?:'placeholder.png') ?>" class="img-fluid rounded"></div>
    <div class="col-md-6">
      <h2><?= h($product['name']) ?></h2>
      <p class="lead"><?= number_format($product['price'],2) ?> BDT</p>
      <p><?= nl2br(h($product['description'])) ?></p>
      <form method="post" class="mb-3">
        <input type="hidden" name="action" value="add_to_cart">
        <div class="mb-2"><label>Qty</label><input type="number" name="qty" value="1" class="form-control" style="width:120px;"></div>
        <button class="btn btn-success">Add to cart</button>
        <a class="btn btn-primary" href="checkout.php?quick=1&pid=<?= $product['id'] ?>">Buy Now</a>
      </form>

      <h5>Product Details</h5>
      <ul>
        <li>Material: Rubber / Foam</li>
        <li>Color: Multiple</li>
        <li>Size: Standard</li>
      </ul>
    </div>
  </div>
</div>

<footer class="text-center py-4">&copy; <?= date('Y') ?> Yoga Store</footer>
</body>
</html>
