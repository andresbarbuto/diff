<?php
// most recent edit - 6-08-2016; exclude dual pricing modules.
// quiz hint -  insert line to explain Baptism single user quiz taking procedure.
// edit to clear errors

	// check for purchased Expedited Fee.
	$stck2 = mysqli()->query("SELECT * FROM `purchase_records` WHERE `user_id`= $id AND `title` LIKE 'Expedited Pro%'");
	$sttCk2 = $stck2->num_rows; // determine if Expedited is already bought
		if ($sttCk2 ==0) {
			  //buy it now..
			$hint_a='<strong><p><span style="color: black">Please follow any instructions that are presented either at the top or bottom of the Quiz Result screen.</span></p><p>Certificate Processing generally takes up to 3 business days. You may choose to expedite this process by obtaining an <a href="/shop/lessons_detail.php?id=599" target="_blank">expedited processing fee here</a>. All certificate requests must be completed on the order form that follows the quiz.  Please look for the prominent link at the top of the completed quiz.</p></strong>';
}
		if ($sttCk2 >=1) {
			  // already has it..
			  $hint_a = '<strong><p><span style="color: black">Please follow any instructions that are presented either at the top or bottom of the Quiz Result screen.</span></p><p> Since you have already obtained an expedited processing fee, your Request Form after the Quiz will be processed within 24 hours according to the order in which we receive it. All certificate requests must be completed on the order form that follows the quiz.  Please look for the prominent link at the top of the completed quiz.</p></strong>';
}		

$hint= $hint_a; // combine  for one message..

echo $hint;

$hint=''; // clear hint

?>