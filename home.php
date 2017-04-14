<?php
$homeactive = "active";
$aboutactive = "";

include("inc/portfolio.php");
include("inc/header.php");

?>
<link rel="stylesheet" href="css/animate.css">
    <section class="first">
        <h1 class="animated fadeInLeft"><span class="animated fadeIn tagA">Respond. </span><span class="animated fadeIn tagB">Adapt. </span><span class="animated fadeIn tagC">Develop. </span><span class="animated bounceInUp tagD">Delight.</span></h1>
    </section>
    <section class="subnav">
        <a href="#Web & UI">Web</a>
        <a href="#Identity">Identity</a>
        <a href="#Print">Print</a>
        <a href="#Photography">Photos</a>
        <a href="#Art & Illustration">Art</a>
    </section>
    <section class="thumbs">
        <?php echo $thumbs; ?>
    </section>
<?php

include("inc/footer.php");
