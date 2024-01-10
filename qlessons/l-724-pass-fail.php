<?php
// test for Sponsor for Confirmation Cert Course not passed.. alert on screen
// to become an include

$tag = 1;
$pDate = '';
$purchased = 0;
// Wahyudi Edit (2022-02-22) escape variable, change query to get max created at
$id = $qls->SQL->real_escape_string($id);
$recent = $qls->SQL->query("SELECT * FROM purchases 
    WHERE user_id = '$id'
        AND (
            (item_type = 'Package' AND item_id IN (23)) 
            OR (item_type = 'Series' AND item_id IN (9, 59)) 
            OR (item_type = 'Lesson' AND item_id = 724)
        )
        AND DATE(created_at) >= '2018-10-01'
    ORDER BY created_at DESC 
    LIMIT 1");
if ($l724PassFailPhp_hasRecent = $qls->SQL->fetch_array($recent)) {
    $pDate = $l724PassFailPhp_hasRecent["created_at"];
    $purchased = strtotime($pDate);
} else $tag = 0;


// Wahyudi Edit (2022-02-02) Add checking if last cert request older than 2 weeks
$l724PassFailPhp_certReqQuery = $qls->SQL->query("SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
    FROM support_tickets
    WHERE user_id = '$id'
        AND nature_of_error = 8
        AND (standard_fee != '' OR expedited_fee != '')
        AND cert_time > '$purchased'
    ORDER BY cert_time DESC
    LIMIT 1");
if ($l724PassFailPhp_certReqResult = $qls->SQL->fetch_assoc($l724PassFailPhp_certReqQuery)) {
    $l724PassFailPhp_certTime = $l724PassFailPhp_certReqResult['cert_time'];
    $l724PassFailPhp_deadline = $l724PassFailPhp_certReqResult['deadline'];
    if ($l724PassFailPhp_certTime < $l724PassFailPhp_deadline) $tag = 0;
}

if ($lesson_id == 724) {
    if ($avg <= 89) {
        echo "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
        echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-724_retake.php\' "/> ';
        echo " </span></p>";
    } else {
        // Wahyudi Edit (2022-02-22) Check if there is new purchase Lesson 724 compare to last quiz
        // if this script included on quiz.php
        $l724PassFailPhp_lastQuiz = gmdate("Y-m-d");
        if (isset($quizPhp_quizResult) && is_array($quizPhp_quizResult)) {
            $l724PassFailPhp_lastQuiz = $quizPhp_quizResult["created_at"];
        } else { 
            // Or get from db
            $l724PassFailPhp_quizQuery = $qls->SQL->query("SELECT * 
                FROM quiz_results 
                WHERE lesson_id = '$lesson_id' AND user_id = '$id' 
                ORDER BY created_at DESC 
                LIMIT 1");
            $l724PassFailPhp_quizResult = $qls->SQL->fetch_assoc($l724PassFailPhp_quizQuery);
            $l724PassFailPhp_lastQuiz = $l724PassFailPhp_quizResult["created_at"];
        }

        // if there is new purchase compare to last quiz
        if ($pDate != '' && strtotime($pDate) > strtotime($l724PassFailPhp_lastQuiz)) {
            // Wahyudi Edit (2022-02-22) Add new Row to reading_timed If there is new Purchase
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

            echo "<h5>Thank you for your purchase of the Sponsor for a Confirmation Preparation Program. Please begin the lesson below, read the materials thoroughly, and attempt the final quiz.<br /> 
            <a href='reading.php?lesson_id=724&page_id=1' class='btn btn-success mt-2 mb-3'><big>Begin the Lesson</big></a></h5>";
        } else {
            $sql = "SELECT lesson_id, COUNT(id) AS cnt
                    FROM purchased_lessons
                    WHERE user_id = '$id'
                        AND lesson_id = '608'
                    GROUP BY lesson_id";
            $result = $qls->SQL->query($sql);
            $isFeeExists = ($row = $qls->SQL->fetch_assoc($result)) ? boolval($row["cnt"]) : false;
            if ($isFeeExists) {
                if ($tag == 1) {
                    echo "<h5>You have successfully completed the Sponsor for a Confirmation Preparation Program Quiz. 
                        You may now request your certificate of completion.<br />
                        <a href='cert-request.php?nature=8' class='btn btn-success mt-2 mb-3' target='_blank'><big>CLICK HERE TO REQUEST</big> </a> </h5>";
                } else {
                    echo "<h5>You are requesting a certificate against a previously used course. Please purchase the lesson again in order to continue with your certificate request. <br/><a href='/shop/lessons_detail.php?id=724' class='btn btn-success mt-2 mb-3' > <big>Purchase a New Lesson</big> </a></h5><br/>";
                }
            } else {
                $l724PassFailPhp_processingFeeButton = '<p><big>
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
                    $l724PassFailPhp_processingFeeButton = '<p><big>
                        To order your certificate, please first remit the necessary processing fee here:
                        <a class="btn btn-success mt-2 mb-3 disabled" href="#" onclick="return !1">Processing Your Last Fee Order...</a>
                    <big></p>';
                }

                echo $l724PassFailPhp_processingFeeButton;
            }
        }
    }
}
?>
