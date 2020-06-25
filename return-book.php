<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

if (isset($_GET['id'])) {
$id = $_GET['id'];
$book_id = $_GET['book_id'];

$getBookCount = new Staff();
$getBookCount = $getBookCount->getBookCount($book_id);
$current_count = $getBookCount['count'];

$returnBook = new Staff();
$returnBook = $returnBook->returnBook($id, $current_count, $book_id);

}


$getBorrowedBooks = new Staff();
$books = $getBorrowedBooks->getBorrowedBooks();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lilongwe Private School| Return Book</title>
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
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

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
        Return a Book
      </h1>
      <ol class="breadcrumb">
        <li><a href="student-index.php"><i class="fa fa-dashboard"></i> Return Book</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
        <div class="box box-primary">
          <?php
                  if(isset($_SESSION["book-returned"]) && $_SESSION["book-returned"]==true)
                  { ?>
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> You have successfully returned a Book
            </div>  <?php
            unset($_SESSION["book-returned"]);
                      }
              ?>
           <div class="box-header">
              <h3 class="box-title">All Borrowed Books</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Book ID</th>
                  <th>Book Title</th>
                  <th>Student Name</th>
                  <th>Class Name</th>
                  <th>Date Borrowed</th>
                  <th>Days Remaining</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($books) && count($books)>0){
          foreach($books as $book){ ?>
            <tr>
              <td><?php echo $book['book_id']; ?></td>
              <td><?php echo $book['title']; ?></td>
              <td><?php echo $book['student_name']; ?></td>
              <td><?php echo $book['sub_class_name']; ?></td>
              <td><button class="btn"><?php $date = date_create($book['date_borrowed']); echo date_format($date,"d, M Y") ?></button>
              </td>
              <td><?php  $date = round(abs(strtotime($book['due_date']) - strtotime($book['date_borrowed']))/86400); if($date <= 0){  ?>
                  <button class="btn btn-danger">0 Days. Due Date Passed!</button>
                <?php

            }else{  ?>
                <p style="font-size: 20px;"><span class="label label-default">
                <?php echo$date = round(abs(strtotime($book['due_date']) - strtotime($book['date_borrowed']))/86400); ?></span> Days</p><?php
            } ?>

                  
                </td>
              <td><a href="return-book.php?id=<?php echo $book['id']; ?>&book_id=<?php echo $book['book_id'];  ?>"><button class="btn btn-primary">Return</button> </a></td>
               
            </tr>
          <?php
            
          }
        }
        ?>
                
        
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
            
        </div>
        </div>
        
        <!-- ./col -->
      </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
  </script>
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
