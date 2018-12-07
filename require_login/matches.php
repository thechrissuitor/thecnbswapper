<?php
include '../top.php';
// GRAB THE INFORMATION FOR THE CURRENT STUDENT
$studentId = '';
$currentStudentDormId = '';
$username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
// SELECT QUERY TO GRAB CURRENT USER'S ID
$userQuery = "SELECT pmkStudentId, fnkDormId, fldEmail FROM tblStudentInfo WHERE fldNetIdUsername LIKE ?";
$queryData[] = $username;
// SEND SELECT QUERY
// we use the writer for this select query so that we can send $username through the ?
if ($thisDatabaseWriter->querySecurityOk($userQuery, 1)) {
    $userQuery = $thisDatabaseReader->sanitizeQuery($userQuery);
    $userId = $thisDatabaseWriter->select($userQuery, $queryData);
}
if(is_array($userId)){
    foreach($userId as $user){
            $studentId= $user['pmkStudentId'];
	    $currentStudentDormId = $user['fnkDormId'];
    }
}

// SELECT QUERY FOR TBLREQUEST TO GET MATCHES
$matchingQuery = 'SELECT fnkDormIdRequested FROM tblRequest WHERE fnkStudentID = ' . $studentId . ' AND fnkStudentID IN (SELECT fnkOtherStudentId FROM tblRequest WHERE fnkOtherStudentId = ' . $studentId . ')';

$matchingRecords = '';
// SEND SELECT QUERY
if ($thisDatabaseReader->querySecurityOk($matchingQuery, 2, 1)) {
    $matchingQuery = $thisDatabaseReader->sanitizeQuery($matchingQuery);
    $matchingRecords = $thisDatabaseReader->select($matchingQuery, '');
}

// GRAB THE DORM IDS
$dormId = array();
if(is_array($matchingRecords)){
    foreach($matchingRecords as $record){
	    if($currentStudentDormId != $record['fnkDormIdRequested']){ // we do not want the user to match with themself, so we filter their own posting out
		$dormId[] = $record['fnkDormIdRequested'];
	}
    }
}

// SELECT QUERY TO DISPLAY MATCHES
$displayQuery = 'SELECT fldFirstName, fldLastName, fldEmail, fldClassStanding, fldHall, fldDormStyle, fldRoomNumber, fldRoommates, fldDescription, fldImagePath FROM tblDorms JOIN tblStudentInfo ON fnkStudentId = pmkStudentId JOIN tblHalls ON fnkHallId = pmkHallId JOIN tblUserImages ON fnkImageId = pmkImageId WHERE ';

// in order to pass security we have to keep track of the conditions
$conditionsCount = 0; 
foreach($dormId as $id){
        $displayQuery .= 'pmkUserDormId = ' . $id . ' OR '; // we do this for each id in $dormId to display the correct matches
        $conditionsCount++;
}
if($conditionsCount != 0){
    $displayQuery = substr($displayQuery, 0, -4); // strip the final " AND "

    $displayRecords = '';
    // SEND QUERY
    if ($thisDatabaseReader->querySecurityOk($displayQuery, 1, $conditionsCount-1)) { // we subtract 1 because of the striped AND
        $displayQuery = $thisDatabaseReader->sanitizeQuery($displayQuery);
        $displayRecords = $thisDatabaseReader->select($displayQuery, '');
    }
}
?>

<main>
<?php
// THIS IS WHAT WILL BE DISPLAYED WITH HTML
if(!empty($displayRecords)){
    if(is_array($displayRecords)){
        foreach($displayRecords as $displayRecord){
            $fullName = $displayRecord['fldFirstName'] . ' ' . $displayRecord['fldLastName'];
            $theImg = $displayRecord["fldImagePath"];
            print '<figure class="row matchInfo">';
            print '<img class="col-sm-6" src="' . $theImg . '" alt="">';
            print '<article class="card-body">';
            print '<h3>Match</h3>';
            print '<p class= "card-text"> ';
            print '<stong>Email:</strong>' . $displayRecord['fldEmail'] . '<br>'; // IMPORTANT!! this is the email of the matched student. This is how contact will be made [string]
            print '<strong> Hall: </strong>' . $displayRecord['fldHall'] . '<br>';
            print '<strong>Room Number: </strong>' . $displayRecord['fldRoomNumber'] . '<br>'; 
            print '<strong> Dorm Style: </strong>' . $displayRecord['fldDormStyle'] . '<br>';
            print '<strong>Roommates: </strong>' .$displayRecord['fldRoommates'] . '<br>';
            print '<strong> Description: </strong>' . $displayRecord['fldDescription'] . '<br>';
            print '</p>';
            print '<p class= "float-right loveLink">Ready to take the next step? ';
            print '<a  class = "btn btn-primary" data-toggle = "modal" data-target = "#exampleModal" data-whatever = "';
            print $displayRecord['fldEmail'];
            print '" role = "button" href="#';
            //print $dormId;
            print '">Accept!</a></p>';
            print '</article>';
            print '</figure>';
            }
        }
} else {
    print '<h1 class="text-center">YOU DO NOT HAVE ANY MATCHES.</h1>';
    print '<h2 class="text-center">Check back later.</h2>';
}
?>
    
    
</main>

<?php include '../footer.php'; ?>