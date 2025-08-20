<?php
require 'inc/db.php';
require 'inc/functions.php';
session_start();

// If quick buy single product
if (isset($_GET['quick']) && isset($_GET['pid'])){
    $pid = (int)$_GET['pid'];
    $_SESSION['cart'] = [$pid => 1];
}

// prepare cart items
$cart = $_SESSION['cart'] ?? [];
if (!$cart){ header('Location: cart.php'); exit; }
$ids = implode(',', array_map('intval', array_keys($cart)));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)"); $rows = $stmt->fetchAll();
$items = []; $total = 0.0;
foreach($rows as $r){ $qty = $cart[$r['id']]; $items[]=['id'=>$r['id'],'name'=>$r['name'],'price'=>$r['price'],'qty'=>$qty]; $total += $r['price']*$qty; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])){
    $name = $_POST['name']; $phone = $_POST['phone']; $email = $_POST['email'] ?? ''; $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $order_number = gen_order_number();
    $items_json = json_encode($items, JSON_UNESCAPED_UNICODE);
    $timeline = json_encode([['at'=>date('c'),'text'=>'Order placed','by'=>'system']]);
    $stmt = $pdo->prepare('INSERT INTO orders (order_number, customer_name, customer_email, phone, address, items, total, payment_method, status, timeline) VALUES (?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([$order_number, $name, $email, $phone, $address, $items_json, $total, $payment_method, 'pending', $timeline]);
    $order_id = $pdo->lastInsertId();
    // notify admin (demo)
    $cfg = require __DIR__.'/inc/config.php';
    notify_admin($cfg['admin_email'], "New order: $order_number", "Order $order_number placed. Total: $total. Phone: $phone");
    // For bKash/Nagad you'd redirect to payment flow here. We'll show mock payment page.
    $_SESSION['last_order'] = $order_id;
    // If COD, mark as cod_pending
    if ($payment_method === 'cod'){
        $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute(['cod_pending', $order_id]);
        $_SESSION['cart'] = [];
        header('Location: order_success.php?id='.$order_id); exit;
    } else {
        header('Location: payment.php?order='.$order_id.'&method='.$payment_method); exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5">
  <h2>Checkout</h2>
  <div class="row">
    <div class="col-md-6">
      <h4>Order Summary</h4>
      <ul class="list-group mb-3">
        <?php foreach($items as $it): ?>
          <li class="list-group-item d-flex justify-content-between">
            <div><?= h($it['name']) ?> x <?= $it['qty'] ?></div>
            <div><?= number_format($it['price']*$it['qty'],2) ?> BDT</div>
          </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between"><strong>Total</strong><strong><?= number_format($total,2) ?> BDT</strong></li>
      </ul>
    </div>
    <div class="col-md-6">
      <form method="post">
        <div class="mb-3"><input name="name" required class="form-control" placeholder="Full name"></div>
        <div class="mb-3"><input name="phone" required class="form-control" placeholder="Phone" value="01797850441"></div>
        <div class="mb-3"><input name="email" class="form-control" placeholder="Email"></div>
        <div class="mb-3"><textarea name="address" class="form-control" placeholder="Shipping address"></textarea></div>
        <h5>Payment Method</h5>
        <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" id="pm1" value="bkash" checked><label class="form-check-label" for="pm1">bKash</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" id="pm2" value="nagad"><label class="form-check-label" for="pm2">Nagad</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" id="pm3" value="cod"><label class="form-check-label" for="pm3">Cash on Delivery</label></div>
        <div class="mt-3"><button name="place_order" class="btn btn-success">Place Order</button></div>
      </form>
    </div>
  </div>
</div></body></html>
