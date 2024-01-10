<?php
// test data
// $user_id = 6664;

if (isset($user_id) && !empty($user_id)){

$uamessage_string_a= "<p>
Thank you for your purchase of our comprehensive Adult Faith Formation Program! We hope that you enjoy the materials and benefit from them spiritually. Please contact us with any and all questions as we are here to help you.</p>
<p>
As a free gift, we are sending you a copy of the Roman Catechism, which is also called the Catechism of the Council of Trent. Please save this PDF to your computer and refer to it as part of your Faith Formation. <a href='https://cc.catechismclass.com/files/pdf/TrentCat.pdf' >Click Here to Receive Your Gift !</a> You may also want to obtain a copy of <a href='https://amzn.to/3xpfv6u'>&quot;The Roman Catechism Explained for the Modern World&quot;</a> as well.</p>
<p>God Bless you in your studies!</p><p>The CatechismClass.com Family
</p>
";
	
	// process message here
 
//SQL to pull a user array
$sql = "SELECT id, email, firstname, lastname FROM qb_users WHERE id = $user_id ";
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
$subject_string="Your Welcome Gift: The Roman Catechism in PDF";
$message_string = $salute;
$message_string .= $uamessage_string_a;

include 'mailer6.php';

} // end while-->
}
else
{
echo 'no message...';
} // end else-->

getQls()->SQL->free_result($result_b);// clears the variables from the user search

?>
