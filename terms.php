<?php
include_once("functions/functions.php");

if(isset($_POST['submit'])){
$academic_year = $_POST['academic_year'];
$term = $_POST['term'];
$fees = $_POST['fees'];

$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$current_id = $settings['id'];
$current_academic_year = $settings['academic_year'];
$current_term = $settings['term'];
$current_fees = $settings['fees'];
$current_status = $settings['status'];

$UpdateSettingsTrail = new Settings();
$UpdateSettingsTrail = $UpdateSettingsTrail->UpdateSettingsTrail($current_id, $current_academic_year, $current_term, $current_fees, $current_status);


$updateSettings = new Settings();
$updateSettings = $updateSettings->updateSettings($academic_year, $term, $fees);
	
}

$status=1;
$getSettings = new Settings();
$settings = $getSettings->getSettings($status);

$getTerms = new Settings();
$terms = $getTerms->getTerms();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Settings | Lilongwe Private School</title>
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
        View Settings
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">View Settings</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-8">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- form start -->
              <div class="box-body">
                <!-- Trigger the modal with a button -->
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New Settings</button>

          <!-- Modal -->
          <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">New Settings</h4>
                </div>
 
                <div class="modal-body">
                <form role="form" action="terms.php" method="POST">
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
                    <label for="email">Fees</label>
                    <input type="text" name="fees" class="form-control" placeholder="E.g 50000" required="">
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
            <?php
            if(isset($_SESSION["settings-added"]) && $_SESSION["settings-added"]==true)
                  { ?>
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success! </strong> New settings has been added
            </div>  <?php
            unset($_SESSION["settings-added"]);
                      }
              ?>
                <h4>Current Term</h4>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees</th>
                  
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($settings) && count($settings)>0){
          foreach($settings as $setting){ ?>
            <tr>
              <td><?php echo $setting['academic_year']; ?></td>
              <td>Term <?php echo $setting['term']; ?></td>
              <td><?php echo $setting['fees']; ?></td>            
            </tr>
          <?php
            
          }
        }
        ?>
                
        
                </tbody>
               
              </table>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
              </div>
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
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
