<?php

function tag_create(int $length = 64): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



function tag_check(string $tag, string $type): string {
    $valid = false;
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        $valid = false;
    }


    if (isset($pdo_write)) {
        $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = :type);';
        $data = ['tag' => htmlspecialchars("$tag"),
                'type' => htmlspecialchars("$type")];
        $sql_prep = $pdo_write->prepare($sql);
        $sql_prep->execute($data);
        $user_id = $sql_prep->fetch();

        if (empty($user_id)) {
            $valid = false;
        } else {
            $valid = true;
        }

    }
    return "$valid";
    }
