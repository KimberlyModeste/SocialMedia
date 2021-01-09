<?php
session_start(); 
require ('../startDatabase.php');
include('../includes/header2.html');
	

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
	if(!isset($_GET['page']))
	header("refresh:.05;url=../index.php");

else
{

	$postID = $_GET['page'];
	
	
	$query = "SELECT Body, DateTime, Users.Nickname FROM Posts INNER JOIN Users on Users.UserID = Posts.UserID Where PostID = ?";

	$stmt = $dbc -> prepare($query);
	 $stmt->bind_param('s',$postID);
	$stmt -> execute();
	$stmt -> store_result();	
	$stmt -> bind_result($postBody, $datetime, $postNickname);
	
	While($stmt -> fetch())
	{
		$postBody = $postBody;
		$datetime= $datetime;
		$postNickname = $postNickname;
	}
	$stmt->free_result();
	
	$query = "SELECT COUNT(Body) FROM Comments where PostID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s', $postID);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($total);
	While($stmt -> fetch())
		$total = $total;
	
	$query = "select body, DateTime,Users.nickname from Comments inner join Users on
	Users.UserID = Comments.UserID where Comments.PostID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s', $postID);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($commentbody, $datetime, $nickname);
	
	
	
	
	
?>

<br> <br> <br> <br> <br>
	
	<table border="1" cellpadding="0" cellspacing="0">
	<tr bgcolor="#C0C0C0">
		<td>Author's Blog</td></tr> <tr><td>
<?php		
		
		 echo $postBody."<br/><br/><br/><br/><br/>";
		
?>			
 </td></tr>
 <tr><td>
 <?php		
		 echo $postNickname.", ".$datetime;
?>
 </td></tr>
 <?php
		if ($total > 0){
 ?>
		<tr bgcolor="#C0C0C0">
		<td>Comments</td></tr> 
<?php
		

While($stmt -> fetch())
	{
		echo "<tr><td> $commentbody<br/>$nickname , $datetime</td></tr>";	
	}
}
	else
	{	
?>
	<tr bgcolor="#C0C0C0">
	<td>No Comments</td></tr> 
<?php	
	}
?>

	</table>
	<br>
	
<?php

function addAComment()
{
?>
	<form method = "post">
	<p><textarea name="Body" cols = "40"></textarea></p>
	
	<input type = "submit" name = "submit" value = "Submit"/>
<?php	
}
if (isset($_SESSION['UserID']))
{	
	
	if (!isset($_POST['submit']))
	{
		addAComment();
	}
	
	else
	{
	$userComm = $_POST['Body'];
	$query = "INSERT INTO `Comments` (`CommentID`, `Body`, `UserID`, `PostID`, `DateTime`) VALUES (NULL, ?, ?, ?, NOW());";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('sss',$userComm, $_SESSION['UserID'],$postID);
	$stmt -> execute();
	$stmt -> store_result();
	header("refresh:.05;url= comments.php?page=$postID");	
	}
}
}

?>
</body>
</html>