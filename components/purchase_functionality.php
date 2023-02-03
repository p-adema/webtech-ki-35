<?php

require_once 'relative_time.php';
require_once 'course_components.php';
require_once 'account_elements.php';

function purchase_tag_exists($tag): bool
{
    ensure_session();

    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.purchases WHERE url_tag = :tag AND user_id = :uid';
    $sth = prepare_readonly($sql);
    $sth->execute(['tag' => $tag, 'uid' => $_SESSION['uid']]);

    return !empty($sth->fetch()['id']);
}

function display_product_information($uid, $url_tag): void
{
    require_once 'pdo_read.php';

    $data = get_purchase_info($uid);

    foreach ($data as $item) {
        if ($item['url_tag'] === $url_tag) {
            foreach($item['items'] as $product) {

                $sql_type = 'SELECT type, tag FROM db.items WHERE id = :id';
                $sth_type = prepare_readonly($sql_type);
                $sth_type->execute(['id' => $product['item_id']]);

                $info = $sth_type->fetch();

                if ($info['type'] === 'video') {
                    $sql = 'SELECT name, subject, uploader, upload_date FROM db.videos WHERE tag = :tag';
                    $sth = prepare_readonly($sql);
                    $sth->execute(['tag' => $info['tag']]);
                    $data_ex = $sth->fetch(PDO::FETCH_ASSOC);
                    $upload_date = relative_time($data_ex['upload_date']);
                    $creator = course_creator($data_ex['uploader'])['name'];
                    $link = '/courses/video/' . $info['tag'];
                }
                else {
                    $sql = 'SELECT name, subject, creator, creation_date FROM db.courses WHERE tag = :tag';
                    $sth = prepare_readonly($sql);
                    $sth->execute(['tag' => $info['tag']]);
                    $data_ex = $sth->fetch(PDO::FETCH_ASSOC);
                    $upload_date = relative_time($data_ex['creation_date']);
                    $creator = course_creator($data_ex['creator'])['name'];
                    $link = '/courses/course/' . $info['tag'];
                }

                echo "
<a href='$link'>
    <div class='purchase-wrapper'>
        <span class='item-name'>{$data_ex['name']}</span>
        <span class='item-subject'>{$data_ex['subject']}</span>
        <div class='purchase-meta'>
            <span class='item-uploader'>Made by: $creator</span>
            <span class='flex-gap'></span>
            <span class='item-upload-since'>Posted $upload_date</span>
        </div>
    </div>
</a>
";
            }
        }
    }
}

function compare_invoice_dates($date1, $date2): int
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
        return -1;
    }
    elseif ($datetime2 > $datetime1) {
        return 1;
    }
    else {
        return 0;
    }
}
