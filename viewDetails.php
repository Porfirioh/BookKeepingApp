<?php 
	session_start();
	include ("lib.php");

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




<?php
	$connection = getConnection();

	if (isset($_POST["submit"]))
	{

		$rating = sql_prep(valid_html($_POST["rating"]));
		$book_id = sql_prep(valid_html($_GET['bookID']));

		$user = $_SESSION["user_name"];
	
		$update = "UPDATE rating SET rating = '$rating' WHERE userName = '$user' AND bookID = '$book_id' ";

		$result = mysqli_query($connection, $update);

		   if (!$result)
		{
			die ("Inserting data failed. ".
				mysqli_error($connection).
			"(".mysqli_errno($connection). ")"
			);
		}

		if(mysqli_affected_rows($connection) == 0){
		
		echo "Thank you for rating this book.";
		$query = "INSERT INTO rating ";
		$query .= "(userName, bookID, rating) ";
		$query .= "VALUES ";
		$query .= "('$user', '$book_id', '$rating')";

		$result = mysqli_query($connection, $query);

		$book = new Book();
		$book->setBookID($book_id);
	    $book->updateOverallRating();
		}
		else{
			echo "You have already rated this book. Updating rating...";

			$book = new Book();
			$book->setBookID($book_id);
	   		$book->updateOverallRating();
		}

	}

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


// 2. Perform database query
	$book_id = $_GET['bookID'];
	$query = "SELECT * ";
	$query .= "FROM book ";
    $query .= "WHERE bookID = $book_id ";
	
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

<?php

    
	while ($row = mysqli_fetch_assoc($result))
	{
		//output data from each row      
		 echo "BookID: ".$row["bookID"]."<br>";                       
		 echo "Title: ".$row["title"]."<br>";        
		 echo "Author: ".$row["author"]."<br>";        
		 echo "Publish Date: ".$row["publishDate"]."<br>";
         echo "ISBN: ".$row["isbn"]."<br>";
         echo "Category: ".$row["category"]."<br>";
         echo "Price: ".$row["price"]."<br>";
         echo "Status: ".$row["status"]."<br>";
         echo "Rating: ".$row["rating"]."<br>";
         echo "Note: ".$row["note"]."<br>";
		
	}

// URL sanitization
	$sanitize = true;
	$book_id = isset($_GET['bookID'])? $_GET['bookID'] : 'nothing yet';
	$url = "$book_id";
	
	if ($sanitize)
	{
		$url = urlencode($url);
	}
	
 ?>

<form  name = "book" method = "POST" action= "viewDetails.php?bookID=<?php echo urlencode($url); ?>" >
Rate this book: <select name = "rating">
    <option value="0">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
        </select>
        <br><br>

<input type ="submit" value="Submit" name="submit"> &nbsp; &nbsp;

</form>


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

