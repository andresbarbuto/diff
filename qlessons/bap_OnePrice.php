<?php
// create a global var for $OnePrice which is used everywhere

global $OnePrice;
$OnePrice = '';
global $currentPrice;
// query the DB for the current price of the Baptism Course.
$a_price="SELECT `id`, `price` FROM `lessons` WHERE `id` = 85";
$T_price=getQls()->SQL->query($a_price);
$p_price=getQls()->SQL->fetch_array(getQls()->SQL->query($a_price));
$currentPrice= $p_price[1];
 
//$currentPrice = 3550; // test data2995;
global $oldPrice;
$oldPrice = 1995;

// creation to test one-price - 6-29-16; to include dual pricing modules.

$op_sql=getQls()->SQL->query("SELECT * FROM `purchase_records` WHERE `user_id`= $id AND `title` LIKE 'Baptism P%'");
$qry1=getQls()->SQL->num_rows($op_sql);
if ($qry1==1){
	$row1 = getQls()->SQL->fetch_array($op_sql);
	$bought = $row1['bought_on'];
	if ($bought <= '2016-06-30 21:02:43'){
	$OnePrice = $oldPrice;// single user
	}else{
	$OnePrice = $currentPrice;// single user
	}
	//echo $OnePrice. ' oneprice-a<br/>';
}

$parish_sql=getQls()->SQL->query("SELECT * FROM `purchase_records` WHERE `user_id`= $id AND `title` LIKE 'Parish B%'");
$qry2=getQls()->SQL->num_rows($parish_sql);
$bought = $row1['bought_on'];
if ($qry2==1){
	if ($bought <= '2016-06-30 21:02:43'){
	$OnePrice = $currentPrice;// single user
	}else{
	$OnePrice = $currentPrice;// single user
	}
	//echo $OnePrice. ' one price-b<br/>';
}

?>