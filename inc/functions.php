<?php
// inc/functions.php - helper functions
function h($s){ return htmlspecialchars($s, ENT_QUOTES); }

// generate order number
function gen_order_number(){
    return 'ORD'.time().mt_rand(100,999);
}

// simple mail wrapper for demo (may not work on some hosts)
function notify_admin($to, $subject, $message){
    @mail($to, $subject, $message);
}
