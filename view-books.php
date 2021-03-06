<?php
include_once("functions/functions.php");

$getAllBooks = new Staff();
$books = $getAllBooks->getAllBooks();

$getSubClasses = new Classes();
$levels = $getSubClasses->getSubClasses();

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
        View Books
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="librarian-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
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

                  <td><?php if($book['count'] == 0 ){  ?><button class="btn btn-danger">Out of Stock</button> <?php } else{  ?><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo $i; ?>">Lend Book</button> <?php } ?></td>
                  <td><a href="edit-book.php?book_id=<?php echo $book['book_id']; ?>"><i class="fa fa-edit"></i> Edit Book</a></td>
          <td><a href="delete-book.php?book_id=<?php echo $book['book_id']; ?>"><i class="fa fa-trash"></i> Delete</a></td>

                        <!-- Modal -->
<div id="<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Choose Class</h4>
      </div>
      <div class="modal-body">
                          <!-- form start -->
            <form role="form" action="select-student.php" method="POST">
      
              <div class="box-body">
                <input hidden="" type="text" name="book_id" value="<?php echo $book['book_id']; ?>">
           <div class="form-group">
                  <label>Choose Class </label>
                  <select name="level" class="form-control">
                  <option selected="">Select Class.....</option>
          <?php
            if(isset($levels) && count($levels)>0){
              foreach($levels as $level){ ?>
                <option value="<?php echo $level['id']; ?>"><?php echo $level['name']; ?></option>
              <?php
                
              }
            }
          ?>
        
                  </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
