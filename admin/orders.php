<?php
session_start();
if (!($_SESSION['admin_logged'] ?? false)) { header('Location: index.php'); exit; }
require '../inc/db.php';
require '../inc/functions.php';

// CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="orders.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['id','order_number','customer','phone','total','payment_method','status','created_at']);
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC');
    while ($r = $stmt->fetch()){
        fputcsv($out, [$r['id'],$r['order_number'],$r['customer_name'],$r['phone'],$r['total'],$r['payment_method'],$r['status'],$r['created_at']]);
    }
    exit;
}

// status update
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_status'])){
    $oid = (int)$_POST['order_id']; $status = $_POST['status']; $note = $_POST['note'] ?? '';
    $stmt = $pdo->prepare('SELECT timeline FROM orders WHERE id = ?'); $stmt->execute([$oid]); $row = $stmt->fetch();
    $timeline = json_decode($row['timeline'], true);
    $timeline[] = ['at'=>date('c'),'text'=>"Status changed to $status. $note",'by'=>'admin'];
    $pdo->prepare('UPDATE orders SET status = ?, timeline = ? WHERE id = ?')->execute([$status, json_encode($timeline, JSON_UNESCAPED_UNICODE), $oid]);
    header('Location: orders.php'); exit;
}

// pagination simple
$per = 10; $page = max(1,(int)($_GET['p'] ?? 1)); $offset = ($page-1)*$per;
$total = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pages = ceil($total/$per);

$stmt = $pdo->prepare('SELECT * FROM orders ORDER BY id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1,(int)$per,PDO::PARAM_INT); $stmt->bindValue(2,(int)$offset,PDO::PARAM_INT); $stmt->execute();
$orders = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin - Orders</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container py-5">
<h2>Orders</h2><a class="btn btn-success mb-3" href="orders.php?export=csv">Export CSV</a>
<table class="table table-striped"><thead><tr><th>#</th><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead><tbody>
<?php foreach($orders as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= h($o['order_number']) ?></td><td><?= h($o['customer_name']) ?><br><?= h($o['phone']) ?></td><td><?= number_format($o['total'],2) ?></td><td><?= h($o['status']) ?></td><td><?= $o['created_at'] ?></td>
<td><a class="btn btn-sm btn-info" href="view_order.php?id=<?= $o['id'] ?>">View</a> <a class="btn btn-sm btn-warning" href="invoice.php?id=<?= $o['id'] ?>">Invoice</a></td></tr>
<?php endforeach; ?>
</tbody></table>
<nav><ul class="pagination">
<?php for($i=1;$i<=$pages;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="orders.php?p=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>
</ul></nav>
</div></body></html>
