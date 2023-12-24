<?php
// most recent edit - 6-29-16; to include dual pricing modules.

// test for Adult Cert Course not passed.. alert on screen 
// to become an include
$adult_les = array (range(4,81,1));
$adultLes = array ($adult_les, 565,926,927);

if (in_array($lesson_id,$adultLes)) {
	
		if ($avg <=99){
			echo "<p><span style='color: black'>If you are seeking a Certificate of Completion at the end of this Course, you may need to retake this Quiz if you either score under 70% on this quiz, or if you miss any fundamental questions. Check for notes at the bottom of the Quiz. </span></p>";
		}
	
}
?>