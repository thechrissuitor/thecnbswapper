<!-- ######################     Footer  #################################### -->
<footer class="footer navbar-fixed-bottom">
    <section class="row divFooter">
        <h3 class="hide">f</h3>
        <figure class="col-sm-3 text-center">
            <?php
            if ($path_parts['dirname'] == "/cs148/thecnbswapper/require_login") {
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
                <?php
                if ($path_parts['dirname'] == "/cs148/thecnbswapper/require_login") {
                    print '<li><a href="../index.php">Home</a></li>';
                    print '<li><a href="apply.php">Create Request</a></li>';
                    print '<li><a href="../explore.php">Explore</a></li>';
                    print '<li><a href="matches.php">Matches</a></li>';
                    print '<li><a href="../trinity.php">Trinity</a></li>';
                    print '<li><a href="../central.php">Central</a></li>';
                    print '<li><a href="../athletic.php">Athletic</a></li>';
                    print '<li><a href="../redstone.php">Redstone</a></li>';
                    
                    if($isAdmin){
                        print '<li><a href="../tables.php">Tables</a></li>';
                    }
                
                }else{
                    print '<li><a href="index.php">Home</a></li>';
                    print '<li><a href="require_login/apply.php">Create Request</a></li>';
                    print '<li><a href="explore.php">Explore</a></li>';
                    print '<li><a href="require_login/matches.php">Matches</a></li>';
                    print '<li><a href="trinity.php">Trinity</a></li>';
                    print '<li><a href="central.php">Central</a></li>';
                    print '<li><a href="athletic.php">Athletic</a></li>';
                    print '<li><a href="redstone.php">Redstone</a></li>';
                    print '<li><a href="tables.php">Tables</a></li>';
                    if($isAdmin){
                        print '<li><a href="tables.php">Tables</a></li>';
                    }
                }
                ?>
            </ul>
        </article>
        
        <article class="col-sm-4">
            <h4 class="text-center">About CNB Swapper</h4>
            <p class="desctiption">The CNB Swapper is web application designed for UVM underclassmen
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
        <span>&COPY; Copyrights. All Rights Reserved</span>
</footer>