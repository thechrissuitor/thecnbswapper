<?php
include 'top.php';
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//       
print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       
// These variables are used in both sections 2 and 3, otherwise we would
// declare them in the section we needed them
if ($request)
print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
// We print out the post array so that we can see our form is working.
// Normally i wrap this in a debug statement but for now i want to always
// display it. when you first come to the form it is empty. when you submit the
// form it displays the contents of the post array.
// if ($debug){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
// }

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
//
// Initialize variables one for each form element
// in the order they appear on the form

$firstName = "";
$lastName = "";
$classStanding = "";
$email = "";
// For the listbox of dorm halls
$hall = "";
$hallRecords = '';
$hallQuery = 'SELECT fldHall, fldHallId ';
$hallQuery .= ' FROM tblHalls ORDER BY fldHall';
if ($thisDatabaseReader->querySecurityOk($hallQuery, 0, 1)) {
    $hallQuery = $thisDatabaseReader->sanitizeQuery($hallQuery);
    $hallRecords = $thisDatabaseReader->select($hallQuery, '');
}
// END dorm halls listbox
$roomNumber = '';
$roommates = "";
$dormStyle = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;
//
// Initialize Error Flags one for each form element we validate
// in the order they appear on the form
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;   
$classStandingERROR = false;
$hallERROR = false;
$roomNumberERROR = false;
$roommateERROR = false;
$dormStyleERROR = false;
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
if (isset($_POST["btnSubmit"])) {

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

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");     
    
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);       
    
    $classStanding = htmlentities($_POST["radClassStanding"], ENT_QUOTES, "UTF-8");
    
    $hall = (int) htmlentities($_POST["lstHall"], ENT_QUOTES, "UTF-8");
    
    $roomNumber = htmlentities($_POST["txtRoomNumber"], ENT_QUOTES, "UTF-8");
    
    $roommates = htmlentities($_POST["lstRoommates"], ENT_QUOTES, "UTF-8");
    
    $dormStyle = htmlentities($_POST["lstDormStyle"], ENT_QUOTES, "UTF-8");
    
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
    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $firstNameERROR = true;
    }
    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have extra character.";
        $lastNameERROR = true;
    }
    
    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {       
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;    
    } 
    if ($classStanding == "") {
        $errorMsg[] = "Please select a class standing";
        $classStandingERROR = true;
    } elseif (!verifyAlphaNum($classStanding)) {
        $errorMsg[] = "Your class standing is not on of our options";
        $classStandingERROR = true;
    }
    if ($hall == "") {
        $errorMsg[] = "Please choose a hall.";
        $hallERROR = true;
    } elseif (!is_int($hall)) {
        $errorMsg[] = "There is something wrong with your hall.";
        $hallERROR = true;
    }
    if ($roomNumber == "") {
        $errorMsg[] = 'Please enter your room number';
        $roomNumberERROR = true;
    } elseif (!verifyAlphaNum($roomNumber)) {       
        $errorMsg[] = 'Your room number is invalid.';
        $roomNumberERROR = true;    
    }
    if ($roommates == "") {
        $errorMsg[] = 'Please select number of roommates';
        $roommateERROR = true;
    } elseif (!verifyAlphaNum($roommates)) {       
        $errorMsg[] = 'Number of roommates is invalid.';
        $roommateERROR = true;    
    }
    
    if ($dormStyle == "") {
        $errorMsg[] = 'Please select dorm style';
        $dormStyleERROR = true;
    } elseif (!verifyAlphaNum($dormStyle)) {       
        $errorMsg[] = 'Dorm style is invalid.';
        $dormStyleERROR = true;    
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
        $studentDataRecord = array(); 
        $dormDataRecord = array(); 
        
        // assign values to the dataRecord array
        $studentDataRecord[] = $firstName;
        $studentDataRecord[] = $lastName;
        $studentDataRecord[] = $email; 
        $studentDataRecord[] = $classStanding;
        $dormDataRecord[] = $hall;
        $dormDataRecord[] = $roomNumber;
        $dormDataRecord[] = $roommates;
        $dormDataRecord[] = $dormStyle;
        
        //INSERT QUERY FOR TBLDORMS
        $dormInsertQuery = "INSERT INTO tblDorms SET fnkHallId = ?, ";
        $dormInsertQuery .= "fldRoomNumber = ?, fldRoommates = ?, ";
        $dormInsertQuery .= "fldDormStyle = ?";
        
        //SEND INSERT QUERY FOR TBLDORMS
        if ($thisDatabaseWriter->querySecurityOk($dormInsertQuery, 0)) {
            $dormInsertQuery = $thisDatabaseWriter->sanitizeQuery($dormInsertQuery);
            $dormDataRecord = $thisDatabaseWriter->insert($dormInsertQuery, $dormDataRecord);
        }
        
        //INSERT QUERY FOR TBLSTUDENTINFO
        //
        //grab the dorm ID from the recent insert
        $dormId = $thisDatabaseWriter->lastInsert();
        $studentDataRecord[] = $dormId;
        
        $studentInsertQuery = "INSERT INTO tblStudentInfo SET fldFirstName = ?, ";
        $studentInsertQuery .= "fldLastName = ?, fldEmail = ?, ";
        $studentInsertQuery .= "fldClassStanding = ?, fnkDormId = ?";
                
        //SEND INSERT QUERY FOR TBLSTUDNETINFO
        if ($thisDatabaseWriter->querySecurityOk($studentInsertQuery, 0)) {
            $studentInsertQuery = $thisDatabaseWriter->sanitizeQuery($studentInsertQuery);
            $studentDataRecord = $thisDatabaseWriter->insert($studentInsertQuery, $studentDataRecord);
        }
        
    
        // setup csv file
        $myFolder = 'data/';
        $myFileName = 'registration';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;
    
        if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
    
        // now we just open the file for append
        $file = fopen($filename, 'a');
    
        // write the forms informations
        fputcsv($file, $dataRecord);
    
        // close the file
        fclose($file);       
    
     
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Your  information.</h2>';       

        foreach ($_POST as $htmlName => $value) {
            
            $message .= '<p>';
            // breaks up the form names into words. for example
            // txtFirstName becomes First Name       
            $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

            foreach ($camelCase as $oneWord) {
                $message .= $oneWord . ' ';
            }
    
            $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';

        }
        
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2g Mail to user -->' . PHP_EOL;
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form     
        $cc = '';       
        $bcc = '';

        $from = 'WRONG site <customer.service@your-site.com>';

        // subject of mail should make sense to your form
        $subject = 'Groovy: ';

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

    } // end form is valid     

}   // ends if form was submitted.



//#############################################################################
//
print PHP_EOL . '<!-- SECTION 3 Display Form -->' . PHP_EOL;
//
?>       
<main>     
    <article>
<?php
    //####################################
    //
    print PHP_EOL . '<!-- SECTION 3a  -->' . PHP_EOL;
    // 
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2>Thank you for providing your information.</h2>';
    
        print '<p>For your records a copy of this data has ';
        if (!$mailed) {    
            print "not ";         
        }
    
        print 'been sent:</p>';
        print '<p>To: ' . $email . '</p>';
    
        print $message;
    } else {       
     print '<h2>Apply to Swap Today!</h2>';
     print '<p class="form-heading">Your information will be sent to our swapping database where we will try to pair you with a fellow swapper.</p>';
     
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

                <fieldset class = "contact">
                    <legend>Swap Information</legend>
                    <p>
                        <label class="required" for="txtFirstName">First Name</label>  
                        <input autofocus
                                <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                id="txtFirstName"
                                maxlength="45"
                                name="txtFirstName"
                                onfocus="this.select()"
                                placeholder="Enter your first name"
                                tabindex="100"
                                type="text"
                                value="<?php print $firstName; ?>"                    
                        >                    
                    </p>
                    
                    <p>
                        <label class = "required" for = "txtLastName">Last Name</label>
                            <input 
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   id = "txtLastName"     
                                   maxlength = "45"
                                   name = "txtLastName"
                                   onfocus = "this.select()"
                                   placeholder = "Enter your last name"
                                   tabindex = "120"
                                   type = "text"
                                   value = "<?php print $lastName; ?>"
                            >
                    </p>
                    <p>
                        <label class = "required" for = "txtEmail">Email</label>
                            <input 
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   id = "txtEmail"     
                                   maxlength = "45"
                                   name = "txtEmail"
                                   onfocus = "this.select()"
                                   placeholder = "sample@uvm.edu"
                                   tabindex = "120"
                                   type = "text"
                                   value = "<?php print $email; ?>"
                            >
                    </p>  
                    <p>
                        <label class="required" for="radClassStanding">Class Standing</label>  
                        <input type="radio" name="radFreshman" value="<?php print $classStanding; ?>"> Freshman
                        <input type="radio" name="radSophomore" value="<?php print $classStanding; ?>"> Sophomore
                        <input type="radio" name="radJunior" value="<?php print $classStanding; ?>"> Junior 
                        <input type="radio" name="radSenior" value="<?php print $classStanding; ?>"> Senior 
                    </p>
                    <p>Hall <br>
                        <select id="" 
                                name="lstHall" 
                                tabindex="180" >
                            <?php
                            if (is_array($hallRecords)) {
                                foreach ($hallRecords as $record) { 
                                    print "<option value = ";
                                    print $record['pmkHallId'];
                                    if($hall == $record['fldHall']){
                                        print " selected ";
                                    }
                                    print " > ";
                                    print $record['fldHall'];
                                    print " </option>";
                                }
                            }
                            ?>
                        </select>
                    </p>
                    <p>
                        <label class = "required" for = "txtRoomNumber">Room Number</label>
                            <input 
                                   <?php if ($roomNumberERROR) print 'class="mistake"'; ?>
                                   id = "txtRoomNumber"     
                                   maxlength = "45"
                                   name = "txtRoomNumber"
                                   onfocus = "this.select()"
                                   placeholder = ""
                                   tabindex = "120"
                                   type = "text"
                                   value = "<?php print $roomNumber; ?>"
                            >
                    </p>
                    <p>
                       <label for="lstRoommates">Roommates
                        <select id="lstRoommates"
                                name="lstRoommates"
                                tabindex="300" >
                            <option value="0" selected>0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>

                        </select></label>
                    </p>  
                    <p>
                       <label for="lstDormStyle">Dorm Style
                        <select id="lstDormStyle"
                                name="lstDormStyle"
                                tabindex="300" >
                            <option value="lstTraditional" selected>Traditional</option>
                            <option value="lstSuite">Suite</option>                     
                        </select></label>
                    </p>  
                </fieldset> <!-- ends contact -->

            <fieldset class="buttons">
                <legend></legend>
                <input class = "button" id = "btnSubmit" name = "btnSubmit" tabindex = "900" type = "submit" value = "Register" >
            </fieldset> <!-- ends buttons -->
</form>     
<?php
    } // ends body submit
?>
    </article>     
</main>     

<?php include 'footer.php'; ?>

</body>     
</html>
