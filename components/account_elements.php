<?php

function load_account_data(int $user_id): array {
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {

    }
    if (isset($pdo_write)) {
        /** @noinspection DuplicatedCode */
        $sql = 'SELECT name, email, full_name FROM db.users WHERE (id = :id);';
        $data = ['id' => htmlspecialchars($user_id)];
        $sql_prep = $pdo_write->prepare($sql);
        $sql_prep->execute($data);
        return $sql_prep->fetch();
    }
    return [];
}
