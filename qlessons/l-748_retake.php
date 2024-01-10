<?php define('QUADODO_IN_SYSTEM', true);
require_once('../includes/header.php'); ?>
<title>Quinceañera Lesson 4: The Sacramental Life</title>
<?php include '../start.php'; ?>
<a name="top"></a>
<div class="static pt-1 pb-2">  
    <?php if ($qls->user_info['username'] != '') { //Check to see if user is logged in.
        $id = $qls->user_info['id']; // set user_id for no accidents
        //echo $id . "id<br/>"; 
        $lesson_id = 748; // set lesson_id for no accidents
        //echo $lesson_id . " lesson<br/>"; ?>
        <h1 class="static-title">Lesson <?= $lesson_id ?> Quiz Retake Assigned</h1>
        <p><?php
            $retake_max = getQls()->SQL->num_rows(getQls()->SQL->query("SELECT * FROM quiz_retakes WHERE lesson_id = $lesson_id AND user_id = '$id' and created_at > DATE_SUB(NOW(), INTERVAL 2 WEEK)"));
            //echo $retake_max. 'retake<br/>';

            if ($retake_max >= 4) {
                echo "<strong><p style='color: black'>
                    Our records indicate that you have already been issued the maximum number of retakes for this lesson. In order to receive a passing score, please review your questions and answers. For each question that you missed, write up a short explanation describing the correct answer. Submit your answers in a document or email reply to <em>documents@catechismclass.com</em> for grading. <br/><br/>
                    The answers to all questions may be combined in one email but follow this format for all questions to which you are submitting an answer:
                    <li> The wording of the question</li>
                    <li> Your submitted answer</li>
                    <li> The correct answer</li>
                    <li> A short written explanation of just one or two sentences on why the correct answer is correct.</li>
                    Include your name, email address, and USER ID number - ". $id. " -  in your submission.
                </p></strong>"; 
            ?>
            <br/><br/>
            <input type="button" value="Return to My Quizzes" class="btn btn-outline-dark mb-2" onClick="window.location='/quiz_results.php'"/>&nbsp;&nbsp;<br/><br/>
            <?php
                include '../info_columns.php';
                include '../bottom.php';
                exit();
            } else {
                $insert_date = date('Y-m-d G:i:s');
                $cupdate =  getQls()->SQL->query("INSERT INTO quiz_retakes VALUES ('', \"$id\",\"$lesson_id\",\"1\", '$insert_date')");
                // update data in mysql database
                $lupdate = getQls()->SQL->query("UPDATE quiz_results SET may_retake=1 WHERE user_id=$id AND lesson_id='$lesson_id' ORDER BY id DESC LIMIT 1;") or die(getQls()->SQL->error());

                // if successfully updated.
                if ($lupdate) {
                    // get Quiz info 
                    $quiz_info = getQls()->SQL->query("SELECT id
                        FROM quiz_results
                        WHERE user_id = '$id' 
                            AND lesson_id = '$lesson_id'
                        ORDER BY lesson_id ASC, created_at DESC"
                    );

                    if ($row_quiz_info = getQls()->SQL->fetch_row($quiz_info)) {
                        $rtupdate = getQls()->SQL->query("UPDATE reading_timed 
                            SET quiz_result_id='$row_quiz_info[0]'
                            WHERE user_id='$id' 
                                AND lesson_id='$lesson_id' 
                                AND quiz_result_id='0' 
                            LIMIT 1") or die(getQls()->SQL->error());

                        $now = gmdate("Y-m-d H:i:s");
                        getQls()->SQL->query("INSERT IGNORE INTO reading_timed (user_id, lesson_id, created_at, reading_time) 
                            VALUES ('$id', '$lesson_id', '$now', '00:00:00') ") or die(getQls()->SQL->error());
                    }

                    echo '<p>Retake Granted.</p>' ;
                    echo '<p>To retake this Quiz &nbsp;&nbsp;';
                    echo '<input type="button" value="Return to Lesson" class="btn btn-success mb-2" onClick="window.location=\'/reading.php?lesson_id='.$lesson_id.'&page_id=1\'"/>';
                    echo '</p>';
                $to_string='postmaster@catechismclass.com';
        $subject_string = 'Retake used';
        $message_string = 'Retake used in lesson '.$lesson_id.' by User '.$id.'.<br/><hr/>';
        include '../mailer.php';
        $to_string='';
        $subject_string = '';
        $message_string = '';
                } else {
                    echo "ERROR";
                }
            } 
        ?> </p>
        <!--<a href="lessons.php">Return to My Lessons</a>-->
        <hr />
    <?php } else { 
        // If not logged in, display links to log in and register
        echo 'You must be <a href="/register.php">registered</a> and <a href="/login.php">logged in</a> to access this area.';
    }

    include '../info_columns.php'; ?>
</div>
<?php include '../bottom.php'; ?>
