<?php
include '../top.php';
//print '<p><pre>';
//phpinfo();
//print '</pre></p>';
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//       
print  PHP_EOL . '<!-- SECTION: 1 Initialize variables -->' . PHP_EOL;       
// These variables are used in both sections 2 and 3, otherwise we would
// declare them in the section we needed them
//if ($request)
print  PHP_EOL . '<!-- SECTION: 1a. debugging setup -->' . PHP_EOL;
// We print out the post array so that we can see our form is working.
// Normally i wrap this in a debug statement but for now i want to always
// display it. when you first come to the form it is empty. when you submit the
// form it displays the contents of the post array.
 if (DEBUG){ 
    print '<p>Path Parts:</p><pre>';
    print_r($path_parts);
    print '<p>Post Array:<br></p><pre>';
    print_r($_POST);
    print '</pre>';
    print '<p>Files Array:</p><pre>';
    print_r($_FILES);
    print '</pre>';
 }

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
//
// Initialize variables one for each form element
// in the order they appear on the form

$hidden = "";
$firstName = "";
$lastName = "";
$classStanding = "";
$email = "";
// For the listbox of dorm halls
$hall = "";
$hallRecords = '';
$hallQuery = 'SELECT fldHall, pmkHallId ';
$hallQuery .= ' FROM tblHalls ORDER BY fldHall';
if ($thisDatabaseReader->querySecurityOk($hallQuery, 0, 1)) {
    $hallQuery = $thisDatabaseReader->sanitizeQuery($hallQuery);
    $hallRecords = $thisDatabaseReader->select($hallQuery, '');
}
// END dorm halls listbox
$roomNumber = "";
$roommates = "";
$dormStyle = "";
$description = "";
$image = "../images/user-dorm-images/";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;
//
// Initialize Error Flags one for each form element we validate
// in the order they appear on the form
$hiddenERROR = false;
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;   
$classStandingERROR = false;
$hallERROR = false;
$roomNumberERROR = false;
$roommateERROR = false;
$dormStyleERROR = false;
$descriptionERROR = false;
$imageERROR = false;
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
    
    $hidden = htmlentities($_POST["hdnUserName"], ENT_QUOTES, "UTF-8");

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");     
    
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);       
    
    $classStanding = htmlentities($_POST["radClassStanding"], ENT_QUOTES, "UTF-8");
    
    $hall = (int) htmlentities($_POST["lstHall"], ENT_QUOTES, "UTF-8");
    
    $roomNumber = htmlentities($_POST["txtRoomNumber"], ENT_QUOTES, "UTF-8");
    
    $roommates = htmlentities($_POST["lstRoommates"], ENT_QUOTES, "UTF-8");
    
    $dormStyle = htmlentities($_POST["lstDormStyle"], ENT_QUOTES, "UTF-8");
    
    $description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");
        
    // code received from https://www.w3schools.com/php/php_file_upload.asp
    $image .= basename($_FILES["imgImage"]["name"]);
    
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
    if ($hidden == ""){
        $errorMsg[] = "There is something wrong with your login.";
        $hiddenERROR = true;
    }
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
    
    if ($description == ""){
        // ignore this. The comments section
    } elseif (!verifyComment($description)) {
        $errorMsg[] = "Your comments appear to have unauthorized characters.";
        $descriptionERROR = true;
    }
    
    if ($image == "../images/user-dorm-images/") {
        $errorMsg[] = 'Please upload an image.';
        $imageERROR = true;
    } else {
        // code received from https://www.w3schools.com/php/php_file_upload.asp
        $imageFileType = strtolower(pathinfo($image,PATHINFO_EXTENSION)); 
        $uploadOk = 1;
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["imgImage"]["tmp_name"]); //returns true or false depending on if the file extension is that of an image
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1; // everythig is good
        } else {
            $errorMsg[] =  "File is not an image.";
            $imageERROR = true;
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["imgImage"]["size"] > 2000000) { //if greater than 2MB
            $errorMsg[] = "Sorry, your file is too large. Must be less than 2MB";
            $imageERROR = true;
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $errorMsg[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed. The image type is " . $imageFileType;
            $imageERROR = true;
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errorMsg[] = "Sorry, your image was not uploaded.";
            $imageERROR = true;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["imgImage"]["tmp_name"], $image)) { //moves uploaded image to the correct location. Returns true on success
                //echo "The file ". basename($_FILES["imgImage"]["name"]). " has been uploaded.";
            } else {
                $errorMsg[] = "Sorry, there was an error uploading your image.";
                $imageERROR = true;
            }
        }
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
        $imageDataRecord = array();
        
        // assign values to the dataRecord array
        $studentDataRecord[] = $firstName;
        $studentDataRecord[] = $lastName;
        $studentDataRecord[] = $email; 
        $studentDataRecord[] = $classStanding;
        $studentDataRecord[] = $hidden;
        $dormDataRecord[] = $hall;
        $dormDataRecord[] = $roomNumber;
        $dormDataRecord[] = $roommates;
        $dormDataRecord[] = $dormStyle;
        $dormDataRecord[] = $description;
        $imageDataRecord[] = $image;
        
        //INSERT QUERY FOR TBLUSERIMAGES
        $imageInsertQuery = "INSERT INTO tblUserImages SET fldImagePath = ?";
        
        //SEND INSERT QUERY FOR TBLUSERIMAGES
        if ($thisDatabaseWriter->querySecurityOk($imageInsertQuery, 0)) {
            $imageInsertQuery = $thisDatabaseWriter->sanitizeQuery($imageInsertQuery);
            $imageDataRecord = $thisDatabaseWriter->insert($imageInsertQuery, $imageDataRecord);
        }
        
        //grab the image ID from the recent insert
        $imageId = $thisDatabaseWriter->lastInsert();
        $dormDataRecord[] = $imageId;
        
        //INSERT QUERY FOR TBLDORMS
        $dormInsertQuery = "INSERT INTO tblDorms SET fnkHallId = ?, ";
        $dormInsertQuery .= "fldRoomNumber = ?, fldRoommates = ?, ";
        $dormInsertQuery .= "fldDormStyle = ?, fldDescription = ?, fnkImageId = ?, ";
        $dormInsertQuery .= "fnkStudentId = 0"; //placeholder value
        
        //SEND INSERT QUERY FOR TBLDORMS
        if ($thisDatabaseWriter->querySecurityOk($dormInsertQuery, 0)) {
            $dormInsertQuery = $thisDatabaseWriter->sanitizeQuery($dormInsertQuery);
            $dormDataRecord = $thisDatabaseWriter->insert($dormInsertQuery, $dormDataRecord);
        }
        
        ////INSERT QUERY FOR TBLSTUDENTINFO
        //
        //grab the dorm ID from the recent insert
        $dormId = $thisDatabaseWriter->lastInsert();
        $studentDataRecord[] = $dormId;
        
        $studentInsertQuery = "INSERT INTO tblStudentInfo SET fldFirstName = ?, ";
        $studentInsertQuery .= "fldLastName = ?, fldEmail = ?, ";
        $studentInsertQuery .= "fldClassStanding = ?, fldNetIdUserName = ?, ";
        $studentInsertQuery .= "fnkDormId = ?";
                
        //SEND INSERT QUERY FOR TBLSTUDNETINFO
        if ($thisDatabaseWriter->querySecurityOk($studentInsertQuery, 0)) {
            $studentInsertQuery = $thisDatabaseWriter->sanitizeQuery($studentInsertQuery);
            $studentDataRecord = $thisDatabaseWriter->insert($studentInsertQuery, $studentDataRecord);
        }
        
        //UPDATE TBLDORMS WITH CORRECT FNKSTUDENTID
        //
        //grab the student ID from the recent insert
        $studentId = $thisDatabaseWriter->lastInsert();
        $dormUpdateDataRecord[] = $studentId;
        $dormUpdateDataRecord[] = $dormId;
        $dormUpdateQuery = "UPDATE tblDorms SET ";
        $dormUpdateQuery .= "fnkStudentId = ? WHERE pmkUserDormId = ?";
        //SEND UPDATE QUERY FOR TBLSTUDNETINFO
        if ($thisDatabaseWriter->querySecurityOk($dormUpdateQuery)) {
            $dormUpdateQuery = $thisDatabaseWriter->sanitizeQuery($dormUpdateQuery);
            $dormUpdateDataRecord = $thisDatabaseWriter->insert($dormUpdateQuery, $dormUpdateDataRecord);
        }
    
     
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        print PHP_EOL . '<!-- SECTION: 2f Create message -->' . PHP_EOL;
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Your form results:</h2>';       

        $message .= '<p>';
        $message .= 'Thank you for using The CNB Swapper. Your dorm has been posted.';
        $message .= ' Please browse the market for rooms that interest you.';
        $message .= ' You will be notified when a match is found.';
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
          method = "post"
          enctype="multipart/form-data">

                <fieldset class = "contact">
                    <legend>Swap Information</legend>
                    
                    <?php
                    //grab the username from the login
                    $username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
                    ?>
                    <input id="hdnUserName" name="hdnUserName" type="hidden" value=<?php print $username; ?>>
                    <p class="row">
                        <label class="required frmLabel" for="txtFirstName">First Name</label>  
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
                    
                    <p class="row">
                        <label class = "required frmLabel" for = "txtLastName">Last Name</label>
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
                    <p class="row">
                        <label class = "required frmLabel" for = "txtEmail">Email</label>
                            <input 
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   id = "txtEmail"     
                                   maxlength = "45"
                                   name = "txtEmail"
                                   onfocus = "this.select()"
                                   placeholder = "sample@uvm.edu"
                                   tabindex = "140"
                                   type = "text"
                                   value = "<?php print $email; ?>"
                            >
                    </p>
                    
                    <p class="row"><label class="frmLabel">Hall</label>
                        <select  
                                name="lstHall" 
                                tabindex="140" >
                            <?php
                            if (is_array($hallRecords)) {
                                foreach ($hallRecords as $record) { 
                                    print "<option value = ";
                                    print $record['pmkHallId'];
                                    if($hall == $record['pmkHallId']){
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
                    
                    
                    <p class="row">
                        <label class = "required frmLabel" for = "txtRoomNumber">Room Number</label>
                            <input 
                                   <?php if ($roomNumberERROR) print 'class="mistake"'; ?>
                                   id = "txtRoomNumber"     
                                   maxlength = "45"
                                   name = "txtRoomNumber"
                                   onfocus = "this.select()"
                                   placeholder = ""
                                   tabindex = "140"
                                   type = "text"
                                   value = "<?php print $roomNumber; ?>"
                            >
                    </p>
                    
                    <p class="row">
                        <label for="lstDormStyle" class="frmLabel">Dorm Style</label>
                        <select id="lstDormStyle"
                                name="lstDormStyle"
                                tabindex="140" >
                            <option value="Traditional" 
                                <?php if($dormStyle==="Traditional") print ' selected '; ?>
                                    >Traditional</option>
                            <option value="Suite"
                                <?php if($dormStyle==="Suite") print ' selected '; ?>
                                    >Suite</option>                     
                        </select>
                    </p>
                    
                    <p class="row">
                        <label for="lstRoommates" class="frmLabel">Roommates</label>
                        <select id="lstRoommates"
                                name="lstRoommates"
                                tabindex="140" >
                            <option value="0" <?php if($roommates==0) print ' selected '; ?>>0</option>
                            <option value="1" <?php if($roommates==1) print ' selected '; ?>>1</option>
                            <option value="2" <?php if($roommates==2) print ' selected '; ?>>2</option>
                            <option value="3" <?php if($roommates==3) print ' selected '; ?>>3</option>

                        </select>
                    </p>  
                    <p class="row"> <br>
                        <label class="required frmLabel" for="radFreshman">Class Standing</label>
                            <input type="radio" id="radFreshman"  name="radClassStanding"
                                   tabindex = "140" 
                                   <?php if($classStanding==="Freshman") print ' checked '; ?>
                                   value="Freshman"><span class="radText">Freshman</span>
                    </p>
                    <p class="row">
                            <label class="required frmLabel hide" for="radSophomore"> Class Standing </label>
                            <input type="radio" id="radSophomore"  name="radClassStanding"
                                   tabindex = "180"
                                   <?php if($classStanding==="Sophomore") print ' checked '; ?>
                                   value="Sophomore"><span class="radText">Sophomore</span>
                    </p>
                    <p class="row">
                            <label class="required frmLabel hide" for="radJunior">Class Standing </label>
                            <input type="radio" id="radJunior" name="radClassStanding"
                                   tabindex = "200"
                                   <?php if($classStanding==="Junior") print ' checked '; ?>
                                   value="Junior"><span class="radText">Junior</span>
                    </p>
                    <p class="row">
                        <label class="required frmLabel hide" for="radSenior">Class Standing </label>
                            <input type="radio" id="radSenior"  name="radClassStanding"
                                   tabindex = "220"
                                   <?php if($classStanding==="Senior") print ' checked '; ?>
                                   value="Senior"><span class="radText">Senior</span>
                    </p>
                    <p>Comments:</p>
                    <textarea class="textArea" name="txtDescription"
                              tabindex="320" placeholder="Enter comments here." 
                              maxlength="250"><?php print $description; ?></textarea>
                    
                    <p class="row imgUpload">Upload a picture of your dorm:
                        <input type="file" name="imgImage" id="imgImage" tabindex="320">
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

<?php include '../footer.php'; ?>

</body>     
</html>