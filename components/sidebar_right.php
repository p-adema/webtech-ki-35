<?php
require 'pdo_write.php';
require 'form_elements.php';


function sidebarRight(): string{
    ensure_session();
    //$ids = $_SESSION['cart']['ids'];
    $ids = [1,2];
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
    }
    $html = '';
    for ($x = 0; $x < count($ids); $x++) {


        if (isset($pdo_write)) {
            $sql = 'SELECT (name) FROM db.videos WHERE (id = :id);';
            $data = ['id' => htmlspecialchars("$ids[$x]")];
            $sql_prep = $pdo_write->prepare($sql);
            $sql_prep->execute($data);
            $name = $sql_prep->fetch();
            $html .= "<a href='#' class='sidebar_item'>$name[name]</a>";
        }
    }
    return "<div class='sidebar_right sidebar_block sidebar_animate_right' style='display:none;right:0;' id='rightMenu'>
                <button onclick='closeRightMenu()' class='sidebar_close'>Close</button>
                <p class='sidebar_text'>Shopping cart:</p>
                <hr>".
                $html . " <hr> 
                <div class='checkout_sidebar'>
                <button onclick='go_to_checkout()' id='checkout_sidebar' type='button'>Checkout</button> </div>
            </div>";
}
// animation dropdown
// animation sidebar leave
// click next to sidebar leave
// sidebar use cart.php


