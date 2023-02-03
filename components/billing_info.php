<?php
/**
 * Fetches the latest billing information entered by the user
 * @param int $uid users.id
 * @return array|false * FROM billing_information
 */
function last_billing_info(int $uid): array|false
{
    require_once "pdo_read.php";
    $sql = 'SELECT * FROM billing_information WHERE user_id = :uid ORDER BY id DESC;';
    $data = ['uid' => $uid];

    $p_sql = prepare_readonly($sql);
    $p_sql->execute($data);
    return $p_sql->fetch(PDO::FETCH_ASSOC);
}

/**
 * Displays the billing information for a past purchase, given the purchase tag
 * @param string $url_tag purchases.url_tag
 */
function display_purchase_billing_info(string $url_tag): void
{
    require_once "pdo_read.php";
    $sql = '
SELECT p.amount, b.legal_name, b.country, b.city, b.zipcode, b.street_number
FROM purchases p
         INNER JOIN billing_information b on b.id = p.info_id
WHERE p.url_tag = :url_tag';

    $data = ['url_tag' => $url_tag];

    $prep = prepare_readonly($sql);
    $prep->execute($data);
    $info = $prep->fetch();
    display_billing_info($info, $info['amount']);
}

/**
 * Displays billing information,
 * given an info object representing a db.billing_info row and a total cost
 * @param array $info db.billing_info row
 * @param float $total Total price
 */
function display_billing_info(array $info, float $total): void
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

/**
 * Utility function to redirect users if they don't have a complete billing info registered
 * @param string $link URL to redirect to
 */
function no_billing_info_redirect(string $link): void
{
    if (!($_SESSION['auth'] and (last_billing_info($_SESSION['uid']) !== false)))
    {
        header('Location: ' . $link);
        exit;
    }

}
