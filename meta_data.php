<?php ?>
<!-- Initialize Meta-Data -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@CatechismClass">
<meta property="og:type" content="website">
<link rel="canonical" href="https://<?php echo $uri.$_SERVER['REQUEST_URI']; ?>" />
<meta name="twitter:image" content="https://<?php echo $uri.'/FB-TW-2.jpg'; ?>">
<meta property="og:image" content="https://<?php echo $uri.'/images/Shield.jpg'; ?>">
<meta property="fb:app_id" content="597613985224777" >
<meta property="author" content="The Goldhead Group, Ltd">
<link rel="icon" type="image/x-icon" href="/Shield.jpg">

<?php
switch ($url) {
    // /[root]
    case "/" :
    case "/index.php" :
    case "/?fbclid" :

        // do this code for 'site index canonical-defaults'
        ?>  
        <link rel="canonical" href="https://<?php echo $uri . '/'; ?>">
        <meta property="og:url" content="https://<?php echo $uri . '/'; ?>">
        <meta property="og:type" content="website">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <?php
        break;

    // default
    default :
        ?>

        <link rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">

<?php }  // end switch
?>

<?php
$urc = substr($_SERVER['REQUEST_URI'], 0, 9);
if (($urc == '/shop/cat') || ($urc == '/categori' )) {
    $url = '/catholic-book-summaries.php';
    echo $urc;
    echo '<br>';
    // force a single URL for the 'meta-data' so that '/categories/8'  go to '/catholic-book-summaries.php' .
    //-- SJK
}

switch ($url) {

    // /[root]
    case "/" :
    case "/index.php" :
    case "/?fbclid" :
        // do this code for 'site index canonical-defaults'
        ?>  

        <!-- meta inserts 7-24-19 SJK > -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="CatechismClass.com">
        <meta property="og:description" content="A Parish Approved Leader in Online Religious Education and Sacramental Preparation Classes for Catholics of all ages. Study the Catholic Faith Online. Enroll in RCIA Online. Study for your Sacraments Online. Study to be a Godparent Online.">
        <meta name="description" content="A Parish Approved Leader in Online Religious Education and Sacramental Preparation Classes for Catholics of all ages. Study the Catholic Faith Online. Enroll in RCIA Online. Study for your Sacraments Online. Study to be a Godparent Online.">
        <meta name="keywords" content="Catholic enrichment, online Catholic preparation, catholic adult online courses, catholic children's online courses, Catholic Religious Education Curriculum, Catholic Religious Education Publishers, catholic classes online, online catholic classes, catechism classes, catechism online, CCD online, RCIA online, catholic online, catholic online school, online RCIA, baptism class, baptism preparation class, catholic baptism class, catholic baptism preparation class, catholic baptism preparation, baptism class for godparents online, catholic religious education, catholic catechist resources, catholic religion teacher resources, at home faith formation, faith formation at home, at home catholic classes, Home-Based Catechesis, Catholic Education Program online, Catholic Education Program, catholic school online, catholic religious education program, best catholic religious education curriculum, catholic class, catholic religious ed, Catholic Religion Curriculum, quick catholic lessons, catholic lessons for youth, catholic lessons, catequesis en l&iacute;nea, catecismo en l&iacute;nea, clases para catolicos">
        <link  rel="canonical" href="https://<?php echo $uri . '/'; ?>">
        <title>CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /extras
    case "/register.php" :
        // do this code for 'all-defaults'
        ?>  

        <!-- meta inserts 7-20-19 SJK > -->
        <meta property="og:title" content="CatechismClass.com">
        <meta property="og:type" content="website">
        <meta property="og:description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="keywords" content="Catholic enrichment, online Catholic preparation, catholic adult online courses, catholic children's online courses, Catholic Religious Education Curriculum, catholic classes online, online catholic classes, catechism classes, catechism online, CCD online, RCIA online, catholic online, online RCIA, baptism class, baptism preparation class, catholic baptism class, catholic baptism preparation class, catholic baptism preparation, baptism class for godparents online, catholic religious education resources, catholic catechist resources, catholic religion teacher resources">
        <link  rel="canonical" href="https://<?php echo $uri . '/' ?>">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /extras
    case "/Registration.php" :
        // do this code for 'all-defaults'
        ?>  

        <!-- meta inserts 7-20-19 SJK > -->
        <meta property="og:title" content="CatechismClass.com">
        <meta property="og:type" content="website">
        <meta property="og:description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="keywords" content="Catholic enrichment, online Catholic preparation, catholic adult online courses, catholic children's online courses, Catholic Religious Education Curriculum, catholic classes online, online catholic classes, catechism classes, catechism online, CCD online, RCIA online, catholic online, online RCIA, baptism class, baptism preparation class, catholic baptism class, catholic baptism preparation class, catholic baptism preparation, baptism class for godparents online, catholic religious education resources, catholic catechist resources, catholic religion teacher resources">
        <link  rel="canonical" href="https://<?php echo $uri . '/' ?>">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /course_calc/index.php
    case "/course_calc/index.php" :
        // do this code for /course_calc/index.php
        ?>  

        <!-- Meta inserts by SJK 2018-04-22-->

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Course Selector - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics. Study for your Sacraments Online. Study the Catholic Faith online.">
        <meta name="description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics. Study for your Sacraments Online. Study the Catholic Faith online.">
        <meta name="keywords" content="Certificate Programs for Sacramental Preparation">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Course Selector - CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // marriage-prep.php 
    case "/marriage-prep.php" :
        // do this code for marriage-prep.php 
        ?>  

        <!-- Meta inserts by Lalita 2015-03-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Marriage Prep - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers a highly affordable marriage preparation course for couples. The CatechismClass.com Marriage Preparation Program is meant to serve as both a preparation for those looking to receive marriage as well as those looking to better understand the sacramental reality of Holy Matrimony.">
        <meta name="description" content="CatechismClass.com offers a highly affordable marriage preparation course for couples. The CatechismClass.com Marriage Preparation Program is meant to serve as both a preparation for those looking to receive marriage as well as those looking to better understand the sacramental reality of Holy Matrimony."> 
        <meta name="keywords" content="catholic marriage preparation course, catholic preparation for marriage, pre cana course, marriage preparation online, catholic class before marriage"> 
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Catholic Marriage Preparation Course Online - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // marriage-prep.php //Spanish
    case "/matri-prep.php" :
        // do this code for marriage-prep.php 
        ?>  

        <!-- Meta inserts by MP 2022-11-18-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Preparaci&oacute;n Matrimonial Cat&oacute;lica en l&iacute;nea">
        <meta property="og:description" content="Estudia para tu matrimonio cat&oacute;lico en l&iacute;nea. Certificado de finalizacin disponible. Muy asequible y f&aacute;cil de usar.">
        <meta name="description" content="Estudia para tu matrimonio cat&oacute;lico en l&iacute;nea. Certificado de finalizacin disponible. Muy asequible y f&aacute;cil de usar."> 
        <meta name="keywords" content="preparaci&oacute;n matrimonial cat&oacute;lica, preparacion matrimonial cat&oacute;lica, curso de preparaci&oacute;n matrimonial, curso de preparaci&oacute;n al matrimonio, preparaci&oacute;n matrimonial cat&oacute;lica coupon, platicas para matrimonios, pl&aacute;ticas prematrimoniales cat&oacute;licas, cursillo matrimonial, catequesis de preparaci&oacute;n al sacramento del matrimonio"> 
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Preparaci&oacute;n Matrimonial Cat&oacute;lica en l&iacute;nea</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /marriage-convalidation-class.php
    case "/marriage-convalidation-class.php" :
// do this code for /marriage-convalidation-class.php
        ?>

        <!-- Meta inserts by Lalita 2015-03-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Marriage Convalidation Class - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers an online marriage preparation class for couples seeking to have their marriage blessed and recognized by the Catholic Church.">
        <meta name="description" content="CatechismClass.com offers an online marriage preparation class for couples seeking to have their marriage blessed and recognized by the Catholic Church.">
        <meta name="keywords" content="marriage convalidation, catholic marriage convalidation program, marriage convalidation ceremony in the catholic church, marriage convalidation ceremony, marriage convalidation process, marriage convalidation class, getting marriage blessed in catholic church, steps to getting marriage blessed in catholic church">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Catholic Marriage Convalidation Class - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // matrimonial_preparacion.php

    case "/matrimonial_preparacion.php" :
// do this code for matrimonial_preparacion.php // addition SJK- 09/26/20
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Matrimonial Preparaci&oacute;n - CatechismClass.com">
        <meta property="og:description" content="El programa de preparaci&oacute;n matrimonial CatechismClass.com est&aacute; destinado a servir tanto para quienes desean recibir el matrimonio como para quienes desean que la Iglesia cat&oacute;lica bendiga su matrimonio.">
        <meta name="description" content="El programa de preparaci&oacute;n matrimonial CatechismClass.com est&aacute; destinado a servir tanto para quienes desean recibir el matrimonio como para quienes desean que la Iglesia cat&oacute;lica bendiga su matrimonio.">
        <meta name="keywords" content="matrimonial preparaci&oacute;n, preparaci&oacute;n matrimonial cat&oacute;lica, preparaci&oacute;n al matrimonio cat&oacute;lica, cursos catolicos para matrimonios, clases para matrimonios cat&oacute;licos">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Preparaci&oacute;n Matrimonial Cat&oacute;lica</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->




        <?php
        break;

    // /curriculum_format.php
    case "/curriculum_format.php" :
// do this code for curriculum_format.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="CatechismClass.com Curriculum">
        <meta property="og:description" content="CatechismClass.com follows a unique 7 step approach: Introduction. Opening Prayers. Scripture Passages. Catechism Passages. Integration and Teaching. Activities. Closing Prayers.">
        <meta name="description" content="CatechismClass.com follows a unique 7 step approach: Introduction. Opening Prayers. Scripture Passages. Catechism Passages. Integration and Teaching. Activities. Closing Prayers.">
        <meta name="keywords" content="curriculum format for catechism classes, curriculum format for catechists, curriculum format for catechism lessons, catholic catechism lesson format">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Curriculum Format - CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // about.php
    case "/about.php" :

        // do this code for about.php
        ?>  

        <!-- Meta inserts by SJK 11/11/17 -->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Who Is CatechismClass.com?">
        <meta property="og:description" content="CatechismClass.com operates with the singular goal of being the best online Catholic catechesis program in the world. We handle the planning, the material gathering, and much of the teaching in our online, self study courses.">
        <meta name="description" content="CatechismClass.com operates with the singular goal of being the best online Catholic catechesis program in the world. We handle the planning, the material gathering, and much of the teaching in our online, self study courses.">
        <meta name="keywords" content="online catholic school, online catholic theology courses, online catholic education, catholic theology courses online, catholic religious education for children, catechismclass.com reviews,catechism class, catholic classes">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Who Is CatechismClass.com? </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // homeschooling.php
    case "/homeschooling.php" :
        // do this code for homeschooling.php
        ?>  

        <!-- Meta inserts edited by SJK 2015-07-13-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Homeschooling by CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers a first-of-its-kind online Catholic homeschooling curriculum. Founded in 2004, CatechismClass.com has been leading the way in online Catholic Faith Formation for homeschoolers.">
        <meta name="description" content="CatechismClass.com offers a first-of-its-kind online Catholic homeschooling curriculum. Founded in 2004, CatechismClass.com has been leading the way in online Catholic Faith Formation for homeschoolers.">
        <meta name="keywords" content="online catholic school, online catholic homeschooling, catholic homeschooling online, catholic religion homeschooling, online catholic homeschool, catholic homeschool curriculum, best catholic homeschooling, best catholic homeschooling curriculum, catholic homeschool, catholic homeschool program, online catholic homeschool, catholic homeschooling programs, Catholic Homeschool Resources">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> The Best Catholic Homeschool Curriculum</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // traditional-catholic-homeschool.php
    case "/traditional-catholic-homeschool.php" :
// do this code for traditional-catholic-homeschool.php
        ?>

        <!-- Meta inserts edited by SJK 2015-07-13-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Traditional Catholic Homeschooling - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers the most traditional Catholic homeschooling curriculum available.">
        <meta name="description" content="CatechismClass.com offers the most traditional Catholic homeschooling curriculum available.">
        <meta name="keywords" content="traditional catholic homeschool curriculum, traditional catholic homeschooling, traditional catholic curriculum, traditional catholic school curriculum, Traditional Catholic Catechism Lessons Online">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Traditional Catholic Homeschool Curriculum</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // ccd.php
    case "/ccd.php" :
        // do this code for ccd.php
        ?>  

        <!-- Meta inserts by SJK 2015-07-13-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Children&apos;s Catholic Education Classes by CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for children.">
        <meta name="description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for children.">
        <meta name="keywords" content="online ccd classes, online catholic ccd, catholic ccd curriculum, children&apos;s catholic prep, online parish school of religion, parish school of religion classes, online psr classes, catholic ccd classes at home, catholic ccd classes online, Faith Formation at Home Resources, ccd class near me, psr class catholic, ccd catholic religious education, childrens catholic catechism lessons, childrens religious education">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Best CCD / Children&apos;s Catholic PREP Online - CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

    // catholic-rcia-classes.php
    case "/catholic-rcia-classes.php" :
        // do this code for catholic-rcia-classes.php
        ?>  

        <!-- Meta inserts by Lalita 2015-03-08-->

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online RCIA - CatechismClass.com">
        <meta property="og:description" content="RCIA (Rite of Christian Initiation of Adults) is the process by which non-Catholics enter the Catholic Faith. CatechismClass.com offers a best-selling online Adult Faith Formation course that has served as the RCIA text for many parishes and is used by those wishing to convert to Catholicism.">
        <meta name="description" content="RCIA (Rite of Christian Initiation of Adults) is the process by which non-Catholics enter the Catholic Faith. CatechismClass.com has developed a best-selling online Adult Faith Formation course that has served as the RCIA text for many parishes and is used by anyone wanting to convert to the Catholic Faith or complete their Sacraments.">
        <meta name="keywords" content="online rcia classes catholic, catholic rcia classes online, rcia online, become Catholic online">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Catholic RCIA Classes Online - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// shop/courses.php
    case "/shop/courses.php" :
        // do this code for shop/courses.php
        ?>   

        <!-- Meta inserts by SJK 2015-03-22-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Courses">
        <meta property="og:description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics of all ages. Dozens of affordable classes.">
        <meta name="description" content="CatechismClass.com is the world leader in providing online religious education and Sacramental preparation classes for Catholics. Dozens of affordable classes.">
        <meta name="keywords" content="catholic online courses, online catholic school, basic catholic catechism course, online catholic theology courses, online catholic education, catholic theology courses online, catholic religious education for children, catholic religion classes">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Catholic Courses</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!---->

        <?php
        break;

// add for Book Summaries
    case "/catholic-book-summaries.php" :
        // do this code for /summaries.php
        ?>  

        <!-- Meta inserts by SJK 2015-05-18-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Book Summaries - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com is pleased to provide a one-of-a-kind online reading program entitled Catholic Book Summaries. The Catholic Book Summaries Program includes 50+ summaries of Catholic books all organized in a concise, clear, and complete manner.">
        <meta name="description" content="CatechismClass.com is pleased to provide a one-of-a-kind online reading program entitled Catholic Book Summaries. The Catholic Book Summaries Program includes 50+ summaries of Catholic books all organized in a concise, clear, and complete manner.">
        <meta name="keywords" content="CatholicBookSummaries.com, Catholic Book Summaries, Catholic Cliff Notes, Catholic Books, Summaries of Catholic Books, Catholic Books">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Catholic Book Summaries - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">

        <!-- -->

        <?php
        break;

// add for New York Diocese

    case "/newyork.php" :
        // do this code for /newyork.php
        ?>  

        <!-- Meta inserts by SJK 2016-07-30-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Religious Education - New York Archdiocese Classes">
        <meta property="og:description" content="CatechismClass.com offers custom Catholic education courses for children which follow the requirements for the Archdiocese of New York.">
        <meta name="description" content="CatechismClass.com offers custom Catholic education courses for children which follow the requirements for the Archdiocese of New York.">
        <meta name="keywords" content="religious education new york, catholic religious education classes in new york, archdiocese of New York religion curriculum , nyfaithformation">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Archdiocese of New York Custom Catholic Classes - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// add for Quinceanera

    case "/quinceanera-prep.php" :
        // do this code for /quinceanera-prep.php"
        ?>  

        <!-- Meta inserts by SJK 2016-07-30-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Quincea&ntilde;era Prep - CatechismClass.com">
        <meta property="og:description" content="Quincea&ntilde;era Preparation is offered by CatechismClass.com, the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="description" content="Quincea&ntilde;era Preparation is offered by CatechismClass.com, the world leader in providing online religious education and Sacramental preparation classes for Catholics.">
        <meta name="keywords" content="Quincea&ntilde;era Class, Catholic Quincea&ntilde;era Class, CatechismClass.com Quincea&ntilde;era Preparation,prepare girls for their Quincea&ntilde;era ">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Quincea&ntilde;era Prep - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /how_do_i_receive_the_sacraments.php
    case "/how_do_i_receive_the_sacraments.php" :
        // do this code for /how_do_i_receive_the_sacraments.php
        ?>  

        <!-- Meta inserts by SJK 2015-03-22-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content=" How Do I Receive the Sacraments? - CatechismClass.com">
        <meta property="og:description" content="To receive the Sacraments, you must first be intellectually prepared to receive a Sacrament. CatechismClass.com is a leading provider of Sacramental Preparation courses. Study at your own pace.">
        <meta name="description" content="To receive the Sacraments, you must first be intellectually prepared to receive a Sacrament. CatechismClass.com is a leading provider of Sacramental Preparation courses. Study at your own pace.">
        <meta name="keywords" content="catholic sacramental preparation, catholic sacramental preparation resources, how to receive my sacraments, how to get my sacraments, how to receive confirmation in the catholic church, how to receive first communion in the catholic church, how can I study for my sacraments, how do i become a catholic, Do I have to be confirmed to be married, how to get confirmed online">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> How Do I Receive the Sacraments? - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

// /high-school.php
    case "/high-school.php" :
        // do this code for /high-school.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="High School - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com is proud to offer a superior catechism program for those in high school. The CatechismClass.com High School Program follows the program designed by the USCCB in 2008 called Doctrinal Elements of a Curriculum Framework for the Development of Catechetical Materials for Young People of High School Age. CatechismClass.com is one of the first programs to create a high school program modeled after the framework.">
        <meta name="description" content="CatechismClass.com is proud to offer a superior catechism program for those in high school. The CatechismClass.com High School Program follows the program designed by the USCCB in 2008 called Doctrinal Elements of a Curriculum Framework for the Development of Catechetical Materials for Young People of High School Age. CatechismClass.com is one of the first programs to create a high school program modeled after the framework.">
        <meta name="keywords" content="online catholic high school, online catholic high school courses, catholic online high school, catholic high school courses online, catholic religious education for highschoolers">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Online Catholic High School Courses - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

    // /sacramental-prep.php
    case "/sacramental-prep.php" :
        // do this code for /sacramental-prep.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Sacramental Prep - CatechismClass.com ">
        <meta property="og:description" content="CatechismClass.com offers online Sacramental Preparation Courses for First Penance, First Eucharist, Confirmation, Baptism, and Holy Matrimony. Study the Catholic Faith Online. Study for your Sacraments Online.">
        <meta name="description" content="CatechismClass.com offers online Sacramental Preparation Courses for First Penance, First Eucharist, Confirmation, Baptism, and Holy Matrimony. Study the Catholic Faith Online. Study for your Sacraments Online.">
        <meta name="keywords" content="online catholic sacramental preparation, online sacramental preparation courses, catholic sacramental preparation courses online, catholic sacramental preparation">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online Catholic Sacramental Courses - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

// shop/lessons_detail.php?id=85
    case "/shop/lessons_detail.php?id=85" :
        // do this code for shop/lessons_detail.php?id=85
        ?>  

        <!-- Meta inserts by SJK 2018-04-19-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Baptism Prep - CatechismClass.com ">
        <meta property="og:description" content="CatechismClass.com offers Baptism Preparation Courses for Parents and God Parents: The leading and most popular Certificate Program available for Baptism Preparation. Enroll in the Online Baptism Preparation Program. Certificates Issued Upon Completion.">
        <meta name="description" content="CatechismClass.com offers Baptism Preparation Courses for Parents and God Parents: The leading and most popular Certificate Program available for Baptism Preparation. Enroll in the Online Baptism Preparation Program. Certificates Issued Upon Completion.">
        <meta name="keywords" content="catholic baptism prep, baptism class, baptism class near me, baptism class Catholic, preparation for parents and godparents, baptism preparation online, pre Baptism class, baptismal seminar, baptism matters">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // shop/series_detail.php?id=34
    case "/shop/series_detail.php?id=34" :
        // do this code for shop/series_detail.php?id=34
        ?>

        <!-- Meta inserts by SJK 2021-09-02-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Easter Season Mystagogy Course - CatechismClass.com ">
        <meta property="og:description" content="In this Pascaltide course, we focus on Our Lord Jesus Christ's conquest of death by studying the Church's traditions, Scripture, Catechism passages, and more. For those new Catholics, this is a truly unique Mystagogy Course. In this course, the newly initiated Catholics will be able to better live out their first liturgical season as baptized and confirmed Catholics! ">
        <meta name="description" content="In this Pascaltide course, we focus on Our Lord Jesus Christ's conquest of death by studying the Church's traditions, Scripture, Catechism passages, and more. For those new Catholics, this is a truly unique Mystagogy Course. In this course, the newly initiated Catholics will be able to better live out their first liturgical season as baptized and confirmed Catholics! ">
        <meta name="keywords" content="Easter season classes, Easter Season Resources, Mystagogy classes ">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Easter Season Mystagogy Course - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /faithful_to_the_church.php
    case "/faithful_to_the_church.php" :
        // do this code for /faithful_to_the_church.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="CatechismClass.com Fidelity">
        <meta property="og:description" content="CatechismClass.com classes are guaranteed to be in accordance with the Faith as taught and preserved by the Holy Catholic Church.">
        <meta name="description" content="CatechismClass.com classes are guaranteed to be in accordance with the Faith as taught and preserved by the Holy Catholic Church.">
        <meta name="keywords" content="faithful Catholic classes, faithful catholic websites, faithfully catholic">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> CatechismClass.com Is Faithful To The Church </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /catholic_confirmation_classes.php
    case "/catholic_confirmation_classes.php" :
        // do this code for /catholic_confirmation_classes.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online Confirmation Classes">
        <meta property="og:description" content="Catholic Confirmation Classes prepare anyone studying for the Sacrament of Confirmation. Both adults and children can conveniently take Confirmation classes online.">
        <meta name="description" content="Catholic Confirmation Classes prepare anyone studying for the Sacrament of Confirmation. Both adults and children can conveniently take Confirmation classes online.">
        <meta name="keywords" content="confirmation for adults program, adult confirmation classes, catholic confirmation classes, catholic confirmation classes for adults online, online confirmation classes, online catholic confirmation classes, catholic confirmation online, Confirmation with Certificate of Completion, online confirmation classes, confirmation classes, online ccd for confirmation, online catholic confirmation classes for adults, quick catholic confirmation">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online Confirmation Classes - Certificate of Completion</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

    // /patron.php
    case "/patron.php" :
        // do this code for /patron.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Our Patron - CatechismClass.com">
        <meta property="og:description" content="St. Charles Borromeo, the patron saint of catechists, is the patron saint for CatechismClass.com.">
        <meta name="description" content="St. Charles Borromeo, the patron saint of catechists, is the patron saint for CatechismClass.com.">
        <meta name="keywords" content="patron saint of catechists, patron saint of catechism">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>The Patron Saint Of CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /faq.php
    case "/faq.php" :
        // do this code for /faq.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="FAQ - CatechismClass.com">
        <meta property="og:description" content="Frequently Asked Questions on CatechismClass.com.">
        <meta name="description" content="Frequently Asked Questions on CatechismClass.com.">
        <meta name="keywords" content="catechismclass.com frequently asked questions">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Frequently Asked Questions - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /paperbacks.php 
    case "/paperbacks.php" :
        // do this code for /paperbacks.php 
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Paperbacks and eBooks - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers a number of paperbacks and eBooks, in addition to online self-study courses.">
        <meta name="description" content="CatechismClass.com is pleased to provide some of its online courses in paperback and ebook format.">
        <meta name="keywords" content="catholic books, catholic religion books, catholic theology books">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Paperbacks and eBooks - CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">

        <!-- -->

        <?php
        break;

    // /terms.php 
    case "/terms.php" :
        // do this code for /terms.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="CatechismClass.com Terms and Conditions">
        <meta property="og:description" content="CatechismClass.com Terms and Conditions of Use.">
        <meta name="description" content="CatechismClass.com Terms and Conditions of Use.">
        <meta name="keywords" content="catechismclass.com terms and conditions">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Terms And Conditions - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /privacy_security.php 
    case "/privacy_security.php" :
        // do this code for /privacy_security.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="CatechismClass.com Privacy Policy">
        <meta property="og:description" content="CatechismClass.com Privacy and Security Policy. We are committed to safeguarding the privacy of visitors and users of CatechismClass.com.">
        <meta name="description" content="CatechismClass.com Privacy and Security Policy. We are committed to safeguarding the privacy of visitors and users of CatechismClass.com.">
        <meta name="keywords" content="catechismclass.com privacy and security statement">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Privacy And Security Policy - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /vasa_talks.php
    case "/vasa_talks.php" :
        // do this code for /vasa_talks.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Bishop Vasa on the Soul of the Apostolate">
        <meta property="og:description" content="CatechismClass.com is pleased to offer an audio series given by Bishop Robert Vasa on the Soul of the Apostolate by Fr. Jean-Baptiste Chautard.">
        <meta name="description" content="CatechismClass.com is pleased to offer an audio series given by Bishop Robert Vasa on the Soul of the Apostolate by Fr. Jean-Baptiste Chautard.">
        <meta name="keywords" content="The Soul of the Apostolate, the soul of the apostolate audio, the soul of the apostolate summary, the soul of the apostolate explanation">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> The Soul of the Apostolate Talks by Bishop Vasa - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /parish.php
    case "/parish.php" :
        // do this code for /parish.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Parish Programs - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com proudly serves many parishes. Our goal is to make life easier for parish catechists and teachers while also providing some of the best religious education materials around. Our programs are convenient, complete, and flexible.">
        <meta name="description" content="CatechismClass.com proudly serves many parishes. Our goal is to make life easier for parish catechists and teachers while also providing some of the best religious education materials around. Our programs are convenient, complete, and flexible.">
        <meta name="keywords" content="parish religious education program, parish religious education resources, parish religious education online, parish faith formation, parish religion classes, parish religious education programs, parish catechetical programs, Resources for Parish Religious Education Programs, Parish Religious Education Program online">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Parish Programs - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /family-catechesis.php
    case "/family-catechesis.php" :
        // do this code for 'family-catechesis.php'
        ?>  

        <!-- Meta inserts by SJK 2018-01-21-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Family Catechesis - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers online, self study classes for families to take together as part of family catechesis.">
        <meta name="description" content="CatechismClass.com offers online, self study classes for families to take together as part of family catechesis.">
        <meta name="keywords" content="family catechesis, family catechesis program, family curriculum, catholic family catechesis, at home family catechesis, family faith formation, family based catholic religious education, family formation, Family Faith Formation Resources, what is family catechesis">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Family Based Catechesis - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

    // /refer-books.php
    case "/refer-books.php" :
        // do this code for 'refer-books.php'
        ?>  

        <!-- Meta inserts by SJK 2018-07-15-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Recommended Catholic Education Books - CatechismClass.com">
        <meta property="og:description" content="Looking to study the Catholic Religion? CatechismClass.com is happy to recommend the following books for a comprehensive understanding of what the Catholic Church teaches.">
        <meta name="description" content="Looking to study the Catholic Religion? CatechismClass.com is happy to recommend the following books for a comprehensive understanding of what the Catholic Church teaches.">
        <meta name="keywords" content="recommended Catholic reading, recommended Catholic books, catholic religion books, what does the catholic church teach, what do catholics believe">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Recommended Catholic Education Books - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->
        <?php
        break;

    // /catholic_sunday_school.php
    case "/catholic_sunday_school.php" :
        // do this code for 'catholic_sunday_school.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Sunday School
              - CatechismClass.com">
        <meta property="og:description" content="Catholic Sunday School is commonly referred to as CCD or religious education and it takes place either on Sundays or another day of the week.">
        <meta name="description" content="Catholic Sunday School is commonly referred to as CCD or religious education and it takes place either on Sundays or another day of the week.">
        <meta name="keywords" content="catholic Sunday school, what is catholic Sunday school, catholic Sunday school programs, catholic Sunday school resources, catholic Sunday school ideas, sunday religion classes, sunday religion classes catholic, what is sunday school, sunday school for catholics">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Catholic Sunday School  - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /special-needs-catechesis.php
    case "/special-needs-catechesis.php" :
        // do this code for 'special-needs-catechesis.php'
        ?>

        <!-- Meta inserts by SJK 2020-03-13-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Special Needs Catechesis - CatechismClass.com">
        <meta property="og:description" content="Ever since we were founded by Fr. James Zatalava in 2004, CatechismClass.com has been serving adults and children with special needs. Not everyone learns at the same pace or in the same way.">
        <meta name="description" content="Ever since we were founded by Fr. James Zatalava in 2004, CatechismClass.com has been serving adults and children with special needs. Not everyone learns at the same pace or in the same way.">
        <meta name="keywords" content="Catechesis for Children with Special Needs, special needs religious education, special needs religious education resources, autism catholic religious education, Celebration of the Sacraments with Persons with Disabilities">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Special Needs Catechesis - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /what_are_ccd_classes.php
    case "/what_are_ccd_classes.php" :
        // do this code for 'what_are_ccd_classes.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="What is the Catechism?">
        <meta property="og:description" content="What is A Catechism?  What is Catechesis?  Who is a Catechist?  What Does CCD Mean?">
        <meta name="description" content=" What is A Catechism?  What is Catechesis?  Who is a Catechist?  What Does CCD Mean?">
        <meta name="keywords" content="catechism, what is catechism, catechisms, what is catechism for children, what is ccd, how long is ccd class">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>The Catechism Explained for Catholics - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /why_be_confirmed.php
    case "/why_be_confirmed.php" :
        // do this code for 'why_be_confirmed.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Should I Be Confirmed? - CatechismClass.com">
        <meta property="og:description" content="The sacrament of confirmation strengthens the baptized and obliges them more firmly to be witnesses of Christ by word and deed and to spread and defend the faith. It imprints a character, enriches by the gift of the Holy Spirit the baptized continuing on the path of Christian initiation, and binds them more perfectly to the Church.">
        <meta name="keywords" content="why become confirmed, why is confirmation important, is confirmation important, too old for confirmation, benefits of confirmation, benefits of sacrament of confirmation, age limit for confirmation, catholic confirmation requirements, how to prepare for confirmation in the catholic church">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Should I Become Confirmed? - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // rito_iniciacion_cristiana_catolica.php
    case "/rito_iniciacion_cristiana_catolica.php" :
// do this code for rito_iniciacion_cristiana_catolica.php
        ?>

        <!-- Meta inserts by Lalita 2015-03-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta property="og:image"  content="https://<?php echo $uri . '/Shield-ad.jpg' ?>">
        <meta name="robots" content="index,follow,noodp,noydir">
        <meta property="og:description" content="&iquest;Est&aacute;s buscando clases de RICA cat&oacute;lica en l&iacute;nea? CatechismClass.com tiene disponible el material superior para los cursos de RICA y clases en l&iacute;nea. Inicie sus clases en l&iacute;nea hoy.">
        <meta name="robots" content="index,follow,noodp,noydir">
        <meta name="description" content="&iquest;Est&aacute;s buscando clases de RICA cat&oacute;lica en l&iacute;nea? CatechismClass.com tiene disponible el material superior para los cursos de RICA y clases en l&iacute;nea. Inicie sus clases en l&iacute;nea hoy.">
        <meta name="keywords" content="rito de iniciaci&oacute;n cristiana de adultos, clases de RICA l&iacute;nea cat&oacute;lica, clases de RICA cat&oacute;licas en l&iacute;nea, clases para adultos cristianos, Clases para ser catolica, cursos de formaci&oacute;n cat&oacute;lica">
        <meta name="robots" content="index,follow,noodp,noydir">
        <title> Cursos Cat&oacute;licos de RICA y clases en l&iacute;nea - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!---->
        <?php
        break;

    // add for Quinceanera
    case "/quinceanera_preparacion.php" :
        // do this code for /shop/series_detail.php?id=59"
        ?>

        <!-- Meta inserts by SJK 2016-07-30-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:image"  content="https://<?php echo $uri . '/Shield-ad.jpg' ?>">
        <meta property="og:description" content="CatechismClass.com Fiesta de quince a&ntilde;os Programa de Preparaci&oacute;n &eacute;poca est&aacute; dise&ntilde;ado para preparar a las ni&ntilde;as para su Fiesta de quince a&ntilde;os. Este programa se centra en la comprensi&oacute;n de la importancia de las Fiesta de quince a&ntilde;os desde una perspectiva religiosa, lo que garantiza que el alumno comprenda los fundamentos de la fe cat&oacute;lica antes de participar en este programa. La atenci&oacute;n se centra en los Sacramentos y las futuras responsabilidades post-Fiesta de quince a&ntilde;os para la ni&ntilde;a.">
        <meta name="keywords" content="clases para quince a&ntilde;os, manual de pl&aacute;ticas para quincea&ntilde;eras, quinceanera preparaci&oacute;n clase, curso para quincea&ntilde;eras, clase para la quincea&ntilde;era">
        <meta name="description" content="CatechismClass.com Fiesta de quince a&ntilde;os Programa de Preparaci&oacute;n &eacute;poca est&aacute; dise&ntilde;ado para preparar a las ni&ntilde;as para su Fiesta de quince a&ntilde;os. Este programa se centra en la comprensi&oacute;n de la importancia de las Fiesta de quince a&ntilde;os desde una perspectiva religiosa, lo que garantiza que el alumno comprenda los fundamentos de la fe cat&oacute;lica antes de participar en este programa. La atenci&oacute;n se centra en los Sacramentos y las futuras responsabilidades post-Fiesta de quince a&ntilde;os para la ni&ntilde;a.">
        <meta name="robots" content="index,follow,noodp,noydir">
        <title>Clase Quincea&ntilde;era - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /padrino_clases.php
    case "/padrino_clases.php" :
        // do this code for 'padrino_clases.php '
        ?>

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Clases Para Padres y Padrinos - CatechismClass.com">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta property="og:image"  content="https://<?php echo $uri . '/Shield-ad.jpg' ?>">
        <meta name="robots" content="index,follow,noodp,noydir">
        <meta property="og:description" content="CatechismClass.com ofrece cursos de preparaci&oacute;n para el bautismo para padrino: el programa de certificaci&oacute;n m&aacute;s popular y m&aacute;s importante del mundo disponible para la preparaci&oacute;n del bautismo. Inscr&iacute;base en el Programa de Preparaci&oacute;n de Bautismo en l&iacute;nea. Estudie para el sacramento en l&iacute;nea. ">
        <meta name="description" content="CatechismClass.com ofrece cursos de preparaci&oacute;n para el bautismo para padrino: el programa de certificaci&oacute;n m&aacute;s popular y m&aacute;s importante del mundo disponible para la preparaci&oacute;n del bautismo. Inscr&iacute;base en el Programa de Preparaci&oacute;n de Bautismo en l&iacute;nea. Estudie para el sacramento en l&iacute;nea.">
        <meta name="keywords" content="clase de bautismo, clase de bautismo cerca de m&iacute;, clase de bautismo cat&oacute;lica, preparaci&oacute;n para padres y padrinos, preparaci&oacute;n de bautismo en l&iacute;nea, bautismo de su beb&eacute;, charla de bautismo para padres y padrinos, cursillo para bautismo, padrino clases, curso de bautismo para padres y padrinos, cursos para padrinos, catequesis para bautismo en l&iacute;nea, clases para padrinos de bautismo, clases para bautismo catolico, clases de bautismo cat&oacute;lico online">
        <link  rel="canonical" href="https://<?php echo $uri; ?>">
        <title>Clases Para Padres y Padrinos - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /baptism_older_children.php
    case "/baptism_older_children.php" :
        // do this code for /baptism_older_children.php
        ?>

        <!-- Meta inserts by SJK(MP) 2022-06-29 -->  
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Baptism of Older Children Class - Ages 7 and Older">
        <meta property="og:description" content="Children ages of 7 or older typically will take part in RCIC classes to receive the Sacrament of Baptism and enter the Catholic Church.">
        <meta name="description" content="Children ages of 7 or older typically will take part in RCIC classes to receive the Sacrament of Baptism and enter the Catholic Church.">
        <meta name="keywords" content="baptism of older children, catholic baptism of older children, older child baptism, baptism of adolescent child, hispanic teenage baptism, teenage baptism class">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Baptism of Older Children Class - Ages 7 and Older</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /godparentclass.php
    case "/godparentclass.php" :
        // do this code for 'godparentclass.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online Godparent Classes - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers Baptism Preparation Courses for Parents and God Parents: The Worlds leading and most popular Certificate Program available for Baptism Preparation. Enroll in the Online Baptism Preparation Program. Study for the Sacrament Online. ">
        <meta name="description" content="CatechismClass.com offers Baptism Preparation Courses for Parents and God Parents: The Worlds leading and most popular Certificate Program available for Baptism Preparation. Enroll in the Online Baptism Preparation Program. Study for the Sacrament Online.">
        <meta name="keywords" content="baptism class, baptism class Catholic, godparent preparation, godparent classes, catholic godparent classes, catholic godparent certificate, become a godparent">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online Godparent Classes - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->


        <?php
        break;

    // //sponsorclass.php
    case "/sponsorclass.php" :
        // do this code for '/sponsorclass.php'
        ?>  

        <!-- Meta inserts by SJK 2019-01-25-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online Confirmation Sponsor Classes - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com has put together an online course that is meant to prepare Confirmation Sponsors for this honor and responsibility. Enroll in the Online Confirmation Sponsor Preparation Program.">
        <meta name="description" content="CatechismClass.com has put together an online course that is meant to prepare Confirmation Sponsors for this honor and responsibility. Enroll in the Online Confirmation Sponsor Preparation Program.">
        <meta name="keywords" content="Confirmation Sponsor Class, confirmation sponsor workshop, confirmation sponsor seminar, confirmation sponsor formation, confirmation sponsor preparation, catholic confirmation sponsor, catholic confirmation sponsor requirements">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online Confirmation Sponsor Classes - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // ///communion_padrino.php
    case "/communion_padrino.php" :
        // do this code for '/communion_padrino.php'
        ?>  

        <!-- Meta inserts by SJK 2019-01-25-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online First Communion Sponsor Classes - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com has put together an online course to prepare godparents for a First Holy Communion. Enroll today. Certificate of Completion Available.">
        <meta name="description" content="CatechismClass.com has put together an online course to prepare godparents for a First Holy Communion. Enroll today. Certificate of Completion Available.">
        <meta name="keywords" content="godparents for first communion, godparents for first holy communion, godparents for first communion class, padrinos for First Communion, first communion godparents, godparents for first communion class, godparent for first communion, clases para padrinos de primera comunion">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online First Communion Sponsor Classes - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /catholic_speakers.php
    case "/catholic_speakers.php" :
        // do this code for 'catholic_speakers.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-05-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Speakers - CatechismClass.com">
        <meta property="og:description" content="Invite CatechismClass.com Authors to Speak at Your Parish.">
        <meta name="description" content="Invite CatechismClass.com Authors to Speak at Your Parish.">
        <meta name="keywords" content="catholic speakers, catholic parish talks">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Catholic Speakers - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /catholic-rcic-children-classes.php
    case "/catholic-rcic-children-classes.php" :
        // do this code for 'catholic-rcic-children-classes.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Rite of Christian Initiation for Children (RCIC) Classes">
        <meta property="og:description" content="RCIC is the process for children to be baptized and enter the Catholic Church who have not been baptized as an infant but are now passed the age of reason and know right and wrong. Study RCIC online.">
        <meta name="description" content="RCIC (Rite of Christian Initiation of Children) Online Course Available Conveniently Through CatechismClass.com.">
        <meta name="keywords" content="catholic rcic, RCIA for children, catholic rcic program, rcic curriculum, rcic catholic, rcic catholic church, rcic teaching materials, rcic lesson plans, rcic resources, Children's Catechumenate resources, rcic class, children rcia resources, rcia for children, what is rcic, Rite of Christian Initiation for Children and Teens ">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Rite of Christian Initiation for Children (RCIC) Online Classes</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /catholic_bible_study.php
    case "/catholic_bible_study.php" :
        // do this code for '/catholic_bible_study.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online Traditional Catholic Bible Study  - CatechismClass.com">
        <meta property="og:description" content="Catholics should engage in daily Scripture reading and they should study the Scriptures using approved traditional Catholic commentary in order to better understand the meanings in them. For that, we are here to help.">
        <meta name="description" content="Bible Study refers to the study of the Holy Scriptures. While the term is often used by Protestants, Catholics should engage in daily Scripture reading and they should study the Scriptures in order to better understand the meanings in them.">
        <meta name="keywords" content="catholic bible study online, traditional catholic bible study, catholic bible study programs, catholic bible study, catholic bible studies for adults, roman catholic bible study, catholic scripture study, catholic scripture study online, catholic scripture commentary online ">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online Catholic Bible Study  - CatechismClass.com </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /abortion_contraception.php
    case "/abortion_contraception.php" :
        // do this code for '/abortion_contraception.php'
        ?>  

        <!-- Meta inserts by SJK 2018-08-08-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online Catholic Natural Family Planning Class - CatechismClass.com">
        <meta property="og:description" content="CatechismClass.com offers an entirely online Natural Family Planning Class meant to prepare Catholics to understand the importance of life and the Church's timeless and science-supported position on the dignity of human life and the role of Natural Family Planning plays in the life of Catholic couples.">
        <meta name="description" content="CatechismClass.com offers a Natural Family Planning Class meant to prepare today's Catholics to under the importance of life and the role of Natural Family Planning in the life of Catholic couples.">
        <meta name="keywords" content="online NFP class, NFP classes online, Natural Family Planning Class, catholic nfp class, online Catholic natural family planning">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>NFP Classes</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // /catholic_teacher_resources.php
    case "/catholic_teacher_resources.php" :
// do this code for '/catholic_teacher_resources.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2020-01-28-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catholic Teacher Resources - CatechismClass.com">
        <meta property="og:description" content="The Best Online Resources for Catholic Teachers, CCD instructors, and Catholic Educators of All Ages.">
        <meta name="description" content="The Best Online Resources for Catholic Teachers, CCD instructors, and Catholic Educators of All Ages.">
        <meta name="keywords" content="resources for catholic teachers, resources for catholic educators, resources for catholic religion teachers, resources for catholic catechists, Certificate in Teaching Religion">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Catholic Teacher Resources</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /baptism_classes.php
    case "/baptism_classes.php" :
        // do this code for '/baptism_classes.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2020-06-18-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="What is a Baptism Class? - CatechismClass.com">
        <meta property="og:description" content="Baptismal classes are classes for parents and godparents to take before a Baptism so they understand the importance of Baptism and their role in this live-giving Sacrament.">
        <meta name="description" content="Baptismal classes are classes for parents and godparents to take before a Baptism so they understand the importance of Baptism and their role in this live-giving Sacrament.">
        <meta name="keywords" content="baptismal classes, what is a baptism class, what is baptism class">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>What is a Baptism Class?</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /catholic_baptism_requirements.php
    case "/catholic_baptism_requirements.php" :
        // do this code for '/catholic_baptism_requirements.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2020-06-18-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="What Are the Requirements for A Catholic Baptism?  - CatechismClass.com">
        <meta property="og:description" content="What are the Requirements for a Catholic Baptism? Infant Baptisms and Adult Baptisms have different requirements. Godparents have separate guidelines as well.">
        <meta name="description" content="What are the requirements for a Catholic Baptism? Infant Baptisms and Adult Baptisms have different requirements. Godparents have separate guidelines as well.">
        <meta name="keywords" content="Catholic baptism requirements, Catholic infant baptism requirements, godparent requirements, catholic baptism requirements for godparents, catholic baptism requirements adults, catholic church infant baptism requirements, catholic godparent requirements, can I be a godparent, catholic baptism requirements for godparents">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>What Are the Requirements for A Catholic Baptism? </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /cert_requirements.php
    case "/cert_requirements.php" :
        // do this code for '/cert_requirements.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2020-01-28-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Certificate Requirements - CatechismClass.com">
        <meta property="og:description" content="The goal of CatechismClass.com is to help all students pass and we will do everything in our power to help our students pass and receive a certificate of completion.">
        <meta name="description" content="The goal of CatechismClass.com is to help all students pass and we will do everything in our power to help our students pass and receive a certificate of completion.">
        <meta name="keywords" content="certificate requirements for catechismclass.com">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Certificate Requirements - CatechismClass.com</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /why-do-you-baptize-a-baby.php
    case "/why-do-you-baptize-a-baby.php" :
// do this code for '/why-do-you-baptize-a-baby.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2021-12-10-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Why Do You Baptize A Baby">
        <meta property="og:description" content="It is essential to baptize your baby to remove original sin from his soul, restore him to God's friendship, allow God to dwell in Him, restore sanctifying grace in the soul, and make Heaven possible.">
        <meta name="description" content="It is essential to baptize your baby to remove original sin from his soul, restore him to God's friendship, allow God to dwell in Him, restore sanctifying grace in the soul, and make Heaven possible.">
        <meta name="keywords" content="why do you baptize a baby, why is it important to baptize a baby, why should i baptize my baby, why do catholics get baptize as baby, why baptize a baby">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <link rel="icon" type="image/x-icon" href="Shield.jpg">
        <title>Why Do You Baptize A Baby</title>
        <!-- -->

        <?php
        break;

// /my_prayers.php
    case "/my_prayers.php" :
// do this code for '/my_prayers.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2022-01-17-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Latin Prayer Audio Pronunciation">
        <meta property="og:description" content="Listen to Traditional Catholic Prayers in Latin and English. ">
        <meta name="description" content="Listen to Traditional Catholic Prayers in Latin and English with audio pronunciation.">
        <meta name="keywords" content="ave maria latin prayer, latin prayer, latin prayer for the dead, latin prayer for protection, catholic latin prayer, latin prayer prunciation, latin audio prayers, latin prayers catholic, easy catholic prayers">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /letter_to_the_bishop_for_confirmation_template.php
    case "/letter_to_the_bishop_for_confirmation_template.php" :
// do this code for '/letter_to_the_bishop_for_confirmation_template.php'
        ?>

        <!-- Meta inserts by SJK(MP) 2022-01-18-->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="How to Write A Letter to the Bishop for Confirmation">
        <meta property="og:description" content="How to Write A Letter to the Bishop for Confirmation.">
        <meta name="description" content="Downloadable Template for How to Write A Bishop For Confirmation.">
        <meta name="keywords" content="letter to the bishop for confirmation template, letter to the bishop for confirmation, letter to the bishop for confirmation outline, confirmation letter to the bishop examples, letter to the bishop for confirmation format, sample confirmation letter to the bishop, confirmation letter to the bishop format, confirmation candidate letter to bishop">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>How to Write A Letter to the Bishop for Confirmation</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /what_do_catholics_really_believe.php
    case "/what_do_catholics_really_believe.php" :
        // do this code for '/what_do_catholics_really_believe.php
        ?>

        <!-- Meta inserts by SJK(MP) 2022-01-25 -->
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="What Do Catholics Really Believe?">
        <meta property="og:description" content="What Do Catholics Really Believe?">
        <meta name="description" content="Learn what Catholics really believe from the experts in Catholicism.">
        <meta name="keywords" content="what do catholics really believe, what do catholics believe, what do catholics believe about salvation, what do catholics believe about death, what do catholics believe about jesus, what do catholics believe about purgatory, what do catholics believe about baptism, what do catholics believe about the pope, what do catholics believe about God">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>What Do Catholics Really Believe?</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /catholic_first_communion_classes.php
    case "/catholic_first_communion_classes.php" :
        // do this code for /catholic_first_communion_classes.php
        ?>

        <!-- Meta inserts by SJK(MP) 2022-05-16 -->    
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online First Holy Communion Classes">
        <meta property="og:description" content="Catholic First Communion Classes prepare anyone studying for their First Holy Communion. Both adults and children can conveniently take Catholic First Communion classes online.">
        <meta name="description" content="Catholic First Communion Classes prepare anyone studying for their First Holy Communion. Both adults and children can conveniently take Catholic First Communion classes online.">
        <meta name="keywords" content="first communion classes online, first communion for adults program, adult first communion classes, catholic first communion classes, catholic first communion classes for adults online, online first communion classes, online catholic communion classes, catholic first communion online, First Communion Class with Certificate of Completion, online first communion classes, first holy communion classes, first communion online, online ccd for communion">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online First Communion Classes - Certificate of Completion </title>" 
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// /catholic_first_confession_classes.php
    case "/catholic_first_confession_classes.php" :
        // do this code for /catholic_first_confession_classes.php
        ?>

        <!-- Meta inserts by SJK(MP) 2022-05-16 -->  
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Online First Confession Classes">
        <meta property="og:description" content="Catholic First Confession Classes prepare anyone studying to make their First Confession. Both adults and children can conveniently take Catholic First Reconciliation classes online.">
        <meta name="description" content="Catholic First Confession Classes prepare anyone studying to make their First Confession. Both adults and children can conveniently take Catholic First Reconciliation classes online.">
        <meta name="keywords" content="first confession class, first confession classes online, what is a first confession, making first confession, what do you say at first confession, first confession for rcia candidate, first confession for children, first confession for adults, first reconciliation preparation, preparing for first reconciliation catholic, is first reconciliation the same as first communion, catechism first reconciliation, first reconciliation class">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Online First Reconciliation Classes - Certificate of Completion </title>" 
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// do this for /eucharistic-revival-lessons.php
    case "/eucharistic-revival-lessons.php" :
// 
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="National Eucharistic Revival Catechism Lessons">
        <meta property="og:description" content="CatechismClass.com has put together a series of resources for adult and children religious education in honor of the National Eucharistic Revival.">
        <meta name="description" content="CatechismClass.com has put together a series of resources for adult and children religious education in honor of the National Eucharistic Revival.">
        <meta name="keywords" content="national eucharistic revival, eucharistic revival initiative, eucharistic revival catechism, usccb eucharistic revival, eucharistic revival">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> National Eucharistic Revival Catechism Lessons </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// do this for /traditional-catholic-catechism-online.php
    case "/traditional-catholic-catechism-online.php" :
// 
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Traditional Catholic Catechism Study Course">
        <meta property="og:description" content="CatechismClass.com offers online traditional Catholic catechism courses for both adults and children.">
        <meta name="description" content="CatechismClass.com offers online traditional Catholic catechism courses for both adults and children.">
        <meta name="keywords" content="traditional catechism, traditional catholic catechism for children, traditional catholic catechism for adults, traditional catholic catechism, traditional catholic catechism education online, traditional catholic catechism online">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Traditional Catholic Catechism Study Course </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- --> 

        <?php
        break;

// /clase_para_primera_comunion.php
    case "/clase_para_primera_comunion.php" :
        // do this code for /clase_para_primera_comunion.php
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catecismo Para Primera Comuni&oacute;n">
        <meta property="og:description" content="Estudio para la Primera Comuni&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta name="description" content="Estudio para la Primera Comuni&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta name="keywords" content="clases de catecismo para primera comuni&oacute;n en l&iacute;nea, clases de catecismo para primera comuni&oacute;n, catecismo para primera comuni&oacute;n, clases de catecismo para primera comunion, catecismo para niños">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Catecismo Para Primera Comuni&oacute;n en L&iacute;nea </title>" 
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

// / clase_para_primera_confesion
    case "/clase_para_primera_confesion.php" :
        // do this code for /clase_para_primera_confesion
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Catecismo Para Primera Confesi&oacute;n">
        <meta property="og:description" content="Estudio para la Primera Confesi&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta name="description" content="Estudio para la Primera Confesi&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta name="keywords" content="clases de catecismo para primera confesi&oacute;n en l&iacute;nea, clases de catecismo para primera confesi&oacute;n">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <!--<title> "Catecismo Para Primera Confesi&oacute;n en L&iacute;nea "</title> -->
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // / confirmacion_catolica_en_linea
    case "/confirmacion_catolica_en_linea.php" :
        // do this code for /confirmacion_catolica_en_linea
        ?>

        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:title" content="Confirmaci&oacute;n Cat&oacute;lica en L&iacute;nea">
        <meta property="og:description" content="Estudio para Confirmaci&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta property="og:type" content="website">
        <meta name="description" content="Estudio para Confirmaci&oacute;n en l&iacute;nea. Clases convenientes. Certificado de finalizaci&oacute;n disponible. Muy asequible.">
        <meta name="keywords" content=" confirmaci&oacute;n cat&oacute;lica en l&iacute;nea,  confirmaci&oacute;n en l&iacute;nea, clases de confirmaci&oacute;n en l&iacute;nea">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Clases Para Confirmaci&oacute;n Cat&oacute;lica en L&iacute;nea </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // / /why-catechism-is-important.php
    case "/why-catechism-is-important.php" :
        // do this code for //why-catechism-is-important.php
        ?>
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Why Is The Catechism Important?">
        <meta property="og:description" content="It is vitally important to know the Catechism (i.e., the Church's teachings) because we can only be saved if we are baptized Catholics who die in the state of grace. To remain a Catholic in the state of grace, we must believe all of the dogmas which Christ teaches through His established Church. And we can only believe them if we know them.">
        <meta name="description" content="It is vitally important to know the Catechism (i.e., the Church's teachings) because we can only be saved if we are baptized Catholics who die in the state of grace. To remain a Catholic in the state of grace, we must believe all of the dogmas which Christ teaches through His established Church. And we can only believe them if we know them.">
        <meta name="keywords" content="why is the catechism important, why study catechism, why study religion, why study catholicism, why catechism is important, why catechism class, why study the bible">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> Why Is Catechism Important </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // / /process-of-becoming-catholic.php
    case "/process-of-becoming-catholic.php" :
        // do this code for //process-of-becoming-catholic.php
        ?>
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Process of Becoming Catholic">
        <meta property="og:description" content="What is the Process to Become Catholic? Learn how you can become a Catholic.">
        <meta name="description" content="What is the Process to Become Catholic? Learn how you can become a Catholic.">
        <meta name="keywords" content="Process of becoming Catholic, become catholic, becoming catholic, how to become a catholic, how to become catholic, how to become catholic online, how do i become catholic, joining the catholic church, should I become Catholic, i want to become catholic, converting to catholicism">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title>Process of Becoming Catholic</title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->

        <?php
        break;

    // / how-do-i-get-married-catholic.php
    case "/how-do-i-get-married-catholic.php" :
        // do this code for //how-do-i-get-married-catholic.php
        ?>
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="How To Get Married in the Catholic Church" />
        <meta property="og:description" content="The 4 Easy Steps to Get Martied Married in the Catholic Church">
        <meta name="description" content="The 4 Easy Steps to Get Martied Married in A Catholic Church">
        <meta name="keywords" content="how to get married in the catholic church, how do i get married in the catholic church, how to get married catholic church, how do you get married in the eyes of god, how to get married in a catholic church">
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <title> How to Get Married in A Catholic Church </title>
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">
        <!-- -->


        <?php
        break;

    // default
    default :
        ?>
        <link  rel="canonical" href="https://<?php echo $uri . $url; ?>">
        <meta property="og:url" content="https://<?php echo $uri . $url; ?>">
        <meta property="og:type" content="website">
        <link rel="icon" type="image/x-icon" href="/Shield.jpg">

<?php }  // end switch
?>

