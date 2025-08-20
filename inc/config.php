<?php
// inc/config.php - configure DB and payment placeholders
return [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'yoga_store_demo',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    // Demo admin credentials (change in production)
    'admin' => [
        'email' => 'admin@example.com',
        'password' => 'Admin@123'
    ],
    // Payment placeholders - replace with real merchant credentials
    'bkash' => [
        'app_key' => 'BKASH_APP_KEY',
        'app_secret' => 'BKASH_APP_SECRET',
        'mode' => 'sandbox'
    ],
    'nagad' => [
        'merchant_id' => 'NAGAD_MERCHANT_ID',
        'mode' => 'sandbox'
    ],
    'admin_email' => 'admin@example.com' // used for order notification (mail)
];
