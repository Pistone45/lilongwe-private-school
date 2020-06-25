<?php
include_once("functions/functions.php");


if(isset($_POST['submit'])){
	$sub_class = $_POST['level'];
	$teacher_id = $_POST['teacher_id'];	
	
	$getSpecificTeacher = new Staff();
	$teacher = $getSpecificTeacher->getSpecificTeacher($teacher_id);
	
	$getClassPerSubClass = new Classes();
	$class_id = $getClassPerSubClass->getClassPerSubClass($sub_class);
	$class_id = $class_id['classes_id'];
				
	//get subjects per class					
	$getSubjectsPerClass = new Subjects();
	$subjects = $getSubjectsPerClass->getSubjectsPerClass($class_id);
	
	//get assigned subjects
	$getAssignedSubjects = new Subjects();
	$assignedSubjects = $getAssignedSubjects->getAssignedSubjects($teacher_id);
	
}

if(isset($_POST['assign'])){
	$teacher_id = $_POST['teacher_id'];
	$sub_class = $_POST['sub_class'];
	$subjects = $_POST['subjects'];
	
	$assignSubjectsToSubClassAndTeacher = new Subjects();
	$assignSubjectsToSubClassAndTeacher->assignSubjectsToSubClassAndTeacher($teacher_id,$sub_class,$subjects);
	
	//get assigned subjects - refresh
	$getAssignedSubjects = new Subjects();
	$assignedSubjects = $getAssignedSubjects->getAssignedSubjects($teacher_id);
}

$getSubClasses = new Classes();
$levels = $getSubClasses->getSubClasses();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Assign Subjects to Teacher| Lilongwe Private School</title>
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
       Assign Subject to  <?php if(isset($teacher) && count($teacher)>0){ echo $teacher['name']; }?>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Teacher Class</a></li>
       
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
            <form role="form" action="assign-subjects.php" method="POST">
			<?php
				if(isset($_SESSION["subjects-assigned-to-teachers"]) && $_SESSION["subjects-assigned-to-teachers"]==true)
				{
					echo "<div class='alert alert-success'>";
					echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
					echo "<strong>Success! </strong>"; echo "You have successfully assigned subjects to the teacher";
					unset($_SESSION["subjects-assigned-to-teachers"]);
					echo "</div>";
					 header('Refresh: 5; URL= view-teachers.php');
				}
			?>
              <div class="box-body">
				<input type="hidden" value="<?php if(isset($teacher_id)){ echo $teacher_id; }?>" name="teacher_id" />
				<input type="hidden" value="<?php if(isset($sub_class)){ echo $sub_class; }?>" name="sub_class" />
				
			     <div class="form-group">
                  <label>Select Teacher's Subjects </label>
				    <div class="checkbox">
					<?php
						if(isset($subjects) && count($subjects)>0){
							foreach($subjects as $subject){ ?>
								  <label><input type="checkbox" name="subjects[]" value="<?php if(isset($subject['subjects_id'])){echo $subject['subjects_id'];} ?>"><?php if(isset($subject['subject'])){echo $subject['subject'];} ?></label> <br>
							<?php
								
							}
						}
					?>
					
					</div>
                </div>
				
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="assign" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
			<div class="box box-primary">
             <table class="table">
			 <thead>
				<th>Subject</th>
				<th>Class</th>
			 </thead>
			 <?php
						if(isset($assignedSubjects) && count($assignedSubjects)>0){
							foreach($assignedSubjects as $new){ ?>
							<tr>
								<td><?php echo $new['subject']; ?></td>
								<td><?php echo $new['sub_class']; ?></td>
								
							</tr>
							<?php
							}
							
						}else{
							echo "No assigned subjects found";
	
						}?>
				
			 </table>
              
                
              </div>
        </div>
        <!--/.col (right) -->
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
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
