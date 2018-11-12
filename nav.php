<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // Repeat this if block for each menu item 
        // designed to give the current page a class but also allows
        // you to have more classes if you need them
        print '<li class="';
        if ($path_parts['filename'] == "index") {
            print ' activePage ';
        }
        print '">';
        print '<a href="index.php">Home</a>';
        print '</li>';

        /* example of repeating */
        print '<li class="';
        if ($path_parts['filename'] == "form") {
            print ' activePage ';
        }
        print '">';
        print '<a href="form.php">Form</a>';
        print '</li>';
        ?>
    </ol>
</nav>