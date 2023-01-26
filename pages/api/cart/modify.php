<?php

/*
 * Expects a POST request with:
 *      type : < 'add' or 'remove' >
 *      item : < item tag >
 */

require "api_resolve.php";
require "pdo_read.php";

$errors = [
    'item' => [],
    'submit' => []
];
$valid = true;
$tag = $_POST['item'] ?? '';
$type = $_POST['type'] ?? '';


if (empty($tag)) {
    $errors['item'][] = 'Please provide an item to be added';
    $valid = false;
}

if (empty($type)) {
    $errors['type'][] = 'Please provide an operation';
    $valid = false;
} elseif ($type !== 'add' and $type !== 'remove') {
    $errors['type'][] = 'Please provide a valid operation';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

require "Cart.php";
$cart = new Cart;

$id = $cart->get_id($tag);

if ($id === false) {
    $errors['item'][] = 'Invalid product';
    api_fail('Please provide a valid product tag', $errors);
}

if ($type === 'add') {
    if (!$cart->add_item($id)) {
        $errors['item'][] = 'You already have this item';
        api_fail('This item was already in your cart', $errors);
    }

    require_once "sidebar_right.php";
    $response = ['html' => render_cart()];

    api_succeed('Item added to cart', $errors, $response);
}

if (!$cart->remove_item($id)) {
    $errors['item'][] = "You don't have this item";
    api_fail("This item wasn't in your cart", $errors);
}
$response = ['tag' => $tag];
api_succeed('Item removed from cart', $errors, $response);
