<?php

// most recent edit - 6-29-16; to include dual pricing modules.
// test for Baptism Cert Course not passed.. alert on screen
// to become an include
// determine recent purchase, else force a new purch

$tag = 1;
$pDate = '';
$lesson_id_escaped = mysqli()->real_escape_string($lesson_id);

// Wahyudi Edit (2022-02-02) escape variable, change query to get max created at
$id = mysqli()->real_escape_string($id);
$recent = mysqli()->query("SELECT * FROM purchases 
    WHERE user_id = '$id'
        AND (
            (item_type = 'Package' AND item_id IN (17, 18)) 
            OR (item_type = 'Series' AND item_id IN (6, 64, 65)) 
            OR (item_type = 'Lesson' AND item_id = 85)
        )
        AND DATE(created_at) >= '2018-10-01'
    ORDER BY created_at DESC 
    LIMIT 1");
if ($baptismPassFailPhp_hasRecent = $recent->fetch_array()) {
    $pDate = $baptismPassFailPhp_hasRecent["created_at"];
    $purchased = strtotime($pDate);
    $now = time();
    $valid = ($now - $purchased);
    $diff = strtotime("-1 year");  // 1 year  //63158400'; //2 years
    //Wahyudi Edit (2022-02-10) Remove checking if the date of purchase is last year
    // if ($valid >= $diff) $tag = 0;
} else {
    $tag = 0;
    $purchased = strtotime($pDate);
}

//echo $purchased.' purch<br>';
//echo $valid.' valid<br>';
//echo $diff.' diff<br>';
// Wahyudi Edit (2022-02-02) Add checking if last cert request older than 2 weeks
$baptismPassFailPhp_certReqQuery = mysqli()->query("SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
    FROM support_tickets
    WHERE user_id = '$id'
        AND nature_of_error = 2
        AND (standard_fee != '' OR expedited_fee != '')
        AND cert_time > '$purchased'
    ORDER BY cert_time DESC
    LIMIT 1");
if ($baptismPassFailPhp_certReqResult = $baptismPassFailPhp_certReqQuery->fetch_assoc()) {
    $baptismPassFailPhp_certTime = $baptismPassFailPhp_certReqResult['cert_time'];
    $baptismPassFailPhp_deadline = $baptismPassFailPhp_certReqResult['deadline'];
    if ($baptismPassFailPhp_certTime < $baptismPassFailPhp_deadline)
        $tag = 0;
}

if ($lesson_id == 85 && $avg >= 99) {
    // Wahyudi Edit (2022-02-09) Check if there is new purchase Lesson 85 compare to last quiz
    // if this script included on quiz.php
    $baptismPassFailPhp_lastQuiz = gmdate("Y-m-d");
    if (isset($quizPhp_quizResult) && is_array($quizPhp_quizResult)) {
        $baptismPassFailPhp_lastQuiz = $quizPhp_quizResult["created_at"];
    } else {
        // Or get from db
        $baptismPassFailPhp_quizQuery = mysqli()->query("SELECT * 
            FROM quiz_results 
            WHERE lesson_id = '$lesson_id_escaped' AND user_id = '$id' 
            ORDER BY created_at DESC 
            LIMIT 1");
        $baptismPassFailPhp_quizResult = $baptismPassFailPhp_quizQuery->fetch_assoc();
        $baptismPassFailPhp_lastQuiz = $baptismPassFailPhp_quizResult["created_at"];
    }

    // if there is new purchase compare to last quiz
    if ($pDate != '' && $pDate > $baptismPassFailPhp_lastQuiz) {
        // Wahyudi Edit (2022-02-14) Add new Row to reading_timed If there is new Purchase
        $rs = mysqli()->query("SELECT id, quiz_result_id 
            FROM reading_timed 
            WHERE lesson_id = '$lesson_id_escaped' 
                AND user_id = '$id' 
            ORDER BY created_at DESC 
            LIMIT 1 ");

        if (($row = $rs->fetch_assoc()) && $row["quiz_result_id"]) {
            $now = gmdate("Y-m-d H:i:s");
            mysqli()->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) VALUES ('$id', '$lesson_id_escaped', '$now', '00:00:00') ");
        }

        echo "<h5>Thank you for your purchase of the Baptism Preparation Program. Please begin the lesson below, read the materials thoroughly, and attempt the final quiz.<br> 
        <a href='reading.php?lesson_id=85&page_id=1' class='btn btn-success mt-2 mb-3 big'>Begin the Lesson</a></h5>";
    } else {
        // Wahyudi Edit (2022-02-02) use if else instead of double (if) checking 
        if ($tag == 1) {
            echo "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br> 
            <a href='cert-request.php?nature=2' class='btn btn-success mt-2 mb-3 big' target='_blank'>CLICK HERE TO REQUEST </a>  </h5>";
        } else {
            echo "<h5>You are requesting a certificate against a previously used course. Please purchase the lesson again in order to continue with your certificate request. <br><a href='/shop/lessons_detail.php?id=85' class='btn btn-success mt-2 mb-3 big' > Purchase New Baptism Lesson </a></h5><br>";
        }
    }
}

/*
  if ($lesson_id ==85) {
  if ($avg <=99 ) {
  echo "<p><span style='color: black'>You have not yet passed this quiz. Please see the bottom of this page for retake instructions.</span></p>";
  } else {

  echo "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br>
  <a href='baptism-cert-request.php' class='btn btn-success mt-2 mb-3 big' target='_blank'>CLICK HERE TO REQUEST </a>
  </h5>";
  }
  }
 */
if (($lesson_id == 9) || ($lesson_id == 28)) {
    if ($avg <= 79) {
        echo "<p><span style='color: black'>You have not yet passed this quiz. Please see the bottom of this page for retake instructions.</span></p>";
    } else {
        echo "<p><span style='color: black'>Please see the bottom of this page for retake instructions.</span></p>";
    }
}
?>
