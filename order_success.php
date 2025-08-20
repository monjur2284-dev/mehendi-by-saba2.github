<?php
require 'inc/db.php';
require 'inc/functions.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?'); $stmt->execute([$id]); $o = $stmt->fetch();
if (!$o) { die('Order not found'); }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Order Success</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5 text-center">
  <h1 class="text-success">✅ Order placed</h1>
  <p>Order Number: <strong><?= h($o['order_number']) ?></strong></p>
  <p>Total: <strong><?= number_format($o['total'],2) ?> BDT</strong></p>
  <p><a class="btn btn-outline-secondary" href="admin/invoice.php?id=<?= $o['id'] ?>">Download Invoice (PDF)</a></p>
  <a class="btn btn-primary" href="index.php">Continue Shopping</a>
</div></body></html>
