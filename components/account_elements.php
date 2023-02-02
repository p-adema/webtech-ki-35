<?php

require_once 'relative_time.php';

function load_account_data(int $user_id): array
{
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {

    }
    if (isset($pdo_write)) {
        $sql = 'SELECT name, email, full_name FROM db.users WHERE (id = :id);';
        $data = ['id' => htmlspecialchars($user_id)];
        $sql_prep = $pdo_write->prepare($sql);
        $sql_prep->execute($data);
        return $sql_prep->fetch();
    }
    return [];
}

function get_purchase_info($uid): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id, url_tag, amount, request_time, "purchase" AS type FROM db.purchases WHERE user_id = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    $data = $sth->fetchAll(PDO::FETCH_ASSOC);

    $sql_join = 'SELECT purchase_items.item_id
FROM db.purchase_items
         INNER JOIN db.purchases ON purchase_items.purchase_id = purchases.id
WHERE purchase_id = :id';
    $sth_join = $pdo_read->prepare($sql_join);

    $count = 0;

    foreach ($data as $element) {
        $sth_join->execute(['id' => $element['id']]);
        $items = $sth_join->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($items)) {
            $data[$count]['items'] = $items;
        }
        else {
            $data[$count]['items'] = '';
        }
        $count += 1;
    }

    return $data;
}

function get_gift_info($uid): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT item_id, confirmation_time, "gift" AS type FROM db.gifts WHERE user_id = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function get_purchase_name($purchase_id): string
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql_type = 'SELECT items.type FROM items INNER JOIN purchase_items ON items.id = purchase_items.item_id WHERE purchase_items.id = :id';
    $sth_type = $pdo_read->prepare($sql_type);
    $sth_type->execute(['id' => $purchase_id]);

    $type = ($sth_type->fetch()['type']);

    if ($type === 'video') {
        $sql = 'SELECT name FROM db.videos WHERE id = :id';

    } else {
        $sql = 'SELECT name FROM db.courses WHERE id = :id';

    }

    $sth = $pdo_read->prepare($sql);
    $sth->execute(['id' => $purchase_id]);
    return $sth->fetch()['name'];

}

function get_gift_name($gift_id): string
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql_tag = 'SELECT tag, type FROM db.items WHERE id = :gift_id';
    $sth_tag = $pdo_read->prepare($sql_tag);
    $sth_tag->execute(['gift_id' => $gift_id]);
    $data = $sth_tag->fetch(PDO::FETCH_ASSOC);

    if ($data['type'] === 'video') {
        $sql = 'SELECT name FROM db.videos WHERE tag = :tag';
    }
    else {
        $sql = 'SELECT name FROM db.courses WHERE tag = :tag';
    }

    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $data['tag']]);

    return $sth->fetch()['name'];
}

function render_items(): void
{
    ensure_session();

    if ($_SESSION['auth']) {
        $items = get_purchase_info($_SESSION['uid']);
        $data = array_pop($items);
        $gifts = get_gift_info($_SESSION['uid']);
        $everything = array_merge($items, $gifts);

        usort($everything, 'compare_dates');

        echo "<div class='box-outline-items'>";

        foreach ($everything as $item) {
            if ($item['type'] === 'purchase') {

                $time = relative_time($item['request_time']);

                $item_count = count($item['items']);
                $count_text = 'item';
                if ($item_count > 1) {
                    $count_text = 'items';
                }

                echo "<button class='item' type='button' onclick='window.location.href=`invoice/{$item['url_tag']}`'>
                    <span class='material-symbols-outlined'>
                    shopping_bag
                    </span>
                    <span class='when'>$time ago </span>
                    <div class='space-inbetween'></div>
                    <span class='amount'>€{$item['amount']}</span>  
                    <div class='space-inbetween'></div>
                    <span class='item-count'>$item_count $count_text</span>
                </button>
            ";
            }
            else {
                $time = relative_time($item['confirmation_time']);
                $name = get_gift_name($item['item_id']);
                echo "
                    <button class='item not-button'>
                    <span class='material-symbols-outlined'>
                    redeem
                    </span>
                    <span class='gift-time'>$time ago </span>
                    <div class='space-inbetween'></div>
                    <span class='amount'>€ 0</span>
                    <div class='space-inbetween'></div>
                    <span class='item-count'>1 item</span>
                    </button>
                ";
            }

        }
    }
    echo "</div>";

}

