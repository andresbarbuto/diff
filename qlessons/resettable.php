<?php
//this is resettable template - 6-10-22 // SJK
// based on these lessons 
//$sacraments= array('86', '87' , '724', '725', '745', '746', '747', '748', '749', '234' );

//test data
//echo $lesson_id  ;

switch ($lesson_id) {
	case "724":
		// insert the retake check for 724
		include 'l-724-pass-fail.php';
	break;

	case "725" :
		//insert the retake check for 725
		include 'l-725-pass-fail.php';
	break;

	case "86" :
		//insert the retake check for 86
		include 'l-86-pass-fail.php';
	break;

	case "87":
		// insert the retake check for 87
		include 'l-87-pass-fail.php';
	break;

	case "745":
		// insert the retake check for 745
		include 'l-745-pass-fail.php';
	break;

	case "746":
		// insert the retake check for 746
		include 'l-746-pass-fail.php';
	break;

	case "747":
		// insert the retake check for 747
		include 'l-747-pass-fail.php';
	break;

	case "748":
		// insert the retake check for 748
		include 'l-748-pass-fail.php';
	break;

	case "749":
		// insert the retake check for 749
		include 'l-749-pass-fail.php';
	break;

	case "234":
		// insert the retake check for 234
		include 'l-234-pass-fail.php';
	break;

	default : break;
} // end switch
?>