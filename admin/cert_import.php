<?php

require_once('../includes/header.php');

$alert = "";
if (isset($_POST["type"])) {
    switch ($_POST["type"]) {
        case 'import':
            $isError = true;
            $msg = "Error File";
            if (isset($_FILES["cert"]) && $_FILES['cert']['name'] != '' && $_FILES['cert']['size'] != 0) {
                $msg = "File is not a PDF";
                if (strtolower(pathinfo($_FILES["cert"]["name"], PATHINFO_EXTENSION)) == "pdf") {
                    $msg = "Cannot Process file";
                    $dir = "../certificates/";
                    if (is_dir($dir)) {
                        $dest = $dir . $_FILES["cert"]["name"];
                        move_uploaded_file($_FILES["cert"]["tmp_name"], $dest);
                        if (file_exists($dest)) {
                            $isError = false;
                            $msg = "File Uploaded";
                        }
                    }
                }
            }

            $alert = '<div class="alert alert-' . ($isError ? "danger" : "success") . '" role="alert">' . $msg . '</div>';
            break;

        default: break;
    }
}

$page_html_title = "Upload Certificate";

$page_css_styles = "<style>
    .types{
        margin-left:0px;
        margin-right:0px;
        width:30%;
        padding:10px;
        display:inline-block
    }
</style>";

include '../start.php';
?>

<div class="static pt-2 pb-2" id="top">
    <?php
    if (!empty($_SESSION['username']) && $_SESSION['auth_admin']) {
        //Check if user is logged in
        //Check if user is a coordinator or an admin
        //Grab user's info 
        ?>
        <h1 class="static-title">Upload Certificate</h1>
        <p>Upload manually created or edited certificates</p>
        <?php echo $alert ?>
        <div class='col-md-6 pb-3 px-0'>
            <form method="post" enctype="multipart/form-data">
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="hidden" name="type" value="import">
                        <input type="file" class="custom-file-input" id="cert" name="cert" accept="application/pdf" required>
                        <label class="custom-file-label" for="cert" aria-describedby="cert-submit" style="overflow:hidden;white-space:nowrap;z-index:0">Choose file</label>
                        <script>
                            $("#cert").on("change", function () {
                                let _this = $(this),
                                        filename = _this.val().split("\\").pop(),
                                        color = filename == "" ? "#aaaaaa" : "#444444",
                                        placeholder = filename == "" ? "Choose File" : filename;
                                _this.siblings(".custom-file-label").addClass("selected").html(placeholder).css("color", color);
                            });
                        </script>
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary ml-3" id="cert-submit">Upload</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <p><input type="button" value="Back to Admin Panel" class="btn btn-outline-dark mb-2" onClick="window.location = '../admin/admin_panel.php'"></p>
        <br>
        <?php
    } else {
        // If not logged in, display links to log in and register
        echo "<script> window.location= '../mustRegister.php' </script>";
    }
    ?>
</div><?php include '../info_columns.php'; ?>
</div> <?php include '../bottom.php'; ?>