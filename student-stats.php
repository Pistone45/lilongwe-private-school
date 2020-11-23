<?php
include_once("functions/functions.php");
if (isset($_GET['id'])) {

  $id = $_GET['id'];
  $getSpecificStudent = new Students();
  $student = $getSpecificStudent->getSpecificStudent($id);

  $academic_year = $_SESSION['academic_year'];
  $term = $_SESSION['term'];
  $getFinalAssignmentMarkPerAdmin = new Staff();
  $FinamMarks = $getFinalAssignmentMarkPerAdmin->getFinalAssignmentMarkPerAdmin($academic_year, $term, $id);

  $getStudentDetailsPerAdmin = new Students();
  $details = $getStudentDetailsPerAdmin->getStudentDetailsPerAdmin($id);
  $sub_class_id = $details['sub_class_id'];//form 2 west = 5 


  $getStudentAssignmentMarksPerAdmin = new Students();
  $assignments = $getStudentAssignmentMarksPerAdmin->getStudentAssignmentMarksPerAdmin($sub_class_id, $id);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $student['firstname'].' '.$student['lastname']; ?>'s Stats | Lilongwe Private School</title>
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
        <?php echo $student['firstname'].' '.$student['lastname']; ?>'s Statistics <a href="view-positions.php"><button class="btn btn-success">Back <i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Student Stats</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- form start -->
			
              <div class="box-body">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home">Exam Results</a></li>
          <li><a data-toggle="tab" href="#menu1">Assignment Results</a></li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
            <h3>Exam Results</h3>
                  <?php
      $i = 0;
        if(isset($FinamMarks) && count($FinamMarks)>0){ 
          ?>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Subject</th>
                  <th>Grading Type</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Marks</th>
                </tr>
                </thead>
                <tbody>
                  <?php

          foreach($FinamMarks as $marks){ 
            $i++;  ?>
          <tr>
                  <td><?php echo $marks['subject_name']; ?></td>
                  <td><?php echo "CE1 + CE2 + Final Exam" ?></td>
                  <td><?php echo $marks['academic_year']; ?></td>
                  <td><?php echo $marks['term']; ?></td>
                  <td><?php echo $marks['mark']; ?> </td>
                  <td></td>

                </tr>



          <?php

          } ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Subject</th>
                  <th>Grading Type</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Marks</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Marked Subjects Available at the moment";
                      }
        ?>
          </div>
          <div id="menu1" class="tab-pane fade">
            <h3>Assignment Results</h3>

                                  <?php
        if(isset($assignments) && count($assignments)>0){ ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Due Date</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                </tr>
                </thead>
                <tbody>
                  <?php
          foreach($assignments as $assignment){ ?>
          <tr>
                  <td><?php echo $assignment['title']; ?></td>
                  <td><?php $date = DATE("Y-m-d h:i");

                  $due_date = date('Y-m-d',strtotime($assignment['due_date'] . "-1 days"));

                  if ($assignment['due_date'] < $date) {echo "<b>Date Passed </b>(";$date = date_create($due_date); echo date_format($date,"d, M Y").')';} else {
                    $date = date_create($due_date); echo date_format($date,"d, M Y");}
                   ?></td>
                  <td><?php echo $assignment['academic_year']; ?></td>
                  <td><?php echo $assignment['terms_id']; ?></td>
          <td><?php echo $assignment['subject_name']; ?> </td>
          <td><?php echo $assignment['assignment_type_name']; ?> </td>
        
          <td><?php if($assignment['marks'] == ""){echo "Not Marked";}else{echo $assignment['marks'];}  ?> </td>
          
                </tr>
          <?php
            
          } ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Title</th>
                  <th>Due Date</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else{
                        echo "No assignments Available at the moment";
                      }
        ?>

          </div>
        </div>
                


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
