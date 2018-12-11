<?php
include "top.php";

if(isset($_GET['dormId'])){
$dormId = (int) htmlentities($_GET['dormId'], ENT_QUOTES, "UTF-8");
}


$query ='DELETE FROM tblDorms WHERE pmkUserDormId = ' . $dormId . ';';
$query .='DELETE FROM `tblStudentInfo` WHERE pmkStudentId = ' . $dormId . ';';
$query .='DELETE FROM `tblUserImages` WHERE pmkImageId =' . $dormId . ';';

if ($thisDatabaseReader->querySecurityOk($query,0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $records = $thisDatabaseReader->select($query, '');
}


print'<p><pre>';
print_r($records);
print'</pre></p>';
?>