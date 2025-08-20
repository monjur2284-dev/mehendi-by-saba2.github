<?php
session_start();
if (!($_SESSION['admin_logged'] ?? false)) { header('Location: index.php'); exit; }
require '../inc/db.php'; require '../inc/functions.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?'); $stmt->execute([$id]); $o = $stmt->fetch();
if (!$o) die('Order not found');
$items = json_decode($o['items'], true);
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>View Order</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5"><h2>Order #<?= $o['order_number'] ?></h2>
<ul class="list-group mb-3"><li class="list-group-item"><b>Customer:</b> <?= h($o['customer_name']) ?> (<?= h($o['phone']) ?>)</li><li class="list-group-item"><b>Address:</b> <?= h($o['address']) ?></li><li class="list-group-item"><b>Payment:</b> <?= h($o['payment_method']) ?></li><li class="list-group-item"><b>Status:</b> <?= h($o['status']) ?></li></ul>
<h4>Items</h4><ul class="list-group mb-3"><?php foreach($items as $it): ?><li class="list-group-item"><?= h($it['name']) ?> x <?= $it['qty'] ?> - <?= number_format($it['price']*$it['qty'],2) ?> BDT</li><?php endforeach; ?></ul>
<h4>Timeline</h4><ul class="list-group"><?php foreach(json_decode($o['timeline'], true) as $t): ?><li class="list-group-item"><?= h($t['at']) ?> - <?= h($t['text']) ?></li><?php endforeach; ?></ul>
<hr>
<h5>Update Status</h5>
<form method="post" action="orders.php">
  <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
  <div class="mb-2"><select name="status" class="form-select"><option value="pending">pending</option><option value="processing">processing</option><option value="shipped">shipped</option><option value="delivered">delivered</option><option value="cancelled">cancelled</option></select></div>
  <div class="mb-2"><input name="note" class="form-control" placeholder="Optional note"></div>
  <button name="update_status" class="btn btn-primary">Update</button>
</form>
</div></body></html>
