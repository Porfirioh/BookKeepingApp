<?php 
    session_start();
    $_SESSION = array();
    session_unset();
    ob_start();
    include 'header.php';
?>
<?php
    
    include ("lib.php");

    $message = " ";

	if (isset($_POST["submit"]))
	{
		// form submitted
        
		$user_name = sql_prep($_POST['user_name']);
		$password = sql_prep($_POST["password"]);

		$message = "Logging in {$user_name} <br>";

        $query = "SELECT password ";
        $query.= "FROM user ";
        $query.= "WHERE password = md5($password) ";
        $query.= "AND userID = '$user_name' ";
        
        $connection = getConnection();
    
	    $result = mysqli_query($connection, $query);

        //    if (!$result)
		// {
		// 	die ("Inserting data failed. ".
		// 		mysqli_error($connection).
		// 	"(".mysqli_errno($connection). ")"
        //     );
		// }

        if ($result){

        $row=mysqli_fetch_assoc($result);
       
        if(md5($_POST["password"]) == $row["password"]){
            
             $_SESSION["user_name"] = $_POST["user_name"];
             $_SESSION["password"] = $_POST["password"];

             if (isset($_POST["cookiepw"])){
                 setcookie("userName", $user_name, time()+60*60*24*30);
	             setcookie("password", $password, time()+60*60*24*30);

             }
             
			 $message = "Successful login. <br>";
             
             header("location:menu");
            
        }
        }
        else 
		{
			$message = "Login failed. <br>";
		}

        if (isset($_POST["clearcookies"])){
            setcookie("userName", $user_name, time()-3600);
            unset ($_COOKIE['userName']);
	        setcookie("password", $password, time()-3600);
            unset ($_COOKIE["password"]);
             }    

	}
	else
	{
		$user_name = " ";
        $password = " ";
	}

?>
<?php
    echo $message;
?>


<!DOCTYPE HTML>
<html>
<head> 
 <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="main-container">

<br><br>

<form name ="login" method ="post" action="login.php">

User Name: <input type = "text" name = "user_name" value = "<?php if (isset ($_COOKIE["userName"])) echo $_COOKIE["userName"]; ?>" ><br><br>

Password:  <input type = "password" name = "password" value = "<?php if (isset ($_COOKIE["password"])) echo $_COOKIE["password"]; ?>" > <br><br>

<input type ="submit" value="Login" name="submit"><br><br>

Remember me? <input type = "checkbox" name ="cookiepw" > <br><br>
Forget me? <input type ="checkbox" name = "clearcookies"  >

</form>


<h1> <u><a href="index.php">Home </a></u> </h1>


<br><br>

</div>

</body>

</html>

<?php
include 'footer.php';
?>

<?php
    ob_end_flush();    
?>