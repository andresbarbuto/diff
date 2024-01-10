<?php
    $isRequiredVariableExists = function_exists("getQls") && function_exists("post") && function_exists("getFileLocation") && isset($user_data) && isset($items) && isset($today);
    if ($isRequiredVariableExists) {
        $boom = explode(':', urldecode(post('custom')));
        $user_id = $boom[0];

        $item_count = 0;
        $user_to = $user_subject = $user_message = "";
        if (post('transaction_subject') != "") {
            $user_to = post('payer_email');
            $user_subject = "Your CatholicParishDirectory.com Purchase";
            $user_message = "Dear ".(ucfirst(post("first_name"))." ".ucfirst(post("last_name"))).", \n<br />\n<br />Thank you again for your purchase from CatholicParishDirectory.com. Please review the details below for accuracy. If there is a problem, please notify us immediately by <a href='https://www.catechismclass.com/support.php'>replying here</a>.  Please provide the information contained in this email for fastest service.\n<br />\n<br />";
            foreach ($items as $itemID => $item) {
                for ($i = 0; $i < $item["qty"]; $i++) { 
                    $user_message .= "$itemID: $item[name]\n<br />";
                    $item_count++;
                }
            }
        } else {
            //User Mailer
            $user_to = $user_data['email'];
            if (($user_data['email'] != post('payer_email')) || ($user_data['email'] == NULL)) {
                $user_to = post('payer_email');
            } // end if !user

            //if (empty($user_id)){
            //$user_subject = ' A Parish Directory Sheet has been Purchased: $'.post('payment_gross');
            //}else{
$user_subject = 'User '.$user_id.', Your CatechismClass.com Purchase';
            //  }

 //User Mailer Intro
 $user_message =
 'Dear '. $user_data['title'].' '. $user_data['firstname'] . ' ' . $user_data['lastname'] . ", \n<br />" . "\n<br />" . 'Thank you again for your purchase from CatechismClass.com. Please review the details below for accuracy. If there is a problem, please notify us immediately by <a href="https://www.catechismclass.com/support.php">replying here</a>.  Please provide the information contained in this email for fastest service. Contact us always using your registered email address: '.$user_data['email'] . "\n<br />" . "\n<br />";
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
if ($item["id"] == 1) {
 $adult_catch = 1;
}

  $sku = 'P-';
 break;
 }

 $user_message .=  'SKU: '.$sku.$item["id"].': '.$item["title"].'  '."\n<br />";
 $item_count++;
   }
  }
 }

 $user_message .=
 'Total Titles Purchased: '.post('num_cart_items').'; Total Item Count: '.$item_count. "\n<br />\n<br />".
 'Payment: '.post('payment_status')."\n<br />" .
 'Total: $' .post('payment_gross')."\n<br />" .
 'Currency: '.post('mc_currency')."\n<br />" .
 'Date of Purchase: '.$today." ".date('T')."\n<br />\n<br />";

 if ($user_id != '') {
 $user_message .='<strong>Next Steps:</strong>'."\n<br />\n<br />" .
 'Thank you again for your purchase. You can view your new lessons immediately by logging in to <a href="https://www.catechismclass.com/login.php"> My Lessons</a>.'."\n<br />\n<br />" .
 '<strong>New User Information:</strong>'."\n<br />\n<br />" .
 'If you are a new user of CatechismClass.com, please refer to our <a href="https://cc.catechismclass.com/manuals/NewCustomerUserManual.pdf" > New Customer User Manual</a> for helpful information on how to use the lessons, how to assign them to students, how to handle quizzes, and more. '."\n<br />\n<br />" .
 'God bless.';
 $user_message .= "\n<br />";
 }

 $to_string = $user_to;
 $subject_string = $user_subject;
 $message_string = $user_message;
 include getFileLocation('mailer3.php');
 $to_string = '';
 $subject_string = '';
 $message_string = '';
  }

if (isset($adult_catch) && $adult_catch !=0){
// include '../adult_msg.php';
$adult_catch=0;
}

if (isset($kid_catch) && $kid_catch !=0){
// include '../kid_msg.php';
$kid_catch=0;
}

?>
