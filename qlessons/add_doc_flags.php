<?php

// most recent review - 9-9-22; 
// includes in quiz.php - SJK
// add_doc_flags.php 
// addition to add $doc_flag to the DB for use in resets
if ($doctrine_count == 0) {
    $doc_flag = 0; // no doctrinal issue
//  echo $doc_flag . " this quiz doc_flag count =0<br>";
}
if ($doctrine_count > 0) {
    if ($lesson_id != 85) {
        $doc_flag = 1; // doctrinal issue within the quiz being taken
        // flag written will be 1
    } else {
        // differentiate between the 2 remedial lessons.
        if ($sp_28 > 0) {
            $doc_flag = 1; // doctrinal issue requires lesson 28
            // flag written will be 1
        }
        if ($sp_9 > 0) {
            $doc_flag = $doc_flag + 10; // doctrinal issue requires lesson 9
            // flag written will be 10 or 11, depending on if only leson 9 or both
        }
    }
}

$id_escaped = mysqli()->real_escape_string($id);
$lesson_id_escaped = mysqli()->real_escape_string($lesson_id);

//echo $doc_flag . " this quiz doc_flag final to DB<br>" ; 
//Add the quiz results $doc_flag detail to the database via UPDATE
$add_doc_flag = mysqli()->query("UPDATE quiz_results SET doc_flag= $doc_flag WHERE lesson_id='$lesson_id_escaped' AND user_id='$id_escaped' ORDER BY created_at DESC LIMIT 1 ") or die(mysqli()->error);
?>
