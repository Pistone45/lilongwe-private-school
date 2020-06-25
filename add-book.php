<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

if(isset($_POST['submit'])){

    $id = $_POST['id'];
	  $title = $_POST['title'];
	  $author = $_POST['author'];
    $year_of_publication = $_POST['year_of_publication'];
    $count = $_POST['count'];

	 $addBook = new Staff();
	 $addBook->addBook($id, $title,$author,$year_of_publication,$count);
	

	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Add Book | Lilongwe Private School</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include_once("header.html"); ?>
  <!-- Left side column. contains the logo and sidebar -->
   <?php include_once('sidebar.html'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Book
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="librarian-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Add Book</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<!-- form start -->
            <form role="form" action="add-book.php" method="POST" enctype="multipart/form-data">
			<?php
                            if(isset($_SESSION["book-added"]) && $_SESSION["book-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully added a Book";
                                unset($_SESSION["book-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= view-books.php');
                            }
							?>
      <div class="row box box-primary">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
              <div class="box-body">

                <div class="form-group">
                  <label for="fatherName">Book ID</label>
                  <input class="form-control" placeholder="ID should be unique" name="id" required>
                </div>
                <div class="form-group">
                  <label for="fatherName">Title</label>
                  <input class="form-control" placeholder="Enter Book Title" name="title" required>
                </div>

                <div class="form-group">
                  <label for="fatherName">Author</label>
                  <input class="form-control" placeholder="Enter Author Name" name="author" required>
                </div>
	         
				        <div class="form-group">
                  <label for="fatherName">Year of Publication</label>
                  <input type="number" min="1900" placeholder="Example 2014" max="3000" class="form-control" name="year_of_publication" required>
                </div>
                <div class="form-group">
                  <label for="fatherName">Book Count</label>
                  <input type="number" placeholder="Example 5" class="form-control" name="count" required>
                </div>
              
                
              </div>
			  
              <!-- /.box-body -->
			  <div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Submit</button>
              </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            
		
			
			
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
	  </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once("footer.html"); ?>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
