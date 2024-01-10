<?php

// test data
//$user_id = 6664;

if (!empty($user_id)) {

    $uamessage_string_a = "<p>Thank you for your purchase of our comprehensive Adult Faith Formation Program. <br><br> We hope that you enjoy the materials and benefit from them spiritually.  Please contact us with any and all questions as we are here to help you.</p><p>As a free gift, we are sending you a copy of the Roman Catechism, also called the Catechism of the Council of Trent.  Please save this PDF to your computer and refer to it as part of your Faith Formation.</p><p>God bless,<br><br>The CatechismClass.com Family</p><p><a href='http://cc.catechismclass.com/files/pdf/TrentCat.pdf' >Click Here to Receive Your Gift !</a></p>";

    // process message here
//echo 'Message sending...'; //test line
//SQL to pull a user array
    $user_id = mysqli()->real_escape_string($user_id);

    $sql = "SELECT id, email, firstname, lastname FROM qb_users WHERE id = $user_id ";
    $result_b = mysqli()->query($sql);

    while ($row = $result_b->fetch_object()) {
        //$id= $row->user_id;
        $firstname = $row->firstname;
        $lastname = $row->lastname;
        $email = $row->email;
        $salute = "Dear {$firstname} {$lastname},  \n<br>";
        echo $email;
//process this email
        $to_string = $email; // check
        $subject_string = "Copy of the Roman Catechism in PDF";
        $message_string = $salute;
        $message_string .= $uamessage_string_a;

        include 'mailer.php';
    } // end while-->
} else {
    echo 'no message...';
} // end else-->

$result_b->free_result(); // clears the variables from the user search
?>
