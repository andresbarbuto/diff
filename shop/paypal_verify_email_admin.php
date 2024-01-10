<?php
    $isRequiredVariableExists = function_exists("getQls") && function_exists("post") && function_exists("getFileLocation") && isset($items) && isset($user_data) && isset($today);
    if ($isRequiredVariableExists) {
        $boom = explode(':', urldecode(post('custom')));
        $user_id = $boom[0];
        $discount_used = isset($boom[1]) ? $boom[1] : "";

        $item_count = 0;
        $admin_message = $admin_buyer_name = $admin_subject = "";
        if (post('transaction_subject') != "") {
            // Wahyudi Edit (2021-11-10) add sister site transaction
            $admin_buyer_name = ucfirst(post("first_name"))." ".ucfirst(post("last_name"));
            $admin_subject = "CatholicParishDirectory.com Purchase Made: \$".post('payment_gross');

            foreach ($items as $itemID => $item) {
                for ($i = 0; $i < $item["qty"]; $i++) { 
                    $admin_message .= "$itemID: $item[name]\n<br />";
                    $item_count++;
                }
            }
        } else {
       
// set-up for sending Gift Message to users
// set-up for sending Kid Coupon Message to users
global $lic27_catch, $lic2_catch, $lic22_catch, $kid_catch, $adult_catch;
$lic2_catch = $lic22_catch = $kid_catch = $adult_catch = 0; // reset any used value
$product_count = "";
  foreach ($items as $item) {
 for ($i = 0; $i < $item["qty"]; $i++) { 
 $sku = "";
switch ($item["type"]) {
 case 'Lesson':
                            if (in_array($item["id"], array('598','599','604','605','608'))) {
                              $sku = 'I-';
                            } else {
                              $sku = 'L-';
                            }
                            break;
                        
case 'Series':
if (in_array($item["id"], array('K','10','11','12','13','14','15','16','45','18','20','19','21','39','40','44','46'))) {
 $kid_catch = 1;
 }

$sku = 'S-';
break;

case 'Package':
 $sku = 'P-';
  if ($item["id"] == 1) {
   $adult_catch = 1;
   } elseif ($item["id"] == 2) {
   $lic2_catch = 1;
   } elseif ($item["id"] == 22) {
   $lic22_catch = 1;
	 } elseif ($item["id"] == 27) {
	 $lic27_catch = 1;
	 }
 break;
                    }

 $product_count .=  'SKU: '.$sku.$item["id"].': '.$item["title"].'  '."\n<br />";
 $item_count++;
                }
            }
    
$admin_subject = 'User '.$user_id.', CatechismClass.com -Purchase Made: $'.post('payment_gross');
            //  }
            

$admin_message = $product_count;
$admin_buyer_name = $user_data['title'].' '.$user_data['firstname'].' '.$user_data['lastname'];
 
/*           
if ($lic2_catch != 0) {
include '../license_distribute2.php'; // send Adult License
}
$lic2_catch = 0; // reset any used value
            
if ($lic22_catch != 0) {
include '../license_distribute22.php'; // send Child License
}
$lic22_catch = 0; // reset any used value
            
if ($lic27_catch >= 1) {
//if ($lic27_catch != 0) {
include '../license_distribute27.php'; // send test
}
// added for test
$lic27_catch = 0; // reset any used value

*/
			

if (isset($adult_catch) && $adult_catch !=0){
include getFileLocation('adult_msg.php'); // send Adult Gift Message
}
$adult_catch = 0; // reset any used value
// Wahyudi Edit (2021-11-10) email notices for both main and sister site

if (isset($kid_catch) && $kid_catch !=0){
include getFileLocation('kid_msg.php'); // send Parent Coupon
}
$kid_catch = 0; // reset any used value
// SJK Edit for K-12 coupon notice (2023-12-14)
// mailer notices-------------
}

//Admin Mailer
$to =  "admin@catechismclass.com"; // edit for testing
$admin_message .=
'Total Titles Purchased: '.post('num_cart_items').'; Total Item Count: '.$item_count."\n<br />\n<br />".
'Payment: '.post('payment_status')."\n<br />".
'Total: $'.post('payment_gross')."\n<br />".
'Currency: '.post('mc_currency')."\n<br />".
'Transaction ID: '.post('txn_id')."\n<br />".
'Date of Purchase: '.$today." ".date('T')."\n<br />".
'Discount Code Used: '.$discount_used."\n<br />\n<br />".
'Buyer Email: '.post('payer_email')."\n<br />";

if ($user_id != "") {
$admin_message .= 'Buyer ID: '.$user_id."\n<br />";
}

$admin_message .= 'Buyer Name: '.$admin_buyer_name."\n<br />";

$to_string = $to;
$subject_string = $admin_subject;
$message_string = $admin_message;
include getFileLocation('mailer.php');
$to_string = '';
$subject_string = '';
$message_string = '';
}
    
?>