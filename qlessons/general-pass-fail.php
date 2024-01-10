<?php

// most recent edit - 6-14-20; to alter display messages
// test for General Course not passed.. alert on screen
// to become an include
// modify to separate Sacrament scores at 90% - 3-25-17 SJK

$bap_array = array("9", "28", "85");
$sacraments = array(
    '82', '83', '84', '86', '87',
    '234',
    '447',
    '470',
    '721', '722', '723', '724', '725', '745', '746', '747', '748', '749');

$cond = 0;

if (in_array($lesson_id, $sacraments) && ($avg <= 89)) {
    $cond = 1;
}

if (!in_array($lesson_id, $bap_array) && !in_array($lesson_id, $sacraments) && ($avg <= 69)) {
    $cond = 2;
}

switch ($cond) {
    case "1" :
        echo "<p><span style='color: black'>You have not yet passed this quiz. All Sacramental lessons require a 90%. Review your Retake Options found in the Retake Instructions panel.</span></p>";
        break;

    case "2" :
        echo "<p><span style='color: red'>" . $avg . "% is not a passing score for any quiz. </span></p>";
        break;

    default :
        break;
}
?>