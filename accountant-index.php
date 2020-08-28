<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

$checkPassword = new User();
$checkPassword = $checkPassword->checkPassword();
		
$getClassesPerTeacher = new Staff();
$levels = $getClassesPerTeacher->getClassesPerTeacher();

$getSubjectsPerTeacher = new Staff();
$subjects = $getSubjectsPerTeacher->getSubjectsPerTeacher();

$getNotices = new Staff();
$notice = $getNotices->getNotices();

$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$fees = $settings['fees'];
$academic_year = $settings['academic_year'];
$term = $settings['term'];

$getStudentsWithFeesBalances = new Staff();
$students = $getStudentsWithFeesBalances->getStudentsWithFeesBalances($fees, $academic_year, $term);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lilongwe Private School | Dashboard</title>
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
      <h1 style="text-transform: uppercase;">
        Welcome <?php echo $user_details['firstname'].' '.$user_details['middlename'].' '.$user_details['lastname']; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-10 col-xs-12">
          <!-- Box -->
          <div class="box box-primary">
          <?php
            if(isset($_SESSION["reminder_sent"]) && $_SESSION["reminder_sent"]==true)
            {
                echo "<div class='alert alert-success'>";
                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                echo "<strong>Success! </strong>"; echo "You have successfully sent a reminder to Guardian";
                unset($_SESSION["reminder_sent"]);
                echo "</div>";
                //header('Refresh: 5; URL= accountant-index.php');
                }
            ?>
              <div class="box-body">
                <h3>Students with Fees Balances</h3>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($students) && count($students)>0){
          foreach($students as $student){ 
              if($fees - $student['amount'] == 0){}else{  ?>          <tr>
                  <td><?php echo $student['student_no']; ?></td>
                  <td><?php echo $student['firstname']; ?></td>
                  <td><?php echo $student['lastname']; ?></td>
                  <td><?php echo $student['sub_class_name']; ?></td>
                  <td><?php echo $academic_year ?></td>
                  <td><?php echo $term = $settings['term']; ?></td>
                  <td><?php if($fees - $student['amount'] == $fees){ ?> <p style="color: red;">Not Paid</p> <?php }else{echo"K"; echo number_format($balance = $fees - $student['amount']);} ?></td>
                  <td>
                    <form action="remind-guardian.php" method="POST">
                      <input type="hidden" name="student_no" value="<?php echo$student['student_no']; ?>">
                      <input type="hidden" name="fees_balance" value="<?php if($fees - $student['amount'] == $fees){ ?>Not Paid<?php }else{echo $balance = $fees - $student['amount'];} ?>">
                    <button type="submit" name="remind_guardian" class="btn btn-warning">Remind Guardian</button>
                    </form>
                  </td>
                </tr><?php } ?>



          <?php
            
          }
        }else{
          echo "No Students with Fees Balances found";
        }
        ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row (main row) -->

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

<script type="text/javascript">
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>

</body>
</html>
