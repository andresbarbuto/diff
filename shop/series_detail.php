<?php

require_once ('../includes/header.php');


$home = $_SERVER['HTTP_HOST'];
include '../start.php';

$series_id = mysqli()->real_escape_string($_GET['id']);
// id FILTER
$series_id = idFilter($series_id);

//for new Cart widget
$sku = 'S-' . $series_id;
$widget_icon = '../widget_icon/' . $sku . '.jpg';
if (!file_exists($widget_icon)) {
    $widget_icon = '../widget_icon/C-0.jpg';
}

if (empty($series_id)) {
    ?>
    <div class="static pt-2">
        <?php
        $backdropHtml = '<h4 style="text-align: center">We are sorry but that item does not exist. </h4>
	<p style="text-align: center"><br/>
	<form style="text-align: center" action="courses.php">
	<input type="submit" value="Explore our Catalog" class="btn btn-success mt-2 mb-3" />
	</form></p>
    </div>
	<br/>	';
        include '../html/backdrop1.php';
        ?>
    </div>
    <?php
    trackIp();
    include_once '../info_columns.php';
    include_once '../bottom.php';
    exit();
}
// end filter
// 
//out of stock items
$outOfStock = array('0');

if (in_array($series_id, $outOfStock)) {
    ?>
    <div class="static pt-2">
        <?php
        $backdropHtml = '<h4 style="text-align: center">We are sorry but that item is discontinued. </h4>
	<p style="text-align: center"><br/>
	<form style="text-align: center" action="courses.php">
	<input type="submit" value="Explore our Catalog" class="btn btn-success mt-2 mb-3" />
	</form></p>
    </div>
	<br/>	';
        include '../html/backdrop1.php';
        ?>
    </div>
    <?php
    include_once '../info_columns.php';
    include_once '../bottom.php';
    exit();
}
// end out of stock items

$series_info_query = mysqli()->query("SELECT * FROM series WHERE id = '" . fString($series_id) . "'") or die(mysqli()->error);
$series_info = $series_info_query->fetch_array();

$ordered = $series_info['is_ordered']; // series DB must be tagged for ordering of lists

if ($ordered != 0) {
    $series_lessons_query = mysqli()->query("SELECT id, title, position FROM lessons WHERE id IN (SELECT lesson_id FROM lessons_series WHERE series_id = '" . fString($series_id) . "') ORDER BY position") or die(mysqli()->error);
} else {
    $series_lessons_query = mysqli()->query("SELECT id, title FROM lessons WHERE id IN (SELECT lesson_id FROM lessons_series WHERE series_id = '" . fString($series_id) . "')") or die(mysqli()->error);
}

$series_in_package = mysqli()->query("SELECT id, title FROM packages WHERE id IN (SELECT package_id FROM packages_series WHERE series_id = '" . fString($series_id) . "')") or die(mysqli()->error);
$in_package_rows = $series_in_package->num_rows;

$lessons_prices_query = mysqli()->query("SELECT price FROM lessons WHERE id IN (SELECT lesson_id FROM lessons_series WHERE series_id = '" . fString($series_id) . "')") or die(mysqli()->error);
$total_lessons_cost = 0;

while ($prices = $lessons_prices_query->fetch_array()) {
    // echo $prices[0] . '<br>';
    $total_lessons_cost = $total_lessons_cost + $prices[0];
}

$related_series_query = mysqli()->query("SELECT id, title FROM series WHERE id IN (SELECT related_series_id FROM related_series WHERE main_series_id = '" . fString($series_id) . "' )") or die(mysqli()->error);
$rel_num_results = $related_series_query->num_rows;

$noCouple = '<br><h4>Each person needing to be named on the certificate of completion needs his/her own account. Materials can not be shared by more than one person.</h4><br>';
?>

<div class="static"> <!-- </div> in bottom -->
    <h2 class="static-title"><?php echo $series_info['title'] ?></h2>
    <?php include '../ShareThis.php'; ?>
    <div  class="static-body" >
        <h4><strong>Price: $<?php echo number_format(($series_info['price'] / 100), 2); ?></strong></h4>

        <?php
        if ($series_id == 9) {
            echo $noCouple;
        }
        ?>

        <p><?php echo 'By purchasing ' . $series_info['title'] . '  you save $' . number_format(($total_lessons_cost - $series_info['price']) / 100, 2) . ' versus the combined cost of the individual lessons'; ?></p>

        <form class="form-inline" method="post" action=""
              id="view-form-<?php echo $series_info['id']; ?>" >

            <input type="hidden" name="my-item-id" value="<?php echo $series_info['id']; ?>"> 
            <input type="hidden" name="my-item-name" value="<?php echo $series_info['title']; ?>">
            <input type="hidden" name="my-item-price" value="<?php echo number_format(($series_info['price'] / 100), 2); ?>">
            <input type="hidden" name="image-val" value="S">

            <div class="form-inline">
                <select class="form-control cart-quantity-select" name="my-item-qty" id="my-item-qty" tabindex="0">
                    <?php
                    if (($series_id == 9) && (($_SESSION['mask_id'] == 2) || (empty($_SESSION['username'])))) {
                        $i = 1;
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                    } else { // if logged in as mask_4 or 1
                        for ($i = 1; $i <= 50; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                        }
                    } // end mask 4
                    ?>
                </select>
                <br>&nbsp;&nbsp;

                <input type="hidden" name="lesson-created-at" value="<?php echo $series_info['created_at'] ?>">

                <?php if (empty($_SESSION['id'])) { ?>
                    <label for='add-to-cart'  style='display: inline-block;font-weight: bold' >You must <a href="../register.php"><strong> register</strong></a> or <a href="../login.php"><strong>login</strong></a> first to order this item</label>
                    <?php
                }

                if (in_array($series_id, [65, 64]) && isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['mask_id'] == 2 && dupBaptism()) {
                    ?>
                    <label for='add-to-cart'  style='display: inline-block;font-weight: bold' >Unavailable: Only One Per User</label>
                    <?php
                } else if (empty($_SESSION['id'])) {
                    
                } else if (!empty($_SESSION['id'])) {
                    ?>
                    <input type="button" onclick="addToCart(<?php echo $series_info['id'] ?>)" name="add-to-cart" class="cart-Link-btn btn btn-success" value="Add to Cart">
                <?php } ?>                               
            </div>

            <br>&nbsp;&nbsp;&nbsp;
            <img src="../images/loading_icon.gif" id="loader">

            <div id="display-message-box" class="display-none display-message form-inline">
                &nbsp;&nbsp;
                <a href="/view/pp-cart.php" class="display-message-btn">Go To Cart</a>
                <span
                    class="normal-font-size">Item added.</span>
                <span
                    class="closebtn"
                    onclick="this.parentElement.style.display = 'none';">&times;</span>
            </div>
        </form>

        <br>
        <div class="right" style="float: right; text-align: left;"><img src= "<?php echo $widget_icon; ?>" style="padding-right: 20px; padding-bottom: 5px;" width="200" alt="widget-icon"></div>		

        <h4><strong>Product Description</strong></h4>
        <p> <?php echo $series_info['description']; ?> </p>
        <div class="clearfix">
            <!-- preview a lesson from the series-->
            <br>
        </div>
        <hr>

        <div class="series-bottom">
            <div class="fBold" >
                <b><em><?php echo $series_info['title'] ?></em> contains all of these lessons: </b>
            </div>
            <?php
            // print '<ul>';// removed to create disks at <li>
            ?>

            <div class="container series-container">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <!-- a panel -->
                        <div class="panel-heading">
                            <h2 class="panel-title fNormal">
                                <a data-toggle="collapse" data-parent="#accordion"
                                   href="#collapse1">Click Here for <?php echo $series_info['title'] ?> Lessons: </a>
                            </h2>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ul style="list-style-type:disc;" class="lesson-list">
                                    <?php
                                    while ($row = $series_lessons_query->fetch_array()) {
                                        echo '<li><a class="fLight" href="lessons_detail.php?id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
                                    }
                                    print '';
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end a panel -->
                </div>

                <?php
                /*
                 * while ($row = $series_lessons_query->fetch_array()) {
                 * echo '<li><a href="lessons_detail.php?id='.$row['id'].'">'.$row['title'].'</a></li>';
                 * }
                 * print '</ul>';
                 */
                ?>

                <!--// possible to use this in Recommendations list-->

                <?php
                if ($rel_num_results > 0) {
                    $no_show = array("53", "54", "55", "56", "57", "58");

                    if (!in_array($series_id, $no_show)) {

                        echo '<br><p class="fBold">Other Courses similar to <em>' . $series_info['title'] . '</em>:</p>';

                        echo '<ul style="list-style-type:disc;" class="parent-list">';

                        while ($rel = $related_series_query->fetch_array()) {
                            echo '<li class="margin-bottom-10"><a class="fLight" href="series_detail.php?id=' . $rel['id'] . '">' . $rel['title'] . '</a></li>';
                        }

                        echo '</ul>';
                    }
                }
                ?>


                <?php
                $cycle = $in_package_rows;
                if ($in_package_rows > 0) {
                    ?>
                    <p></p>
                    <p class="fBold">
                        <em><?php echo $series_info['title'] ?></em> is part of the following Package(s):
                    </p>
                    <ul style="list-style-type:disc;" class="parent-list">
                        <?php
                        for ($cycle > 0; $cycle--;) {
                            $PK_info = $series_in_package->fetch_array();
                            $pk_id = $PK_info[0];
                            $title = $PK_info[1];
                            echo '<li class="margin-bottom-10"><a class="fLight" href="package_detail.php?id=' . $pk_id . ' ">' . $title . '</a></li>';
                        }
                        ?>
                    </ul>
                    <p></p>
                    <p>Purchase this Course in a package with other Courses and save even more !</p>
                <?php } ?>
            </div>

            <script src="/view/cartjs/cart.js"></script>
        </div>
    </div>

    <?php
    include '../info_columns.php';
    include '../bottom.php';

    function dupBaptism() {
        $user_id = $_SESSION['id'];

        $sql = "select count(*) as cnt  
                from purchases 
            where (item_type = 'Lesson' and item_id = 85 or
                   item_type = 'Series' and item_id in (64,65) or
                   item_type = 'Package' and item_id in (17, 18, 19)) 
              and  created_at > DATE_SUB(NOW(), INTERVAL 2 WEEK)
              and user_id = $user_id";

        $result = mysqli()->query($sql) or die(mysqli()->error);
        $row = $result->fetch_assoc();
        return (bool) $row['cnt'];
    }

    function fString($S = "") {
        if ($S == "") {
            return "";
        }

        $SS = (string) $S;
        if (strlen($SS) != 0) {
            $SS = trim($SS);
            $SS = preg_replace("/ +/", " ", $SS);
            //temporary disabled to fix encoding problem in product name symbol
            //$SS = iconv("UTF-8", "ASCII//TRANSLIT", $SS);
            $SS = str_ireplace("'", "''", $SS);
            $SS = mb_convert_encoding($SS, 'UTF-8', 'UTF-8');
        }
        return $SS;
    }
    
