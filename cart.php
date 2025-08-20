<?php
require 'inc/db.php';
require 'inc/functions.php';
session_start();

$cart = $_SESSION['cart'] ?? [];
$items = []; $total = 0.0;
if ($cart){
    $ids = array_keys($cart);
    $in = implode(',', array_map('intval',$ids));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($in)");
    $rows = $stmt->fetchAll();
    foreach($rows as $r){
        $qty = $cart[$r['id']];
        $r['qty'] = $qty;
        $r['line_total'] = $qty * $r['price'];
        $items[] = $r;
        $total += $r['line_total'];
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cart</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5">
  <h2>Your Cart</h2>
  <?php if (empty($items)): ?>
    <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
  <?php else: ?>
    <table class="table"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>
    <?php foreach($items as $it): ?>
      <tr>
        <td><?= h($it['name']) ?></td>
        <td><?= $it['qty'] ?></td>
        <td><?= number_format($it['price'],2) ?></td>
        <td><?= number_format($it['line_total'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <h4>Total: <?= number_format($total,2) ?> BDT</h4>
    <a class="btn btn-primary" href="checkout.php">Proceed to Checkout</a>
  <?php endif; ?>
</div></body></html>
