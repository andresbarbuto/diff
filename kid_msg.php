<?php
// test data
//$user_id = 6664;

if (isset($user_id) && !empty($user_id)){

$message_string_k = '<p>Thank you for purchasing a children&apos;s faith formation course on CatechismClass.com.</p><p>We hope that you enjoy the materials and benefit from them spiritually. Please contact us with any and all questions as we are here to help you.</p><p><strong>Discount Offer for Your Own Education</strong></p><p>For parents looking to better learn the Faith themselves, we are pleased to make available our best-selling <a href= "https://catechismclass.com/shop/package_detail.php?id=1" target="_blank" >Adult Faith Formation Program </a>as a teacher&apos;s manual to you at a substantial discount. You may order our Adult Faith Formation Program, which was the basis of the content we created for all of our children&apos;s programs, for nearly 40% off by using discount code S724.k12teach_ed during checkout.</p>

<p><strong>Free Children&apos;s Activities</strong></p>
<p>And finally, please be aware that we also provide free online Bible pages, audio prayer guides in English and Latin, and online coloring activities to use as activities as well. Please bookmark the links and use it with your family.</p>
<p>God Bless,</p>
<p>The CatechismClass.com Family</p>';	
	// process message here

//SQL to pull a user array
$sql = "SELECT id, email, firstname, lastname, mask_id FROM qb_users WHERE id = $user_id and mask_id = 2";
$result_b= getQls()->SQL->query($sql);

while ($row = getQls()->SQL->fetch_object($result_b)) {
 //$id= $row->user_id;
 $firstname = $row->firstname;
 $lastname = $row->lastname;
 $email = $row->email;
 $salute = "Dear {$firstname} {$lastname},  \n<br/>";
echo $email;
//process this email
$to_string =  $email; // check
$subject_string = "3 Free Catholic Children's Activities";
$message_string = $salute;
$message_string .= $message_string_k;


include 'mailer6.php';

} // end while-->
} // end msg

getQls()->SQL->free_result($result_b);// clears the variables from the user search

?>
