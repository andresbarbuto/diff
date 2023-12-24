<?php
// test for Marriage Cert Course not passed.. alert on screen
// to become an include

if ($lesson_id ==234) {
	if ($avg <=89 ) {
echo "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
?>
<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location='qlessons/l-234_retake.php' "/> 
<?php echo " </span></p>";
	} else {

	//	echo "<h5>You have successfully completed the Communion Godparent Preparation Program Quiz. You may now request your certificate of completion.<br /><a href='cert-request.php?nature=7' class='btn btn-success mt-2 mb-3' target='_blank'><big>CLICK HERE TO REQUEST</big> </a>	</h5>";
	}
}
?>
