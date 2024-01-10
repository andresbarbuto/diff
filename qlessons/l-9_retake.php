<?php
// most recent edit - 6-29-16; to include dual pricing modules.

require_once('../includes/header.php');

$page_html_title = "Baptism Quiz Retake Assigned";
include '../start.php';
?>

<div class="static pt-1 pb-2" id="top">
    <?php
    //Check if user is logged in
    if (!empty($_SESSION['username'])) {
        $id = $_SESSION['id']; // set user_id for no accidents
        //echo $id . "id<br>";

        $lesson_id = 9; // set lesson_id for no accidents
        //echo $lesson_id . " lesson<br>";
        ?>

        <h1 class="static-title">&quot;The Meaning of Christ&apos;s Baptism&quot; Quiz Retake Assigned</h1>

        <?php
        $retake_max = mysqli()->query("SELECT * FROM quiz_retakes WHERE lesson_id = 9 AND user_id = '$id' AND created_at > DATE_SUB(NOW(), INTERVAL 2 WEEK)")->num_rows;
        //echo $retake_max. 'retake<br>';

        if ($retake_max >= 6) {
            ?>
            <p style='color: black'><strong>Our records indicate that you have already been issued the maximum number of retakes for this lesson. In order to receive a passing score, please review your questions and answers. For each question that you missed, write up a short explanation describing the correct answer. Submit your answers in a document or email reply to <em>documents@catechismclass.com</em> for grading. 
                    <br><br>The answers to all questions may be combined in one email but follow this format for all questions to which you are submitting an answer:</strong></p>
            <ul>
                <li><strong> The wording of the question</strong></li>
                <li><strong> Your submitted answer</strong></li>
                <li><strong> The correct answer</strong></li>
                <li><strong> Your written explanation to demonstrate why the correct answer is correct.</strong></li>
            </ul>
            <p><strong>Include your name, email address, and USER ID number -"<?php echo $id; ?>" -  in your submission.</strong></p>
            <br></br>
            <input type="button" value="Return to My Quizzes" class="btn btn-outline-dark mb-2" onClick="window.location = '/quiz_results.php'">&nbsp;&nbsp;<br><br>

            <?php
            include '../info_columns.php';
            include '../bottom.php';
            exit;
        } else { /* $retake_max < 2 */
            $insert_date = date('Y-m-d G:i:s');
            $cupdate = mysqli()->query("INSERT INTO quiz_retakes VALUES ('', '$id', '$lesson_id', '1', '$insert_date')");

            // update data in mysql database
            $lupdate = mysqli()->query("UPDATE quiz_results SET may_retake=1 WHERE user_id=$id AND lesson_id=9 ORDER BY id DESC LIMIT 1;")
                    or die(mysqli()->error);

            // if successfully updated.
            if ($lupdate) {
                // get Quiz info 
                $quiz_info = mysqli()->query("SELECT id FROM quiz_results WHERE user_id = '$id' AND lesson_id = '9' ORDER BY lesson_id ASC, created_at DESC");

                if ($row_quiz_info = $quiz_info->fetch_row()) {
                    $rtupdate = mysqli()->query("UPDATE reading_timed SET quiz_result_id='$row_quiz_info[0]' WHERE user_id='$id' AND lesson_id='9' AND quiz_result_id='0' LIMIT 1") or die(mysqli()->error);

                    $now = gmdate("Y-m-d H:i:s");
                    mysqli()->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) VALUES ('$id', '9', '$now', '00:00:00') ") or die(mysqli()->error);
                }
                ?>

                <p>Retake Granted.</p>
                <p>To retake this Quiz &nbsp;&nbsp;
                    <input type="button" value="Return to Lesson" class="btn btn-success mb-2" onClick="window.location = '/reading.php?lesson_id=9&page_id=1'">
                </p>

                <?php
                //include '../email_retake_granted.php'; include when fixed
            } else {
                /* not successfully updated */
                echo "<p>ERROR</p>";
            }
        } /* end $retake_max < 2 */
        ?> 
        <!--<a href="lessons.php">Return to My Lessons</a>-->
        <hr>

        <?php
    }
    // If not logged in, display links to log in and register
    else {
        echo "<script> window.location= '../mustRegister.php' </script>";
    }
    include '../info_columns.php';
    ?>
</div>
<?php include '../bottom.php'; ?>
