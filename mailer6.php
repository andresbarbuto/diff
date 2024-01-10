<?php
/**
 * Wahyudi Add (2021-11-30) Add Mailer, parent email notification for new user by dre
 */

if (!function_exists("getFileLocation")) {
	function getFileLocation($FileLocation) {
	    $RootDir = "";
	    for($i = 0; $i <= 5; $i++) {
	        if(file_exists($RootDir.$FileLocation)) {
	            $i = 6; return $RootDir.$FileLocation;
	        }

	        $RootDir .= "../";
	    }

	    return "";
	}
}

include_once getFileLocation('includes/aws/client.php');
$message_string .= '<br/><hr/>';

if (!is_array($bcc)) $bcc = array();

if (!in_array("admin@catechismclass.com", $bcc)) {
	$bcc[] = "admin@catechismclass.com"; //boss does want a copy of this coupon
}

sendEmail([$to_string], $subject_string, $message_string, [], $bcc);

$to_string='';
$subject_string='';
$message_string='';
$bcc='';
?>