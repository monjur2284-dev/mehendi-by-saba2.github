<?php
// simple contact handler - demo
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../index.php'); exit; }
$name = $_POST['name'] ?? ''; $email = $_POST['email'] ?? ''; $msg = $_POST['message'] ?? '';
$cfg = require __DIR__.'/config.php';
$to = $cfg['admin_email'] ?? 'admin@example.com';
$subject = 'Contact message from website';
$message = "Name: $name\nEmail: $email\n\n$msg";
@mail($to, $subject, $message);
header('Location: ../index.php?contact=1');
exit;
