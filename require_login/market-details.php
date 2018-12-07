<?php
include "../top.php";

if(isset($_GET['dormId'])){
$dormId = (int) htmlentities($_GET['dormId'], ENT_QUOTES, "UTF-8");
}

$query = "SELECT pmkUserDormId, pmkStudentId, fldFirstName, fldLastName, fldClassStanding, ";
$query .= "fldHall, fldDormStyle, fldRoomNumber, fldRoommates, fldDescription, ";
$query .= "fldImagePath FROM tblDorms JOIN tblStudentInfo ON fnkStudentId = pmkStudentId ";
$query .= "JOIN tblHalls ON fnkHallId = pmkHallId JOIN tblUserImages ON fnkImageId = pmkImageId";
$query .= " WHERE pmkUserDormId = " . $dormId;
	
if ($thisDatabaseReader->querySecurityOk($query)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, '');
}
?>


<!--src:http://getbootstrap.com/docs/4.1/components/card/-->
<main>
	<?php
	if (is_array($records)) {
            foreach($records as $record) {
                    $fullName = $record['fldFirstName'] . ' ' . $record['fldLastName']; //this is the student's full name (first and last)
                    //$theImg = str_replace('../', , subject)$record['fldImagePath']; // THIS IS A STRING. The image path (the location) of the image in the server
                    $theImg = $record["fldImagePath"];
                    print '<figure class="row studentPost">';
                    print '<img class="col-sm-6" src="' . $theImg . '" alt="">';
                    print '<article class="card-body">';
                    print '<h3>Post</h3>';
                    print '<p class= "card-text"> <strong> Name: </strong> '. $fullName .'<br>';
                    print '<strong> Hall: </strong>' . $record['fldHall'] . '<br>';
                    print '<strong> Room Number: </strong>' . $record['fldRoomNumber'] .'<br>';
                    print '<strong> Dorm Style: </strong>' . $record['fldDormStyle'] . '<br>';
                    print '<strong> Description: </strong>' . $record['fldDescription'] . '<br>';
                    print '</p>';
                    print '</article>';
                    print '</figure>';
//			$record['fldClassStanding'] //this is the student's class standing (i.e. Sophomore)
//			$record['fldHall'] //THIS IS A STRING. The name of the hall (i.e. McAuley)
//			$record['fldDormStyle'] //this the style of their dorm (i.e. suite or traditional)
//			$record['fldRoomNumber'] //student's room number (this is a string)
//			$record['fldRoommates'] //the integer number of roomates
//			$record['fldDescription'] //the comments that the user typed in the form
//			$record['fldImagePath'] // THIS IS A STRING. The image path (the location) of the image in the server
            }
	}
        
        // ************************** START THE FORM PORTION OF THIS PAGE *****************************************
        //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//       
print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       
// These variables are used in both sections 2 and 3, otherwise we would
// declare them in the section we needed them

print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
// We print out the post array so that we can see our form is working.
// Normally i wrap this in a debug statement but for now i want to always
// display it. when you first come to the form it is empty. when you submit the
// form it displays the contents of the post array.
 if (DEBUG){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
 }

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
//
// Initialize variables one for each form element
// in the order they appear on the form

$username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
// $dormId declared earlier

// GRAB THE INFORMATION FOR THE CURRENT STUDENT
$studentOneId = '';
// SELECT QUERY TO GRAB CURRENT USER'S ID
$userOneQuery = "SELECT pmkStudentId FROM tblStudentInfo WHERE fldNetIdUsername LIKE ?";
$queryData[] = $username;
// SEND SELECT QUERY
// we use the writer for the select query so that we can send the data
if ($thisDatabaseWriter->querySecurityOk($userOneQuery, 1, 0, 0, 0, 0)) {
    $userOneQuery = $thisDatabaseReader->sanitizeQuery($userOneQuery);
    $userOneId = $thisDatabaseWriter->select($userOneQuery, $queryData);
}
if(is_array($userOneId)){
    foreach($userOneId as $userOne){
            $studentOneId = $userOne['pmkStudentId'];
    }
}

// GRAB THE INFORMATION OF THE STUDENT REQUESTED
$studentTwoId = '';
$studentTwoDorm = '';
if(is_array($records)){
    foreach($records as $record){
        $studentTwoId = $record['pmkStudentId'];
        $studentTwoDorm = $record['pmkUserDormId'];
    }
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;
//
// Initialize Error Flags one for each form element we validate
// in the order they appear on the form
$usernameERROR = false;       
$dormIdERROR = false;
$studentOneIdERROR = false;
$studentTwoIdERROR = false;
$studentTwoDormERROR = false;

////%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();       
 
// have we mailed the information to the user, flag variable?
$mailed = false;       

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
print PHP_EOL . '<!-- SECTION: 2 Process for when the form is submitted -->' . PHP_EOL;
//
if (isset($_POST["btnSwap"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2a Security -->' . PHP_EOL;
    
    // the url for this form
    $thisURL = $domain . $phpSelf;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2b Sanitize (clean) data  -->' . PHP_EOL;
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.

    $username = htmlentities($_POST["hdnUsername"], ENT_QUOTES, "UTF-8");      
        
    $dormId = htmlentities($_POST["hdnDormId"], ENT_QUOTES, "UTF-8");
    
    $studentOneId = htmlentities($_POST["hdnStudentOneId"], ENT_QUOTES, "UTF-8");
    
    $studentTwoId = htmlentities($_POST["hdnStudentTwoId"], ENT_QUOTES, "UTF-8");
    
    $studentTwoDorm = htmlentities($_POST["hdnStudentTwoDorm"], ENT_QUOTES, "UTF-8");
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2c Validation -->' . PHP_EOL;
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
    if ($username == ""){
        $errorMsg[] = "There is something wrong with your login. ERROR CODE [001]";
        $hiddenERROR = true;
    }    
    if ($dormId == ""){
        $errorMsg[] = "There is something wrong with your login. ERROR CODE [002]";
        $dormIdERROR = true;
    }  
    if ($studentOneId == ""){
        $errorMsg[] = "You must post your own dorm before you can swap. ERROR CODE [003]";
        $dormIdERROR = true;
    } 
    if ($studentTwoId == ""){
        $errorMsg[] = "The swap was unsuccessful. ERROR CODE [004]";
        $dormIdERROR = true;
    } 
    if ($studentTwoDorm == ""){
        $errorMsg[] = "The swap was unsuccessful. ERROR CODE [005]";
        $dormIdERROR = true;
    } 
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    print PHP_EOL . '<!-- SECTION: 2d Process Form - Passed Validation -->' . PHP_EOL;
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //    
    if (!$errorMsg) {
        if ($debug)
                print '<p>Form is valid</p>';
             

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2e Save Data -->' . PHP_EOL;
        //
        // This block saves the data to a CSV file.   
        
        // array used to hold form values that will be saved to the database
        $requestData = array();     
        $requestData[] = $studentOneId;
        $requestData[] = $studentTwoId;
        $requestData[] = $studentTwoDorm;
        
        // SAVE TO DATABASE HERE
        //
        // INSERT QUERY IN TBLREQUEST
        $insertQuery = 'INSERT INTO tblRequest (fnkStudentID, fnkOtherStudentId, fnkDormIdRequested) VALUES (?,?,?)';
        // SEND INSERT QUERY
        if ($thisDatabaseWriter->querySecurityOk($insertQuery, 0)) {
            $insertQuery = $thisDatabaseWriter->sanitizeQuery($insertQuery);
            $requestData = $thisDatabaseWriter->insert($insertQuery, $requestData);
        }
     
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Thank you for using the CNB Swapper.</h2>';       

            
            $message .= '<p>';
            $message .= ' We are working diligently to put you in your desired dorm ';
            $message .= '</p>';
            
            $message .= '<p><a class = "submission-reset-link" href="../explore.php">Keep Exploring</a></p>';

        
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form     
        $cc = '';       
        $bcc = '';

        $from = 'csuitor@uvm.edu';

        // subject of mail should make sense to your form
        $subject = 'The CNB Swapper';

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

    } // end form is valid     

}   // ends if form was submitted.



//#############################################################################
//
print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;
//
?>       
    <article>
<?php
    //####################################
    //
    print PHP_EOL . '<!-- SECTION 3a  -->' . PHP_EOL;
    // 
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    
    if (isset($_POST["btnSwap"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2>Your Swap Request Has Been Sent.</h2>';
    
        print $message;
    } else {       
     print '<h2>SWAP</h2>';
     print '<p class="form-heading text-center">Would you like to place a request to swap with this dorm?</p>';
     
        //####################################
        //
        print PHP_EOL . '<!-- SECTION 3b Error Messages -->' . PHP_EOL;
        //
        // display any error messages before we print out the form
   
       if ($errorMsg) {    
           print '<div id="errors">' . PHP_EOL;
           print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
           print '<ol>' . PHP_EOL;

           foreach ($errorMsg as $err) {
               print '<li>' . $err . '</li>' . PHP_EOL;       
           }

            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
       }

        //####################################
        //
        print PHP_EOL . '<!-- SECTION 3c html Form -->' . PHP_EOL;
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
            is defined in top.php
            NOTE the line:
            value="<?php print $email; ?>
            this makes the form sticky by displaying either the initial default value (line ??)
            or the value they typed in (line ??)
            NOTE this line:
            <?php if($emailERROR) print 'class="mistake"'; ?>
            this prints out a css class so that we can highlight the background etc. to
            make it stand out that a mistake happened here.
       */
?>    



<form action = "<?php print $phpSelf; ?>"
          id = "frmRegister"
          method = "post">
                <fieldset class = "contact text-center">
                    <legend>Send Your Request!</legend>
                    <input id="hdnUserame" name="hdnUsername" type="hidden" value=<?php print $username; ?>>
                    <input id="hdnDormId" name="hdnDormId" type="hidden" value=<?php print $dormId; ?>>
                    <input id="hdnStudentOneId" name="hdnStudentOneId" type="hidden" value=<?php print $studentOneId; ?>>
                    <input id="hdnStudentTwoId" name="hdnStudentTwoId" type="hidden" value=<?php print $studentTwoId; ?>>
                    <input id="hdnStudentTwoDorm" name="hdnStudentTwoDorm" type="hidden" value=<?php print $studentTwoDorm; ?>>
                </fieldset> <!-- ends contact -->

            <fieldset class="buttons text-center">
                <legend></legend>
                <input class = "btn btn-success" role="button" id = "btnSwap" name = "btnSwap" tabindex = "900" type = "submit" value = "Swap" >
            </fieldset> <!-- ends buttons -->
</form>     
<?php
    } // ends body submit
?>
</article>
</main>



<?php include "../footer.php"; ?>

</body>
</html>
