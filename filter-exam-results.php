<?php
include_once("functions/functions.php");


$getClassesPerTeacher = new Staff();
$levels = $getClassesPerTeacher->getClassesPerTeacher();

$getExamTypes = new Staff();
$exam_type = $getExamTypes->getExamTypes();

$status = 1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Class| Lilongwe Private School</title>
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
        Class Level For Exams
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="teacher-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="select-exam-class.php">Exam Level</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            
           
            <!-- form start -->
            <form role="form" action="view-exam-results.php" method="POST">
      
              <div class="box-body">
           <div class="form-group">
                  <label>Select Level </label>
                  <select name="sub_class_id" class="form-control" id="sub_class" required="" onchange="showSubject(this.value)">
                    <option VALUE="">Select Level</option>
          <?php
            if(isset($levels) && count($levels)>0){
              foreach($levels as $level){ ?>
                <option value="<?php echo $level['class_id']; ?>"><?php echo $level['class_name']; ?></option>
              <?php
                
              }
            }
          ?>
        
                  </select>
                </div>

      <label>Select Subject </label>
      <select class="form-control" required="" name="subject_id" id="subject">
        <option VALUE="">Select Subject</option>     
      </select>
      <br>

    <div class="form-group">
      <label style="color: red;">Select Exam Type </label>
      <select required="" name="exam_type_id" class="form-control" id="exam_type_id">
        <option VALUE="">Select Exam Type</option>
<?php
  if(isset($exam_type) && count($exam_type)>0){
    foreach($exam_type as $exam_types){ ?>
      <option value="<?php echo $exam_types['id']; ?>"><?php echo $exam_types['name']; ?></option>
    <?php
      
    }
  }
?>

        </select>
</div>

                  <div class="form-group">
                    <label for="email">Select Year</label>
                    <select class="form-control" name="academic_year" required="">
                      <option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option>
                    </select>
                  </div>
        
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
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
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
function showSubject(val) {
    // alert(val);
    $.ajax({
  type: "POST",
  url: "results.php",
  data:'sub_class='+val,
  success: function(data){
    // alert(data);
    $("#subject").html(data);
  }
  });
  
}
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
