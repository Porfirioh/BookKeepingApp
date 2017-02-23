<?php
session_start();


?>

<?php
if (isset($_SESSION["user_name"]))
		{
			echo "Welcome ".$_SESSION["user_name"]. "!<br>";
            
		} else 
		{
			echo "Please register or log in.";
			include "index.php";
			exit;
		}
include 'header.php';

    ?>

<!DOCTYPE HTML>
<html>

<head> 
 <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="main-container">

<br>
<h1>Menu:</h1>
<h1> <a href="addBook.php"> Add a Book </a> </h1>
<h1> <a href="viewBooks.php"> View Books </a> </h1>

<h1>Search by book title: </h1>

<form method = "POST" action= "search.php">

<input type = "text" name = "search" > 
<input type ="submit" value="Search" name="submit">

</form>

<h1> <u><a href="index.php">Home </a></u> &nbsp&nbsp <u><a href="logoff.php">Logoff </a></u> </h1>
<br>

</div>

</body>



</html>

<?php 
include 'footer.php';
?>