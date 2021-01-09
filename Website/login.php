<?php session_start();?>
<html>
<body>
<?php
Require("../startDatabase.php");
include("FunctionBox.php");
include('../includes/header2.html');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
function login($error)
{
	echo "<font color='red'>".$error."</font>";
?>	
<br> <br> <br> <br> <br>
	<form action="login.php" method="post">
	<table>
		<tr><td> Email: </td><td> <input type = "text" name = "email"/></td></tr>
		<tr><td> Password: </td><td> <input type = "password" name = "password"/></td></tr>
	</table>
	
		<input type = "submit" name = "submit" value = "Login"/>
		<button><span style="color:black;"><a href="Register.php">Register</a></button>

</body>
</html>

<?php

}
if (!isset($_POST['submit']))
{
	login("");
}

else
{				  
// e = entered

$uEmail= htmlentities(trim($_POST['email']));
$uPassword = Sha1(htmlentities($_POST['password']));


	
	if($uEmail === "" && $uPassword === "")
		login("Please enter a valid option.");

else
{
$query = "SELECT UserID, Nickname FROM `Users` WHERE Users.Email = ? AND Users.Password = ?";
$stmt = $dbc -> prepare($query);
$stmt -> bind_param('ss', $uEmail, $uPassword);
$stmt -> execute();
$stmt -> store_result();
$stmt -> bind_result($UserID, $Nickname);
$isUser = $stmt -> affected_rows;


if($isUser > 0){
 while ($stmt->fetch()) 
 {
	$_SESSION['UserID'] = $UserID;
	$_SESSION['Nickname'] = $Nickname;
 }
	$_SESSION['Password'] = $uPassword;
	$_SESSION['Email'] = $uEmail;
	header( "refresh:.05;url=../index.php");
}
else
	login("Could not login!");

}
}

?>