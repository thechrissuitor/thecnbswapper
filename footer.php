<!-- ######################     Footer  #################################### -->
<footer class="footer navbar-fixed-bottom">
    <section class="row divFooter">
        <figure class="col-sm-4 text-center">
            <?php
            if ($path_parts['dirname'] == "/cs148/dev-final-project/require_login") {
            ?>
            <img class="fLogo" src="../images/fLogo.png" alt="">
            <?php
            }else{
            ?>
            <img class="fLogo" src="images/fLogo.png" alt="">
            <?php
            }
            ?>
        </figure>
        <article class="col-sm-4 text-center">
            <h4 class="">Links</h4>
            <ul class="fNav">
                <li><a href="index.php">Home</a></li>
                <li><a href="require_login/apply.php">Create Request</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="trinity.php">Trinity</a></li>
                <li><a href="central.php">Central</a></li>
                <li><a href="athletic.php">Athletic</a></li>
                <li><a href="redstone.php">Redstone</a></li>
            </ul>
        </article>
        
        <article class="col-sm-4">
            <h4 class="text-center">About CNB Swapper</h4>
            <p class="">The CNB Swapper is web application designed for UVM underclassmen
                to swap dorms with another student on campus. The student fills 
                out our form application, and their application is sent to our 
                explore page. From here, users can view all swap postings. 
                If they see a dorm they would be interested in swapping into, 
                they can select that dorm and request a swap. For a match to be 
                made, the two users have to request each other's posting. 
                When that occurs, an email message is sent to both users 
                notifying them that a match has been made.</p>
        </article>
    </section>
	<hr>
 	<figure class="row">
            <p>Contact Us:</p>
		<a href="https://fb.com" class="fa fa-facebook"></a>
		<a href="https://instagram.com" class="fa fa-instagram"></a>
                <a href="https://snapchat.com" class="fa fa-snapchat-ghost"></a>
                <a href="https://linkedin.com" class="fa fa-linkedin"></a>
                <a href="https://google.com" class="fa fa-google"></a>
	</figure>
        <span>&COPY;Copyrights. All Rights Reserved</span>
</footer>