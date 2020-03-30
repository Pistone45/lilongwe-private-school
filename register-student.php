<?php
include_once("passport/functions.php");

$getGrades = new Grade();
$classes = $getGrades->getGrades();

$getLearningCenter = new Student();
$centers = $getLearningCenter->getLearningCenter();

if(isset($_POST['submit'])){
	
	//validate ID attachment
	//validate  file
	 if(isset($_FILES['photo'])){
      $errors= array();
      $file_name = $_FILES['photo']['name'];
      $file_size =$_FILES['photo']['size'];
      $file_tmp =$_FILES['photo']['tmp_name'];
      $file_type=$_FILES['photo']['type'];
	  $dot = ".";

     // $file_ext=strtolower(end(explode($dot,$file_name)));

	  $imagePath = "students/";
	  $imagePath = $imagePath . basename($file_name);
	   $file_ext = pathinfo($imagePath,PATHINFO_EXTENSION);
      $expensions= array("JPG", "jpg","PNG","png","GIF","gif");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="This file extension is not allowed.";
      }

      if($file_size > 3007152){

         $errors[]='File size must be not more than 3 MB';

      }

      if(empty($errors)==true){
		move_uploaded_file($file_tmp, $imagePath);

      }else{
		   $errors[]='Error Uploading file';

         //print_r($errors);
      }
	   
	  $student_photo = $imagePath;
	 // echo $image_Path; die();
	 }
	
		//validate ID attachment
	//validate  file
	 if(isset($_FILES['id'])){
      $errors= array();
      $file_name = $_FILES['id']['name'];
      $file_size =$_FILES['id']['size'];
      $file_tmp =$_FILES['id']['tmp_name'];
      $file_type=$_FILES['id']['type'];
	  $dot = ".";

     // $file_ext=strtolower(end(explode($dot,$file_name)));

	  $imagePath = "students/";
	  $imagePath = $imagePath . basename($file_name);
	   $file_ext = pathinfo($imagePath,PATHINFO_EXTENSION);
      $expensions= array("JPG", "jpg","PNG","png","GIF","gif");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="This file extension is not allowed.";
      }

      if($file_size > 3007152){

         $errors[]='File size must be not more than 3 MB';

      }

      if(empty($errors)==true){
		move_uploaded_file($file_tmp, $imagePath);

      }else{
		   $errors[]='Error Uploading file';

         //print_r($errors);
      }
	   
	  $id = $imagePath;
	 // echo $image_Path; die();
	 }
	 
	 
	 	//validate ID attachment
	//validate  file
	 if(isset($_FILES['certificate'])){
      $errors= array();
      $file_name = $_FILES['certificate']['name'];
      $file_size =$_FILES['certificate']['size'];
      $file_tmp =$_FILES['certificate']['tmp_name'];
      $file_type=$_FILES['certificate']['type'];
	  $dot = ".";

     // $file_ext=strtolower(end(explode($dot,$file_name)));

	  $imagePath = "students/";
	  $imagePath = $imagePath . basename($file_name);
	   $file_ext = pathinfo($imagePath,PATHINFO_EXTENSION);
      $expensions= array("PDF", "DOCX","DOC","doc","docx","pdf");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="This file extension is not allowed.";
      }

      if($file_size > 3007152){

         $errors[]='File size must be not more than 3 MB';

      }

      if(empty($errors)==true){
		move_uploaded_file($file_tmp, $imagePath);

      }else{
		   $errors[]='Error Uploading file';

         //print_r($errors);
      }
	   
	  $certificate = $imagePath;
	 // echo $image_Path; die();
	 }
	 
	$firstname= $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$employer = $_POST['employer'];
	$class =$_POST['class'];
	$center =$_POST['center'];
	
	//echo $certificate; die();
	$addStudent = new Student();
	$addStudent->addStudent($firstname,$middlename,$lastname,$email,$phone,$employer,$class,$center,$student_photo,$id,$certificate);
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Register Students | Bankers Association of Malawi</title>
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
        Student Registration
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="register-student.php">Student Registration</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	   <div class="box box-primary">
      <div class="row">
        <!-- left column -->
		   <!-- form start -->
            <form role="form" action="register-student" method="POST" enctype="multipart/form-data">
			<?php
                            if(isset($_SESSION["student-added"]) && $_SESSION["student-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully added a Student";
                                unset($_SESSION["student-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= index.php');
                            }
							?>
              <div class="box-body">
			  
        <div class="col-md-6">
          <!-- general form elements -->
       
            
           
         
			
                <div class="form-group">
                  <label for="firstname">Firstname</label>
                  <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                  <label for="Middlename">Middlename</label>
                  <input type="text" class="form-control" id="Middlename" name="middlename">
                </div>
				
				 <div class="form-group">
                  <label for="Lastname">Lastname</label>
                  <input type="text" class="form-control" id="Lastname" name="lastname" required>
                </div>
				
				<div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
				
				 <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" id="Lastname" name="phone" required>
                </div>
				
				<div class="form-group">
                  <label for="class">Employer</label>
                  <input type="text" class="form-control" id="employer" name="employer">
                </div>
                
            
              
          
         
        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          
				
                <div class="form-group">
                  <label>Select Level</label>
                  <select name="class" class="form-control">
				  <?php
					if(count($classes)>0){
						foreach($classes as $row){ ?>
							<option value="<?php echo $row['level_id']; ?>"><?php echo $row['level_name']?></option>
							<?php
						}
					}else{
						echo "No classes Available";
					}
				  ?>                    
                  
                  </select>
                </div>
				
				<div class="form-group">
                  <label>Select Center</label>
                  <select name="center" class="form-control">
				  <?php
					if(count($centers)>0){
						foreach($centers as $row){ ?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
							<?php
						}
					}else{
						echo "No classes Available";
					}
				  ?>                    
                  
                  </select>
                </div>
				
				<div class="form-group">
                  <label for="cert">Upload Passport Photo Size</label>
                  <input type="file" id="photo"  name="photo" required>

                 
                </div>
				
				<div class="form-group">
                  <label for="cert">Upload ID</label>
                  <input type="file" id="id"  name="id" required>

                 
                </div>
				
				<div class="form-group">
                  <label for="cert">Upload Highest Academic Document (Pdf/Word Doc)</label>
                  <input type="file" id="certificate"  name="certificate" required>

                 
                </div> 
				<br>
				<div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </div>
			  
        </div>
        <!--/.col (right) -->
		
		  </div>
              <!-- /.box-body -->
			  
			 
		</form>

      </div>
      <!-- /.row -->
	  
	   </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once("footer.html"); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
