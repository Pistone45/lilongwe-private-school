<?php
include_once("functions/functions.php");

$getAllStudentsPerClassPerPayment = new Staff();
$students = $getAllStudentsPerClassPerPayment->getAllStudentsPerClassPerPayment();

$getTerms = new Settings();
$terms = $getTerms->getTerms();

$getPaymentType = new Settings();
$type = $getPaymentType->getPaymentType();

if (isset($_POST['record'])) {
$fees = $_POST['fees'];
$student_no = $_POST['student_no'];
$academic_year = $_POST['academic_year'];
$term = $_POST['term'];
$remarks = $_POST['remarks'];
$payment_type = $_POST['payment_type'];

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//usage 
$ref_num = generateRandomString(40);

$RecordFees = new Staff();
$RecordFees = $RecordFees->RecordFees($fees, $student_no, $academic_year, $term, $remarks, $ref_num, $payment_type);
  
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Student| Lilongwe Private School</title>
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
        Select Student to record Fees
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="accountant-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Select Student</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
        <?php
          if(isset($_SESSION["fees-recorded"]) && $_SESSION["fees-recorded"]==true)
          {
              echo "<div class='alert alert-success'>";
              echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
              echo "<strong>Success! </strong>"; echo "You have successfully Recorded Fees for a Student";
              unset($_SESSION["fees-recorded"]);
              echo "</div>";
          //header('Refresh: 5; URL= view-books.php');
          }
        ?>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php
        $i = 0;
				if(isset($students) && count($students)>0){
					foreach($students as $student){ 
            $i++;   ?>
					<tr>
                  <td><?php echo $student['student_no']; ?></td>
                  <td><?php echo $student['firstname']; ?></td>
                  <td><?php echo $student['lastname']; ?></td>
                  <td><?php echo $student['sub_class_name']; ?></td>
                  <td><a href="" data-toggle="modal" data-target="#<?php echo $i; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Record Payment</a></td>
                  <td><a href="track-payments.php?id=<?php echo $student['student_no']; ?>"><button class="btn btn-info">Track Payment</button></a></td>
                </tr>

                <!-- Modal -->
<div id="<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Record Payment for <?php echo $student['firstname']." ".$student['lastname']; ?></h4>
      </div>
      <div class="modal-body">
            <form role="form" action="accountant-select-student.php" method="POST">
              <input type="text" hidden="" name="student_no" value="<?php echo $student['student_no']; ?>">
              <div class="form-group">
                        <label>Select Payment Type</label>
                        <select name="payment_type" class="form-control" required="">
                <?php
                  if(isset($type) && count($type)>0){
                    foreach($type as $types){ ?>
                      <option value="<?php echo $types['id']; ?>"><?php echo $types['name']; ?></option>
                    <?php
                      
                    }
                  }
                ?>
              
                        </select>
                </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Fees</label>
                <input type="number" name="fees" class="form-control" placeholder="Enter Fees E.g 10000" required="">
                <small id="emailHelp" class="form-text text-muted">Do not put commas.</small>
              </div>

                  <div class="form-group">
                    <label for="email">Select Year</label>
                    <select class="form-control" name="academic_year" required="">
                      <option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option>
                    </select>
                  </div>
                 <div class="form-group">
                        <label>Select Term </label>
                        <select name="term" class="form-control" required="">
                        <option selected=""><b>Choose...</b></option>
                <?php
                  if(isset($terms) && count($terms)>0){
                    foreach($terms as $term){ ?>
                      <option value="<?php echo $term['id']; ?>"><?php echo $term['name']; ?></option>
                    <?php
                      
                    }
                  }
                ?>
              
                        </select>
                </div>

                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Remarks</label>
                  <textarea name="remarks" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                
                <button type="submit" name="record" class="btn btn-primary">Record Fees</button>
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
				}else{
          echo "No Students for this particular Class found";
        }
				?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
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

  <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
        placement : 'top',
        trigger : 'hover'
    });
});
</script>
<style>
  .bs-example{
      margin: 150px 50px;
    }
</style>

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
