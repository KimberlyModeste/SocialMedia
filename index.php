<?php
session_start(); 
# Script 3.7 - index.php #2
// This function outputs theoretical HTML
// for adding ads to a Web page.
require ('startDatabase.php');
function create_ad() {
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) 
	OR die('Could not connect to MySQL: ' . mysqli_connect_error() );
	

	  if (isset($_GET['page']))
	  $thispage = $_GET['page'];
  else
	  $thispage = 1;
  
	
	$query = "SELECT COUNT(PostID) FROM Posts";
	
	$stmt = $dbc -> prepare($query);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($total);
	$stmt->fetch();
	
	$stmt->free_result();	

	$recordsperpage = 5;
	
	$totalpages = ceil($total / $recordsperpage);
	$offset = ($thispage - 1) * $recordsperpage;


	
	$query = "SELECT Body, DateTime, PostID, Users.Nickname FROM Posts INNER JOIN Users on Users.UserID = Posts.UserID ORDER by Posts.DateTime desc limit ?,?";

	$stmt = $dbc -> prepare($query);
	 $stmt->bind_param('ss',$offset,$recordsperpage);
	$stmt -> execute();
	$stmt -> store_result();	
	$stmt -> bind_result($body, $datetime, $Post, $user);
	 while ($stmt->fetch()) 
	 {
     echo "$body <br /> $user  $datetime <br/> <span style='color:black;'><a href=\"Website/comments.php?page=$Post\">View Post</a> <br /><br />";
		   
		   
	 }
	 
	$stmt->free_result();
  
  
 if ($thispage > 1)

   {

      $page = $thispage - 1;

      $prevpage = "<a href=\"index.php?page=$page\">Previous</a>";

   } else

   {
      $prevpage = "";

   }

  $bar = "";

if ($totalpages > 1)

{ 

    for($page = 1; $page <= $totalpages; $page++)

    {

        if ($page == $thispage)      

       {

           $bar .= " $page ";

       } else

       {

          $bar .= " <a href=\"index.php?page=$page\">$page</a> ";

       }

    }
echo $bar;
}
} // End of the function definition.
include('includes/header.html');
// Call the function:

?>
<br>
<br>
<div class="page-header"><h1>Most Recent Post</h1></div>

<?php
// Call the function again:
create_ad();

			
include('includes/footer.html');
?>