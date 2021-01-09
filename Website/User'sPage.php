<?php session_start();
require ('../startDatabase.php');
include('../includes/header2.html');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
function nickname()
{
?>
<form action="change.php" method = "post">
	
	Please Enter New Nickname <br>
	<input type = "text"   name = "Nickname"/>
	<input type = "submit" name = "submit" value = "Submit"/>
	
<?php
}
function email()
{
?>
<form action="change.php" method = "post">
	<table>
		<tr><td> Old Email:</td><td> <input type = "text" name = "oEmail"/></td></tr>
		<tr><td> New Email:</td><td> <input type = "text" name = "nEmail"/></td></tr>
	</table>
	
	<input type = "submit" name = "submit" value = "Submit"/>
<?php
}
function password()
{
?>
<form action="change.php" method = "post">
	<table>
		<tr><td> Old Password:</td><td> <input type = "Password" name = "oPassword"/></td></tr>
		<tr><td> New Password:</td><td> <input type = "Password" name = "nPassword"/></td></tr>
	</table>
	
	<input type = "submit" name = "submit" value = "Submit"/>
<?php
}
?>
<html>
<head>
<br> <br> <br> <br> <br> 
<h1>
Welcome <?php echo $_SESSION['Nickname'];?> 
</h1>
</head>
<br>
What do you want to do?
<br> 
<?php $change = 'n';
	echo "<a href=\"User'sPage.php?change=$change\"> Change Nickname</a><br>";
	$change = 'e';
	echo "<a href=\"User'sPage.php?change=$change\"> Change Email </a><br>";
	$change = 'p';
	echo "<a href=\"User'sPage.php?change=$change\"> Change Password </a><br>";
	

	if (!isset($_GET['change']))
		$_GET['change'] = NULL;
	
	if ($_GET['change']=='n')
	{
		nickname();
	}
	
	if ($_GET['change']=='e')
	{
		email();
	}
	
	if ($_GET['change']=='p')
	{
		password();
	}

  if (isset($_GET['page']))
	  $thispage = $_GET['page'];
  
  else 
	  $thispage = 0;

	$toReset = false;
	
	
	if($thispage === 1)
		$query = "UPDATE `Users` SET `Nickname` = ? WHERE `Users`.`UserID` = $_SESSION[UserID]";
	
	if($thispage === 2)
	{
		$query = "UPDATE `Users` SET Email = ? WHERE `Users`.`UserID` = $_SESSION[UserID]";
		$toReset = true;
	}
	
	if($thispage === 3)
	{
		$query = "UPDATE `Users` SET `Password` = Sha1(?) WHERE `Users`.`UserID` = $_SESSION[UserID]";
		$toReset = true;
	}

	//to see if there is a comment or post in the database made by the user
	$query = "SELECT COUNT(*) FROM `Comments` WHERE UserID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$_SESSION['UserID']);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($count);
	while($stmt -> fetch())
		$pcount=$count;
	
	$query = "SELECT COUNT(*) FROM Posts WHERE UserID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$_SESSION['UserID']);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($count);
	while($stmt -> fetch())
		$pcount += $count;

if($pcount > 0)
{
?>	
<h2> Do you want to delete your past mistakes?</h2>
<?php
}
else
{
?>
<h2>You haven't made any post or comments.</h2>
<?php
}
	//Sees puts out posts.
	$query = "SELECT body, PostID FROM Posts WHERE UserID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$_SESSION['UserID']);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($body, $page);
	$letter = 'p';
While($stmt -> fetch())
{
	
	echo $body . "<br/><a href=\"User'sPage.php?page=$page&letter=$letter\">DELETE</a> <br /><br />"; 
}

$stmt->free_result();

	$query = "SELECT body, CommentID FROM Comments WHERE UserID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$_SESSION['UserID']);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($Cbody, $page);
	$letter = 'c';

While($stmt -> fetch())
{
	
	echo $Cbody . "<br/><a href=\"User'sPage.php?page=$page&letter=$letter\">DELETE</a> <br /><br />"; 
}

	if(!isset($_GET['page']))
	$_GET['page']= NULL;

	if(!isset($_GET['letter']))
	$_GET['letter']= NULL;

//if its a post
	if ($_GET['letter'] ==	'p')
	{
	$PostID = $_GET['page'];

	$query = "SELECT Count(Body) from Comments WHERE Comments.PostID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$PostID);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($total);
	While($stmt -> fetch())
		$total = $total;
	
	
	if ($total > 0)
	{
		//delete all commnets from posts
	$query = " DELETE FROM `Comments` WHERE Comments.PostID = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$PostID);
	$stmt -> execute();
	$stmt -> store_result();
	}
	$stmt->free_result();
	
	//delete the post
	$query = "DELETE FROM `Posts` WHERE `Posts`.`PostID` = ?";
	$stmt = $dbc -> prepare($query);
	$stmt -> bind_param('s',$PostID);
	$stmt -> execute();
	$stmt -> store_result();
	
	if($stmt -> affected_rows>0)
	{
		$stmt->free_result();
		header("refresh:.05;url= User'sPage.php");
	}
	}
	
	
	if ($_GET['letter']== 'c')
	{
		$CommentID = $_GET['page'];
		$query = "DELETE FROM Comments WHERE `Comments`.`CommentID` = ?";
		$stmt = $dbc -> prepare($query);
		$stmt -> bind_param('s',$CommentID);
		$stmt -> execute();
		$stmt -> store_result();
		if($stmt -> affected_rows>0)
	{
		$stmt->free_result();
		header("refresh:.05;url= User'sPage.php");
	}
	}
	

?>
</html>