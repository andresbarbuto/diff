<?php
// test for Quinceanera 5 Cert Course not passed.. alert on screen
// to become an include

if ($lesson_id ==749) {
	if ($avg <=89 ) {
echo "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
?>
<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location='qlessons/l-749_retake.php' "/> 
<?php echo " </span></p>";
	} else {

$quinceSuccess= "select quiz_result where user_id= '$id and $lesson_id in('745','746','747','748','749')";	
$quinceComplete = $qls->SQL->query($quinceSuccess);
$quinceCompleted = $qls->num_rows($quinceComplete);
echo $quinceCompleted.' completed<br/.';	

echo "<h5>You have successfully completed the Quinceanera 5 Preparation Program Quiz. 
You may now request your certificate of completion.<br />"?>
<input type="button" value="Order Your Certificate" class="btn btn-success  mb-2" onClick="window.location='cert-request.php&nature=9' "/> 
<?php echo	"</h5>";
	}
}
?>
