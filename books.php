<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Book List</title>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="container">
    <h1>Awesome Books List!</h1>
    
    <a href="index.php"> Add A New Book </a>
    
    <?php
      
      ob_start();
      require_once('appvars.php');
      
    try{
    //access the database 
    require('db.php');
    
    // set up SQL query 
    
    $sql = "SELECT * FROM books";
    
    // prepare & execute query and then story results 
    $cmd = $conn->prepare($sql); 
    $cmd->execute(); 
    $books = $cmd->fetchAll(); 
    
    echo '<table class="table table-striped table-hover"><thead><th>Title</th><th>Genre</th><th>Review</th><th>Name</th><th>Email</th><th>Link</th><th> Photo </th><th> Edit </th><th> Delete </th></thead><tbody>';
    
    //loop through data create a new table row for each record 
    
    foreach ($books as $book) {
      echo '<tr>
          <td>' . $book['title'] . '</td>
          <td>' . $book['genre'] . '</td>
          <td>' . $book['review'] . '</td>
          <td>' . $book['name'] . '</td>
          <td>' . $book['email'] . '</td>
          <td><a href="' . $book['link'] . '">Buy Book</a></td>
          <td><img src="' . UPLOADPATH . $book['book_image'] . '" alt="Book cover page" height="100" width="60"/> </td>
          <td><a href="index.php?book_id=' . $book['book_id']. '">Edit </a></td>
          <td><a href="delete-book.php?book_id=' . $book['book_id'] .'"onclick="return confirm(\'You are going to delete a record, are you sure?\'); "> Delete </a></td>
      </tr>';
  
    }
    
    //close the table
    
    echo   '</tbody></table>';
    
    $conn = NULL; 
    }
  catch(Exception $e){
      //set us an email to let us know something went wrong
      mail('luluwheelan@gmail.com', 'Books Database problem', $e);
      header('location:error.php');
  }
  ob_flush();
  ?>
  </div>

</body>
</html>