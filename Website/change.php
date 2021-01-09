<?php
session_start();
Require("../startDatabase.php");
include("FunctionBox.php");
include('../includes/header2.html');


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
	if(isset($_POST['Nickname']))
	{
		$query = "UPDATE `Users` SET `Nickname` = ? WHERE `Users`.`UserID` = ?";

		$stmt = $dbc -> prepare($query);
		$stmt->bind_param('ss',$_POST['Nickname'], $_SESSION['UserID']);
		$stmt -> execute();
		$stmt -> store_result();	
	}
	
	if(isset($_POST['oEmail']))
	{
		if ($_POST['oEmail'] === $_SESSION['Email'])
		{
		$query = "UPDATE `Users` SET `Email` = ? WHERE `Users`.`UserID` = ?";

		$stmt = $dbc -> prepare($query);
		$stmt->bind_param('ss',$_POST['nEmail'], $_SESSION['UserID']);
		$stmt -> execute();
		$stmt -> store_result();
		header("refresh:.05;url= logout.php");		
		}
		else
		echo "<br/><br/><br/><br/><br/><br/>Your old Email doesn't match our database, reloading page.";
		header("refresh:2;url= User'sPage.php");	
	}
	

	if(isset($_POST['oPassword']))
	{
		$_POST['oPassword'] = Sha1($_POST['oPassword']);
		$_POST['nPassword'] = Sha1($_POST['nPassword']);
		if ($_POST['oPassword'] === $_SESSION['Password'])
		{
		$query = "UPDATE `Users` SET `Password` = ? WHERE `Users`.`UserID` = ?";

		$stmt = $dbc -> prepare($query);
		$stmt->bind_param('ss',$_POST['nPassword'], $_SESSION['UserID']);
		$stmt -> execute();
		$stmt -> store_result();
		header("refresh:.05;url= logout.php");	
		}
		else 
		echo "<br/><br/><br/><br/><br/><br/>Your old password doesn't match our database, reloading page.";
		header("refresh:2;url= User'sPage.php");	
	}

?>