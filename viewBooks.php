<?php 
    session_start();
	
	include ('lib.php');
?>

<?php
if (isset($_SESSION["user_name"]))
		{
			echo "Welcome ".$_SESSION["user_name"]. "!<br>";
            
		} else 
		{
			echo "Please register or log in.";
            include ('index.php');
			exit;
		}
include ('header.php');

?>

<!DOCTYPE HTML>
<html>
<head> 
 <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="main-container" >

<table style="margin-left:30px;" border = "2">
<caption>Book Informaion</caption>
<th>Book Title</th> <th>Book Author</th> <th>Publish Date</th> <th>View Book Details</th>

<?php
// Perform database query
	
	$connection = getConnection();
	
	$query = "SELECT * ";
	$query .= "FROM book ";
	$query .= "ORDER BY rating DESC";
	$result = mysqli_query($connection, $query);
	
	//test if fails
	if (!$result)
	{
		die ("Database query failed!");
	}

	//urlencode
 		
	

	//input date into table 
	while ($row = mysqli_fetch_assoc($result))
	{
		//output data from each row
		 $bookid = $row["bookID"];
		 $url_string = urlencode($bookid);

		 echo "<tr>";                
		 echo "<td>".$row["title"]."</td>";        
		 echo "<td>".$row["author"]."</td>";
		 echo "<td>".$row["publishDate"]."</td>";        
		 echo "<td>"."<a href='viewDetails.php?bookID=$url_string'>View Details<a>". "</td>";  
		 echo "</tr>";
	}


   ?>

</table>

<h1 style="padding-bottom:20px;"> <u><a href="index.php">Home </a></u> &nbsp <u><a href="menu.php">Menu </a></u> &nbsp <u><a href="logoff.php">Logoff</a></u> </h1>


</div>
</body>



</html>

<?php
include 'footer.php';
?>

