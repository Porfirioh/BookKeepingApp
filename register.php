<?php 
    session_start();
    ob_start();

    include ("lib.php");

	if (isset($_POST["submit"]))
	{
        $message = " ";
		// form submitted
		$user_name = sql_prep(htmlspecialchars($_POST["user_name"]));
		$password = htmlspecialchars($_POST["password"]);
        $c_pass = htmlspecialchars($_POST["c_password"]);

if (isPresent($user_name) && isPresent($password) && isPresent($c_pass) && $password == $c_pass && $password != ' '){

        $md5_password = sql_prep(md5($password));

		$query = "INSERT INTO user ";
		$query .= "(userID, password) ";
		$query .= "VALUES ";
        $query .= "('$user_name', '$md5_password') ";
        
        $connection = getConnection();
	    $result = mysqli_query($connection, $query);

		echo "Registration Complete.<br>";

           if (!$result)
		{
			die ("Inserting data failed. ".
				mysqli_error($connection).
			"(".mysqli_errno($connection). ")"
            );
		}
        else
            //include ("menu.php");
             $_SESSION["user_name"] = $_POST["user_name"];
             $_SESSION["password"] = $_POST["password"];
             


            header("Location: menu");
            exit;
}
        else{
            echo "Registration failed<br>";
        }
       

    }

?>

<?php
include 'header.php';
?>

<!DOCTYPE HTML>
<html>
<head> 
 <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="main-container">

<br><br>

<form class="left" name ="login" method ="post" accept-charset = "utf-8" action="register.php">

User Name: <input type = "text" name = "user_name" value = "" ><br><br>

Password:  <input type = "password" name = "password" value = "" > <br><br>

Confirm Password:  <input type = "password" name = "c_password" value = "" > <br><br>



<input type ="submit" value="Register" name="submit"><br><br>

</form>


<h1> <u><a href="index.php">Home </a></u> </h1>


<br><br>

</div>

</body>

</html>

<?php
include 'footer.php';
 ob_end_flush();
?>
