 <!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Book Lovers </title>
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
    <h1 class="container">Save All Your Books</h1>
    <?php
    //initializing our variables
    $book_id = null;
    $title = null;
    $genre = null;
    $review = null;
    $name = null;
    $email = null;
    $link = null;
    $photo = null;
    
    if(!empty($_GET['book_id']) && (is_numeric($_GET['book_id'])))
    {
        //grab the book from Url string
        
        $book_id = $_GET['book_id'];
        
        //connect to database
        require('db.php');
        require('appvars.php');
        
        $sql = "SELECT * FROM books WHERE book_id = :book_id";
        //prepare
        $cmd = $conn->prepare($sql);
        
        //bind
        $cmd->bindParam(':book_id', $book_id);
        
        //execute
        $cmd->execute();
        
        //use the fetch all methos to store info
        $books = $cmd->fetchAll();
        
        foreach($books as $book){
            
            $title = $book['title'];
            $genre = $book['genre'];
            $review = $book['review'];
            $name = $book['name'];
            $email = $book['email'];
            $link = $book['link'];
            $extract_photo = UPLOADPATH . $book['book_image'];
            
        }
        $conn = null;
        
    }
    ?>
    
    
  <div class="container">
   
   <a href="books.php"> View All Books </a>
   
   <form enctype="multipart/form-data" method="post" action="save_book.php">
       
     <div class="form-group">
       <label> Title: </label>
       <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
     </div>
       
    <div class="form-group">
       <label> Genre: </label>
       <input type="text" name="genre" class="form-control" value="<?php echo $genre ?>">
     </div>
     
     <div class="form-group">   
         <label> Review: </label>         
         <textarea name="review" class="form-control"><?php echo ($review); ?></textarea>     
     </div>
       
     <div>    
       <label> Name: </label>
       <input type="text" name="name" class="form-control" value="<?php echo $name ?>">
     </div>
       
     <div class="form-group">
       <label> Email: </label>
       <input type="text" name="email" class="form-control" value="<?php echo $email ?>">
     </div>
     
     <div class="form-group">
       <label> Link: </label>
       <input type="text" name="link" class="form-control" value="<?php echo $link ?>">
     </div>

     <div class="form-group">
       <label> Upload File </label>
         
<!--         show the exist photo of the book if edit.-->
           <?php
            if(!empty($extract_photo)){
                echo '<img src="' . $extract_photo . '" alt="Book cover page" height="100" width="60"/>';
            }

            ?>
            
       <input type="file" name="photo" class="form-control" >
       <p class="help-block">Only GIF, JPEG, JPG, or PNG allowed</p>

     </div>
       
     <input name="book_id" type="hidden", value="<?php echo $book_id ?>">
       
     <input type="submit" value="Submit Book" class="btn btn-primary" >

     
   </form>
  </div>
</body>
</html>