<?php
define('QUADODO_IN_SYSTEM', true);
require_once('../includes/header.php'); ?>
<title>
	Assign Learning
</title>
<?php include '../start.php'; ?>

<div class="static pt-2 pb-2">

	<a name="top"></a>
	<?php
	global $panel;
	$panel=$_GET['panel'];
	//Check to see if user is logged in.
	if ($qls->user_info['username'] != '') {
	?>
	<?php //Check to see if user is NOT a student
	if ($qls->user_info['mask_id'] != 3) {
	?>

	<?
	//Grab user's info
	$id=$qls->user_info["id"];
	//$parish=$qls->user_info["parish"];

	$student_id=$_GET['id'];

	// Retrieve data from database
	$sql="SELECT id, username, firstname, lastname, grade_level FROM qb_users WHERE id='$student_id'" or die(getQls()->SQL->error());
	$result=getQls()->SQL->query($sql);
	$rows=getQls()->SQL->fetch_array($result);
	?>

	<h1 class="static-title"> Assign Learning</h1>

	<p>Assign an item to:
		<a href='student_info1.php?student_id=<? echo $rows['id']; ?>'><? echo $rows['firstname']; ?> <? echo $rows['lastname']; ?></a>, Grade: <? echo $rows['grade_level']; ?> (student ID <? echo $rows['id']; ?>).</p>

	<form name="form1" method="post" action="process_assign_lessons1.php?panel=<?php echo $panel; ?>">

		<div class="col-lg-10 col-xs-12 p-0">
			<table class="table table-striped">
				<tr>
					<td colspan="2">
						<p>When you assign this material,
							<strong>the material will leave your account and enter  the student&apos;s account.</strong> The student will need to login to his/her account to access it after the transfer is complete. This process will assign one particular item at a time.  Be careful, this action cannot be undone.</p>
						<input name="student_id" type="hidden" id="id" value="<? echo $student_id; ?>"></td></tr>

				<tr>
					<td>
						<label>Available Items to Assign</label>
						<select name="available" class="form-control" tabindex="0" size="5">
							<!--<option value="" ></option>-->
							<? // Grab list of packages available for assigning
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

							$aiq = getQls()->SQL->query($available_items_query)	or die(getQls()->SQL->error());

							while ($row=getQls()->SQL->fetch_array($aiq)) {
								$row_type = $row['row_type'];
								$item_id = $row['item_id'];
								$title = $row['title'];
								$available = $row['available'];
								echo  '<option value="' .$row_type.$item_id. '">Qty available: '.$available.'    --   '.$title.'  </option>' ;
							}
							?>
						</select>
					</td></tr>
			</table>

			<input type="submit" name="Submit" class="btn btn-outline-dark mt-2" value="Submit">&nbsp;&nbsp;
			<input type="button" value="Return to My Students" class="btn btn-outline-dark mt-2" onClick="window.location='panel-drop.php?panel=<?php echo $panel; ?>'"/>
		</div>
	</form>
	<br/><br/>
	<?php
	// If not logged in, display links to log in and register
} else {
	echo "<script type='text/javascript'> window.location= '../mustRegister.php' </script>";
}
// If not logged in, display links to log in and register
} else {
	echo "<script type='text/javascript'> window.location= '../mustRegister.php' </script>";
} // end not logged in else
include '../info_columns.php'; ?>
</div>
<?php include '../bottom.php'; ?>