<?php
define('QUADODO_IN_SYSTEM', true);
require_once ('../includes/header.php');
$qls->Security->check_auth_registration();

include '../start.php';
$package_id = getQls()->SQL->real_escape_string($_GET['id']);
// id FILTER
$package_id = idFilter($package_id);
//for new Cart widget
if (($package_id==2)||($package_id==22)) {
    $sku = 'PL-'.$package_id;
    //echo $sku.'<br/>';
    } else {
    $sku = 'P-'.$package_id;
    //echo $sku.'<br/>';
    }
  $widget_icon= '../widget_icon/'.$sku.'.jpg';
  if (!file_exists($widget_icon)) {
  $widget_icon= '../widget_icon/C-0.jpg';
  }
if ($package_id == '') {
  ?>

<div class="static pt-2">
    <?php $backdropHtml = '<h4 style="text-align: center">We are sorry but that item does not exist. </h4>
    <p style="text-align: center"><br/>
    <form style="text-align: center" action="courses.php">
    <input type="submit" value="Explore our Catalog" class="btn btn-success mt-2 mb-3" />
    </form></p>
    </div>
    <br/>   ';
    include '../html/backdrop1.php'; ?>
</div>
       
    <?php
    trackIp();
    include_once '../info_columns.php';
    include_once '../bottom.php';
    exit();
}
// end filter

//out of stock items
$outOfStock = array ('0','9');

if (in_array($package_id,$outOfStock)) {
?>
<div class="static pt-2">
    <?php $backdropHtml = '<h4 style="text-align: center">We are sorry but that item is discontinued. </h4>
    <p style="text-align: center"><br/>
    <form style="text-align: center" action="courses.php">
    <input type="submit" value="Explore our Catalog" class="btn btn-success mt-2 mb-3" />
    </form></p>
    </div>
    <br/>   ';
    include '../html/backdrop1.php'; ?>
</div>

<?php
include_once '../info_columns.php';
include_once '../bottom.php';
exit;
}
// end out of stock items

$package_info_query = getQls()->SQL->query("SELECT * FROM packages WHERE id = '".fString($package_id)."' ") or die(getQls()->SQL->error());
$package_info = getQls()->SQL->fetch_array($package_info_query);

$package_lessons_query = getQls()->SQL->query("SELECT id, title FROM lessons WHERE id IN (SELECT lesson_id FROM lessons_packages WHERE package_id = '".fString($package_id)."')") or die(getQls()->SQL->error());
$pl_num_results = getQls()->SQL->num_rows($package_lessons_query);

$lessons_prices_query1 = getQls()->SQL->query("SELECT price FROM lessons WHERE id IN (SELECT lesson_id FROM lessons_packages WHERE package_id = '".fString($package_id)."')") or die(getQls()->SQL->error());
$total_lessons_cost = 0;
while ($prices1 = getQls()->SQL->fetch_array($lessons_prices_query1)) {
    // echo $prices[0] . '<br>';
    $total_lessons_cost = $total_lessons_cost + $prices1[0];
}

$package_series_query = getQls()->SQL->query("SELECT id, title, is_ordered, pkg_seq FROM series WHERE id IN (SELECT series_id FROM packages_series  WHERE package_id = '".fString($package_id)."'  ) ORDER BY pkg_seq ") or die(getQls()->SQL->error()); // SJK 2014-5-13 ORDER BY 'pkg_seq' in series table
$ps_num_results = getQls()->SQL->num_rows($package_series_query);
//

$package_series_query2 = getQls()->SQL->query("SELECT id FROM series WHERE id IN (SELECT series_id FROM packages_series  WHERE package_id = '".fString($package_id)."'  )   ") or die(getQls()->SQL->error()); // SJK 2014-5-13 ORDER BY ?

while ($zz = getQls()->SQL->fetch_array($package_series_query2)) {
    $series_ids = $zz['id'];
    $lessons_prices_query2 = getQls()->SQL->query("SELECT price FROM lessons WHERE id IN
        (SELECT lesson_id FROM lessons_series WHERE series_id = $series_ids )") or die(getQls()->SQL->error());
    while ($prices2 = getQls()->SQL->fetch_array($lessons_prices_query2)) {
        // echo $prices[0] . '<br>';
        $total_lessons_cost = $total_lessons_cost + $prices2[0];
    }
}

$related_packages_query = getQls()->SQL->query("SELECT id, title FROM packages WHERE id IN (SELECT related_package_id FROM related_packages WHERE main_package_id = '".fString($package_id)."')") or die(getQls()->SQL->error());
$rel_num_results = getQls()->SQL->num_rows($related_packages_query);
?>
<div class="static">
  
  <h1 class="static-title"><?php echo $package_info['title'] ?></h1>
  
  <?php include '../ShareThis.php'; ?>
  
<div class="static-body">

<h4><strong>Price: $<?php echo number_format(($package_info['price']/100),2); ?></strong></h4>

<?php 
$noCouple = '<br/><h4>Each person needing to be named on the certificate of completion needs his/her own account. Materials can not be shared by more than one person. All persons expecting a certificate must make his/her own purchase.</h4><br/>';
if ($package_id == 23){ echo $noCouple; } ?>

<?php
if (($package_id==2)||($package_id==22)) {
    echo ''; 
    }else{ ?>
<p><?php echo   '<em>By purchasing ' . $package_info['title'] . '  you save $' . number_format(($total_lessons_cost - $package_info['price']) / 100, 2) . ' versus the combined cost of the individual lessons</em>'; ?></p>
<?} ?>

<?php  $add_to_cart = "Add to Cart";  ?>

<form class="form-inline" method="post" action=""
            id="view-form-<?php echo $package_info['id']; ?>" >
    <input type="hidden" name="my-item-id"
    value="<?php echo $package_info['id']; ?>"> <input type="hidden"
            name="my-item-name" value="<?php echo $package_info['title']; ?>">
    <input type="hidden" name="my-item-price"
            value="<?php echo number_format(($package_info['price']/100),2); ?>">
    <input type="hidden" name="image-val" value="P">
    <div class="form-inline">
        <select class="form-control cart-quantity-select" name="my-item-qty" id="my-item-qty"
            tabindex="0">
<?php

if (($package_id==23)&&(($qls->user_info['mask_id']==2)||(empty($qls->user_info['username'])))) {  $i=1;  ?>

<option value="<?php echo $i; ?>"><?php echo $i; ?></option>

<?php   }// end if

else {
    // if logged in as mask_4 or 1
      for ($i=1;$i<=50;$i++) {
    ?>

<option value="<?php echo $i; ?>"><?php echo $i; ?></option>

<?php }// end for
                // end mask 4
            } ?>
</select><br/>&nbsp;&nbsp;

<input type="hidden" name="lesson-created-at" value="<?php echo $package_info['created_at'] ?>">
<?php
      if (empty($qls->user_info['id'])) { ?>
 <label for='add-to-cart'  style='display: inline-block;font-weight: bold' >You must <a href="../preCreate.php"><strong> register</strong></a> or <a href="../login.php"><strong>login</strong></a> first to order this item</label>
<?php
   }           
        
if (in_array($package_id,[17, 18, 19]) and isset($qls->user_info['id']) and $qls->user_info['mask_id']==2 and dupBaptism()) { ?>
<label for='add-to-cart'  style='display: inline-block;font-weight: bold' >Unavailable: Only One Per User</label>
    <?php } else  
 if(empty($qls->user_info['id'])) { ?>

<?php }
else 
 if(!empty($qls->user_info['id'])) { ?>

<input type="button" onclick="addToCart(<?php echo $package_info['id'] ?>)" name="add-to-cart" class="cart-Link-btn btn btn-success" value="Add to Cart">
    <?php } ?>     
    </div>
    <br/>&nbsp;&nbsp;&nbsp;
    <img src="../images/loading_icon.gif" id="loader" />

    <div id="display-message-box" class="display-none display-message form-inline">
        &nbsp;&nbsp;
        <a href="/view/pp-cart.php" class="display-message-btn">Go To Cart</a>
        <span
            class="normal-font-size">Item added.</span>
        <span
            class="closebtn"
            onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
</form> <br/>
        <?php
        if ($package_id!=1){ ?>
 <div style="float: right; text-align: left;" class="right"><img src= "<?php echo $widget_icon; ?>" width="200px" alt="widget-icon" /></div> 
<?php } ?>
<h4><strong>Product Description</strong></h4>
<p> <?php echo $package_info['description']; ?>
</p>
<div class="clearfix">
  <!-- preview a lesson from the series-->

        <?php
        if ($package_id == 1) {
            echo '<a href="#electives">CLICK HERE to see a list of the required electives</a> from which you could choose.<br/><br/>';
        } ?>
        <br /></div>
        <hr />

<?php if ($package_id == 1) : ?>

    <div class="container-fluid" style= "width: 85.5%">
    <center>
    <video src="../files/mp4s/adultFaith.mp4" poster="" width="auto" controls>
    </video>
    </center>
    </div>

<?php else : ?>
    <!-- Nothing -->
<?php endif; // end first section ?>

        <!-- end of main section-->
        
<div class="series-bottom">
 <?php $excluder = array ('2','22');
 if (!IN_ARRAY ($package_id, $excluder)) { ?> 
  <div class="panel-group" id="accordion">
    <br />
      <h2 class="fBold" >
          <em><?php echo $package_info['title'] ?></em> contains the
          following Core Courses:
      </h2>
      
      <?php } ?>
      <!-- begins in package section -->
      <!-- series in package -->
  <?php

  if ($ps_num_results > 0) {
      $a = 100;

      while ($srow = getQls()->SQL->fetch_array($package_series_query)) {

          $series_id = $srow['id'];
          $ordered = $srow['is_ordered'];
          if ($ordered != 0) {
              $package_series_lessons_query = getQls()->SQL->query("SELECT id, title, position FROM lessons WHERE id IN(SELECT lesson_id FROM lessons_series WHERE series_id = $series_id ) ORDER BY position") or die(getQls()->SQL->error());
          } else {
              $package_series_lessons_query = getQls()->SQL->query("SELECT id, title FROM lessons WHERE id IN(SELECT lesson_id FROM lessons_series WHERE series_id = $series_id )") or die(getQls()->SQL->error());
          }

          ?>
  <!-- included series panels section-->
      <div class="panel panel-default">
          <!-- a panel -->
          <div class="panel-heading">
              <h2 class="panel-title">
                
                <a href="series_detail.php?id=<?php echo $srow['id'];?>">
                  <?php echo $srow['title']; ?> Course</a>
                              
              </h2>
              <div>
                <a data-toggle="collapse" data-parent="#accordion"
                    href="#collapse<?php echo $a; ?>">Click Here for <?php echo $srow['title']; ?> Lessons</a>
              </div>
          </div>
          <div id="collapse<?php echo $a; ?>"
              class="panel-collapse collapse">
              <div class="panel-body">
                <ul style="list-style-type:disc;" class="lesson-list">

          <?php
          while ($slrow = getQls()->SQL->fetch_array($package_series_lessons_query)) {
              echo '<li><a href="lessons_detail.php?id=' . $slrow['id'] . '">' . $slrow['title'] . '</a></li>';
          }
          print '';
          ?>
        </li>

          </div>
              <!-- panel-body-->
          </div>
          <!-- panel-collapse collapse-->
      </div>
      <!-- panel panel-default-->


  <?php
          $a ++;
      } // end while series
      $a = 0;
  } // end if series
  ?>

  <?php

  if ($pl_num_results > 0) {
      // echo $pl_num_results. ' <br/>';
      ?>

      <div class="panel panel-default">
          <!-- a panel -->
          <div class="panel-heading">
              <h2 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion"
                      href="#collapse-b">Lessons in <? echo $package_info['title'] ?></a>
              </h2>
          </div>
          <div id="collapse-b" class="panel-collapse collapse">
              <div class="panel-body">
                <ul style="list-style-type:disc;" class="lesson-list">

          <?php

      while ($lrow = getQls()->SQL->fetch_array($package_lessons_query)) {
          echo '<li><a href="lessons_detail.php?id=' . $lrow['id'] . '">' . $lrow['title'] . '</a></li>';

          /*
           * while ($lrow = getQls()->SQL->fetch_array($package_lessons_query)) {
           * echo '<li><a href="lessons_detail.php?id='.$lrow['id'].'">'.$lrow['title'].'</a></li>';
           * print '</ul>'; ?>
           *
           * </div>
           * <?php } ?>
           * </div>
           * <?php } ?>
           * </div> <!-- end a panel -->
           *
           * <?php
           */
          /*
           * print '<h4>Lessons:</h4>'; // removed <ul> to create bullets on lessons
           * while ($lrow = getQls()->SQL->fetch_array($package_lessons_query)) {
           * echo '<li><a href="lessons_detail.php?id='.$lrow['id'].'">'.$lrow['title'].'</a></li>';
           */
      } // end while lessons
      print ' ';
  } // end if lessons
  ?>
</ul>


  <?php

  if ($rel_num_results > 0) {
      echo '<br/><p>Other Courses similar to <em>' . $package_info['title'] . '</em>:</p>';
      while ($rel = getQls()->SQL->fetch_array($related_packages_query)) {
          echo '<li><a href="package_detail.php?id=' . $rel['id'] . '">' . $rel['title'] . '</a></li>';
      }
      print '</ul>';
  }
  ?>



<?php include '../shop/pack1_detail.php'; ?>
  
<script src="/view/cartjs/cart.js" type="text/javascript"></script>

</div> <!-- panel-body -->
</div> <!-- collapse-b -->

</div> <!-- panel default -->
</div><!--class="panel-group" id="accordion"-->

</div> <!-- series bottom -->
</div> <!-- end static body -->
</div><!-- end static-->

</div><!--  stray opening somewhere-- SJK 2-25-23 -->
<?php include '../info_columns.php'; ?>

<?php include '../bottom.php'; ?>
<?php 
    function dupBaptism() {
        $qls = getQls();
        $user_id = $qls->user_info['id'];
        
        $sql = "select count(*) as cnt  
                from purchases 
            where (item_type = 'Lesson' and item_id = 85 or
                   item_type = 'Series' and item_id in (64,65) or
                   item_type = 'Package' and item_id in (17, 18, 19)) 
              and  created_at > DATE_SUB(NOW(), INTERVAL 2 WEEK)
              and user_id = $user_id";
        $result = $qls->SQL->query($sql) or die($qls->SQL->error) ;
        $row=$qls->SQL->fetch_assoc($result);
        return (bool) $row['cnt'];
    }

    function fString($S = "") {
        if($S == "") {
            return "";
        }

        $SS = (string)$S;
        if(strlen($SS) != 0) {
            $SS = trim($SS);
            $SS = preg_replace("/ +/", " ", $SS);
            //temporary disabled to fix encoding problem in product name symbol
            //$SS = iconv("UTF-8", "ASCII//TRANSLIT", $SS);
            $SS = str_ireplace("'", "''", $SS);
            $SS = mb_convert_encoding($SS, 'UTF-8', 'UTF-8');
        }
        return $SS;
    }
?>
