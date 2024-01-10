<?php

// most recent edit - 6-13-20; to remove dual pricing modules and errors from dupes.

$OnePrice = ''; // empty OnePrice
include 'bap_OnePrice.php'; //test for a OnePrice purchase

// begin OnePrice @ $oldPrice= 1995
// =====================================================
// above is removable after transition is complete
//======================================================
// standard processing fee  inserted here
//test for 598 already
//remove old processing fees.
function oldBaptism() {
    $user_id = $_SESSION['id'];
    $sql = "SELECT COUNT(*) AS cnt FROM purchase_records
	WHERE (title = 'Baptism Preparation Program (For Either Godparents or Parents)' OR title = 'Sacramental Preparation' OR title = 'Sacramental Capstone Lessons for Adults' OR title = 'All Access Pass - Adults' OR 	title = 'Parish All Access Pass Licensing Agreement' ) AND  bought_on < DATE_SUB(NOW(), INTERVAL 2 WEEK) AND user_id = $user_id";
    $result = mysqli()->query($sql) or die(mysqli()->error);
    $row = $result->fetch_assoc();
    return (bool) $row['cnt'];
}

$id = mysqli()->real_escape_string($id);

if (oldBaptism()) {
    //clears all old processed fees

    $clear600 = "UPDATE purchased_lessons SET lesson_id= '999600' WHERE lesson_id = '600' AND user_id= '$id' ";
    $cleared = mysqli()->query($clear600) or die(mysqli()->error);

    $clear601 = "UPDATE purchased_lessons SET lesson_id= '999601' WHERE lesson_id = '601' AND user_id= '$id' ";
    $cleared = mysqli()->query($clear601) or die(mysqli()->error);

    $clear602 = "UPDATE purchased_lessons SET lesson_id= '999602' WHERE lesson_id = '602' AND user_id= '$id' ";
    $cleared = mysqli()->query($clear602) or die(mysqli()->error);

    $clear606 = "UPDATE purchased_lessons SET lesson_id= '999606' WHERE lesson_id = '606' AND user_id= '$id' ";
    $cleared = mysqli()->query($clear606) or die(mysqli()->error);
}

// test for dupBap
function dupBap() {
    $user_id = $_SESSION['id'];

    $sql = "SELECT COUNT(*) AS cnt
                FROM purchases
            WHERE (item_type = 'Lesson' AND item_id = 85 OR
                   item_type = 'Series' AND item_id IN (6,64) OR
                   item_type = 'Package' AND item_id IN (17, 18, 19))
              AND  created_at < DATE_SUB(NOW(), INTERVAL 2 WEEK)
              AND user_id = $user_id";
    $result = mysqli()->query($sql) or die(mysqli()->error);
    $row = $result->fetch_assoc();
    return (bool) $row['cnt'];
}

if (dupBap()) {

    //85?
    $pDetail = "SELECT id FROM purchased_lessons WHERE lesson_id=85 AND user_id= '$id' ORDER BY id DESC LIMIT 0,1";
    $pdl = mysqli()->query($pDetail->fetch_array());
    $maxID = $pdl[0];

    $clear85 = "SELECT * FROM purchased_lessons WHERE lesson_id = '85' AND user_id= '$id'  ";
    $countingBap = mysqli()->query($clear85);
    $countBap = $countingBap->num_rows;

    if ($countBap >= 2) {
        $reset = mysqli()->query("UPDATE purchased_lessons SET lesson_id = 99985 WHERE user_id = '$id' AND lesson_id = '85' AND id < '$maxID' ");
    } // clears all dupes

    $check85 = "SELECT * FROM purchased_lessons WHERE lesson_id = '85' AND user_id= '$id'  ";
    $checkingBap = mysqli()->query($check85);
    $newCountBap = $checkingBap->num_rows;
}

// insert 598
$stpk1 = mysqli()->query("SELECT * FROM `purchased_lessons` WHERE `user_id`= $id AND lesson_id ='598' ");
$strdk1 = $stpk1->num_rows; // prevent duplication - only insert if none are there
if ($strdk1 == 0) {
    $sqrdl_1h = "INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`,`id`) VALUES ($id, '598', '0', '0','')";
    $strd_a = mysqli()->query($sqrdl_1h);
}// end if 598
//================
//add lesson 9 to user account
//lesson 85 & q.6 then remedial lesson_id=9 is needed
if (($lesson_id = 85) && ($sp_9 > 0)) {
    $sqlck1 = mysqli()->query("SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id ='9' ");
    $sqlCk1 = $sqlck1->num_rows; // prevent duplication
    if ($sqlCk1 == 0) {
        $sql1 = "INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`) VALUES ($id, '9', '0', '0')";
        $sql_a = mysqli()->query($sql1);
    }
}

// add lesson 28 to user account
//lesson 85 & q.1,3,7,8,9,10 then remedial lesson_id=28 is needed
if (($lesson_id = 85) && ($sp_28 > 0)) {
    $sqlck2 = mysqli()->query("SELECT * FROM purchased_lessons WHERE user_id = $id AND lesson_id ='28' ");
    $sqlCk2 = $sqlck2->num_rows; // prevent duplication

    if ($sqlCk2 == 0) {
        $sql2 = "INSERT INTO `purchased_lessons` (`user_id`, `lesson_id`, `parish_assigned`, `parish_student_id`) VALUES ($id, '28', '0', '0')";
        $sql_b = mysqli()->query($sql2);
    }
}


if (($lesson_id == 85 ) && ($avg <= 99) && ((!$sp_9 > 0) && (!$sp_28 > 0))) {
    echo "<p><strong>You may request a re-take of this quiz. Be sure to review the material that you have missed.</p>";
    ?>
    <p style="text-align: center;">
        <input type="button" value="Auto-Reset" class="btn btn-success  mb-2" onClick="window.location = '/qlessons/baptism_retake.php'">
    </p></div>
    <p>&nbsp;</p>
    <?php

}

if (($lesson_id == 85 ) && ($avg <= 99) && (($sp_9 > 0) || ($sp_28 > 0))) {
    echo "<p>Based on your results, you are required to complete the following remedial quizzes before you may retake the Baptism Quiz:</p>";
}
?>