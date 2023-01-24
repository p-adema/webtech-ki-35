<?php
function last_billing_info(int $uid): array|false
{
    require_once "pdo_read.php";
    $sql = 'SELECT * FROM billing_information WHERE user_id = :uid ORDER BY id DESC;';
    $data = ['uid' => $uid];
    $pdo_read = new_pdo_read();

    $p_sql = $pdo_read->prepare($sql);
    $p_sql->execute($data);
    return $p_sql->fetch(PDO::FETCH_ASSOC);
}

function render_billing_info($info, $total): void
{
    echo "
<div class='billing-wrapper'>
    <div class='billing-group'>
        <span class='billing-label'> Legal name: </span>
        <span class='billing-value'> {$info['legal_name']} </span>
    </div>
    <div class='billing-group'>
        <span class='billing-label'> Country: </span>
        <span class='billing-value'> {$info['country']} </span>
    </div>
    <div class='billing-group'>
        <span class='billing-label'> City: </span>
        <span class='billing-value'> {$info['city']} </span>
    </div>
    <div class='billing-group'>
        <span class='billing-label'> Zipcode: </span>
        <span class='billing-value'> {$info['zipcode']} </span>
    </div>
    <div class='billing-group'>
        <span class='billing-label'> Street number: </span>
        <span class='billing-value'> {$info['street_number']} </span>
    </div>
    <hr class='billing-seperator'/>
    <div class='billing-group'>
        <span class='billing-label'> Total: </span>
        <span class='billing-value'> €$total </span>
    </div>
</div>
";
}

function has_info_redirect(string $link): void
{
    if (!($_SESSION['auth'] and (last_billing_info($_SESSION['uid']) !== false)))
    {
        header('Location: ' . $link);
        exit;
    }

}
