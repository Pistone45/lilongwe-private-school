<?php
include_once("functions/functions.php");

if (isset($_POST['subject_options'])) {
  
  $_SESSION['option_a'] = $_POST['option_a'];
  $_SESSION['option_b'] = $_POST['option_b'];
  $_SESSION['option_c'] = $_POST['option_c'];
  $_SESSION['option_d'] = $_POST['option_d'];
  $_SESSION['option_e'] = $_POST['option_e'];
  $_SESSION['option_f'] = $_POST['option_f'];

}

$getSubClasses = new Classes();
$sub_classes = $getSubClasses->getSubClasses();

$getGender = new Gender();
$gender = $getGender->getGender();

$getBloodType = new Blood();
$blood_type = $getBloodType->getBloodType();

if(isset($_SESSION['guardian_id'])){
	$guardian_id = $_SESSION['guardian_id'];
}

if(isset($_POST['submit'])){
	
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
	   
	  $student_picture = $imagePath;
	  if(strlen($student_picture)==9){
		  $student_picture = null;
	  }
	 }
	 
	
	$guardian_id = $_POST['guardian_id'];
	$firstname= $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$gender = $_POST['gender'];
	$blood_type = $_POST['blood_type'];
	$dob = $_POST['dob'];
	$place_of_birth =$_POST['place_of_birth'];
	$country_of_birth =$_POST['country_of_birth'];
	$nationality = $_POST['nationality'];
	$home_language =$_POST['home_language'];
	$year_of_entry = $_POST['year_of_entry'];
	$sporting_interests = $_POST['sporting_interests'];
	$musical_interests = $_POST['musical_interests'];
	$other_interests = $_POST['other_interests'];
	$medical_information = $_POST['medical_information'];
	$other_schools_attended = $_POST['other_schools_attended'];
	$home_doctor = $_POST['home_doctor'];
	$admission_date = $_POST['admission_date'];
	$sub_class = $_SESSION['sub_class'];
	
	//echo $certificate; die();
	$addStudent = new Students();
	$addStudent->addStudent($guardian_id,$sub_class, $student_picture,$firstname,$middlename,$lastname,$gender,$blood_type,$dob,$place_of_birth,$country_of_birth,$nationality,$home_language,$year_of_entry,$sporting_interests,$musical_interests,$other_interests,$medical_information,$other_schools_attended,$home_doctor,$admission_date);
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Register Student | Lilongwe Private School</title>
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
            <form role="form" action="add-student.php" method="POST" enctype="multipart/form-data">
			<?php
      if(isset($_SESSION["student-added"]) && $_SESSION["student-added"]==true)
      {
          echo "<div class='alert alert-success'>";
          echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
          echo "<strong>Success! </strong>"; echo "You have successfully added a Student";
          unset($_SESSION["student-added"]);
          echo "</div>";
			 header('Refresh: 5; URL= view-students.php');
        }
		 ?>
        <div class="box-body">
			  
        <div class="col-md-6">
          <!-- general form elements -->
       
				<input type="hidden" value="<?php if(isset($guardian_id)){ echo $guardian_id; } ?>" name="guardian_id" />
			
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
                  <label>Select Gender</label>
                  <select name="gender" class="form-control">
				  <?php
					if(count($gender)>0){
						foreach($gender as $row){ ?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
							<?php
						}
					}else{
						echo "No Gender Available";
					}
				  ?>                    
                  
                  </select>
                </div>
				
					<div class="form-group">
                  <label>Select Blood Type</label>
                  <select name="blood_type" class="form-control">
				  <?php
					if(count($blood_type)>0){
						foreach($blood_type as $row){ ?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
							<?php
						}
					}else{
						echo "No Blood Type Available";
					}
				  ?>                    
                  
                  </select>
                </div>
				
				<div class="form-group">
                  <label for="Lastname">Date of Birth</label>
                  <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
				
				<div class="form-group">
                  <label for="email">Place of Birth</label>
                  <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                </div>
				
				 <div class="form-group">
                  <label for="phone">Country of Birth</label>
                  <input type="text" class="form-control" id="country_of_birth" name="country_of_birth" required>
                </div>
				<div class="form-group">
                  <label for="class">Nationality</label>
                  <input type="text" class="form-control" id="nationality" name="nationality">
                </div>
				
				<div class="form-group">
                  <label for="class">Home Language</label>
                  <input type="text" class="form-control" id="home_language" name="home_language">
                </div>
				
                
            
              
          
         
        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
				
				
				<div class="form-group">
                  <label for="class">Year of Entry</label>
                  <input type="text" class="form-control" id="year_of_entry" name="year_of_entry">
                </div>
				
				<div class="form-group">
                  <label for="class">Sporting Interests</label>
                  <input type="text" class="form-control" id="sporting_interests" name="sporting_interests">
                </div>
				
				<div class="form-group">
                  <label for="class">musical_interests</label>
                  <input type="text" class="form-control" id="musical_interests" name="musical_interests">
                </div>
				<div class="form-group">
                  <label for="class">Other Interests</label>
                  <input type="text" class="form-control" id="other_interests" name="other_interests">
                </div>
				
				<div class="form-group">
                  <label for="class">Medical Information</label>
                  <input type="text" class="form-control" id="medical_information" name="medical_information">
                </div>
				
				<div class="form-group">
                  <label for="class">Other Schools Attended</label>
                  <input type="text" class="form-control" id="other_schools_attended" name="other_schools_attended">
                </div>
				
				<div class="form-group">
                  <label for="class">Home Doctor</label>
                  <input type="text" class="form-control" id="home_doctor" name="home_doctor">
                </div>
				
				<div class="form-group">
                  <label for="class">Admission Date</label>
                  <input type="date" class="form-control" id="admission_date" name="admission_date">
                </div>				
				
				<div class="form-group">
                  <label for="cert">Student Picture</label>
                  <input type="file" id="id"  name="id" >

                 
                </div>
				<br>
				<div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Student</button>
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
