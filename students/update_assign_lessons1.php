<?php

require_once('../includes/header.php');

$page_html_title = "Assign Learning";
include '../start.php';
?>

<div class="static pt-2 pb-2" id="top">

    <?php
    global $panel;
    $panel = make_safe($_GET['panel'], true);

    //Check if user is logged in
    if (!empty($_SESSION['username'])) {
        //Check if user is NOT a student
        if ($_SESSION['mask_id'] != 3) {

            //Grab user's info
            $id = $_SESSION["id"];
            $user_mask = $_SESSION['mask_id'];
            $group = $_SESSION["group_id"];
            $student_id = $_GET['id'];
            $student_id_escaped = mysqli()->real_escape_string($_GET['id']);

            // Retrieve data from database
            $sql = "SELECT id, username, firstname, lastname, grade_level, mask_id FROM qb_users WHERE id='$student_id_escaped'";
            $result = mysqli()->query($sql) or die(mysqli()->error);
            $rows = $result->fetch_array();

            $student_grade = $rows['grade_level'];
            $student_mask = $rows['mask_id'];

            $sub = "SELECT * FROM users_subusers WHERE subuser_id = '$student_id_escaped' AND user_id= '$id' ";
            $res = mysqli()->query($sub) or die(mysqli()->error);
            $lics = $res->fetch_array();

            $licsa = $lics['user_id'] . ' - ' . $lics['subuser_id'] . ' - ' . $lics['id'] . ' - ' . $lics['licensed'] . ' <br>';

            $licensed_type2 = "SELECT package_id FROM purchased_packages WHERE user_id= '$id' AND package_id='2'";
            $type2 = mysqli()->query($licensed_type2);
            $t2 = $type2->num_rows;

            $licensed_type22 = "SELECT package_id FROM purchased_packages WHERE user_id= '$id' AND package_id='22'";
            $type22 = mysqli()->query($licensed_type22);
            $t22 = $type22->num_rows;

            /**
              echo"
              <br>// assignment conditions matrix...<br>

              <br>//parent to student, child or adult - cond1<br> - User Mask=2 + User_Group=1 + student_mask = (2 or 3) + license N/A<br>SQL for material list to transfer.<br>

              <br>//DRE non-license to child, adult or Catechist - cond2<br> - User Mask = 4 + User_Group=1 + student_mask = ( 2, 3 or 5) + license N/A<br>SQL for material list to transfer.<br>

              <br>//DRE child-license to child - cond3<br> - User Mask = 4 + User_Group=4 + student_mask = 3 + license 22 valid + receiver flag = 4<br>SQL for material list to transfer.<br>

              <br>//DRE adult license to adult - cond4<br> - User Mask = 4 + User_Group=4 + student_mask =  2 + license 2 valid + receiver flag = 4 <br>SQL for material list to transfer.<br>

              <br>//DRE with both license to either child, adult  from which license - cond5(child) <br> - User Mask = 4 + User_Group=4 + (license 22 valid + receiver flag = 4 + student_mask = 3 ) <br>SQL for material list to transfer.<br>
              //OR - cond6(adult) <br> - User Mask = 4 + User_Group=4 + (license 2 valid + receiver flag = 4 + student_mask = 2 )<br>SQL for material list to transfer.<br>

              <br>//DRE with either license who has a non-licensed user and purchased material - cond7<br> - User Mask = 4 + User_Group=4 + receiver flag = 1 + student_mask = ( 2, 3 or 5) <br>SQL for material list to transfer.<br>

              <br>//DRE with both license to Catechist  from which license - cond8(child-Catechist) <br> - User Mask = 4 + User_Group=4 + (license 22 valid + receiver flag = 4 + student_mask = 5 ) <br>SQL for material list to transfer.<br>
              //OR - cond9(adult-Catechist) <br> - User Mask = 4 + User_Group=4 + (license 2 valid + receiver flag = 4 + student_mask = 5 )<br>SQL for material list to transfer.<br>

              <br>//DRE with both license to Catechist  from which license - cond10(child or adult-Catechist) <br> - User Mask = 4 + User_Group=4 + (license 2 + license 22 valid + receiver flag = 4 + student_mask = 5 ) <br>SQL for material list to transfer.<br>
              ";
             */
            echo '<br>';
            // define case rules
            //parent

            $cond = 0;

            if ((($user_mask == 2) || ($user_mask == 1)) && ($group == 1) && (($student_mask == 2) || ($student_mask = 3))) {
                $cond = 1;
            }

            //DRE - no license
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 1) && (($student_mask == 2) || ($student_mask = 3) || ($student_mask == 5) )) {
                $cond = 2;
            }

            //DRE - child license
            $child_grade = array("K", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && ($t22 >= 1) && ($student_mask == 3) && (($lics[3] == 4) && (in_array($student_grade, $child_grade)))) {
                $cond = 3;
            }

            //DRE - adult license
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && ($t2 >= 1) && (($lics[3] == 4) && ($student_mask == 2))) {
                $cond = 4;
            }

            //DRE - both license -child 
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && (($t22 >= 1) && ($t2 >= 1)) && (($lics[3] == 4) && ((in_array($student_grade, $child_grade))))) {
                $cond = 5;
            }

            //DRE - both license -adult 
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && (($t22 >= 1) && ($t2 >= 1)) && ($lics[3] == 4) && ($student_mask == 2)) {
                $cond = 6;
            }

            //DRE - license but adding to a non-license student
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && (($t2 >= 1) || ($t22 >= 1)) && ($lics[3] == 1) && ( (($student_mask = 2) || ($student_mask = 3) || ($student_mask = 5)) )) {
                $cond = 7;
            }

            //DRE - child license -Catechist
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && ($t22 >= 1) && (($lics[3] == 4) && ($student_mask == 5))) {
                $cond = 8;
            }

            //DRE - adult license -Catechist
            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && ($t2 >= 1) && (($lics[3] == 4) && ($student_mask == 5))) {
                $cond = 9;
            }

            if ((($user_mask == 4) || ($user_mask == 1)) && ($group == 4) && (($t2 >= 1) || ($t22 >= 1)) && (($lics[3] == 4) && ($student_mask == 5))) {
                $cond = 10;
            }
            ?>

            <h1 class="static-title"> Assign Learning </h1>

            <p>Assign an item to:
                <a href='student_info1.php?student_id=<?php echo $rows['id']; ?>'><?php echo $rows['firstname'] . ' ' . $rows['lastname']; ?></a>, Grade: <?php echo $rows['grade_level']; ?> (student ID <?php echo $rows['id']; ?>).</p>
            <p>Note: Be sure to click on and <strong>Highlight</strong> the item that you are assigning or the process will not complete.</p>

            <form name="form1" method="post" action="process_assign_lessons1.php?panel=<?php echo $panel; ?>">

                <div class="col-lg-10 col-xs-12 p-0">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <p>When you assign this material,
                                    <strong>the material will leave your account and enter  the student&apos;s account.</strong> The student will need to login to his/her account to access it after the transfer is complete. This process will assign one particular item at a time.  Be careful, this action cannot be undone.</p>
                                <input name="student_id" type="hidden" id="id" value="<?php echo $student_id; ?>"></td></tr>

                        <tr>
                            <td>
                                <label>Available Items to Assign</label><?php //echo '&nbsp;&nbsp;'; echo $cond.' &nbsp;here';               ?>
                                <select name="available" class="form-control" tabindex="0" size="5">
                                    <!--<option value="" ></option>-->
                                    <?php
        $available_items_query =
                                    "select row_type, lesson_id as item_id, title, mask_id, available  from (
            SELECT 'L'as row_type, l.id as lesson_id, l.title, qb.mask_id, count(*) as available
            FROM purchased_lessons pl
                ,lessons l
                ,qb_users qb
                    WHERE pl.user_id = '$id' AND pl.parish_assigned = 0
                    and pl.lesson_id = l.id
                    AND qb.id = pl.user_id
                    AND qb.mask_id in (1,2,4)
                    AND  l.id NOT IN (85,600,601,602,606)
                        GROUP BY l.id
            ) t1
                where available >
            (
                case
                when exists (select *
                from  quiz_results qr,
                qb_users qb
                    where qr.user_id = '$id' and lesson_id = t1.lesson_id and qb.id=qr.user_id and qb.mask_id = 2 )
                    then 1 else
                        0
                        end)
        UNION ALL
            select * from (
            SELECT 'S' as row_type, s.id as series_id, s.title, qb.mask_id, count(*) as available
                FROM purchased_series ps
                    ,series s
                    ,qb_users qb
                        WHERE ps.user_id = '$id' AND ps.parish_assigned = 0
                        and ps.series_id = s.id
                        AND qb.id = ps.user_id
                        AND qb.mask_id in (1,2,4)
                        GROUP BY s.id
            ) t1
                where available >
            (
                case
                    when exists (select *
                    from lessons_series ls,
                    quiz_results qr,
                    qb_users qb
                        where ls.lesson_id = qr.lesson_id and qr.user_id = '$id' and series_id = t1.series_id and qb.id=qr.user_id and qb.mask_id = 2 )
                        then 1 else
                            0
                            end)

        UNION ALL
            select * from (
            SELECT 'P' as row_type, p.id as package_id, p.title, qb.mask_id, count(*) as available
                FROM purchased_packages pp
                ,packages p
                ,qb_users qb
                    WHERE pp.user_id = '$id' AND pp.parish_assigned = 0
                    and p.id = pp.package_id
                    AND qb.id = pp.user_id
                    AND qb.mask_id in (1,2,4)
                    GROUP BY p.id
            ) t1
                where available >
            (
                case
                    when exists (select *
                    from  packages_series ps,
                    lessons_series ls,
                    quiz_results qr,
                    qb_users qb
                        where  
        ps.package_id not in(2,22,27) 
                        and ls.series_id=ps.series_id
                        and ls.lesson_id = qr.lesson_id
                        and qr.user_id = '$id'
                        and qb.id=qr.user_id
                        and qb.mask_id = 2 )
                        then 1 else
                            0
                            end) ";


                    $aiq = mysqli()->query($available_items_query) or die(mysqli()->error);
                    // populate the table from the SQL
                    while ($row = $aiq->fetch_array()) {
                        $row_type = $row['row_type'];
                        $item_id = $row['item_id'];
                        $title = $row['title'];
                        $available = $row['available'];
                        echo '<option value="' . $row_type . $item_id . '">Qty available: ' . $available . '    --   ' . $title . '  </option>';
                    }



                                    ?>
                                </select>
                            </td></tr>
                    </table>

                    <input type="submit" name="Submit" class="btn btn-outline-dark mt-2" value="Submit">&nbsp;&nbsp;
                    <input type="button" value="Return to My Students" class="btn btn-outline-dark mt-2" onClick="window.location = 'panel-drop.php?panel=<?php echo $panel; ?>'">
                </div>
            </form>
            <br><br>
            <?php
        } else {
            // If user is a student, display links to log in and register
            echo "<script> window.location= '../mustRegister.php' </script>";
        }
    } else {
        // If not logged in, display links to log in and register
        echo "<script> window.location= '../mustRegister.php' </script>";
    }
    include '../info_columns.php';
    ?>
</div>
<?php
include '../bottom.php';
