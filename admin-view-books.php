<?php
include_once("functions/functions.php");

$getAllBooks = new Staff();
$books = $getAllBooks->getAllBooks();

$getSubClasses = new Classes();
$levels = $getSubClasses->getSubClasses();

if (isset($_POST['book_count'])) {
  $book_id = $_POST['book_id'];
  $old_count = $_POST['old_count'];
  $new_count = $_POST['new_count'];

  $count = $old_count + $new_count;

  $increaseBookCount = new Staff();
  $increaseBookCount->increaseBookCount($book_id, $count);
}


if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  $deleteBook = new Librarian();
  $deleteBook->deleteBook($id);
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Books | Lilongwe Private School</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
        View Books <a href="books-pdf.php"><button class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Download PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></a>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">View Books</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
            <?php
              if(isset($_SESSION["book_deleted"]) && $_SESSION["book_deleted"]==true)
                {
                  echo "<div class='alert alert-warning'>";
                  echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                  echo "<strong>Success! </strong>"; echo "You have successfully Deleted a Book";
                  unset($_SESSION["book_deleted"]);
                  echo "</div>";
                 header('Refresh: 4; URL= admin-view-books.php');
                  }
              ?>

              <?php
              if(isset($_SESSION["count_increased"]) && $_SESSION["count_increased"]==true)
                {
                  echo "<div class='alert alert-success'>";
                  echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                  echo "<strong>Success! </strong>"; echo "You have successfully increase a Book count";
                  unset($_SESSION["count_increased"]);
                  echo "</div>";
                 header('Refresh: 4; URL= admin-view-books.php');
                  }
              ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Publication Year</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php
        $i = 0;
				if(isset($books) && count($books)>0){
					foreach($books as $book){ 
            $i++;   ?>
					<tr>
                  <td><?php echo $book['book_id']; ?></td>
                  <td><?php echo $book['title']; ?></td>
                  <td><?php echo $book['author']; ?></td>
                  <td><?php echo $book['year_of_publication']; ?></td>
                  <td><button type="button" class="btn btn-primary"> <span class="badge"><?php echo $book['count'];?> </span> Available</td></button>

                  <td><?php if($book['count'] == 0 ){  ?><button class="btn btn-danger">Out of Stock</button> <?php } else{  ?><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo $i; ?>">Increase Books</button> <?php } ?></td>
                  <td><a href="admin-view-books.?delete=<?php echo $book['book_id']; ?>"><i class="fa fa-trash"></i> Delete</a></td>

<!-- Modal -->
<div id="<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Increase the Book count</h4>
      </div>
      <div class="modal-body">
                          <!-- form start -->
            <form role="form" action="admin-view-books.php" method="POST">
      
              <div class="box-body">
                <input hidden="" type="text" name="book_id" value="<?php echo $book['book_id']; ?>">
                <input type="text" name="old_count" value="<?php if(isset($book)){ echo $book['count'];} ?>" hidden>
                <div class="form-group">
                  <label for="fatherName">Book Count</label>
                  <input type="number" placeholder="Example 5" class="form-control" name="new_count" required>
                </div>

                <button type="submit" name="book_count" class="btn btn-primary">Submit</button>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


                </tr>
					<?php
						
					}
				}
				?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Publication Year</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once("footer.html"); ?>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
