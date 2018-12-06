<!-- ######################     Main Navigation   ########################## -->
<nav class="navbar sticky-top navbar-expand-lg navbar-light">
    <!-- When the page resizes for a dropdown button -->
    <?php
    if ($path_parts['dirname'] == "/cs148/dev-final-project/require_login") {
    ?>
    <a href="index.php"><img src="../images/logo.png" alt=""></a>
    <?php
    }  else {
    ?>
    <a href="index.php"><img src="images/logo.png" alt=""></a>
    <?php
    }
    
    ?>
    <button class="navbar-toggler float-right" type="button" 
            data-toggle="collapse" data-target="#navbarNavDropdown" 
            aria-controls="navbarNavDropdown" aria-expanded="false" 
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- End of dropdown button clicker -->

    <!-- Ask BOB -->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ol class="navbar-nav justify-content-center">
            <?php
            // Repeat this if block for each menu item 
            // designed to give the current page a class but also allows
            // you to have more classes if you need them
            /*Home*/
            if ($path_parts['dirname'] == "/cs148/dev-final-project/require_login") {
                print '<li class="';
                if ($path_parts['filename'] == "index") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>';
                print '</li>';
                
                
                /*Apply*/
                print '<li class="';
                if ($path_parts['filename'] == "apply") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="apply.php">Create Request</a>';
                print '</li>';
                
                /*Explre*/
                print '<li class="';
                if ($path_parts['filename'] == "explore") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../explore.php">Explore</a>';
                print '</li>';

                /*Trinity*/
                print '<li class="';
                if ($path_parts['filename'] == "trinity") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../trinity.php">Trinity</a>';
                print '</li>';

                /*Central*/
                print '<li class="';
                if ($path_parts['filename'] == "central") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../central.php">Central</a>';
                print '</li>';

                /*Athletics*/
                print '<li class="';
                if ($path_parts['filename'] == "athletic") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../athletic.php">Athletic</a>';
                print '</li>';

                /*Redstone*/
                print '<li class="';
                if ($path_parts['filename'] == "redstone") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="../redstone.php">Redstone</a>';
                print '</li>';
                
                
            } else {
                print '<li class="';
                if ($path_parts['filename'] == "index") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>';
                print '</li>';

                /*Put your dorm on the market*/
                print '<li class="';
                if ($path_parts['filename'] == "apply") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="require_login/apply.php">Create Request</a>';
                print '</li>';
                
                /*Explre*/
                print '<li class="';
                if ($path_parts['filename'] == "explore") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="explore.php">Explore</a>';
                print '</li>';

                /*Trinity*/
                print '<li class="';
                if ($path_parts['filename'] == "trinity") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="trinity.php">Trinity</a>';
                print '</li>';

                /*Central*/
                print '<li class="';
                if ($path_parts['filename'] == "central") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="central.php">Central</a>';
                print '</li>';

                /*Athletics*/
                print '<li class="';
                if ($path_parts['filename'] == "athletic") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="athletic.php">Athletics</a>';
                print '</li>';

                /*Redstone*/
                print '<li class="';
                if ($path_parts['filename'] == "redstone") {
                    print ' activePage ';
                }
                print ' nav-item">';
                print '<a class = "nav-link" href="redstone.php">Redstone</a>';
                print '</li>';
                
                }
            ?>
        </ol>
    </div>
</nav>