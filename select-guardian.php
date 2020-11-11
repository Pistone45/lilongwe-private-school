<?php
include_once("functions/functions.php");

$getGuardians = new Guardian();
$guardians = $getGuardians->getGuardians();

$getSubClasses = new Classes();
$sub_classes = $getSubClasses->getSubClasses();

if (isset($_POST['select_class'])) {

  $guardian_id = $_POST['guardian_id'];
  $sub_class = $_POST['sub_class'];

  $getClassPerSubClass = new Classes();
  $classes_id = $getClassPerSubClass->getClassPerSubClass($sub_class);
  $class_id = $classes_id['classes_id'];

  if ($class_id == 1 || $class_id==2 || $class_id==3) {
    $_SESSION['guardian_id'] = $_POST['guardian_id'];
    $_SESSION['sub_class'] = $_POST['sub_class'];
    header("location: add-student.php");

  }elseif($class_id==4 || $class_id==5 || $class_id==6 || $class_id==7){
    $_SESSION['guardian_id'] = $_POST['guardian_id'];
    $_SESSION['sub_class'] = $_POST['sub_class'];
    $_SESSION['class_id'] = $class_id;
    header("location: select-subject-options.php");
          
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Guardian | Lilongwe Private School</title>
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
        Select Guardian
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Select Guardian</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         <div class="box-header with-border">	
            </div>
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Lastname</th>
                  <th>Primary Phone</th>
        				  <th>Email</th>
        				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php
        $i = 0;
				if(isset($guardians) && count($guardians)>0){
					foreach($guardians as $guardian){ 
            $i++;   ?>
					<tr>
                  <td><?php echo $guardian['firstname']; ?></td>
                  <td><?php echo $guardian['middlename']; ?></td>
                  <td> <?php echo $guardian['lastname']; ?></td>
                  <td><?php echo $guardian['primary_phone']; ?></td>
        				  <td><?php echo $guardian['email']; ?> </td>
        				  <td><a data-toggle="modal" data-target="#my<?php echo $i; ?>Modal" href=""><i class="fa fa-plus"></i> Add Student</a></td>
                </tr>

<!-- Modal -->
<div id="my<?php echo $i; ?>Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Class</h4>
      </div>
      <div class="modal-body">
        <form action="select-guardian.php" method="POST">
        <input type="hidden" name="guardian_id" value="<?php echo $guardian['id']; ?>">
        <div class="form-group">
          <label>Select Class</label>
          <select name="sub_class" class="form-control">
          <?php
          if(count($sub_classes)>0){
            foreach($sub_classes as $row){ ?>
              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
              <?php
            }
          }else{
            echo "No classes Available";
          }
          ?>                            
          </select>
        </div>
        <button type="submit" name="select_class" class="btn btn-primary">Continue</button>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

					<?php
						
					}
				}
				?>
                
                </tbody>
                <tfoot>
                <tr>
                   <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Lastname</th>
                  <th>Primary Phone</th>
				  <th>Email</th>
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
