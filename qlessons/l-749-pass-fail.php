<?php
// test for Sponsor for Confirmation Cert Course not passed.. alert on screen
// to become an include
if ($lesson_id == 749) {
    if ($avg <= 89) {
        echo "<p><span style='color: black'>".$avg . "% is not a passing score. You may click to reset here. ";
        echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-'.$lesson_id.'_retake.php\' "> ';
        echo " </span></p>";
    } else {
        $tag = 1;
        $pDate = '';
        $purchased = 0;
        // Wahyudi Edit (2022-08-04) escape variable, change query to get max created at
        $id = mysqli()->real_escape_string($id);

        $recent = mysqli()->query("SELECT * FROM purchases 
            WHERE user_id = '$id'
                AND item_type = 'Series' 
                AND item_id = 59
                AND DATE(created_at) >= '2018-10-01'
            ORDER BY created_at DESC 
            LIMIT 1");

        if ($l749PassFailPhp_hasS59Recent = $recent->fetch_array()) {
            $pDate = $l749PassFailPhp_hasS59Recent["created_at"];
            $purchased = strtotime($pDate);
        } else {
            $tag = 0;
        }

        if ($tag > 0) {
            // Wahyudi Edit (2022-08-04) Add checking if last cert request older than 2 weeks
            $l749PassFailPhp_certReqQuery = mysqli()->query("SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
                FROM support_tickets
                WHERE user_id = '$id'
                    AND nature_of_error = 9
                    AND (standard_fee != '' OR expedited_fee != '')
                    AND cert_time > '$purchased'
                ORDER BY cert_time DESC
                LIMIT 1");

            if ($l749PassFailPhp_certReqResult = $l749PassFailPhp_certReqQuery->fetch_assoc()) {
                $l749PassFailPhp_certTime = $l749PassFailPhp_certReqResult['cert_time'];
                $l749PassFailPhp_deadline = $l749PassFailPhp_certReqResult['deadline'];
                if ($l749PassFailPhp_certTime < $l749PassFailPhp_deadline) $tag = 0;
            }

            // Wahyudi Edit (2022-08-04) Check if there is new purchase Series 59 compare to last quiz
            // if this script included on quiz.php
            $l749PassFailPhp_lastQuiz = gmdate("Y-m-d");
            if (isset($quizPhp_quizResult) && is_array($quizPhp_quizResult)) {
                $l749PassFailPhp_lastQuiz = $quizPhp_quizResult["created_at"];
            } else { 
                // Or get from db
                $l749PassFailPhp_quizQuery = mysqli()->query("SELECT * 
                    FROM quiz_results 
                    WHERE lesson_id = '$lesson_id' AND user_id = '$id' 
                    ORDER BY created_at DESC 
                    LIMIT 1");
                $l749PassFailPhp_quizResult = $l749PassFailPhp_quizQuery->fetch_assoc();
                $l749PassFailPhp_lastQuiz = $l749PassFailPhp_quizResult["created_at"];
            }

            // if there is new purchase compare to last quiz
            if ($pDate != '' && strtotime($pDate) > strtotime($l749PassFailPhp_lastQuiz)) {
                // Wahyudi Edit (2022-08-04) Add new Row to reading_timed If there is new Purchase
                $now = gmdate("Y-m-d H:i:s");
                mysqli()->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) 
                    VALUES ('$id', '745', '$now', '00:00:00'),
                        ('$id', '746', '$now', '00:00:00'),
                        ('$id', '747', '$now', '00:00:00'),
                        ('$id', '748', '$now', '00:00:00'),
                        ('$id', '749', '$now', '00:00:00')");

                echo "<h5>Thank you for your purchase of the Quinceañera Preparation Program. Please begin the lesson below, read the materials thoroughly, and attempt the final quiz.<br> 
                <a href='reading.php?lesson_id=$lesson_id&page_id=1' class='btn btn-success mt-2 mb-3 big'><>Begin the Lesson</a></h5>";
            } else {
                $sql = "SELECT lesson_id, COUNT(id) AS cnt
                        FROM purchased_lessons
                        WHERE user_id = '$id'
                            AND lesson_id = '608'
                        GROUP BY lesson_id";
                $result = mysqli()->query($sql);

                $l749PassFailPhp_certRequestButton = $l749PassFailPhp_processingFeeButton = "";
                $l749PassFailPhp_isFeeExists = ($row = $result->fetch_assoc()) ? boolval($row["cnt"]) : false;
                if ($l749PassFailPhp_isFeeExists) {
                    $l749PassFailPhp_certRequestButton = "<h5>You have successfully completed the Quinceañera Preparation Program Quiz. 
                        You may now request your certificate of completion.<br>
                        <a href='cert-request.php?nature=9' class='btn btn-success mt-2 mb-3 big' target='_blank'>CLICK HERE TO REQUEST </a>
                    </h5>";
                } else {
                    $l749PassFailPhp_processingFeeButton = '<p class="big">
                        To order your certificate, please first remit the necessary processing fee here:
                        <a class="shop-pop-up-button btn btn-success mt-2 mb-3" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=608">Processing Fee</a>
                    </p><script src="/scripts/shop-pop-up-button.js"></script>';

                    $result = mysqli()->query("SELECT a.id
                        FROM tbl_order AS a 
                            INNER JOIN tbl_order_items AS b 
                            ON a.id = b.order_id 
                        WHERE a.customer_id = '$id' 
                            AND b.product_id = '608' 
                            AND TIMESTAMPDIFF(MINUTE, a.create_at, NOW()) <= 3");

                    if ($row = $result->fetch_assoc()) {
                        $l749PassFailPhp_processingFeeButton = '<p class="big">
                            To order your certificate, please first remit the necessary processing fee here:
                            <a class="btn btn-success mt-2 mb-3 disabled" href="#" onclick="return !1">Processing Your Last Fee Order...</a>
                        </p>';
                    }
                }

                if ($tag == 1) {
                    $l749PassFailPhp_l745Score = $l749PassFailPhp_l746Score = $l749PassFailPhp_l747Score = $l749PassFailPhp_l748Score = -1;
                    $l749PassFailPhp_checkOtherQuizQuery = mysqli()->query("SELECT MAX(score) AS score, lesson_id
                        FROM quiz_results 
                        WHERE lesson_id IN (745, 746, 747, 748) 
                            AND user_id = '$id' 
                            AND created_at >= '$pDate'
                        GROUP BY lesson_id");
                        
                    while ($row = $l749PassFailPhp_checkOtherQuizQuery->fetch_assoc()) {
                        switch ($row["lesson_id"]) {
                            case '745': $l749PassFailPhp_l745Score = round(($row["score"] * 100), 2); break;
                            case '746': $l749PassFailPhp_l746Score = round(($row["score"] * 100), 2); break;
                            case '747': $l749PassFailPhp_l747Score = round(($row["score"] * 100), 2); break;
                            case '748': $l749PassFailPhp_l748Score = round(($row["score"] * 100), 2); break;
                        }
                    }

                    $l749PassFailPhp_isL745Passed = $l749PassFailPhp_l745Score >= 90;
                    $l749PassFailPhp_isL746Passed = $l749PassFailPhp_l746Score >= 90;
                    $l749PassFailPhp_isL747Passed = $l749PassFailPhp_l747Score >= 90;
                    $l749PassFailPhp_isL748Passed = $l749PassFailPhp_l748Score >= 90;
                    if ($l749PassFailPhp_isL745Passed && $l749PassFailPhp_isL746Passed && $l749PassFailPhp_isL747Passed && $l749PassFailPhp_isL748Passed) {
                        if ($l749PassFailPhp_isFeeExists) {
                            echo $l749PassFailPhp_certRequestButton;
                        } else {
                            echo $l749PassFailPhp_processingFeeButton;
                        }
                    } else {
                        // Wahyudi Edit (2022-08-04) Show text For successfull l749 but not completed S59 
                        echo "<h5>You have passed this lesson but have not yet finished the program. Please return to your 'My Lessons' screen to continue with your lessons.</h5>";
                        if ($l749PassFailPhp_l745Score >= 0 && !$l749PassFailPhp_isL745Passed) {
                            echo "<p><span style='color: black'>".$l749PassFailPhp_l745Score . "% is not a passing score for Quinceañera Lesson 1: Living a Holy Life. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-746_retake.php\' "> ';
                            echo " </span></p>";
                        }

                        if ($l749PassFailPhp_l746Score >= 0 && !$l749PassFailPhp_isL746Passed) {
                            echo "<p><span style='color: black'>".$l749PassFailPhp_l746Score . "% is not a passing score for Quinceañera Lesson 2: Overview of the Catholic Faith Part 1. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-747_retake.php\' "> ';
                            echo " </span></p>";
                        }

                        if ($l749PassFailPhp_l747Score >= 0 && !$l749PassFailPhp_isL747Passed) {
                            echo "<p><span style='color: black'>".$l749PassFailPhp_l747Score . "% is not a passing score for Quinceañera Lesson 3: Overview of the Catholic Faith Part 2. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-748_retake.php\' "> ';
                            echo " </span></p>";
                        }

                        if ($l749PassFailPhp_l748Score >= 0 && !$l749PassFailPhp_isL748Passed) {
                            echo "<p><span style='color: black'>".$l749PassFailPhp_l748Score . "% is not a passing score for Quinceañera Lesson 4: The Sacramental Life. You may click to reset here. ";
                            echo '<input type="button" value="Reset this Lesson" class="btn btn-success  mb-2" onClick="window.location=\'qlessons/l-749_retake.php\' "> ';
                            echo " </span></p>";
                        }
                    }
                } else {
                    echo "<h5>You are requesting a certificate against a previously used course. Please purchase the Quinceañera Preparation Program again in order to continue with your certificate request. <br><a href='/shop/series_detail.php?id=59' class='btn btn-success mt-2 mb-3 big' > Purchase </a></h5><br>";
                }
            }
        }
    }
}
?>