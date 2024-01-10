<?php

require_once('includes/header.php');

$page_html_title = "CatechismClass.com";
include 'start.php';
?>

<div class="content_padded static-login">
    <div class="static ">
        <!--<h3 style="margin-top: 2vh;">Login to Your Account</h3> -->
        <br>

        <?php
        $_SESSION['refer'] = $_SERVER['HTTP_REFERER'];
        // include 'html/login_form.php'; interceptor 12/15/23 Nargish
        ?>
        <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-6 col-md-8 login-box"><br>
                <div class="col-lg-12 login-key">
					<i class="fas fa-file-signature" aria-hidden="true"></i>
                </div>
                <div class="col-lg-12 login-title">
                    Terms and Agreements
                </div>

                <div class="col-lg-12 login-form">
                    <div class="col-lg-12 login-form">
                        <div class="form-group">
                            <p class="precreate-p-text">CatechismClass.com is an educational organization. As such there are certain items that all new registrants must know and accept.<br/><br/></p>
                                <p class="precreate-p-text">
                                    <span class="precreate-dot">&bull;</span> Certificates are only issued for lessons or programs that have met all requirements.<br/>
                                    <span class="precreate-dot">&bull;</span> Our Certificates are accepted internationally, however the priest / parish holds the final disgression on acceptance. We advise users to have a discussion with the priest / parish prior to beginning any lesson course.<br/>
                                    <span class="precreate-dot">&bull;</span> Without exception All Sales are Final. No refund will be had for failed lessons, purchases made in error, or non-acceptance of a certificate by a priest / parish. As in any purchase the material is provided.<br/>
                                    <span class="precreate-dot">&bull;</span> Accounts may not be shared. Attempts to do so will result in delays or denial.<br/>
                                </p>
                            </br>
                            <p><span class="btn btn-outline-primary" onClick="window.location='/Registration.php'">Agree and Continue</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $_SESSION['login_err'] = ''; // reset any  previous login error
        //include 'login_form.php'; 
        ?>
    </div>
</div>

<?php
include 'info_columns.php';
include 'bottom.php';
