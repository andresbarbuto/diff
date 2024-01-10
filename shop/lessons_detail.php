<?php

require_once('../includes/header.php');

include '../start.php';

$lesson_id = mysqli()->real_escape_string($_GET['id']);

// id FILTER
$lesson_id = idFilter($lesson_id);

$lid = $lesson_id; // shortcut name
//for new Cart widget
$sku = 'L-' . $lid;

$no_icon = array('85', '566', '567', '598', '599', '604', '605');
$widget_icon = '../widget_icon/' . $sku . '.jpg';

if (!file_exists($widget_icon)) {
    $widget_icon = '../widget_icon/C-0.jpg';
}

if (empty($lid)) {
    trackIp();
    $item_status = 'does not exist';
    $backdropHtml = bkdropLessonStatus($item_status);
    outputBackdropHtml($backdropHtml, 'footer');
    exit;
}

// end filter
$lesson_info_query = mysqli()->query("SELECT * FROM lessons WHERE id = '" . fString($lesson_id) . "'") or die(mysqli()->error);

if ($lesson_info_query->num_rows == 0) {
    $item_status = 'does not exist';
    $backdropHtml = bkdropLessonStatus($item_status);
    outputBackdropHtml($backdropHtml, 'footer');
    exit;
}

if ($lesson_id > 9900) {
    $item_status = 'does not exist';
    $backdropHtml = bkdropLessonStatus($item_status);
    outputBackdropHtml($backdropHtml, 'footer');
    exit;
}

//out of stock items
$outOfStock = array('566', '567');

if (in_array($lesson_id, $outOfStock)) {
    $item_status = 'is discontinued';
    $backdropHtml = bkdropLessonStatus($item_status);
    outputBackdropHtml($backdropHtml, 'footer');
    exit;
}
// end out of stock items

$lesson_info = $lesson_info_query->fetch_array();

$lesson_in_series_query = mysqli()->query("SELECT id, title FROM series WHERE id IN (SELECT series_id FROM lessons_series WHERE lesson_id = '" . fString($lesson_id) . "')") or die(mysqli()->error);

$related_lessons_query = mysqli()->query("SELECT id, title FROM lessons WHERE id IN (SELECT related_lesson_id FROM related_lessons WHERE main_lesson_id = '" . fString($lesson_id) . "')") or die(mysqli()->error);
$rel_num_results = $related_lessons_query->num_rows;
?>

<div class="static">

    <h2 class="static-title"><?php echo $lesson_info['title'] ?></h2>

    <?php include '../ShareThis.php'; ?>
    <div class="static-body">

        <h4>
            <strong>Price: $<?php echo number_format(($lesson_info['price'] / 100), 2); ?></strong></h4>

        <?php
        $add_to_cart = "Add to Cart";
        $capstone = array('82', '83', '84', '721', '722', '723', '470', '724', '725');

        $no_cert = '<br><h4><strong>This stand alone lesson may not meet the requirements for a certificate. Please use the <a href="../course_calc/index.php">Course Selector to determine your proper requirements</a> for this program.</strong><br></h4>';

        if ((in_array($lesson_id, $capstone)) && ( (empty($_SESSION['username'])) || ($_SESSION['mask_id'] == 3) || ($_SESSION['mask_id'] == 2) )) {
            echo $no_cert;
        }


        $noCouple = '<br><h4>Each person needing to be named on the certificate of completion needs his/her own account. Materials can not be shared by more than one person.</h4><br>';

        if (($lesson_id == 85) && ((($_SESSION['mask_id'] ?? '') == 2) || (($_SESSION['mask_id'] ?? '') == 3) || (($_SESSION['username'] ?? '') == ''))) {
            echo $noCouple;
        }  // if not  mask-4 or 1
        ?>

        <form class="form-inline" method="post" action="" id="view-form-<?php echo $lesson_info['id'] ?>">

            <input type="hidden" name="jcartToken"    value="<?php echo ($_SESSION['jcartToken'] ?? ''); ?>">
            <input type="hidden" name="my-item-id"    value="<?php echo $lesson_info['id'] ?>">
            <input type="hidden" name="my-item-name"  value="<?php echo $lesson_info['title'] ?>">
            <input type="hidden" name="image-val" value="L">
            <input type="hidden" name="my-item-price" value="<?php echo number_format(($lesson_info['price'] / 100), 2) ?>">

            <div class="form-inline">
                <select class="form-control cart-quantity-select" name="my-item-qty" id="my-item-qty" tabindex="0">
                    <?php
                    if (($lesson_id == 85) && isset($_SESSION) && ((($_SESSION['mask_id'] ?? '') == 2) || (($_SESSION['mask_id'] ?? '') == 3) || (($_SESSION['username'] ?? '') == ''))) {
                        $i = 1;
                        ?>

                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

                        <?php
                    }// end if
                    else {
                        // if logged in as mask_4 or 1
                        for ($i = 1; $i <= 50; $i++) {
                            ?>

                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

                            <?php
                        }// end for
                        // end mask 4
                    }
                    ?>

                </select><br>&nbsp;&nbsp;

                <input type="hidden" name="lesson-created-at" value="<?php echo $lesson_info['created_at'] ?>">
                <!---->
                <?php if (empty($_SESSION['id'])) { ?>
                    <label for='add-to-cart'  style='display: inline-block;font-weight: bold' >You must <a href="../register.php"><strong> register</strong></a> or <a href="../login.php"><strong>login</strong></a> first to order this item</label>
                    <?php
                }

                $dis = 0;

                if ($lesson_id == 85 && isset($_SESSION) && isset($_SESSION['id']) && $_SESSION['mask_id'] == 2 && dupBaptism()) {
                    $dis = 1;
                    ?>
                    <label for='add-to-cart'  style='display: inline-block;font-weight: bold' >Unavailable: Only One Per User</label>

                    <?php
                }
                if ((!empty($_SESSION['id'])) && ($dis != 1)) {
                    ?>
                    <input type="button" onclick="addToCart(<?php echo $lesson_info['id'] ?>)" name="add-to-cart" class="cart-Link-btn btn btn-success" value="Add to Cart">
                    <?php
                } //if not logged in
                ?>
            </div>
            <!---->
            <br>&nbsp;&nbsp;&nbsp;

            <img src="../images/loading_icon.gif" id="loader">

            <div id="display-message-box" class="display-none display-message form-inline">&nbsp;&nbsp;
                <a href="/view/pp-cart.php" class="display-message-btn">Go To Cart</a>
                <span class="normal-font-size">Item added.</span>
                <span class="closebtn" onclick="this.parentElement.style.display = 'none';">&times;</span>
            </div>
        </form>

        <?php
        if (!in_array($lid, $no_icon)) {
            ?>
            <div class="right" style="float: right; text-align: left;"><img src= "<?php echo $widget_icon; ?>" style="padding: 10px; width: 200px;" alt="widget-icon"></div>

<?php } ?>

        <h4><strong>Product Description</strong></h4>
        <p><?php echo ($lesson_info['description'] ?? ''); ?></p>
    </div>

<?php if ($lesson_id == 85) : ?>
        <div class="container-fluid text-center" style= "width: 85.5%;">
            <video src="../files/mp4s/baptism.mp4" poster="" controls>
            </video>
        </div>
    <?php else : ?>
        <!-- Nothing -->
<?php endif; ?>
    <br>

    <div class="series-bottom">

        <?php
        if ($lesson_id != 85) {
            $rows = $lesson_in_series_query->num_rows;
            if ($rows > 0) {
                ?>
                <p><em><?php echo $lesson_info['title'] ?></em> is part of the following course(s):</p>
                <?php
            } else {
                echo '';
            }
            ?>

            <?php
            //print '<ul>'; // removed to create disks at <li>

            while ($row = $lesson_in_series_query->fetch_array()) {
                echo '<li><a href="/shop/series_detail.php?id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
            }
            print '</ul>';
            ?>

            <?php
            if ($rel_num_results > 0) {
                echo '<br><p>Other lessons similar to <em>' . $lesson_info['title'] . '</em>:</p>';
                while ($rel = $related_lessons_query->fetch_array()) {
                    echo '<li class="margin-bottom-10"><a href="/shop/lessons_detail.php?id=' . $rel['id'] . '">' . $rel['title'] . '</a></li>';
                }
                print '</ul>';
            }
        }
        ?>

    </div>
</div>

<?php include '../info_columns.php'; ?>
<script src="/view/cartjs/cart.js"></script>
<?php include '../bottom.php'; ?>

<?php

function dupBaptism() {
    $user_id = $_SESSION['id'];

    $sql = "SELECT count(*) AS cnt
                FROM purchases
            WHERE (item_type = 'Lesson' AND item_id = 85 OR
                   item_type = 'Series' AND item_id IN (6,64) OR
                   item_type = 'Package' AND item_id IN (17, 18, 19))
              AND  created_at > DATE_SUB(NOW(), INTERVAL 2 WEEK)
              AND user_id = $user_id";
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
