<?php
// most recent edit - 6-29-16; to include dual pricing modules.
// test for General RCIA Doctrinal errors.. alert on screen 
// to become an include

// additional edit 4/16/17 to include Sacrament Exceptions to pass-fail statement. SJK

$sacraments= array('82','83','84','721','722','723', '745', '746', '747', '748', '749');

if (isset($remedial_true) && $remedial_true == 0) {

	if ($doctrine_count > 1) {
		echo "<p><span style='color: black'>You have missed some fundamental questions to which all Catholics must know the answer. Please carefully study this lesson again. You will need to retake this quiz later in order to qualify for a CatechismClass.com Certificate. </span></p>";
	}
	
	if ($doctrine_count == 1) {
		echo "<p><span style='color: black'>You have missed a fundamental question to which all Catholics must know the answer. Please carefully study this lesson again. You will need to retake this quiz later in order to qualify for a CatechismClass.com Certificate. </span></p>";
	}

	if ($doctrine_count == 0){
	if (in_array($lesson_id, $sacraments)){
		if ($avg <=89){
			echo"<p><span style='color: black'>Sacramental quizzes besides Baptism require the score 90% to pass. You will require a retake of this quiz.  </span></p>";
			}}
				
		if ($avg <= 69){
		echo "<p><span style='color: black'>You have not missed any fundamental questions; however, a retake of this quiz will still be necessary since the score is below 70%. </span></p>";	
		}

		if (($avg !=100)&&($avg >=70)&&(!in_array($lesson_id, $sacraments))){
		echo "<p><span style='color: black'>Congratulations. You have passed this quiz. Please review any questions above that you may have missed. </span></p>";
		} 
		
	}
}else{
	//echo "remedial slot <br>"; // to be used for remedial lesson logic
}

?>