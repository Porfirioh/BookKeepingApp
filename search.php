<?php 
    session_start();
	include 'lib.php';

?>


<?php

	if (isset($_POST["submit"]))
	{
        $search = $_POST["search"];
        
		$book_id_search = sql_prep(valid_html($search));

	}

if (isset($_SESSION["user_name"]))
		{
			echo "Welcome ".$_SESSION["user_name"]. "!<br>";
            
		} else 
		{
			echo "Please register or log in.";
            include ('index.php');
			exit;
		}

include 'header.php';

$connection = getConnection();

// 2. Perform database query
	$query = "SELECT * ";
	$query .= "FROM book ";
    $query .= "WHERE title LIKE '%$book_id_search%' ";
	$query .= "ORDER BY rating DESC";
	
	$result = mysqli_query($connection, $query);
	
	//if query fails
	if (!$result)
	{
		die ("Database query failed!");
	}
?>


<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="css/style.css">

</head>
<body>

<div class = "main-container">

<h1>Book Details: </h1>

<table style="margin-left:30px;" border = "2">
<caption>Book Informaion</caption>
<th>Book Title</th> <th>Book Author</th> <th>Publish Date</th> <th>View Book Details</th>

<?php
	while ($row = mysqli_fetch_assoc($result))
	{
		//output data from each row
		 $bookid = $row["bookID"];

		 echo "<tr>";                
		 echo "<td>".$row["title"]."</td>";        
		 echo "<td>".$row["author"]."</td>";
		 echo "<td>".$row["publishDate"]."</td>";        
		 echo "<td>"."<a href='viewDetails.php?bookID=$bookid'>View Details<a>". "</td>";  
		 echo "</tr>";
	}


 ?>

</table>

<h1 style="padding-bottom:20px;"> <u><a href="index.php">Home </a></u> &nbsp <u><a href="menu.php">Menu </a></u> &nbsp <u><a href="logoff.php">Logoff</a></u> </h1>


   <?php 
	//4. Release returned data
	mysqli_free_result($result);
   ?>


</table>

</div>
</body>



</html>

<?php
include 'footer.php';
?>

