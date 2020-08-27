<?php
include_once("functions/functions.php");
if (isset($_GET['book_id'])) {
  $id = $_GET['book_id'];

  $deleteBook = new Librarian();
  $deleteBook->deleteBook($id);

  header("location: view-books.php");

} else{
	echo "Failed";
}

?>