<?php
/*
 * Expects a POST request with:
 *      item : < video or course tag >
 */

require "api_resolve.php";
require "pdo_read.php";

$errors = [
    'item' => [],
    'submit' => []
];
$valid = true;
$item_tag = $_POST['item'];

if (empty($item_tag)) {
    $errors['item'][] = 'Please provide an item to be added';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

$data = [
    'item' => $item_tag,
];

$sql = 'SELECT `id`, `price` FROM db.items WHERE (tag = :tag)';
$data = [
    'tag' => $item_tag
];
$pdo_read = new_pdo_read();
$sql_prep = $pdo_read->prepare($sql);
$sql_prep->execute($data);

$item = $sql_prep->fetch(PDO::FETCH_ASSOC);

if ($item === false) {
    $errors['item'][] = 'Invalid product';
    api_fail('Please provide a valid product tag', $errors);
}

require "Cart.php";

$cart = new Cart;
if (!$cart->add_item($item['id'], $item['price'])) {
    $errors['item'][] = 'You already have this item';
    api_fail('This item was already in your cart', $errors);
}

$return = [
    'new_item' => $cart->item_short($item['id']),
    'cart' => $cart->items_short()
];

api_succeed('Item added to cart', $errors, $return);
