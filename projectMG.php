<?php

include("inc/portfolioMG.php");
include("inc/header.php");
?>

    <section class="first feature">
        <div class="third">
            <?php echo $featuretext; ?>
        </div>
        <div class="twothird">
            <?php echo $featureimgs; ?>
        </div>
    </section>
    <section class="smallthumbs">
        <?php echo $thumbs; ?>
        
    </section>   
<section class="subnav">
<!--    <a href="home.php" class="getmore">view all work</a>-->
        <a href="home.php#Web & UI">Web</a>
        <a href="home.php#Identity">Identity</a>
        <a href="home.php#Print">Print</a>
        <a href="home.php#Photography">Photos</a>
        <a href="home.php#Art & Illustration">Art</a>
    </section>

<?php

include("inc/footer.php");
?>