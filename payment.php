<?php
require 'inc/db.php';
require 'inc/functions.php';
session_start();

$order_id = isset($_GET['order']) ? (int)$_GET['order'] : 0;
$method = $_GET['method'] ?? 'bkash';
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?'); $stmt->execute([$order_id]); $order = $stmt->fetch();
if (!$order) { die('Order not found'); }

// Mock confirm via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mock_txid'])){
    $txid = $_POST['mock_txid'];
    // in real flow: verify tx with bkash/nagad API here
    $pdo->prepare('UPDATE orders SET status = ?, timeline = ? WHERE id = ?')->execute([
        'paid',
        json_encode(array_merge(json_decode($order['timeline'], true), [['at'=>date('c'),'text'=>'Payment verified (mock): '.$txid,'by'=>'system']]), JSON_UNESCAPED_UNICODE),
        $order_id
    ]);
    $_SESSION['cart'] = [];
    header('Location: order_success.php?id='.$order_id); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Payment - <?= h($method) ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5">
  <h3>Payment - <?= h($method) ?> (Mock)</h3>
  <p>Order: <?= h($order['order_number']) ?> — Total: <?= number_format($order['total'],2) ?> BDT</p>
  <div class="card p-3">
    <p>For this demo, enter a mock transaction id to mark the order as paid.</p>
    <form method="post">
      <div class="mb-3"><input name="mock_txid" required class="form-control" placeholder="Mock transaction id"></div>
      <button class="btn btn-primary">Confirm Mock Payment</button>
    </form>
  </div>
</div>
</body></html>
