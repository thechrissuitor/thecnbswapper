<?php
include "../top.php";

if(isset($_GET['dormId'])){
$dormId = (int) htmlentities($_GET['dormId'], ENT_QUOTES, "UTF-8");
}


$query1 ='DELETE FROM tblDorms WHERE pmkUserDormId = ' . $dormId;
$query2 ='DELETE FROM `tblStudentInfo` WHERE pmkStudentId = ' . $dormId;
$query3 ='DELETE FROM `tblUserImages` WHERE pmkImageId =' . $dormId;

if ($thisDatabaseWriter->querySecurityOk($query1)) {
    $query1 = $thisDatabaseWriter->sanitizeQuery($query1);
    $records1 = $thisDatabaseWriter->insert($query1, '');
}
if ($thisDatabaseWriter->querySecurityOk($query2)) {
    $query2 = $thisDatabaseWriter->sanitizeQuery($query2);
    $records2 = $thisDatabaseWriter->insert($query2, '');
}
if ($thisDatabaseWriter->querySecurityOk($query3)) {
    $query3 = $thisDatabaseWriter->sanitizeQuery($query3);
    $records3 = $thisDatabaseWriter->insert($query3, '');
}

?>

<main>     
    <article>
        <h1 class="text-center">The entry has been deleted.</h1>
    </article>
</main>

<?php
include '../footer.php';
?>