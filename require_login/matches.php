<?php
include '../top.php';
// GRAB THE INFORMATION FOR THE CURRENT STUDENT
$studentId = '';
$currentStudentDormId = '';
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
$matchingQuery = 'SELECT pmkRequestId, fnkDormIdRequested FROM tblRequest WHERE fnkStudentID IN (SELECT fnkOtherStudentId FROM tblRequest) AND fnkStudentID = ' . $studentId;

$matchingRecords = '';
// SEND SELECT QUERY
if ($thisDatabaseReader->querySecurityOk($matchingQuery, 1, 1)) {
    $matchingQuery = $thisDatabaseReader->sanitizeQuery($matchingQuery);
    $matchingRecords = $thisDatabaseReader->select($matchingQuery, '');
}

// GRAB THE DORM IDS
$dormId = array();
if(is_array($matchingRecords)){
    foreach($matchingRecords as $record){
	    if($currentStudentDormId == $record['fnkDormIdRequested']){ // we do not want the user to match with themself, so we filter their own posting out
		$dormId[] = $record['fnkDormIdRequested'];
	}
    }
}

// SELECT QUERY TO DISPLAY MATCHES
$displayQuery = 'SELECT fldFirstName, fldLastName, fldEmail, fldClassStanding, fldHall, fldDormStyle, fldRoomNumber, fldRoommates, fldDescription, fldImagePath FROM tblDorms JOIN tblStudentInfo ON fnkStudentId = pmkStudentId JOIN tblHalls ON fnkHallId = pmkHallId JOIN tblUserImages ON fnkImageId = pmkImageId WHERE pmkUserDormId =';

// in order to pass security we have to keep track of the conditions
$conditionsCount = 0; 
if(is_arrary($dormId)){
	foreach($dormId as $id){
		$displayQuery .= 'pmkUserDormId = ' . $id . ' AND '; // we do this for each id in $dormId to display the correct matches
		$conditionsCount++;
	}
}

$displayQuery = substr($displayQuery, 0, -5); // strip the final " AND "

$displayRecords = '';
// SEND QUERY
if ($thisDatabaseReader->querySecurityOk($displayQuery, 1, $conditionsCount)) {
    $displayQuery = $thisDatabaseReader->sanitizeQuery($displayQuery);
    $displayRecords = $thisDatabaseReader->select($displayQuery, '');
}
print '<p><pre>';
print_r($displayRecords);
print '</pre></p>';
?>

<main>
<?php
// THIS IS WHAT WILL BE DISPLAYED WITH HTML
if(is_array($displayRecords)){
    foreach($displayRecords as $displayRecord){
        $fullName = $displayRecord['fldFirstName'] . ' ' . $displayRecord['fldLastName'];
        $theImg = substr($displayRecord["fldImagePath"], 3);
        print '<figure class="row matchInfo">';
        print '<img class="col-sm-6" src="' . $theImg . '" alt="">';
        print '<article class="card-body">';
        print '<h3>Match</h3>';
        print '<p class= "card-text"> ';
        print '<stong>Email:</strong>' . $displayRecord['fldEmail'] . '<br>'; // IMPORTANT!! this is the email of the matched student. This is how contact will be made [string]
        print '<strong> Hall: </strong>' . $displayRecord['fldHall'] . '<br>';
        print '<strong>Room Number: </strong>' . $displayRecord['fldRoomNumber'] . '<br>'; 
        print '<strong> Dorm Style: </strong>' . $displayRecord['fldDormStyle'] . '<br>';
        print '<strong>Roommates: </stong>' .$displayRecord['fldRoommates'] . '<br>';
        print '<strong> Description: </strong>' . $displayRecord['fldDescription'] . '<br>';
        print '</p>';
        print '<p class= "float-right loveLink">Ready to take the next step? <a  class = "btn btn-primary" data-toggle = "modal" data-target = "#sendMessage" data-whatever = "';
        print $displayRecord['fldEmail'];
        print '" role = "button" href="#';
        //print $dormId;
        print '">Accept!</a></p>';
        print '</article>';
        print '</figure>';
                
        
		
        $displayRecord['fldClassStanding']; // this is the class standing of the matched student [string]
		
		//$displayRecord['fldHall']; // the hall of the matched dorm [string]
		//$displayRecord['fldDormStyle']; // the dorm style of the matched dorm [string]
		//$displayRecord['fldRoomNumber']; // the room number of the mached dorm [string]
		//$displayRecord['fldRoommates']; // the number of roommates that the matched student has [int]
		//$displayRecord['fldDescription']; // the comments that the matched student wrote [string]
		//$displayRecord['fldImagePath']; // the image path of the match dorm [string]
                
                
                
                
                
	}
}
?>
    
    <section class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <figure class="modal-dialog" role="document">
      <figure class="modal-content">
        <article class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New message</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </article>

        <section class="modal-body">
          <form>
            <p class="form-group">
              <label for="recipient-name" class="col-form-label">Recipient:</label>
              <input type="text" class="form-control" id="recipient-name">
            </p>
            <p class="form-group">
              <label for="message-text" class="col-form-label">Message:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </p>
          </form>
        </section>

        <section class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Send message</button>
        </section>
      </figure>
    </figure>
  </section>
</main>

<?php include '../footer.php'; ?>