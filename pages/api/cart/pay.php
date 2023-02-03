<?php
require "api_resolve.php";
require "Cart.php";
require_once "pdo_write.php";
require_once "tag_actions.php";

api_require_login();

$cart = new Cart;

$sql_cart = "INSERT INTO db.purchases (url_tag, amount, user_id, info_id)
             SELECT :tag, :total, :uid, MAX(id)
             FROM billing_information
             WHERE user_id = :uid";

$data = [
    'tag' => tag_create(),
    'total' => $cart->total(),
    'uid' => $_SESSION['uid']
];

$p_cart = prepare_write($sql_cart);

if (!$p_cart->execute($data)) {
    api_fail('Internal error 1', ['submit' => 'Internal error']);
}
$sql_lid = 'SELECT LAST_INSERT_ID();';
$p_lid = prepare_write($sql_lid);
$p_lid->execute();
$cart_id = $p_lid->fetch()[0];

$sql_item = "INSERT INTO purchase_items (purchase_id, item_id) VALUES (:cart, :item);";
$p_item = prepare_write($sql_item);

$success = true;
foreach ($cart->ids() as $item) {
    $success &= $p_item->execute([
        'cart' => $cart_id,
        'item' => $item
    ]);
}
if (!$success) {
    api_fail('Internal error 2', ['submit' => 'Internal error']);
}

$sql_transaction = 'INSERT INTO transactions_pending (amount, url_tag, user_id, purchase_id) VALUES (:total, :tag, :uid, :pid);';
$p_transaction = prepare_write($sql_transaction);

$pending_tag = tag_create();
$data_transaction = [
    'total' => $cart->total(),
    'tag' => $pending_tag,
    'uid' => $_SESSION['uid'],
    'pid' => $cart_id
];

if (!$p_transaction->execute($data_transaction)) {
    api_fail('Internal error 3', ['submit' => 'Internal error']);
}
$link = "/bank/verify/$pending_tag";
$msg = "Payment successfully requested: please <br> 
<a href='$link'> confirm the transaction </a> <br> 
with your bank.";

$cart->clear();

api_succeed($msg, data: ['link' => $link]);
