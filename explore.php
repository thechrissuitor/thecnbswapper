<?php
include "top.php";
$query = "SELECT pmkUserDormId, fldFirstName, fldLastName, fldClassStanding, ";
$query .= "fldHall, fldDormStyle, fldRoomNumber, fldRoommates, fldDescription, ";
$query .= "fldImagePath FROM tblDorms JOIN tblStudentInfo ON fnkStudentId = pmkStudentId ";
$query .= "JOIN tblHalls ON fnkHallId = pmkHallId JOIN tblUserImages ON fnkImageId = pmkImageId";
	
$records = '';

if ($thisDatabaseReader->querySecurityOk($query, 0)) {
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
                    $theImg = substr($record["fldImagePath"], 3);
                    print '<figure class="card mb-3">';
                    print '<img class="card-imgage card-img-top" src="' . $theImg . '" alt="">';
                    print '<article class="card-body">';
                    print '<h3>Post</h3>';
                    print '<p class= "card-text"> <strong> Name: </strong> '. $fullName .'<br>';
                    print '<strong> Hall: </strong>' . $record['fldHall'] . '<br>';
                    print '<strong> Room Number: </strong>' . $record['fldRoomNumber'] .'<br>';
                    print '<strong> Dorm Style: </strong>' . $record['fldDormStyle'] . '<br>';
                    print '<strong> Description: </strong>' . $record['fldDescription'] . '<br>';
                    print '</p>';
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
	?>
</main>



<?php include "footer.php"; ?>

</body>
</html>








<a href="market-details.php?dormId=';
print $record['pmkUserDormId'];
print '"></a> ';