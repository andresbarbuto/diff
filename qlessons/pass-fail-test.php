<?php

// most recent edit - 6-29-16; to include dual pricing modules.
// test for Courses not passed.. alert on screen 
// to become an include

if (($lesson_id > 3 && $lesson_id < 82) ||
        $lesson_id == 565 || $lesson_id == 926 || $lesson_id == 927) {
    include 'adult-pass-fail.php';
} else {
    include 'general-pass-fail.php';
}
?>