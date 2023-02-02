<?php

require_once 'relative_time.php';
require_once 'course_components.php';
require_once 'account_elements.php';

function purchase_tag_exists($tag): bool
{
    ensure_session();

    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.purchases WHERE url_tag = :tag AND user_id = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $tag, 'uid' => $_SESSION['uid']]);

    return !empty($sth->fetch()['id']);
}

function info_by_tag($item_tag): string
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT amount FROM db.purchases WHERE url_tag = :tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $item_tag]);

    return $sth->fetch()['amount'];
}

function item_tag_by_url($url): string
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT purchase_items.item_id FROM db.purchase_items INNER JOIN db.purchases ON purchase_items.purchase_id = purchases.id WHERE purchases.url_tag = :tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $url]);

    $item_id = $sth->fetch()['item_id'];

    $sql_item = 'SELECT tag FROM items WHERE id = :id';
    $sth_item = $pdo_read->prepare($sql_item);
    $sth_item->execute(['id' => $item_id]);

    return $sth_item->fetch()['tag'];
}

function product_information($uid, $url_tag): void
{
    require_once 'pdo_read.php';

    $data = get_purchase_info($uid);

    $lmao = array_pop($data);

    foreach ($data as $item) {
        if ($item['url_tag'] === $url_tag) {
            foreach($item['items'] as $product) {

                $pdo_read = new_pdo_read();
                $sql_type = 'SELECT type, tag FROM db.items WHERE id = :id';
                $sth_type = $pdo_read->prepare($sql_type);
                $sth_type->execute(['id' => $product['item_id']]);

                $info = $sth_type->fetch();

                if ($info['type'] === 'video') {
                    $sql = 'SELECT name, subject, uploader, upload_date FROM db.videos WHERE tag = :tag';
                    $sth = $pdo_read->prepare($sql);
                    $sth->execute(['tag' => $info['tag']]);
                    $data_ex = $sth->fetch(PDO::FETCH_ASSOC);
                    $upload_date = relative_time($data_ex['upload_date']);
                    $creator = course_creator($data_ex['uploader'])['name'];
                }
                else {
                    $sql = 'SELECT name, subject, creator, creation_date FROM db.courses WHERE tag = :tag';
                    $sth = $pdo_read->prepare($sql);
                    $sth->execute(['tag' => $info['tag']]);
                    $data_ex = $sth->fetch(PDO::FETCH_ASSOC);
                    $upload_date = relative_time($data_ex['creation_date']);
                    $creator = course_creator($data_ex['creator'])['name'];
                }

                echo "
                    <span class='item-name'>{$data_ex['name']}</span>
                    <span class='item-subject'>Subject: {$data_ex['subject']}</span>
                    <span class='item-uploader'>Made by: $creator</span>
                    <span class='item-upload-since'>Posted $upload_date ago</span>
                ";
            }
        }
    }
}

function compare_dates($date1, $date2): int
{
    if ($date1['type'] === 'purchase') {
        $datetime1 = strtotime($date1['request_time']);
    }
    else {
        $datetime1 = strtotime($date1['confirmation_time']);
    }
    if ($date2['type'] === 'purchase') {
        $datetime2 = strtotime($date2['request_time']);
    }
    else {
        $datetime2 = strtotime($date2['confirmation_time']);
    }

    if ($datetime1 > $datetime2) {
        return 1;
    }
    elseif ($datetime2 > $datetime1) {
        return -1;
    }
    else {
        return 0;
    }
}