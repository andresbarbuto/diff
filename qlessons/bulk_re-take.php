<?php

echo 'I\'m here!';
$user_id_escaped = mysqli()->real_escape_string($user_id);

$sql = "UPDATE  `catclass692993`.`quiz_results` SET  `may_retake` =  '1' WHERE  `quiz_results`.`user_id` ='$user_id_escaped' AND (`score` < .69 OR `doc_flag` > 0);";

// get Quiz info 
$quiz_info = mysqli()->query("SELECT id, lesson_id FROM `catclass692993`.`quiz_results`WHERE user_id = '$user_id_escaped' AND (`score` < .69 OR `doc_flag` > 0) ORDER BY lesson_id ASC, created_at DESC");

while ($row_quiz_info = $quiz_info->fetch_row()) {
    $rtupdate = mysqli()->query("UPDATE reading_timed SET quiz_result_id='$row_quiz_info[0]' WHERE user_id='$user_id_escaped' AND lesson_id='$row_quiz_info[1]' AND quiz_result_id='0' LIMIT 1") or die(mysqli()->error);

    $now = gmdate("Y-m-d H:i:s");
    mysqli()->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) VALUES ('$user_id_escaped', '$row_quiz_info[1]', '$now', '00:00:00') ") or die(mysqli()->error);
}
?>