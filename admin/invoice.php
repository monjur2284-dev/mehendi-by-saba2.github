<?php
session_start();
if (!($_SESSION['admin_logged'] ?? false)) { header('Location: index.php'); exit; }
require '../inc/db.php'; require '../inc/functions.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT o.*, p.name as product_name FROM orders o LEFT JOIN products p ON JSON_EXTRACT(o.items, "$[0].id") = p.id WHERE o.id = ?');
$stmt->execute([$id]);
$o = $stmt->fetch();
// fallback: build HTML invoice using order items JSON
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?'); $stmt->execute([$id]); $o = $stmt->fetch();
if (!$o) die('Order not found');
$items = json_decode($o['items'], true);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Invoice #<?= $o['order_number'] ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container py-4"><h2>Yoga Store - Invoice</h2><p>Invoice: <?= h($o['order_number']) ?></p><p>Customer: <?= h($o['customer_name']) ?> (<?= h($o['phone']) ?>)</p><table class="table"><thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody><?php foreach($items as $it): ?><tr><td><?= h($it['name']) ?></td><td><?= $it['qty'] ?></td><td><?= number_format($it['price'],2) ?></td><td><?= number_format($it['price']*$it['qty'],2) ?></td></tr><?php endforeach; ?></tbody></table><h4>Total: <?= number_format($o['total'],2) ?> BDT</h4></div></body></html>
