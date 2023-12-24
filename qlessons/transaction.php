<?php
	define('QUADODO_IN_SYSTEM', true);
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

	if (isset($_POST["type"])) {
		$id = $qls->user_info["id"];
		switch ($_POST["type"]) {
			case 'getFee':
				$result = false;
				if (isset($_GET["lesson_id"])) {
					switch ($_GET["lesson_id"]) {
						case '85':
							$result = true;
							break;
						
						case '86':
						case '87':
						case '234':
						case '724':
						case '725':
						case '745':
						case '746':
						case '747':
						case '748':
						case '749':
							$sql = "SELECT lesson_id, COUNT(id) AS cnt
				                    FROM purchased_lessons
				                    WHERE user_id = '$id'
				                        AND lesson_id = '608'
				                    GROUP BY lesson_id";
				            $result = $qls->SQL->query($sql);
				            $result = ($row = $qls->SQL->fetch_assoc($result)) ? boolval($row["cnt"]) : false;
							break;
					}
				}

				echo json_encode($result);
				break;
			
			default:
				# code...
				break;
		}
	}
?>