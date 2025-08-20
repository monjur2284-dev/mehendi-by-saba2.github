<?php
// admin/index.php - simple login
session_start();
require '../inc/config.php';
$cfg = require '../inc/config.php';
if (isset($_POST['login'])){
    $email = $_POST['email']; $pass = $_POST['password'];
    if ($email === $cfg['admin']['email'] && $pass === $cfg['admin']['password']){
        $_SESSION['admin_logged'] = true; header('Location: orders.php'); exit;
    } else { $err = 'Invalid credentials'; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-5"><h2>Admin Login</h2><?php if(isset($err)) echo '<div class="alert alert-danger">'.htmlspecialchars($err).'</div>'; ?>
<form method="post" style="max-width:420px;"><div class="mb-3"><input name="email" required class="form-control" placeholder="Email"></div><div class="mb-3"><input name="password" type="password" required class="form-control" placeholder="Password"></div><button name="login" class="btn btn-primary">Login</button></form></div></body></html>
