<?php
include_once("functions/functions.php");

$getAllUsers = new User();
$users = $getAllUsers->getAllUsers();

if (isset($_GET['deactivate'])) {
  $username = $_GET['deactivate'];

  $disableSpecificUser = new User();
  $disableSpecificUser = $disableSpecificUser->disableSpecificUser($username);
}

if (isset($_GET['activate'])) {
  $username = $_GET['activate'];

  $enableSpecificUser = new User();
  $enableSpecificUser = $enableSpecificUser->enableSpecificUser($username);
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>All users | Lilongwe Private School</title>
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
        All System Users
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">All users</a></li>
       
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
              if(isset($_SESSION["user_activated"]) && $_SESSION["user_activated"]==true)
                {
                  echo "<div class='alert alert-success'>";
                  echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                  echo "<strong>Success! </strong>"; echo "You have successfully Activated a User";
                  unset($_SESSION["user_activated"]);
                  echo "</div>";
                 header('Refresh: 4; URL= view-users.php');
                  }
              ?>

              <?php
              if(isset($_SESSION["user_deactivated"]) && $_SESSION["user_deactivated"]==true)
                {
                  echo "<div class='alert alert-warning'>";
                  echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                  echo "<strong>Success! </strong>"; echo "You have successfully Deactivated a User";
                  unset($_SESSION["user_deactivated"]);
                  echo "</div>";
                 header('Refresh: 4; URL= view-users.php');
                  }
              ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Username</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Lastname</th>
                  <th>Role</th>
        				  <th>Date Added</th>
        				  <th>Status</th>
                </tr>
                </thead>
                <tbody>
				<?php
        $i = 0;
				if(isset($users) && count($users)>0){
					foreach($users as $user){ 
            $i++;   ?>
          
					     <tr>
                  <td><?php echo $user['username']; ?></td>
                  <td><?php echo $user['firstname']; ?></td>
                  <td><?php echo $user['middlename']; ?></td>
                  <td><?php echo $user['lastname']; ?></td>
                  <td><?php echo $user['role_name']; ?></td>
        				  <td><?php $date = date_create($user['date_added']); echo date_format($date,"d, M Y"); ?></td>
                  <td><?php if($user['user_status_id'] == 1){  ?><a href="view-users.php?deactivate=<?php echo $user['username']; ?>"><i style="color: green; font-size: 28px;" class="fa fa-toggle-on" aria-hidden="true"></i></a><?php  }else{  ?><a href="view-users.php?activate=<?php echo $user['username']; ?>"><i style="color: red; font-size: 28px;" class="fa fa-toggle-off" aria-hidden="true"></i></a><?php } ?></td>
                </tr>

					<?php
						
					}
				}
				?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Username</th>
                  <th>Firstname</th>
                  <th>Middlename</th>
                  <th>Lastname</th>
                  <th>Role</th>
                  <th>Date Added</th>
                  <th>Status</th>
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
      <script type="text/javascript">
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
  </script>
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
