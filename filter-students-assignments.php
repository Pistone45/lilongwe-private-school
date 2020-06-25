<?php
include_once("functions/functions.php");
$getTerms = new Settings();
$terms = $getTerms->getTerms();

$getAllclasses = new Staff();
$levels = $getAllclasses->getAllclasses();

$status = 1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Filter Assignments| Lilongwe Private School</title>
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
  <script src="http://code.jquery.com/jquery-latest.js"></script>
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
        Filter Assignments
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="select-level.php">Course Level</a></li>
       
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
            <form role="form" action="view-students-assignments.php" method="POST">
      
          <div class="box-body">
          <div class="form-group">
          <label>Select Class </label>
          <select name="class_id" class="form-control" required="" onchange="showRecepient(this.value)" name="class_id" id="getRecepient">
          <option>Select Class</option>
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
      
      <label>Select Sub-Class </label>
      <select class="form-control" id="sub_class" required="" onchange="showSubject(this.value)" name="sub_class_id" id="get_sub_class">
        <option VALUE="">Select Sub-Class</option>     
      </select>

      <br>
      <label>Select Subject </label>
      <select class="form-control" required="" name="subject_id" id="subject">
        <option VALUE="">Select Subject</option>     
      </select>
      <br>
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
      <br>


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
          <form></form>
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<script type="text/javascript">
function showRecepient(val) {
  // alert(val);
  $.ajax({
  type: "POST",
  url: "results.php",
  data:'class_id='+val,
  success: function(data){
    // alert(data);
    $("#sub_class").html(data);
  }
  });
  
}
</script>

<script type="text/javascript">
function showSubject(val) {
    // alert(val);
    $.ajax({
  type: "POST",
  url: "results.php",
  data:'sub_class_id='+val,
  success: function(data){
    // alert(data);
    $("#subject").html(data);
  }
  });
  
}
</script>

<script type="text/javascript">
function showTerm(val) {
    // alert(val);
    $.ajax({
  type: "POST",
  url: "results.php",
  data:'term='+val,
  success: function(data){
    // alert(data);
    $("#term").html(data);
  }
  });
  
}
</script>
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
