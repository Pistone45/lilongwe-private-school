<?php
include_once("functions/functions.php");


$getAllSubclassesOnFilter = new Staff();
$levels = $getAllSubclassesOnFilter->getAllSubclassesOnFilter();

$status = 1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$academic_year =(int)$settings['academic_year'];
//from academic_year get the last 10 years
$ten_years = $academic_year-10;
$years =range($academic_year,$ten_years,-1);

$getTerms = new Settings();
$terms = $getTerms->getTerms();

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
        Select Level
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="filter-approved-results.php"> Select Level</a></li>
       
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
            <form role="form" action="approve-results.php" method="POST">
      
              <div class="box-body">
           <div class="form-group">
                  <label>Select Level </label>
                  <select name="sub_class_id" class="form-control" id="sub_class_id" required="" onchange="showSubject(this.value)">
                    <option VALUE="">Select Level</option>
          <?php
            if(isset($levels) && count($levels)>0){
              foreach($levels as $level){ ?>
                <option value="<?php echo $level['sub_class_id']; ?>"><?php echo $level['name']; ?></option>
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
        <label>Select Academic Year </label>
        <select required="" name="academic_year" class="form-control" id="academic_year" onchange="showTerm(this.value)">
         <?php
                    if(isset($years) && count($years)>0){
                      foreach($years as $year){ ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                      <?php
                        
                      }
                    }
                  ?>
        </select>
      </div>
      
      <div class="form-group">
                        <label>Select Term </label>
                        <select name="term" class="form-control" required="">
                        
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
  url: "admin-results.php",
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
