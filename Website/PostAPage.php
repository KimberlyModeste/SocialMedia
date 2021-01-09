<?php session_start(); 
require ('../startDatabase.php');
include('../includes/header2.html');
function post()
{
?>
<html>
<br><br><br>
	<form action="PostAPage.php" method="post">
	<fieldset>
	<p><textarea name="Body" rows="20" cols="80"></textarea></p>
	</fieldset>
	<input type = "submit" name = "submit" value = "Submit"/>
</html>
<?php
}
if (!isset($_POST['submit']))
{
	post();
}
else{
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
	$userID = $_SESSION['UserID'];
	$body = $_POST['Body'];

	$query = "INSERT INTO `Posts` (`PostID`, `Body`, `UserID`, `DateTime`) VALUES (NULL,
	?, ?, NOW())";

	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('ss', $body, $userID);
	$stmt -> execute();
	$stmt -> store_result();
	
	header( "refresh:.05;url=../index.php");


}
?>