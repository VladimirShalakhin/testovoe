<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();
preg_match_all('(\d+)', $_POST['data'][2], $matches);
$priceAdditional = 0;
foreach ($matches[0] as $value) {
    $priceAdditional += $value * $_POST['data'][1];
}
$days = unserialize($dbh->mselect_rows('a25_products', ['ID' => $_POST['data'][0]], 0, 1, 'id')[0]['TARIFF']);
    if ($days) {
        $price = 0;
        foreach ($days as $key => $value) {
            if ((int)$_POST['data'][1] > $key) {
                $price = $value;
            }
        }
        $result = $price * $_POST['data'][1] + $priceAdditional;        
    } else {
        $price = 0;
        $price = $dbh->mselect_rows('a25_products', ['ID' => $_POST['data'][0]], 0, 1, 'id')[0]['PRICE'];
        $result = $price * $_POST['data'][1] + $priceAdditional;
    }

echo "Сумма заказа ".$result;
?>