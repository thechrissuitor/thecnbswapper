<?php
include "top.php";
?>
<main class="container-fluid">
	<!-- The Slider goes Here -->
    <article class="row" id="slideShow">
    	<div id="carouselExampleIndicators" class="carousel slide row sliderImg" data-ride="carousel">
    		<ul class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
    		</ul>
            <div class="carousel-inner">
                <figure class="carousel-item active">
                    <img class="d-block w-100" src="images/indexImg/mcAuley_outside.JPG" alt="">
                </figure>

                <?php
                $folder = "images/indexImg";
                $pictures = scandir($folder);
                foreach ($pictures as $picture) {
                    if (is_file($folder . '/' . $picture)) {
                        echo '<figure class="carousel-item">';
                        echo '<img class="d-block w-100" src="'. $folder . '/' . $picture . '" alt="">';
                        echo '</figure>';
                    }
                }
                ?>
            </div>

            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>    
    	</div>
        <p class="mainMenu text-center">
            <a href="explore.php" class="hExplore"> Explore </a> | <a href="require_login/apply.php" class="hApply">Swap</a>
        </p>
    </article>

	<!--  The Slider ends here -->
</main>

<?php include "footer.php"; ?>
</body>
</html>