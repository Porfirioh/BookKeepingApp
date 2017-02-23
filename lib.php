<?php

//Create a database connection
function getConnection(){
	$dbhost = "db-mysql.zenit";
	$dbuser = "bti320_163a25";
	$dbpass = "ggXT9488";
	$dbname = "bti320_163a25";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if (mysqli_connect_errno())
	{
		die("Database connection failed: ".
			mysqli_connect_error().
			" ( ". mysqli_connect_errno(). " )"
		);
	}

    return $connection;
}

function valid_html($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function sql_prep($string)
	{
		global $connection;
		if ($connection)
		{
			return mysqli_real_escape_string($connection, $string);
		}else {
			return addslashes($string);
		}
	}





function isPresent($val)
{
	return isset($val) && !trim(empty($val)) && strlen(trim($val)) != 0;
}

function isPositive($price)
{
    return $price > 0;
}

function dateFormat($date)
{

return (preg_match("/(\d{2})\/(\d{2})\/(\d{4})$/",$date));

}

function uniqueisbn($isbn)
{		
		$isbnExist = "SELECT isbn ";
		$isbnExist .= "FROM book ";
		$isbnExist .= "WHERE isbn = $isbn;";

		$connection = getConnection();
		$result = mysqli_query($connection, $isbnExist);

		return mysqli_num_rows($result) == 0;

}

function isbnlen($isbn){

		$isbnpatt = '/^[0-9]{1,13}$/';
		return preg_match($isbnpatt,$isbn);

}


// display error message in red
function display_errors_in_red($errorMsg, $errorsArr)
{
	
	if (array_key_exists($errorMsg, $errorsArr))
	{
		 echo "<span style = 'color:red'> * ";
		 echo $errorsArr[$errorMsg];
		 echo "</span>";
	}		
}

class DB{
	private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbName;
    private $dbConnection;
    private $dbError;
	
    public function __construct()
	{
        $lines=file('/home/bti320_163a25/secret/topsecret.txt');
        $this->dbHost=trim($lines[0]);
        $this->dbUser=trim($lines[1]);
        $this->dbPassword=trim($lines[2]);
        $this->dbName=trim($lines[3]);
        $this->dbError = '';
        $this->dbConnection = $this->connectDB();
        
	}

    public function connectDB()
    {
        $connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);

        if ($connection->connect_errno){ 
            $this->dbError = "Database connection failed ". $connection->connect_error. 
            " ( " . $connection->connect_errno. " )" ;
        }
        echo $this->dbError;

        return $connection;
    }

    public function query($query)
    {
        $result = $this->dbConnection->query($query);
        return $result;
        //$result = mysqli_query($this->dbConnection, $query);
        //return $result;
    }

    public function getErrorMsg()
    {
        return $this->dbError;
    }

    public function __destruct()
    {
        //print "Destroying " . $this->dbName . " <br>";
        $this->dbConnection->close();
    }

}



class Book{

    private $bookID;
    
    public function __construct()
    {
        $this->bookID = 0;
    }

    public function getBookID()
    {
        return $this->bookID;
    }

    public function setBookID($bID)
    {
        $this->bookID = $bID;
    }    

    public function updateOverallRating()
    {
        $db = new DB;
        $update = 'UPDATE book SET rating = (SELECT avg(rating) FROM rating WHERE bookID = ' .
        $this->bookID .' GROUP BY bookID) WHERE bookID =' . $this->bookID. ';';

        $result = $db->query($update);
        
        $r = 'SELECT AVG(rating) FROM rating WHERE bookID = ' .$this->bookID;
		$r_result = $db->query($r);

		$row = mysqli_fetch_assoc($r_result);
		echo "Book id:" .$this->bookID. " rating was updated to " .round($row['AVG(rating)'],3). "<br>";

        return $result;
    }


}

?>


