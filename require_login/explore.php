<?php
include "../top.php";
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
        if(!empty($records)){
            if (is_array($records)) {
                foreach($records as $record) {
                        $dormId = $record['pmkUserDormId'];
                        $fullName = $record['fldFirstName'] . ' ' . $record['fldLastName']; //this is the student's full name (first and last)
                        //$theImg = str_replace('../', , subject)$record['fldImagePath']; // THIS IS A STRING. The image path (the location) of the image in the server
                        $theImg = $record["fldImagePath"];
                        print '<figure class="row studentPost">';
                        print '<img class="col-sm-6" src="' . $theImg . '" alt="">';
                        print '<article class="card-body">';
                        print '<h3>Post</h3>';
                        print '<p class= "card-text"> ';
                        print '<strong> Hall: </strong>' . $record['fldHall'] . '<br>';
                        print '<strong> Dorm Style: </strong>' . $record['fldDormStyle'] . '<br>';
                        print '<strong> Description: </strong>' . $record['fldDescription'] . '<br>';
                        print '</p>';
                        print '<p class= "float-right loveLink">Intereted in this dorm? <a  class = "btn btn-primary" role = "button" href="market-details.php?dormId=';
                        print $dormId;
                        print '">Click Here!</a></p>';
                        print '</article>';
                        if($isAdmin){
                            //ADMIN DELETE BUTTON
                            print '<p class= "float-right adminButton"><a  class = "btn btn-primary" role = "button" href="delete.php?dormId=';
                            print $dormId;
                            print '">DELETE</a></p>';
                            //ADMIN UPDATE BUTTON
                            print '<p class= "float-right adminButton"><a  class = "btn btn-primary" role = "button" href="apply.php?dormId=';
                            print $dormId;
                            print '">UPDATE</a></p>';
                        }
                        print '</figure>';
                }
            }
        } else {
            print '<h1 class="text-center">There are no post available.</h1>';
            print '<h2 class="text-center">Check out <a href="apply.php">our form</a> to submit a request.</h2>';
        }
	?>
</main>



<?php include "../footer.php"; ?>

</body>
</html>








 ';