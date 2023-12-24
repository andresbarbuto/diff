<?php
// most recent edit - 6-13-20; to remove dual pricing modules and errors from dupes.

$OnePrice= ''; // empty OnePrice
include 'bap_OnePrice.php'; //test for a OnePrice purchase


// begin OnePrice @ $oldPrice= 1995
// =====================================================
// above is removable after transition is complete
//======================================================

// standard processing fee  inserted here
//test for 598 already
//remove old processing fees.
function oldBaptism()
{
	$qls = getQls();
	$user_id = $qls->user_info['id'];
	$sql = "select count(*) as cnt from purchase_records
	where (title = 'Baptism Preparation Program (For Either Godparents or Parents)' or title =  'Sacramental Preparation' or 	title =  'Sacramental Capstone Lessons for Adults' or title = 'All Access Pass - Adults' or 	title = 'Parish All Access Pass Licensing Agreement'  )  and  bought_on < DATE_SUB(NOW(), INTERVAL 2 WEEK)  and user_id = $user_id";
	$result = $qls->SQL->query($sql) or die($qls->SQL->error) ;
	$row=$qls->SQL->fetch_assoc($result);
	return (bool) $row['cnt'];
}

if (oldBaptism()) {
	 //clears all old processed fees

	$clear600="update purchased_lessons set lesson_id= '999600' where lesson_id = '600' and user_id= '$id' ";
	$cleared = $qls->SQL->query($clear600) or die($qls->SQL->error) ;

	$clear601="update purchased_lessons set lesson_id= '999601' where lesson_id = '601' and user_id= '$id' ";
	$cleared = $qls->SQL->query($clear601) or die($qls->SQL->error) ;

	$clear602="update purchased_lessons set lesson_id= '999602' where lesson_id = '602' and user_id= '$id' ";
	$cleared = $qls->SQL->query($clear602) or die($qls->SQL->error) ;

	$clear606="update purchased_lessons set lesson_id= '999606' where lesson_id = '606' and user_id= '$id' ";
	$cleared = $qls->SQL->query($clear606) or die($qls->SQL->error) ;

}

// test for dupBap
function dupBap()
{
	$qls = getQls();
	$user_id = $qls->user_info['id'];

	$sql = "select count(*) as cnt
                from purchases
            where (item_type = 'Lesson' and item_id = 85 or
                   item_type = 'Series' and item_id in (6,64) or
                   item_type = 'Package' and item_id in (17, 18, 19))
              and  created_at < DATE_SUB(NOW(), INTERVAL 2 WEEK)
              and user_id = $user_id";
	$result = $qls->SQL->query($sql) or die($qls->SQL->error) ;
	$row=$qls->SQL->fetch_assoc($result);
	return (bool) $row['cnt'];
}

if (dupBap()) {

	//85?
	$pDetail = "SELECT id FROM purchased_lessons WHERE lesson_id=85 AND user_id= '$id' ORDER BY id DESC LIMIT 0,1";
	$pdl = getQls()->SQL->fetch_array(getQls()->SQL->query($pDetail));
	$maxID = $pdl[0];
	$clear85="select * from purchased_lessons where lesson_id = '85' and user_id= '$id'  ";
	$countingBap= getQls()->SQL->query($clear85);
	$countBap= getQls()->SQL->num_rows($countingBap);
	if ($countBap >=2) {
		$reset= getQls()->SQL->query("update purchased_lessons set lesson_id = 99985 where user_id = '$id' and lesson_id = '85' and id < '$maxID' ");
	}	// clears all dupes
	$check85="select * from purchased_lessons where lesson_id = '85' and user_id= '$id'  ";
	$checkingBap= getQls()->SQL->query($check85);
	$newCountBap= getQls()->SQL->num_rows($checkingBap);
}

// insert 598
$stpk1 = getQls()->SQL->query("SELECT * FROM `purchased_lessons` WHERE `user_id`= $id AND lesson_id ='598' ");
$strdk1 = getQls()->SQL->num_rows($stpk1); // prevent duplication -- only insert if none are there
if ($strdk1 ==0) {
	$sqrdl_1h="INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`,`id`) VALUES ($id, '598', '0', '0','')";
	$strd_a =getQls()->SQL->query($sqrdl_1h);
}// end if 598

	//================
	//add lesson 9 to user account
	//lesson 85 & q.6 then remedial lesson_id=9 is needed
	if (($lesson_id=85)&&($sp_9 >0)) {
		$sqlck1 = getQls()->SQL->query("SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id ='9' ");
		$sqlCk1 = getQls()->SQL->num_rows($sqlck1); // prevent duplication
		if ($sqlCk1 ==0) {
			$sql1="INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`) VALUES ($id, '9', '0', '0')";
			$sql_a=getQls()->SQL->query($sql1);			}
	}

	// add lesson 28 to user account
	//lesson 85 & q.1,3,7,8,9,10 then remedial lesson_id=28 is needed
	if (($lesson_id=85)&&($sp_28 >0)) {
		$sqlck2 = getQls()->SQL->query("SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id ='28' ");
		$sqlCk2 = getQls()->SQL->num_rows($sqlck2); // prevent duplication
		if ($sqlCk2 ==0) {
			$sql2="INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`) VALUES ($id, '28', '0', '0')";
			$sql_b=getQls()->SQL->query($sql2);		}
}


if (($lesson_id==85 )&&($avg<=99)&&((!$sp_9>0)&&(!$sp_28>0))) {
	echo "<p><strong>You may request a re-take of this quiz. Be sure to review the material that you have missed.</p>"; ?>
<p style="text-align: center;" align="center">
	<input type="button" value="Auto-Reset" class="btn btn-success  mb-2" onClick="window.location='/qlessons/baptism_retake.php'"/>
</p></div>
<p>&nbsp;</p>
<?php	}

if (($lesson_id==85 )&&($avg<=99)&&(($sp_9>0)||($sp_28>0))) {
	echo "<p>Based on your results, you are required to complete the following remedial quizzes before you may retake the Baptism Quiz:</p>";
}

?>