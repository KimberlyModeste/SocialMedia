<?php

function deleteAllPostComments()
{
	$query = " DELETE FROM `Comments` WHERE Comments.PostID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$PostID);
	$stmt -> execute();
	$stmt -> store_result();
	
}
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
function deletePost()
{
	$query = "DELETE FROM `Posts` WHERE `Posts`.`PostID` = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$PostID);
	$stmt -> execute();
	$stmt -> store_result();
}

?>