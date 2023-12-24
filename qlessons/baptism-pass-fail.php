<?php
	// most recent edit - 6-29-16; to include dual pricing modules.
	// test for Baptism Cert Course not passed.. alert on screen
	// to become an include
	// determine recent purchase, else force a new purch

	// Wahyudi Edit (2022-02-02) escape variable, change query to get max created at
	$id = $qls->SQL->real_escape_string($id);
	$recent = "SELECT id, created_at 
		FROM purchases 
		WHERE item_type = 'Lesson' 
			AND item_id = '85' 
			AND user_id = '$id' 
		ORDER BY created_at DESC 
		LIMIT 1";
	$chRecent =  $qls->SQL->query($recent);
	$hasRecent = $qls->SQL->fetch_array($chRecent);
	$pDate = $hasRecent["created_at"];

	$purchased= strtotime($pDate);
	$now = time();
	$valid = ($now - $purchased);
	$diff = strtotime("-1 year");  // 1 year  //63158400'; //2 years

	//echo $purchased.' purch<br/>';
	//echo $valid.' valid<br/>';
	//echo $diff.' diff<br/>';

	$tag = 1;
	if ($valid >= $diff) {
		$tag = 0;
	}


	// Wahyudi Edit (2022-02-02) Add checking last cert request
    $sql = "SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
        FROM support_tickets
        WHERE user_id = '$id'
            AND nature_of_error=2
            AND LOWER(`text`) LIKE '%request for baptism certificate%'
            AND (standard_fee != '' OR expedited_fee != '')
        ORDER BY cert_time DESC
        LIMIT 1";
    $result=$qls->SQL->query($sql) or die($qls->SQL->error());
    if ($row=$qls->SQL->fetch_assoc($result)) {
        $cert_time = $row['cert_time'];
        $deadline = $row['deadline'];
		if ($cert_time < $deadline) $tag = 0;
    }


	if ($lesson_id == 85) {
		// Wahyudi Edit (2022-02-02) use elseif instead of double (if) checking 
		if (($tag == 1) && ($avg >= 99)) {
			echo "<h5>You have successfully completed the Baptism Preparation Program Quiz. You may now request your certificate of completion.<br /> 
			<a href='cert-request.php&nature=2' class='btn btn-success mt-2 mb-3' target='_blank'><big>CLICK HERE TO REQUEST</big> </a>	</h5>";
		} else if (($tag == 0) && ($avg >= 99)){
			echo "<h5>You are requesting a new Certificate against an expired purchase. Please purchase another lesson here: <br/><a href='/shop/lessons_detail.php?id=85' class='btn btn-success mt-2 mb-3' > <big>Purchase New Baptism Lesson</big> </a></h5><br/>";
		}
	}


	if (($lesson_id == 9) || ($lesson_id == 28)) {
		if ($avg <= 79) {
			echo "<p><span style='color: black'>You have not yet passed this quiz. Please see the bottom of this page for retake instructions.</span></p>";
		} else {
			echo "<p><span style='color: black'>Please see the bottom of this page for retake instructions.</span></p>";
		}
	}
?>
