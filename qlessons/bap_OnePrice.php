<?php

// create a global var for $OnePrice which is used everywhere

global $OnePrice;
$OnePrice = '';
global $currentPrice;
// query the DB for the current price of the Baptism Course.
$a_price = "SELECT `id`, `price` FROM `lessons` WHERE `id` = 85";
$T_price = mysqli()->query($a_price);
$p_price = mysqli()->query($a_price->fetch_array());
$currentPrice = $p_price[1];

//$currentPrice = 3550; // test data2995;
global $oldPrice;
$oldPrice = 1995;

$id = mysqli()->real_escape_string($id);
// creation to test one-price - 6-29-16; to include dual pricing modules.

$op_sql = mysqli()->query("SELECT * FROM `purchase_records` WHERE `user_id`= $id AND `title` LIKE 'Baptism P%'");
$qry1 = $op_sql->num_rows;

if ($qry1 == 1) {
    $row1 = $op_sql->fetch_array();
    $bought = $row1['bought_on'];
    if ($bought <= '2016-06-30 21:02:43') {
        $OnePrice = $oldPrice; // single user
    } else {
        $OnePrice = $currentPrice; // single user
    }
    //echo $OnePrice. ' oneprice-a<br>';
}

$parish_sql = mysqli()->query("SELECT * FROM `purchase_records` WHERE `user_id`= $id AND `title` LIKE 'Parish B%'");
$qry2 = $parish_sql->num_rows;
$bought = $row1['bought_on'];

if ($qry2 == 1) {
    if ($bought <= '2016-06-30 21:02:43') {
        $OnePrice = $currentPrice; // single user
    } else {
        $OnePrice = $currentPrice; // single user
    }
    //echo $OnePrice. ' one price-b<br>';
}
?>