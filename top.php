<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CNB Swapper</title>
        <meta charset="utf-8">
        <meta name="author" content="Christopher Suitor, Nana Nimako, Ben Sylvester">
        <meta name="description" content="CS148A Final Project: The CNB Swapper">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->

        <!-- Bootstrap  Link -->
        <!-- Bootstrap Received from: https://getbootstrap.com/docs/4.1/getting-started/introduction/ -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
                 <!-- Bootstrap core CSS -->
                <link href="css/bootstrap.min.css" rel="stylesheet">
                <!-- Material Design Bootstrap -->
                <link href="css/mdb.min.css" rel="stylesheet">
                <!-- Your custom styles (optional) -->
                <link href="css/style.css" rel="stylesheet">
    
    
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
		<!-- End Bootstrap  Link -->

		<!-- Footer Icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!-- End of Footer Icons -->

        <?php
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // PATH SETUP
        $domain = '//';
        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');
        $domain .= $server;
        if ($debug) {
            print '<p>php Self: ' . $phpSelf;
            print '<pdomain: ' . $domain;
            print '<p>Path Parts<pre>';
            print_r($path_parts);
            print '</pre></p>';
        } 
        
        // get username from the server of whoever logged in
        $adminUsername = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
        if($adminUsername == 'csuitor' || $adminUsername == 'rerickso' || $adminUsername == 'bduval' || $adminUsername == 'ckweston'
                || $adminUsername == 'nnimako' || $adminUsername == 'bsylvest'){
            $isAdmin = true;
        } else {
            $isAdmin = false;
        }
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // inlcude all libraries. 
        // 
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        if ($path_parts['dirname'] == "/cs148/thecnbswapper/require_login") { //if statement to change file paths for if a file is in the require_login folder
            
            // WE HAVE TO USE IF-STATEMENTS FOR ANYTHING USING FILE PATHS BECAUSE
            // WE HAVE FILES IN THE require_login FOLDER

            ?>
            
            <link rel="stylesheet" href="../css/custom.css" type="text/css" media="screen">
            
            <!-- Begin of Slider -->
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
            <script src="../js/jquery.flexslider.js"></script>
            
            <?php
                    
            print '<!-- begin including libraries -->';
        
            include '../lib/constants.php';

            include LIB_PATH . '/Connect-With-Database.php';

            require_once '../lib/security.php';

            include_once '../lib/validation-functions.php';  

            include_once '../lib/mail-message.php';   

            print '<!-- libraries complete-->';
            
            
        } else { //end if, start else
            
        ?>
            
        <link rel="stylesheet" href="css/custom.css" type="text/css" media="screen">    
        <?php
        
        print '<!-- begin including libraries -->';
        
        include 'lib/constants.php';

        include LIB_PATH . '/Connect-With-Database.php';
        
        require_once 'lib/security.php';
        
        include_once 'lib/validation-functions.php';  
        
        include_once 'lib/mail-message.php';   

        print '<!-- libraries complete-->';
        
        } //end else
        ?>	

    </head>

    <!-- **********************     Body section      ********************** -->
    <?php
    print '<body id="' . $PATH_PARTS['filename'] . '">';
    
    if ($path_parts['dirname'] == "/cs148/thecnbswapper/require_login") {
        include '../nav.php';
//        include '../header.php';
    } else {
        include 'nav.php';
//        include 'header.php';
    }
    
    if ($debug) {
    print '<p>DEBUG MODE IS ON</p>';
    }
    
    print "<!-- End of top.php -->";

    ?>