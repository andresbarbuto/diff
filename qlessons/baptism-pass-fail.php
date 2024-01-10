<?php
// most recent edit - 6-29-16; to include dual pricing modules.
// test for Baptism Cert Course not passed.. alert on screen
// to become an include
// determine recent purchase, else force a new purch

$isNewMethod = false;
$tag = 1;
$pDate = '';
// Wahyudi Edit (2022-02-02) escape variable, change query to get max created at
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
    $purchased= strtotime($pDate);
    $now = time();
    $valid = ($now - $purchased);
    $diff = strtotime("-1 year");  // 1 year  //63158400'; //2 years
    //Wahyudi Edit (2022-02-10) Remove checking if the date of purchase is last year
    // if ($valid >= $diff) $tag = 0;
    $isNewMethod = $purchased >= strtotime("2024-01-01 00:00:00");
} else $tag = 0;

//echo $purchased.' purch<br/>';
//echo $valid.' valid<br/>';
//echo $diff.' diff<br/>';

// Wahyudi Edit (2022-02-02) Add checking if last cert request older than 2 weeks
$baptismPassFailPhp_certReqQuery = $qls->SQL->query("SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
    FROM support_tickets
    WHERE user_id = '$id'
        AND nature_of_error = 2
        AND (standard_fee != '' OR expedited_fee != '')
        AND cert_time > '$purchased'
    ORDER BY cert_time DESC
    LIMIT 1");
if ($baptismPassFailPhp_certReqResult = $qls->SQL->fetch_assoc($baptismPassFailPhp_certReqQuery)) {
    $baptismPassFailPhp_certTime = $baptismPassFailPhp_certReqResult['cert_time'];
    $baptismPassFailPhp_deadline = $baptismPassFailPhp_certReqResult['deadline'];
    if ($baptismPassFailPhp_certTime < $baptismPassFailPhp_deadline) $tag = 0;
}

if ($lesson_id == 85) {
    $echo = "";
    if ($avg <= 98) {
        if ($isNewMethod) {
            $echo .= "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
            $echo .= "<a href='/qlessons/baptism_retake.php' class='btn btn-success mt-2'>Reset this Lesson</a>";
            $echo .= " </span></p>";
        }
    } else {
        // Wahyudi Edit (2022-02-09) Check if there is new purchase Lesson 85 compare to last quiz
        // if this script included on quiz.php
        $baptismPassFailPhp_lastQuiz = gmdate("Y-m-d");
        if (isset($quizPhp_quizResult) && is_array($quizPhp_quizResult)) {
            $baptismPassFailPhp_lastQuiz = $quizPhp_quizResult["created_at"];
        } else { 
            // Or get from db
            $baptismPassFailPhp_quizQuery = $qls->SQL->query("SELECT * 
                FROM quiz_results 
                WHERE lesson_id = '$lesson_id' AND user_id = '$id' 
                ORDER BY created_at DESC 
                LIMIT 1");
            $baptismPassFailPhp_quizResult = $qls->SQL->fetch_assoc($baptismPassFailPhp_quizQuery);
            $baptismPassFailPhp_lastQuiz = $baptismPassFailPhp_quizResult["created_at"];
        }

        // if there is new purchase compare to last quiz
        if ($pDate != '' && strtotime($pDate) > strtotime($baptismPassFailPhp_lastQuiz)) {
            // Wahyudi Edit (2022-02-14) Add new Row to reading_timed If there is new Purchase
            $rs = $qls->SQL->query("SELECT id, quiz_result_id 
                FROM reading_timed 
                WHERE lesson_id = '$lesson_id' 
                    AND user_id = '$id' 
                ORDER BY created_at DESC 
                LIMIT 1 ");
            if (($row = $qls->SQL->fetch_assoc($rs)) && $row["quiz_result_id"]) {
                $now = gmdate("Y-m-d H:i:s");
                $qls->SQL->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) VALUES ('$id', '$lesson_id', '$now', '00:00:00') ");
            }

            $echo .= "<h5>Thank you for your purchase of the Baptism Preparation Program. Please begin the lesson below, read the materials thoroughly, and attempt the final quiz.<br /> 
            <a href='/reading.php?lesson_id=85&page_id=1' class='btn btn-success mt-2'><big>Begin the Lesson</big></a></h5>";
        } else {
            // Wahyudi Edit (2022-02-02) use if else instead of double (if) checking 
            if ($tag == 1) {
                if ($isNewMethod) {
                    $sql = "SELECT lesson_id, COUNT(id) AS cnt
                            FROM purchased_lessons
                            WHERE user_id = '$id'
                                AND (lesson_id = '598' OR lesson_id = '608')
                            GROUP BY lesson_id";
                    $result = $qls->SQL->query($sql);
                    $isFeeExists = false;
                    while (!$isFeeExists && ($row = $qls->SQL->fetch_assoc($result))) {
                        $condition = $row["lesson_id"] == "598" ? " AND item_id = '598' AND created_at >= '2024-01-01 00:00:00' " : " AND item_id = '608' ";
                        $resultPurchased = $qls->SQL->fetch_assoc(
                            $qls->SQL->query(
                                "SELECT id
                                FROM purchases
                                WHERE user_id = '$id'
                                    AND item_type = 'Lesson'
                                    $condition
                                ORDER BY created_at DESC
                                LIMIT 1"
                            )
                        );

                        $isFeeExists = $resultPurchased && isset($resultPurchased["id"]);
                    }

                    if ($isFeeExists) {
                        $echo .= "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br /><a href='/cert-request.php?nature=2' class='btn btn-success mt-2' target='_blank'><big>CLICK HERE TO REQUEST</big> </a>  </h5>";
                    } else {
                        $l85PassFailPhp_processingFeeButton = '<p><big>
                            To order your certificate, please first remit the necessary processing fee here:
                            <a class="shop-pop-up-button btn btn-success mt-2 mb-3" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=608">Processing Fee</a>
                        <big></p><script src="/scripts/shop-pop-up-button.js"></script>';
                        $result = $qls->SQL->query("
                            SELECT a.id
                            FROM tbl_order AS a 
                                INNER JOIN tbl_order_items AS b 
                                ON a.id = b.order_id 
                            WHERE a.customer_id = '$id' 
                                AND b.product_id = '608' 
                                AND TIMESTAMPDIFF(MINUTE, a.create_at, NOW()) <= 3");
                        if ($row = $qls->SQL->fetch_assoc($result)) {
                            $l85PassFailPhp_processingFeeButton = '<p><big>
                                To order your certificate, please first remit the necessary processing fee here:
                                <a class="btn btn-success mt-2 mb-3 disabled" href="#" onclick="return !1">Processing Your Last Fee Order...</a>
                            <big></p>';
                        }

                        $echo .= $l85PassFailPhp_processingFeeButton;
                    }
                } else {
                    $echo .= "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br /> 
                    <a href='/cert-request.php?nature=2' class='btn btn-success mt-2' target='_blank'><big>CLICK HERE TO REQUEST</big> </a>  </h5>";
                }
            } else {
                $echo .= "<h5>You are requesting a certificate against a previously used course. Please purchase the lesson again in order to continue with your certificate request. <br/><a href='/shop/lessons_detail.php?id=85' class='btn btn-success mt-2' > <big>Purchase New Baptism Lesson</big> </a></h5><br/>";
            }
        }
    }

    echo $echo;
    if ($echo != "") {
        // $notificationDesc = "L-$lesson_id Quiz Result";
        // $notificationRemark = strtotime($baptismPassFailPhp_lastQuiz);
        // $notificationIdUser = $qls->user_info['id'];
        // $notificationCheck = $qls->SQL->query("SELECT Remark FROM a_Notification 
        //     WHERE idUser = '$notificationIdUser' AND Description = '$notificationDesc' 
        //     ORDER BY EntryDate DESC 
        //     LIMIT 1");
        // if (!($notificationRow = $qls->SQL->fetch_array($notificationCheck)) || $notificationRemark > $notificationRow["Remark"]) {
        //     $notificationNow = date('Y-m-d G:i:s');
        //     $notificationTitle = current($qls->SQL->fetch_row($qls->SQL->query("SELECT title FROM lessons WHERE id='$lesson_id'")));
        //     $echo = htmlentities("<h5>".$notificationTitle."</h5><br>".$echo, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE | ENT_IGNORE, "UTF-8", false);
        //     getQls()->SQL->query("INSERT INTO a_Notification (EntryBy, EntryDate, UpdateBy, UpdateDate, isDelete, isVoid, idUser, `Text`, Remark, Description, ExpiredDate, isSeen) 
        //         VALUES ('$notificationIdUser', '$notificationNow', '$notificationIdUser', '$notificationNow', 'N', 'N', '$notificationIdUser', '$echo', '$notificationRemark', '$notificationDesc', '$notificationNow', 'N')");
        // }
    }
}

/*
if ($lesson_id ==85) {
    if ($avg <=99 ) {
        echo "<p><span style='color: black'>You have not yet passed this quiz. Please see the bottom of this page for retake instructions.</span></p>";
    } else {

        echo "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br />
        <a href='baptism-cert-request.php' class='btn btn-success mt-2 mb-3' target='_blank'><big>CLICK HERE TO REQUEST</big> </a>
        </h5>";
    }
}
*/
if (!$isNewMethod) {
    if (($lesson_id == 9) || ($lesson_id == 28)) {
        if ($avg <= 79) {
            echo "<p><span style='color: black'>You have not yet passed this quiz. Please see the bottom of this page for retake instructions.</span></p>";
        } else {
            echo "<p><span style='color: black'>Please see the bottom of this page for retake instructions.</span></p>";
        }
    }
}
?>
