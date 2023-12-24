<?php
// test for Sponsor for Confirmation Cert Course not passed.. alert on screen
// to become an include
if ($lesson_id == 87) {
    if ($avg <= 89) {
        echo "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
        echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-87_retake.php\' "/> ';
        echo " </span></p>";
    } else {
        $tag = 1;
        $pDate = '';
        $purchased = 0;
        // Wahyudi Edit (2022-07-19) escape variable, change query to get max created at
        $id = $qls->SQL->real_escape_string($id);

        // Wahyudi Edit (2022-07-27) Check P23 First
        $recent = $qls->SQL->query("SELECT * FROM purchases 
            WHERE user_id = '$id'
                AND item_type = 'Package' 
                AND item_id = 23
                AND DATE(created_at) >= '2018-10-01'
            ORDER BY created_at DESC 
            LIMIT 1");
        if ($l87PassFailPhp_hasP23Recent = $qls->SQL->fetch_array($recent)) {
            $pDate = $l87PassFailPhp_hasP23Recent["created_at"];
            $purchased = strtotime($pDate);
            $tag = 2;
            // Wahyudi Edit (2022-07-22) Add Check Package 1
            // Wahyudi Edit (2022-07-25) Add Check Package 1 if it's P23
            $p1 = $qls->SQL->query("SELECT id FROM purchases 
                    WHERE user_id = '$id'
                        AND item_type = 'Package' 
                        AND item_id = 1
                        AND DATE(created_at) >= '2018-10-01'
                    ORDER BY created_at DESC 
                    LIMIT 1");
            if ($p1Row = $qls->SQL->fetch_array($p1)) {
                $tag = -1;
            } else {
                $l234PassFailPhp_certReqQuery = $qls->SQL->query("SELECT cert_time
                    FROM support_tickets
                    WHERE user_id = '$id'
                        AND nature_of_error = 6
                        AND (standard_fee != '' OR expedited_fee != '')
                        AND cert_time > '$purchased'
                    ORDER BY cert_time DESC
                    LIMIT 1");
                if ($l234PassFailPhp_certReqResult = $qls->SQL->fetch_assoc($l234PassFailPhp_certReqQuery)) {
                    $tag = -2;
                }
            }
        }

        if ($tag == 1 || $tag == -2) {
            $recent = $qls->SQL->query("SELECT * FROM purchases 
            WHERE user_id = '$id'
                AND item_type = 'Series' 
                AND item_id = 9
                AND DATE(created_at) >= '2018-10-01'
            ORDER BY created_at DESC 
            LIMIT 1");
            if ($l87PassFailPhp_hasRecent = $qls->SQL->fetch_array($recent)) {
                $tag = 1;
                $pDate = $l87PassFailPhp_hasRecent["created_at"];
                $purchased = strtotime($pDate);
            } else if ($tag == -2) {
                $tag = 2;
            } else {
                $tag = 0;
            }
        }

        if ($tag > 0) {
            // Wahyudi Edit (2022-07-19) Add checking if last cert request older than 2 weeks
            $l87PassFailPhp_nature = $tag == 2 ? 6 : 5;
            $l87PassFailPhp_program = $tag == 2 ? "Marriage Preparation (Pre Cana and NFP) Program" : "Natural Family Planning Preparation Program";
            $l87PassFailPhp_shopUrl = $tag == 2 ? "/shop/package_detail.php?id=23" : "/shop/series_detail.php?id=9";
            $l87PassFailPhp_insertReadingQuery = $tag == 2 ? ", ('$id', '234', '$now', '00:00:00')" : "";

            $l87PassFailPhp_certReqQuery = $qls->SQL->query("SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
                FROM support_tickets
                WHERE user_id = '$id'
                    AND nature_of_error = $l87PassFailPhp_nature
                    AND (standard_fee != '' OR expedited_fee != '')
                    AND cert_time > '$purchased'
                ORDER BY cert_time DESC
                LIMIT 1");
            if ($l87PassFailPhp_certReqResult = $qls->SQL->fetch_assoc($l87PassFailPhp_certReqQuery)) {
                $l87PassFailPhp_certTime = $l87PassFailPhp_certReqResult['cert_time'];
                $l87PassFailPhp_deadline = $l87PassFailPhp_certReqResult['deadline'];
                if ($l87PassFailPhp_certTime < $l87PassFailPhp_deadline) $tag = 0;
            }

            // Wahyudi Edit (2022-07-19) Check if there is new purchase Lesson 87 compare to last quiz
            // if this script included on quiz.php
            $l87PassFailPhp_lastQuiz = gmdate("Y-m-d");
            if (isset($quizPhp_quizResult) && is_array($quizPhp_quizResult)) {
                $l87PassFailPhp_lastQuiz = $quizPhp_quizResult["created_at"];
            } else { 
                // Or get from db
                $l87PassFailPhp_quizQuery = $qls->SQL->query("SELECT * 
                    FROM quiz_results 
                    WHERE lesson_id = '$lesson_id' AND user_id = '$id' 
                    ORDER BY created_at DESC 
                    LIMIT 1");
                $l87PassFailPhp_quizResult = $qls->SQL->fetch_assoc($l87PassFailPhp_quizQuery);
                $l87PassFailPhp_lastQuiz = $l87PassFailPhp_quizResult["created_at"];
            }

            // if there is new purchase compare to last quiz
            if ($pDate != '' && strtotime($pDate) > strtotime($l87PassFailPhp_lastQuiz)) {
                // Wahyudi Edit (2022-02-22) Add new Row to reading_timed If there is new Purchase
                $rs = $qls->SQL->query("SELECT id, quiz_result_id 
                    FROM reading_timed 
                    WHERE lesson_id = '$lesson_id' 
                        AND user_id = '$id' 
                    ORDER BY created_at DESC 
                    LIMIT 1 ");
                if (($row = $qls->SQL->fetch_assoc($rs)) && $row["quiz_result_id"]) {
                    $now = gmdate("Y-m-d H:i:s");
                    $qls->SQL->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) 
                        VALUES  ('$id', '86', '$now', '00:00:00'),
                                ('$id', '87', '$now', '00:00:00') $l87PassFailPhp_insertReadingQuery");
                }

                echo "<h5>Thank you for your purchase of the $l87PassFailPhp_program. Please begin the lesson below, read the materials thoroughly, and attempt the final quiz.<br /> 
                <a href='reading.php?lesson_id=87&page_id=1' class='btn btn-success mt-2 mb-3'><big>Begin the Lesson</big></a></h5>";
            } else {
                $sql = "SELECT lesson_id, COUNT(id) AS cnt
                        FROM purchased_lessons
                        WHERE user_id = '$id'
                            AND lesson_id = '608'
                        GROUP BY lesson_id";
                $result = $qls->SQL->query($sql);

                $l87PassFailPhp_certRequestButton = $l87PassFailPhp_processingFeeButton = "";
                $l87PassFailPhp_isFeeExists = ($row = $qls->SQL->fetch_assoc($result)) ? boolval($row["cnt"]) : false;
                if ($l87PassFailPhp_isFeeExists) {
                    $l87PassFailPhp_certRequestButton = "<h5>You have successfully completed the $l87PassFailPhp_program Quiz. 
                        You may now request your certificate of completion.<br />
                        <a href='cert-request.php?nature=$l87PassFailPhp_nature' class='btn btn-success mt-2 mb-3' target='_blank'><big>CLICK HERE TO REQUEST</big> </a> </h5>";
                } else {
                    $l87PassFailPhp_processingFeeButton = '<p><big>
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
                        $l87PassFailPhp_processingFeeButton = '<p><big>
                            To order your certificate, please first remit the necessary processing fee here:
                            <a class="btn btn-success mt-2 mb-3 disabled" href="#" onclick="return !1">Processing Your Last Fee Order...</a>
                        <big></p>';
                    }
                }

                if ($tag == 1) {
                    $l87PassFailPhp_l86Score = -1;
                    $l87PassFailPhp_checkOtherQuizQuery = $qls->SQL->query("
                        SELECT MAX(score) AS score, lesson_id
                        FROM quiz_results 
                        WHERE lesson_id = 86
                            AND user_id = '$id' 
                            AND created_at >= '$pDate'
                        GROUP BY lesson_id");
                    if ($row = $qls->SQL->fetch_assoc($l87PassFailPhp_checkOtherQuizQuery)) {
                        $l87PassFailPhp_l86Score = round(($row["score"] * 100), 2);
                    }

                    $l87PassFailPhp_isL86Passed = $l87PassFailPhp_l86Score >= 90;
                    if ($l87PassFailPhp_isL86Passed) {
                        if ($l87PassFailPhp_isFeeExists) {
                            echo $l87PassFailPhp_certRequestButton;
                        } else {
                            echo $l87PassFailPhp_processingFeeButton;
                        }
                    } else {
                        // Wahyudi Edit (2022-07-25) Show text For successfull l87 but not completed S9
                        echo "<h5>You have passed this lesson but have not yet finished the program. Please return to your 'My Lessons' screen to continue with your lessons.</h5>";
                        if ($l87PassFailPhp_l86Score >= 0 && !$l87PassFailPhp_isL86Passed) {
                            echo "<p><span style='color: black'>".$l87PassFailPhp_l86Score . "% is not a passing score for Abortion Short Course. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-86_retake.php\' "/> ';
                            echo " </span></p>";
                        }
                    }
                } else if ($tag == 2) {
                    $l87PassFailPhp_l86Score = $l87PassFailPhp_l234Score = -1;
                    $l87PassFailPhp_checkOtherQuizQuery = $qls->SQL->query("
                        SELECT MAX(score) AS score, lesson_id
                        FROM quiz_results 
                        WHERE lesson_id IN (86, 234) 
                            AND user_id = '$id' 
                            AND created_at >= '$pDate'
                        GROUP BY lesson_id");
                    while ($row = $qls->SQL->fetch_assoc($l87PassFailPhp_checkOtherQuizQuery)) {
                        if ($row["lesson_id"] == "86") $l87PassFailPhp_l86Score = round(($row["score"] * 100), 2);
                        else if ($row["lesson_id"] == "234") $l87PassFailPhp_l234Score = round(($row["score"] * 100), 2);
                    }

                    $l87PassFailPhp_isL86Passed = $l87PassFailPhp_l86Score >= 90;
                    $l87PassFailPhp_isL234Passed = $l87PassFailPhp_l234Score >= 90;
                    if ($l87PassFailPhp_isL86Passed && $l87PassFailPhp_isL234Passed) {
                        if ($l87PassFailPhp_isFeeExists) {
                            echo $l87PassFailPhp_certRequestButton;
                        } else {
                            // echo $l87PassFailPhp_processingFeeButton;
                        }
                    } else {
                        // Wahyudi Edit (2022-07-25) Show text For successfull l87 but not completed P23 
                        echo "<h5>You have passed this lesson but have not yet finished the program. Please return to your 'My Lessons' screen to continue with your lessons.</h5>";
                        if ($l87PassFailPhp_l86Score >= 0 && !$l87PassFailPhp_isL86Passed) {
                            echo "<p><span style='color: black'>".$l87PassFailPhp_l86Score . "% is not a passing score for Abortion Short Course. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-86_retake.php\' "/> ';
                            echo " </span></p>";
                        }

                        if ($l87PassFailPhp_l234Score >= 0 && !$l87PassFailPhp_isL234Passed) {
                            echo "<p><span style='color: black'>".$l87PassFailPhp_l234Score . "% is not a passing score for Pre Cana Preparation. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-234_retake.php\' "/> ';
                            echo " </span></p>";
                        }
                    }
                } else {
                    echo "<h5>You are requesting a certificate against a previously used course. Please purchase the $l87PassFailPhp_program again in order to continue with your certificate request. <br/><a href='$l87PassFailPhp_shopUrl' class='btn btn-success mt-2 mb-3' > <big>Purchase</big> </a></h5><br/>";
                }
            }
        }
    }
}
?>
