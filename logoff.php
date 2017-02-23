<?php
session_start();


if (isset($_SESSION["user_name"]))
		{
			echo "Goodbye ".$_SESSION["user_name"]. "...<br>";
            $_SESSION = array();
            session_unset();
            
		} else 
		{
			echo "userName does not exist <br>";
		}


session_destroy();
    ?>

<!DOCTYPE HTML>
<html>
<head>
</head>
<body>

<span><a href = "index.php">Home</a> </span>

</body>

</html>	
