<!DOCTYPE html>
<html>
<head>
    <title>Saving your Books</title>
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

<a href="index.php">Add A New Book</a><br>
<a href="books.php">View All Books</a><br>
<?php
   require_once('appvars.php');
// store the form inputs in variables
$title = filter_input(INPUT_POST, 'title');
$genre = filter_input(INPUT_POST, 'genre');
$review = filter_input(INPUT_POST, 'review');
$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$link = filter_input(INPUT_POST, 'link', FILTER_VALIDATE_URL); 
$photo = $_FILES['photo']['name'];
$photo_type = $_FILES['photo']['type'];
$photo_size = $_FILES['photo']['size'];

// add book_id in case we are editing
$book_id = null;
$book_id = $_POST['book_id'];
    
//create a flag to track the completion status of the form 
$ok = true;

    
if(empty($title)) {
  echo "<p>Title is required</p>";
  $ok = false; 
}
  
if(empty($genre)) {
  echo "<p>Genre is required</p>";
  $ok = false; 
}
    
if(empty($review)) {
  echo "<p>Review is required</p>";
  $ok = false; 
}
  
if(empty($name)) {
  echo "<p>Name is required</p>";
  $ok = false; 
}
  
if(empty($email)) {
  echo "<p>Email is required</p>";
  $ok = false; 
}
  
if($email === FALSE) {
  echo "<p>Email must be valid</p>";
  $ok = false; 
}


if(empty($link)) {
  echo "<p>Link is required</p>";
  $ok = false; 
}
 
if ($link === FALSE) {
    echo(" <p>Link is not a valid URL</p>");
    $ok = false;
}
    
//If edit not include a image, will only update the data not include photo.
if((empty($photo)) && ($ok === TRUE) && (!empty($book_id))){

    require_once('db.php');

    $sql = "UPDATE books SET title = :title, genre = :genre, review = :review , name = :name, email = :email, link = :link WHERE book_id = :book_id";

    // set up a command object
    $cmd = $conn->prepare($sql);

    // fill the placeholders with  input variables
    $cmd->bindParam(':title', $title, PDO::PARAM_STR, 50);
    $cmd->bindParam(':genre', $genre, PDO::PARAM_STR, 50);
    $cmd->bindParam(':review', $review, PDO::PARAM_STR, 250);
    $cmd->bindParam(':name', $name, PDO::PARAM_STR, 50);
    $cmd->bindParam(':email', $email, PDO::PARAM_STR, 50);
    $cmd->bindParam(':link', $link, PDO::PARAM_STR, 250);
    $cmd->bindParam(':book_id', $book_id);
    
    
    // execute the update
    $cmd->execute();

    // show message
    echo "Info updated, thanks!";

    // disconnecting
    $cmd->closeCursor();
        
    }

//If user uploaded a photo, validate the photo and update or insert the data to database
 else if ((($photo_type == 'image/gif') ||  ($photo_type == 'image/jpg') || ($photo_type == 'image/jpeg') || ($photo_type == 'image/pjpeg') || ($photo_type == 'image/png'))
          && ($photo_size > 0) && ($photo_size <= MAXFILESIZE)) {
          if ($_FILES['photo']['error'] == 0) {
            // Move the file to the target upload folder
            $target = UPLOADPATH . $photo;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
              if($ok === TRUE) {
  
                // connecting to the database
                require_once('db.php');


                //if we have an existing book id, run an update
                if(!empty($book_id)){
                    $sql = "UPDATE books SET title = :title, genre = :genre, review = :review , name = :name, email = :email, link = :link, book_image = :photo WHERE book_id = :book_id";
                }
                else{
                    // set up an SQL command to save the new game
                $sql = "INSERT INTO books (title, genre, review, name, email, link, book_image) VALUES (:title, :genre, :review, :name, :email, :link, :photo)";
                }

                // set up a command object
                $cmd = $conn->prepare($sql);

                // fill the placeholders with  input variables
                //this part need be fixed
                $cmd->bindParam(':title', $title, PDO::PARAM_STR, 50);
                $cmd->bindParam(':genre', $genre, PDO::PARAM_STR, 50);
                $cmd->bindParam(':review', $review, PDO::PARAM_STR, 250);
                $cmd->bindParam(':name', $name, PDO::PARAM_STR, 50);
                $cmd->bindParam(':email', $email, PDO::PARAM_STR, 50);
                $cmd->bindParam(':link', $link, PDO::PARAM_STR, 250);
                $cmd->bindParam(':photo', $photo, PDO::PARAM_STR, 20);


                if(!empty($book_id)){
                    $cmd->bindParam(':book_id', $book_id);
                }
                // execute the insert
                $cmd->execute();

                // show message
                echo "Book Saved, thanks!";

                // disconnecting
                $cmd->closeCursor();
            }
    
            }
            else {
              echo '<p class="error">Sorry, there was a problem uploading your photo</p>';
                $ok = false; 
            }
          } 
        } 
        else {
          echo '<p class="error">The image must be a GIF, JPEG, JPG, or PNG image file no greater than ' . (MAXFILESIZE / 1024) . ' KB in size.</p>';
            $ok = false; 
        }

    
    

?>
</body>
</html>