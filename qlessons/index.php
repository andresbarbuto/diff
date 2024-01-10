<?php
define('QUADODO_IN_SYSTEM', true);
require_once('../includes/header.php');
$qls->Security->check_auth_registration();
?>
<title>CatechismClass.com</title>
<?php include '../start.php'; ?>
<div class="static pt-1 pb-1">
<div id="panel"><p>This is a restricted area of CatechismClass.com</p></div>
<?php include '../info_columns.php'; ?>
</div> <?php include '../bottom.php'; ?>