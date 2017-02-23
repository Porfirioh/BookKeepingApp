<?php 
    session_start();

       
       if (isset($_SESSION["user_name"])){
        echo "Welcome ".$_SESSION["user_name"]. "!<br>";
        $user_name = $_SESSION["user_name"];
        }
        else
        {
            echo "Please register or log in.";
			include ("index.php");
            exit;
        }
     include 'header.php';
?>

<?php 
	
    include("lib.php");

    $errors = [];

	if (isset($_POST["submit"]))
	{
        $title = sql_prep(valid_html($_POST["title"]));
        $author = sql_prep(valid_html($_POST["author"]));
        $date = sql_prep(valid_html($_POST["date"]));
        $isbn = sql_prep(valid_html($_POST["isbn"]));
        $price = sql_prep(valid_html($_POST["price"]));
        $note = sql_prep(valid_html($_POST["note"]));
        $status = sql_prep(valid_html($_POST["status"]));
        $rating = sql_prep(valid_html($_POST["rating"]));

        if (!isPresent($title))
            {
                $errors['title'] = "Error: Input title.";
            }

        if (!isPresent($author))
            {
                $errors['author'] = "Error: Input author.";
            }

        if (!dateFormat($date))
            {
                $errors['date'] = "Error: Input publish date in format mm/dd/yyyy.";
            }
    
        if (!isPresent($isbn))
            {
                $errors['isbn'] = "Error: Input isbn.";
            }
            else if(!uniqueisbn($isbn)){
                $errors['isbn'] = "Error: isbn $isbn already exist.";
            }
            else if(!isbnlen($isbn)){
                $errors['isbn'] = "Error: isbn must be a positive integer, no more than 13 digits long.";
            }
            
       if (!isPresent($price))
            {
                $errors['price'] = "Error: Input a numeric value for price";
            }
            else if (!isPositive($price)){
                $errors['price'] = "Error: Price must be a positive number";
            }

        if (count($errors) === 0) 
		    {

          $category = isset($_POST['category']) ? $_POST['category'] : '';


        if (class_exists("Book")){
   		$book = new Book();
    }
      
		$query = "INSERT INTO book ";
		$query .= "(title, author, publishDate, isbn, category, price, rating, status, note) ";
		$query .= "VALUES ";
		$query .= "('$title', '$author', '$date', '$isbn', '$category', '$price', '$rating', '$status', '$note' ) ";
		
        $connection = getConnection();
		
        $result = mysqli_query($connection, $query);

        echo "Book added.<br>";
        
        if (!$result)
		{
			die ("Inserting data failed. ".
				mysqli_error($connection).
			"(".mysqli_errno($connection). ")"
			);
		}

    $query = "SELECT bookID FROM book";        

    if ($result=mysqli_query($connection, $query)){
        $rowcount=sql_prep(mysqli_num_rows($result));
        mysqli_free_result($result);
    }
    else{
    echo " no results" ;
    }
        $query = "";

        $query = "INSERT INTO rating ";
		$query .= "(userName, bookID, rating) ";
		$query .= "VALUES ";
		$query .= "('$user_name', '$rowcount', '$rating' ) ";
		$result = mysqli_query($connection, $query);
        $book->setBookID($rowcount);
	    $book->updateOverallRating();
		
        echo "Rating updated.<br>";
		
        if (!$result)
		{
			die ("Inserting data failed. ".
				mysqli_error($connection).
			"(".mysqli_errno($connection). ")"
			);
		}
                    
            }

    }
    	else
	{
		$title = "";
		$author = "";
        $date = "";
        $isbn = "";
		$price = "";
        $category = "";
        $note = "";
        $rating = "";
        $status = "";

	}

   
?>



<!DOCTYPE HTML>
<html>
<head> 
 <link rel="stylesheet" href="css/style.css">

</head>


<body>

<div class="main-container" >

<form style="margin-left:30px;padding-top:20px;text-align: center;" method = "POST" action= "addBook.php">

Title: <input type ="text" name="title" value = "<?php if (isset($_POST['title'])) echo $_POST['title']; ?>">

				  <?php  display_errors_in_red('title', $errors); 
				  ?>  
                  
                  <br><br>


Author: <input type ="text" name="author" value= "<?php if (isset($_POST['author'])) echo $_POST['author']; ?>"  > 
                
                  <?php  display_errors_in_red('author', $errors); 
				  ?>

                  <br><br>

Publish Date: <input type ="text" name="date" value = "<?php if (isset($_POST['date'])) echo $_POST['date']; ?>">

				  <?php  display_errors_in_red('date', $errors); 
				  ?>  <br><br>

ISBN: <input type ="text" name="isbn" value ="<?php if (isset($_POST['isbn'])) echo $_POST['isbn']; ?>"  > 
                
                  <?php  display_errors_in_red('isbn', $errors); 
				  ?>  
                  
                  <br><br>

Category: 
          <input type = "radio" name = "category" value "Hardcover" checked="checked">Hardcover
          <input type = "radio" name = "category" value = "Paperback">Paperback
          <input type = "radio" name = "category" value = "eBook">eBook
         
        <br><br>


Price($): <input type ="text" name="price" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>"  > 
                
                  <?php  display_errors_in_red('price', $errors); 
				  ?>  <br><br>

Status: <select name = "status">
    <option value="instock">in stock</option>
    <option value="out_of_stock">out of stock</option>
    <option value="pre_order">pre-order</option>
    <option value="n/a">N/A</option>
        </select>
        <br><br>
Rating: <select name = "rating">
    <option value="0">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
        </select>
        <br><br>

Note:<textarea name="note" rows="4" col="50"> </textarea>


<br><br>
<input type ="submit" value="Add Book" name="submit"> &nbsp; &nbsp;

</form>

<h1 style="padding-bottom:20px;"> <u><a href="index.php">Home </a></u> &nbsp <u><a href="menu.php">Menu </a></u> &nbsp <u><a href="logoff.php">Logoff</a></u> </h1>


</body>

</div>

</html>

<?php
include 'footer.php';
?>

