<?php session_start();?>
<html>
<body>
<?php
Require("../startDatabase.php");
include('../includes/header2.html');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	
function register($error)
{
	echo "<font color='red'>".$error."</font>";
?>	
<br> <br> <br> <br> <br>
	<form action="Register.php" method="post">
	<table>
		<tr><td> Nickname: </td><td> <input type = "text" name = "nickname"/></td></tr>
		<tr><td> Email: </td><td> <input type = "text" name = "email"/></td></tr>
		<tr><td> Password: </td><td> <input type = "password" name = "password"/></td></tr>
	</table>
	
		<input type = "submit" name = "submit" value = "register"/>

</body>
</html>

<?php

}
if (!isset($_POST['submit']))
{
	register("");
}

else
{				  
// e = entered

$uNickname = htmlentities(trim($_POST['nickname']));
$uEmail= htmlentities(trim($_POST['email']));
$uPassword = Sha1(htmlentities($_POST['password']));


	
	if($uNickname === "" || $uEmail === "" || $uPassword === "")
		register("Please enter a valid option.");

else
{
$query = "SELECT * FROM `Users` WHERE Users.Email = ?";
$stmt = $dbc -> prepare($query);
$stmt -> bind_param('s', $uEmail);
$stmt -> execute();
$stmt -> store_result();
$isUser = $stmt -> affected_rows;


if($isUser > 0)
	
	echo "<p> This email is already associated with an account!</p>";

else
{
	
$query = "INSERT INTO `Users` (`UserID`, `Nickname`, `Password`, `Email`) VALUES (NULL, ?, ?, ?)";
$stmt = $dbc -> prepare($query);
$stmt -> bind_param('sss', $uNickname, $uPassword, $uEmail);
$stmt -> execute();
$stmt -> store_result();

$query = "SELECT UserID FROM `Users` WHERE Users.Email = ? AND Users.Password = ?";
$stmt = $dbc -> prepare($query);
$stmt -> bind_param('ss', $uEmail, $uPassword);
$stmt -> execute();
$stmt -> store_result();
$stmt -> bind_result($UserID);


if($stmt -> affected_rows >0);{
	 while ($stmt->fetch()) 
	$_SESSION['UserID'] = $UserID;
	$_SESSION['Nickname'] = $uNickname;
	$_SESSION['Password'] = $uPassword;
	$_SESSION['Email'] = $uEmail;

header( "refresh:.05;url=../index.php" );

}
}

}
}