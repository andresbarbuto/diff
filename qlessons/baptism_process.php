<?php
// most recent edit - 6-29-16; to include dual pricing modules.
// includes in quiz.php -- SJK
// begin section for remedial resets
// checked for OnePrice

$isNewMethod = false;
$id = $qls->SQL->real_escape_string($id);
$recent = $qls->SQL->query("SELECT * FROM purchases 
    WHERE user_id = '$id'
        AND (
            (item_type = 'Package' AND item_id IN (17, 18)) 
            OR (item_type = 'Series' AND item_id IN (6, 64, 65)) 
            OR (item_type = 'Lesson' AND item_id = 85)
        )
        AND DATE(created_at) >= '2018-10-01'
    ORDER BY created_at DESC 
    LIMIT 1");
if ($baptismPassFailPhp_hasRecent = $qls->SQL->fetch_array($recent)) {
    $pDate = $baptismPassFailPhp_hasRecent["created_at"];
    $purchased = strtotime($pDate);
    $isNewMethod = $purchased >= strtotime("2024-01-01 00:00:00");
}

if (!$isNewMethod) {
	$bapProcessing=1;

	//See if Baptism quiz results exist and if retake is allowed
	// $quiz_exists = getQls()->SQL->num_rows(getQls()->SQL->query("SELECT * FROM quiz_results WHERE lesson_id = 85 AND user_id = '$id' ORDER BY created_at DESC LIMIT 1"));
	$may_retake_query = getQls()->SQL->query("SELECT may_retake, doc_flag, created_at FROM quiz_results WHERE lesson_id = 85 AND user_id = '$id' ORDER BY created_at DESC LIMIT 1");
	$may_retake = getQls()->SQL->fetch_row($may_retake_query);
	$retake = $may_retake[0];
	$_85doc_flag = $may_retake[1];

	//echo $_85doc_flag . "  DB_85doc_flag<br/>"; // this is the doc-flags from the last Baptism Quiz
	// prepare the user with the material.

	if ($_85doc_flag>0) {
		// check if this is a remedial lesson
		if (($lesson_id==9)||($lesson_id==28)) {
			$remedial_true = 1;
			//		echo $remedial_true . " is this a remedial quiz= true<br/>";
		} else {
			$remedial_true = 0;
			//		echo $remedial_true . " is this a remedial quiz= false<br/>";
		}// end is this remedial

		if ($lesson_id !=85) {
			if ($remedial_true==1) {
				if ($lesson_id==9) {
					if ($avg<=79) {
						echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of Christ&apos;s Baptism&quot; requires an 80% to pass and needs to be taken again before a Baptism Quiz retake may be granted. Please study the material again. &nbsp;&nbsp;";
						echo '<input type="button" value="Retake this Quiz" class="btn btn-success mb-2" onClick="window.location=\'qlessons/l-9_retake.php\'"/>';
						echo "</p><hr style='height:1.5px; border:none; color:#000; background-color:#000;'>";
					} else {
						$posible_tag9 =1;
		//				echo $posible_tag9 . " possible9<br/>";
					}// end low score
				}// end is it lesson 9

				if ($lesson_id==28) {
					if ($avg<=79) {
						echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of the Sacrament of Baptism&quot; requires an 80% to pass and needs to be taken again before a Baptism Quiz retake may be granted. Please study the material again. &nbsp;&nbsp;";
						echo '<input type="button" value="Retake this Quiz" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-28_retake.php\'"/>';
						echo "</p><hr style='height:1.5px; border:none; color:#000; background-color:#000;'>";
					} else {
						$posible_tag28 =1;
		//				echo $posible_tag28 . " possible28<br/>";
					}// end low score
				}// end is it lesson 28
			}// end is it remedial
		}// end is it not lesson 85
	}// end is the 85doc_flags set
	//echo '// here revised 6/13/20 -SJK<br/>';
	switch ($_85doc_flag) {

		case "11" :
			$remedial9 = 1; // sets required for l_9
			$remedial28 = 1;// sets required for l_28
			break;

		case "10" :
			$remedial9 = 1; // sets required for l_9
			break;

		case "1" :
			$remedial28 = 1;// sets required for l_28
			break;

		default :
			break;
	}//end switch
	//
	// begin test for remedial 9
	if ($remedial9==1) {
		//	echo $remedial9 . " lesson 9 is required<br/>";
		// test to see if lesson 9 is purchased, quizzed, and scored
		$r1_score = 0;
		$confirm_sp9 = "SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id =9";
		$result3= getQls()->SQL->query($confirm_sp9);
		$has_sp9 = getQls()->SQL->num_rows($result3);
		//echo $has_sp9 . " l9 has been purchased<br/>"; //save for use later
		$remedial_quiz1 = "SELECT id, score FROM quiz_results WHERE user_id = $id AND lesson_id =9 ORDER BY id DESC LIMIT 0,1";
		$r1_quiz = getQls()->SQL->num_rows(getQls()->SQL->query($remedial_quiz1));
		if ($r1_quiz >= 1) {
			$r1_detail= getQls()->SQL->fetch_array(getQls()->SQL->query($remedial_quiz1));
			$r1_id= $r1_detail["id"];// tested -save for later
			$r1_score= $r1_detail["score"]; // tested -save for later
			//		$r1_flag= $r1_detail[doc_flag]; // tested -save for later -Matthew declined using this
			//		echo $r1_id ." remedial quiz1 id<br />";
			//		echo $r1_score ." remedial quiz1 score<br />";
			//		echo $r1_flag ." remedial quiz1 flag<br />";
		}
		if ($r1_score>=.8) {
			$l9_clear = 1;
			if ($l9_clear==1) {
				//					echo $l9_clear . " l9_clear is true<br/>";
			}
		} else {
		//					echo $l9_clear . " l9_clear is false<br/>";
		}
	}// end test remedial 9
	//
	// begin testing for remedial28
	if ($remedial28==1) {
		//	echo $remedial28 . "  lesson 28 is required<br/>";
		//test to see if lesson 28 is purchased, quizzed, and scored
		$confirm_sp28 = "SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id =28";
		$result4= getQls()->SQL->query($confirm_sp28);
		$has_sp28 = getQls()->SQL->num_rows($result4);
		//echo $has_sp28 . " l28 has been purchased<br/>"; //save for use later
		$remedial_quiz2 = "SELECT id, score FROM quiz_results WHERE user_id = $id AND lesson_id =28 ORDER BY id DESC LIMIT 0,1";
		$r2_quiz = getQls()->SQL->num_rows(getQls()->SQL->query($remedial_quiz2));
		if ($r2_quiz >= 1) {
			$r2_detail= getQls()->SQL->fetch_array(getQls()->SQL->query($remedial_quiz2));
			$r2_id= $r2_detail["id"]; // tested -save for later
			$r2_score= $r2_detail["score"]; // tested -save for later
			//		$r2_flag= $r2_detail[doc_flag]; // tested -save for later -Matthew declined using this
			//		echo $r2_id ." remedial quiz2 id<br />";
			//		echo $r2_score ." remedial quiz2 score<br />";
			//		echo $r1_flag ." remedial quiz2 flag<br />";
		}
		if ($r2_score>=.8) {
			$l28_clear =1;
			if ($l28_clear==1) {
				//					echo $l28_clear . " l28_clear is true<br/>";
			}
		} else {
			//				echo $l28_clear . " l28_clear is false<br/>";
		}
	}// end test for l28

	//echo "// ==complete to here 6/13/20<br/>";

	// if both are required, test for reset
	if (($remedial9==1)&&($remedial28==1)) {
		if (($l9_clear==0)||($l28_clear==0)) {
			// not cleared
			echo "<p><span style='color: black'>One or more remedial lessons are yet to be completed.</span></p>";
		}

		if (($l9_clear==1)&&($l28_clear==1)) {
			// if true, reset process here
			echo "<p><span style='color: black'>The Remedial work is now satisfied. You may now reset the Baptism Quiz.&nbsp;&nbsp;";
			echo '<input type="button" value=" Click to Reset" class="btn btn-success mb-2" onClick="window.location=\'/qlessons/baptism_retake.php\'"/>';
			echo"</span></p>";
		} else {
			$tq1="SELECT * FROM purchased_lessons WHERE user_id= $id AND lesson_id= 9";
			$t1= getQls()->SQL->num_rows(getQls()->SQL->query($tq1));
			if ($t1 !=0) {
				// if lesson 9 not clear
				if (($remedial9==1)&&($l9_clear!=1)) {
					echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of Christ&apos;s Baptism&quot; is not yet completed.";
					echo '&nbsp;<input type="button" value="Read the Lesson" class="btn btn-success mb-2" onClick="window.location=\'/reading.php?lesson_id=9&page_id=1\'"/>';
					echo " </span></p>";
				}	// end if required l9 not clear
			} else {
				echo '';
			}

			$tq2="SELECT * FROM purchased_lessons WHERE user_id= $id AND lesson_id= 28";
			$t2= getQls()->SQL->num_rows(getQls()->SQL->query($tq2));
			if ($t2 !=0) {
				// if lesson 28 not clear
				if (($remedial28==1)&&($l28_clear!=1)) {
					echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of the Sacrament of Baptism&quot; is not yet completed.";
					echo '&nbsp;<input type="button" value="Read the Lesson" class="btn btn-success mb-2" onClick="window.location=\'/reading.php?lesson_id=28&page_id=1\'"/>';
					echo " </span></p>";
				}	// end if required l28 not clear
			} else {
				echo '';
			}
		} // end else for both clear
	} //end if both are required

	// if only 9 is required, test for reset
	if (($remedial9==1)&&($remedial28==0)) {
		if ($l9_clear==1) {
			// if true, reset process here
			echo "<p><span style='color: black'>The Remedial work is now satisfied. You may now reset the Baptism Quiz.&nbsp;&nbsp;";
			echo '<input type="button" value=" Click to Reset" class="btn btn-success mb-2" onClick="window.location=\'/qlessons/baptism_retake.php\'"/>';
			echo"</span></p>";
		} else {
			// if not true
			echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of Christ&apos;s Baptism&quot; has not been successfully completed.";
			echo '&nbsp;<input type="button" value="Read the Lesson" class="btn btn-success mb-2" onClick="window.location=\'/reading.php?lesson_id=9&page_id=1\'"/>';
			echo " </span></p>";
		} //  else lesson 9 not clear
	}// end is lesson 9  clear

	// if only 28 is required, test for reset
	if (($remedial9==0)&&($remedial28==1)) {
		if ($l28_clear==1) {
			// if true, reset process here
			echo "<p><span style='color: black'>The Remedial work is now satisfied. You may now reset the Baptism Quiz.&nbsp;&nbsp;";
			echo '<input type="button" value=" Click to Reset" class="btn btn-success mb-2" onClick="window.location=\'/qlessons/baptism_retake.php\'"/>';
			echo"</span></p>";
		} else {
			// 	if not true
			echo "<p><span style='color: black'>The remedial work for &quot;The Meaning of the Sacrament of Baptism&quot; has not been successfully completed.";
			echo '&nbsp;<input type="button" value="Read the Lesson" class="btn btn-success mb-2" onClick="window.location=\'/reading.php?lesson_id=28&page_id=1\'"/>';
			echo " </span></p>";
		}	// else lesson 28 not clear
	}// end is lesson 28  clear
	// end section for remedial resets
	//echo "// ==complete to here 6/13/20<br/>";
}
?>