<?php
ob_start();
session_start();
error_reporting(E_ALL);
if(file_exists("connection/connection.php")){
	include_once ('connection/connection.php');
}else{
	include_once ('../connection/connection.php');
}
	


date_default_timezone_set("Africa/Harare");

class Settings{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}
	
	public function getCurrentSettings($status){
		$getCurrentSettings = $this->dbCon->PREPARE("SELECT id,academic_year,term,fees,status FROM settings WHERE status=?");
		$getCurrentSettings->bindParam(1,$status);
		$getCurrentSettings->execute();
		
		if($getCurrentSettings->rowCount()>0){
			$row = $getCurrentSettings->fetch();
			
			return $row;
		}
	}
	


public function getSpecificCurrentSettings($settings_id){
		$getSpecificCurrentSettings = $this->dbCon->PREPARE("SELECT id,academic_year,term,fees,status FROM settings WHERE id=?");
		$getSpecificCurrentSettings->bindParam(1,$settings_id);
		$getSpecificCurrentSettings->execute();
		
		if($getSpecificCurrentSettings->rowCount()>0){
			$rows = $getSpecificCurrentSettings->fetchAll();
			
			return $rows;
		}
	}


} //end of class settings


class User{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function login($username, $password){

		if(!empty($username) && !empty($password)) {
			
			$status = 0;// inactive status
		$login_query = $this->dbCon->PREPARE("SELECT username, firstname,middlename,lastname, password, roles_id,user_status_id  FROM users WHERE username=?" );

				$login_query->bindParam(1, $username);
				$login_query->execute();

				if($login_query->rowCount() ==1){
					
				$row = $login_query -> fetch();
				$hash_pass =trim($row['password']);
				//verify password
				if (password_verify($password, $hash_pass)) {
					
					// Success!
					$_SESSION['user'] = $row;
				
					header("Location: index.php");
					//die();
				}else {
					
					 $_SESSION['invalidUser']=true;
				}


				}else{

                    $_SESSION['invalidUser']=true;

				}

		}
	} //end of login authentication


	
	public function getUserProfile(){	
				$getUserProfile = $this->dbCon->prepare("SELECT username,firstname,middlename,lastname FROM users WHERE username=?" );
				$getUserProfile->bindParam(1, $_SESSION['user']['username']);
				$getUserProfile->execute();

				if($getUserProfile->rowCount() ==1){
				$row = $getUserProfile -> fetch();

				return $row;
				//verify password



				}

		
	}

  public function addUser($username,$firstname,$middlename, $lastname, $role,$password,$status){
	  $date = DATE("Y-m-d h:i");
		//check if the user is already in the system before adding new user
		$checkUser = $this->dbCon->prepare("SELECT username from users where username=?" );
		$checkUser->bindValue(1, $username);
		$checkUser->execute();
		if($checkUser->rowCount() ==1){
			//user already in the system
			$_SESSION['user_found']= true;
		}else{
				$addUser = $this->dbCon->prepare("INSERT INTO users (username, password, firstname,middlename, lastname,user_status_id,roles_id,date_added) 
				VALUES (:username, :password, :firstname,:middlename, :lastname,:user_status_id,:roles_role_id,:date_added)" );
				$addUser->execute(array(
						  ':username'=>($username),
						  ':password'=>($password),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':user_status_id'=>('1'),
						  ':roles_role_id'=>($role),
						  ':date_added'=>($date)
						  ));		

		  $_SESSION['user-added']=true;
			}
		

	} //end adding users

//edit user
	  public function editUser($username,$firstname,$middlename, $lastname, $role,$phone,$email,$status,$UID){
				//echo $username; die();	
				$editUser = $this->dbCon->prepare("UPDATE users SET firstname,middlename,lastname?,email=?,phone=?,role=?,status=? WHERE username=?" );
				$editUser->bindParam(1,$firstname);
				$editUser->bindParam(2,$middlename);
				$editUser->bindParam(3,$lastname);
				$editUser->bindParam(4,$email);
				$editUser->bindParam(5,$phone);
				$editUser->bindParam(6,$role);
				$editUser->bindParam(7,$status);
				$editUser->bindParam(8,$UID);
				$editUser->execute();
				if($role == 10 || $role==200){ //add him/her to layers table
					$status = 1; //active					
					$officer_code =substr($firstname,0,1).substr($lastname,0,1);
				
					$editlawyer = $this->dbCon->prepare("UPDATE lawyer SET firstname=?,middlename=?, lastname=?, status=?,phone=?,email=?,role=? 
					WHERE id=?");
					$editlawyer->bindParam(1,$firstname);
					$editlawyer->bindParam(2,$middlename);
					$editlawyer->bindParam(3,$lastname);
					$editlawyer->bindParam(4,$status);
					$editlawyer->bindParam(5,$phone);
					$editlawyer->bindParam(6,$email);
					$editlawyer->bindParam(7,$role);
					$editlawyer->bindParam(8,$UID);
					$editlawyer->execute();
				}

		  $_SESSION['user-edited']=true;



	} //end adding users



	public function getUsers(){
		//get all users
		try{
			$getUsers = $this->dbCon->prepare("SELECT username, firstname, middlename,lastname, email, phone from users WHERE username != ?" );
			$getUsers->bindParam(1,$_SESSION['user']['username']);
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetchAll();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting users
	
		public function getAllUsers(){
		//get all users
		try{
			$getUsers = $this->dbCon->prepare("SELECT username, firstname,middlename,lastname, email, phone from users " );
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetchAll();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting users
	//get specfic user
	public function getSpecificUser($username){
		
		try{
			$getSpecificUser = $this->dbCon->prepare("SELECT username, password,firstname, middlename,lastname, email, phone, role, roles.name as role_name,
			status, IF(status=1, 'Active','Not Active') as status_name FROM users INNER JOIN roles ON (users.role=roles.id)WHERE username = ?" );
			$getSpecificUser->bindParam(1,$username);
			$getSpecificUser->execute();
			if($getSpecificUser->rowCount()>0){
				$row = $getSpecificUser->fetch();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting users
	

	public function getSingleUser($username){
		//get one users
		try{
			$getUsers = $this->dbCon->prepare("SELECT id, username, fname, lname, email, phone, users.role_id AS role_id, roles.name AS role from users INNER JOIN roles ON (roles.role_id = users.role_id) WHERE username = ?" );
			$getUsers->bindParam(1, $username);
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetch();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting single user

	//Update Password
	public function updatePassword($username, $password){

		try{
			$updatepassword = $this->dbCon->prepare("UPDATE users SET password =? WHERE username=?");
			$updatepassword->bindparam(1, $password);
			$updatepassword->bindparam(2, $username);
			$updatepassword->execute();

			$_SESSION['password_updated'] =true;
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}



	



} //End of Class Users

class Students{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getStudents(){
		$getStudents = $this->dbCon->Prepare("SELECT student_no, firstname, middlename, lastname,dob, gender.name as gender,place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type,
		student_status.name as student_status,sub_classes.name as sub_class
		FROM students INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id)");
		$getStudents->execute();
		
		if($getStudents->rowCount()>0){
			$rows = $getStudents->fetchAll();
			return $rows;
		}
	} //end of getting students
	
	public function getSpecificStudent($id){
		$getSpecificStudent = $this->dbCon->Prepare("SELECT student_no, firstname, middlename, lastname,dob, gender.name as gender,place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type,
		student_status.name as student_status,sub_classes.name as sub_class, sub_classes.id as sub_class_id
		FROM students INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id) WHERE student_no=?");
		$getSpecificStudent->bindParam(1,$id);
		$getSpecificStudent->execute();
		
		if($getSpecificStudent->rowCount()>0){
			$row = $getSpecificStudent->fetch();
			return $row;
		}
	} //end of getting Specific Student
	
		public function getStudentDetails(){
		$getSpecificStudent = $this->dbCon->Prepare("SELECT student_no, firstname, middlename, lastname,dob, gender.name as gender,place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type,
		student_status.name as student_status,sub_classes.name as sub_class, sub_classes.id as sub_class_id
		FROM students INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id) WHERE student_no=?");
		$getSpecificStudent->bindParam(1,$_SESSION['user']['username']);
		$getSpecificStudent->execute();
		
		if($getSpecificStudent->rowCount()>0){
			$row = $getSpecificStudent->fetch();
			return $row;
		}
	} //end of getting Specific Student
	


public function getLoginStatus($id){
		$getLoginStatus = $this->dbCon->Prepare("SELECT user_status_id FROM users WHERE username=?");
		$getLoginStatus->bindParam(1,$id);
		$getLoginStatus->execute();
		
		if($getLoginStatus->rowCount()>0){
			$row = $getLoginStatus->fetch();
			return $row;
		}
	} //end of getting Specific Login Status of Student


	//add student
	public function addStudent($guardian_id,$sub_class,$student_picture,$firstname,$middlename,$lastname,$gender,$blood_type,$dob,$place_of_birth,$country_of_birth,$nationality,$home_language,$year_of_entry,$sporting_interests,$musical_interests,$other_interests,$medical_information,$other_schools_attended,$home_doctor,$admission_date){
				//echo 'dd'; die();
				$student_status_id =1; // active status
				
				//getStudentCount
				$id = 10; //current id
				$getStudentCount = $this->dbCon->PREPARE("SELECT student_count FROM student_count WHERE id=?");
				$getStudentCount->bindParam(1,$id);
				$getStudentCount->execute();
				
				if($getStudentCount->rowCount()>0){
					//echo "ff"; die();
					$row = $getStudentCount->fetch();
					
					$count = $row['student_count'];
					$student_no = "LPS/S/".$count;
				}
				
				$getClassPerSubClass = new Classes();
				$class_id = $getClassPerSubClass->getClassPerSubClass($sub_class);
				$class_id = $class_id['classes_id'];
				
				//check his class
				if($class_id == 1 || $class_id==2 || $class_id==3){
					//all subjects are mandatory - get subjects per class					
					$getSubjectsPerClass = new Subjects();
					$subjects = $getSubjectsPerClass->getSubjectsPerClass($class_id);
					
					//insert into students
					
					$addStudent = $this->dbCon->prepare("INSERT INTO students (student_no,firstname,middlename,lastname,dob,place_of_birth,country_of_birth,nationality,home_language,year_of_entry,sporting_interests,
					musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,blood_type_id,student_status_id,sub_classes_id,guardians_id, gender_id)
					VALUES (:student_no,:firstname,:middlename,:lastname,:dob,:place_of_birth,:country_of_birth,:nationality,:home_language,:year_of_entry,:sporting_interests,
					:musical_interests,:other_interests,:medical_information,:other_schools_attended,:student_picture,:home_doctor,:admission_date,:blood_type_id,:student_status_id,:sub_classes_id,:guardian_id, :gender_id)" );
					$addStudent->execute(array(
						  ':student_no'=>($student_no),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),						  
						  ':dob'=>($dob),
						  ':place_of_birth'=>($place_of_birth),
						  ':country_of_birth'=>($country_of_birth),
						  ':nationality'=>($nationality),
						  ':home_language'=>($home_language),
						  ':year_of_entry'=>($year_of_entry),
						  ':sporting_interests'=>($sporting_interests),
						  ':musical_interests'=>($musical_interests),
						  ':other_interests'=>($other_interests),
						  ':medical_information'=>($medical_information),
						  ':other_schools_attended'=>($other_schools_attended),
						  ':student_picture'=>($student_picture),
						  ':home_doctor'=>($home_doctor),
						  ':admission_date'=>($admission_date),
						  ':blood_type_id'=>($blood_type),
						  ':home_doctor'=>($home_doctor),
						  ':student_status_id'=>($student_status_id),
						  ':sub_classes_id'=>($sub_class),
						  ':guardian_id'=>($guardian_id),
						  ':gender_id'=>($gender)
						  ));
						  
						  //insert into students_has_classes_has_subjects
						  
						  if(isset($subjects) && count($subjects)>0){
							  foreach($subjects as $subject){
								  $addStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
								  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
								  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
								  $addStudentSubject->execute(array(
									':students_student_no'=>$student_no,
									':classes_has_subjects_classes_id'=>$class_id,
									':classes_has_subjects_subjects_id'=>$subject['subjects_id']
								  ));
							  }
						  }
						  
						  //update student count
						  $newCount = $count+1;
						  $updateStudentCount = new Students();
						  $updateStudentCount->updateStudentCount($newCount);
						  
						  //add the teacher to users table for logins
							$role =30; //Students role id
							$status = 1; //active status
							$username = $student_no;
							$password = password_hash($student_no, PASSWORD_DEFAULT)."\n"; 
							$addUser = new User();
							$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);
			
						  $_SESSION['student-added']=true;
		
					
				}elseif($class==4){
					//opr
					
				}elseif($class==5){
					
				}elseif($class == 6){
					
				}
				
	}
	
	
	
	public function editStudent($bannerpath,$title,$news,$news_id){
		$editStudent = $this->dbCon->PREPARE("UPDATE news SET title=?, news=?, image_url=? WHERE id=?");
		$editStudent->bindParam(1,$title);
		$editStudent->bindParam(2,$news);
		$editStudent->bindParam(3,$bannerpath);
		$editStudent->bindParam(4,$news_id);
		$editStudent->execute();
		$_SESSION['student-edited'] = true;
	}
	
    public function getStudentCount(){
		$id = 10; //default id for current studentt count
		$getStudentCount = $this->dbCon->Prepare("SELECT student_count FROM student_count WHERE id=?");
		$getStudentCount->bindParam(1,$id);
		$getStudentCount->execute();
		
		if($getStudentCount->rowCount()>0){
			$row = $getStudentCount->fetch();
			return $row;
		}
		
	} 
	
	public function updateStudentCount($newCount){
		$id =10;// current student count id
		$updateStudentCount = $this->dbCon->PREPARE("UPDATE student_count SET student_count =? WHERE id=?");
		$updateStudentCount->bindParam(1,$newCount);
		$updateStudentCount->bindParam(2,$id);
		$updateStudentCount->execute();
	}
	
	
	public function getStudentAssignment($sub_class_id){

	$getStudentAssignment = $this->dbCon->Prepare("SELECT assignments.id as assignment_id, title, due_date, subjects_id, assignment_type.name as assignment_type_name, terms_id, assignment_url, academic_year, subjects.name as subject_name FROM assignments INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id) INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) WHERE sub_classes_has_assignments.sub_classes_id=?");
		$getStudentAssignment->bindParam(1, $sub_class_id);
		$getStudentAssignment->execute();
		
		if($getStudentAssignment->rowCount()>0){
			$rows = $getStudentAssignment->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student



	public function getUploadedStudentAssignment($assignments_id){

	$getUploadedStudentAssignment = $this->dbCon->Prepare("SELECT assignments.id as assignment_id, assignments.title as assignment_title, subjects_id, submitted_assignment, subjects.name as subject_name, assignments.due_date as due_date, classes.name as class_name, marks
	FROM submissions INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id) INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id)  INNER JOIN classes ON(sub_classes.classes_id=classes.id) WHERE submissions.assignments_id=? AND students_student_no=? LIMIT 1");
		$getUploadedStudentAssignment->bindParam(1, $assignments_id);
		$getUploadedStudentAssignment->bindParam(2, $_SESSION['user']['username']);
		$getUploadedStudentAssignment->execute();
		
		if($getUploadedStudentAssignment->rowCount()>0){
			$rows = $getUploadedStudentAssignment->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student



public function uploadStudentAssignment($assignments_id, $submitted_assignment){
			$date = DATE("Y-m-d h:i");
				$uploadStudentAssignment = $this->dbCon->prepare("INSERT INTO submissions (assignments_id, students_student_no, submitted_assignment, date_submitted)
				VALUES ( :assignments_id, :students_student_no, :submitted_assignment, :date_submitted)" );
				$uploadStudentAssignment->execute(array(
						':assignments_id'=>($assignments_id),
						':students_student_no'=>($_SESSION['user']['username']),
						':submitted_assignment'=>($submitted_assignment),
						':date_submitted'=>($date)					  
						  ));
				//$assignments_id = $this->dbCon->lastInsertId();
						  
						  $_SESSION['uploaded']=true;
		
	}


public function disableSpecificStudent($id){
		$status = '0';
		$disableSpecificStudent = $this->dbCon->PREPARE("UPDATE users SET user_status_id=? WHERE username=?");
		$disableSpecificStudent->bindParam(1,$status);
		$disableSpecificStudent->bindParam(2,$id);
		$disableSpecificStudent->execute();
	}//End of Disabling a student


public function enableSpecificStudent($id){
		$status = '1';
		$enableSpecificStudent = $this->dbCon->PREPARE("UPDATE users SET user_status_id=? WHERE username=?");
		$enableSpecificStudent->bindParam(1,$status);
		$enableSpecificStudent->bindParam(2,$id);
		$enableSpecificStudent->execute();
	}//End of enabling a student


public function getSubclass($level){
		$getSubclass = $this->dbCon->Prepare("SELECT name FROM sub_classes WHERE id=?");
		$getSubclass->bindParam(1,$level);
		$getSubclass->execute();
		
		if($getSubclass->rowCount()>0){
			$row = $getSubclass->fetch();
			return $row;
		}
	} //end of getting Specific Sub Class Name



public function getSpecificStudentId($assignment_id){
		$getSpecificStudentId = $this->dbCon->Prepare("SELECT students_student_no FROM submissions WHERE assignments_id=?");
		$getSpecificStudentId->bindParam(1,$assignment_id);
		$getSpecificStudentId->execute();
		
		if($getSpecificStudentId->rowCount()>0){
			$row = $getSpecificStudentId->fetch();
			return $row;
		}
	} //end of getting Specific Sub Class Name



public function getAllSubmittedAssignments($class_id, $sub_class_id, $subject_id, $settings_id){

	$getAllSubmittedAssignments = $this->dbCon->Prepare("SELECT students_student_no, assignments.id as assignments_id, marks, assignment_type.name as assignment_type_name, submitted_assignment, students.firstname as firstname, students.lastname as lastname, date_submitted, assignments.title as assignment_title, subjects.name as subject_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON(sub_classes_has_assignments.assignments_id=assignments.id) INNER JOIN sub_classes ON(sub_classes_has_assignments.sub_classes_id=sub_classes.id) INNER JOIN sub_classes_has_subjects ON(sub_classes_has_subjects.sub_classes_id=sub_classes.id) INNER JOIN subjects ON(sub_classes_has_subjects.subjects_id=subjects.id) INNER JOIN classes_has_subjects ON(classes_has_subjects.subjects_id=subjects.id) INNER JOIN classes ON(classes_has_subjects.classes_id=classes.id) WHERE classes.id=? AND sub_classes.id=? AND sub_classes_has_subjects.subjects_id=? AND assignments.terms_id=?");
		$getAllSubmittedAssignments->bindParam(1, $class_id);
		$getAllSubmittedAssignments->bindParam(2, $sub_class_id);
		$getAllSubmittedAssignments->bindParam(3, $subject_id);
		$getAllSubmittedAssignments->bindParam(4, $settings_id);
		$getAllSubmittedAssignments->execute();
		
		if($getAllSubmittedAssignments->rowCount()>0){
			$rows = $getAllSubmittedAssignments->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student on the admin side

	
	
}

class Subjects{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

		//add Subject
	public function addSubject($subject){
				$date = DATE("Y-m-d h:i");
				$addSubject = $this->dbCon->prepare("INSERT INTO subjects (name) VALUES (:name)" );
				$addSubject->execute(array(
						  ':name'=>($name)));
						  
						  $_SESSION['subject-added']=true;
		
	}

	
	public function getSubjects(){
		$getSubjects = $this->dbCon->Prepare("SELECT id,name FROM subjects");
		$getSubjects->execute();
		
		if($getSubjects->rowCount()>0){
			$row = $getSubjects->fetchAll();
			return $row;
		}
	} //end of getting subjects
	
	public function getSubjectsPerClass($class_id){
		$getSubjectsPerClass = $this->dbCon->Prepare("SELECT classes_id,subjects_id, subjects.name as subject FROM classes_has_subjects
		INNER JOIN subjects ON (subjects.id=classes_has_subjects.subjects_id)  WHERE classes_id=?");
		$getSubjectsPerClass->bindParam(1,$class_id);
		$getSubjectsPerClass->execute();
		
		if($getSubjectsPerClass->rowCount()>0){
			$rows = $getSubjectsPerClass->fetchAll();
			return $rows;
		}
	} //end of getting subjects per class
	
	public function assignSubjectsToClasses($class,$subjects){
		if(!empty($subjects)){
			
			foreach($subjects as $subject){
				$assignSubjectsToClasses = $this->dbCon->Prepare("INSERT INTO classes_has_subjects (classes_id,subjects_id) VALUES(:classes_id,:subjects_id)");
				$assignSubjectsToClasses->execute(array(
					':classes_id'=>$class,
					':subjects_id'=>$subject));
			}
			
			$_SESSION['subjects-assigned']=true;
		}
		
	} //end of getting assigning subjects to classes
	
	public function assignSubjectsToSubClassAndTeacher($teacher_id,$sub_class,$subjects){
		if(!empty($subjects)){			
			foreach($subjects as $subject){
				//check if subject is not already assigned to teacher
				$checkIfSubjectAlreadyAssigned = $this->dbCon->PREPARE("SELECT sub_classes_id,subjects_id,staff_id FROM  sub_classes_has_subjects WHERE sub_classes_id=? AND subjects_id=? AND staff_id=? ");
				$checkIfSubjectAlreadyAssigned->bindParam(1, $sub_class);
				$checkIfSubjectAlreadyAssigned->bindParam(2, $subject);
				$checkIfSubjectAlreadyAssigned->bindParam(3,$teacher_id);
				$checkIfSubjectAlreadyAssigned->execute();
				
				if($checkIfSubjectAlreadyAssigned->rowCount()>0){
					//skip insertion
				}else{
					$assignSubjectsToSubClassAndTeacher = $this->dbCon->Prepare("INSERT INTO sub_classes_has_subjects (sub_classes_id,subjects_id,staff_id) VALUES(:sub_classes_id,:subjects_id,:staff_id)");
						$assignSubjectsToSubClassAndTeacher->execute(array(
							':sub_classes_id'=>$sub_class,
							':subjects_id'=>$subject,
							':staff_id'=>$teacher_id
							));
					}
			
					$_SESSION['subjects-assigned-to-teachers']=true;
				}
				
				
		}
		
	} //end of getting assigning subjects to sub classes and teachers
	
	public function getAssignedSubjects($teacher_id){
		$getAssignedSubjects = $this->dbCon->Prepare("SELECT sub_classes_id,subjects_id, staff_id,subjects.name as subject, sub_classes.name as sub_class FROM sub_classes_has_subjects
		INNER JOIN subjects ON (subjects.id=sub_classes_has_subjects.subjects_id) INNEr JOIN sub_classes ON (sub_classes.id=sub_classes_has_subjects.sub_classes_id) WHERE staff_id=?");
		$getAssignedSubjects->bindParam(1,$teacher_id);
		$getAssignedSubjects->execute();
		
		if($getAssignedSubjects->rowCount()>0){
			$rows = $getAssignedSubjects->fetchAll();
			return $rows;
		}
	} //end of getting assigned subjects per teacher
	
	

}

class Exams{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function registerExams($specialisation,$generic,$elective, $period){
		$year = DATE('Y');
		if(isset($generic) && count($generic)>0){			
			foreach($generic as $ge){
				//get examiners id
				$id = $ge;
				$getExaminerID = new Examiner();
				$examiner = $getExaminerID->getExaminerID($id);
				
				$registerExams =$this->dbCon->PREPARE("INSERT INTO grades (period,academic_year,course_code,student_id,examiners_id) VALUES(:period,:academic_year,:course_code,:student_id,:examiners_id)");
				$registerExams->execute(array(':period'=>($period),
									':academic_year'=>($year),
									':course_code'=>($ge),
									':student_id'=>($_SESSION['user']['username']),
									':examiners_id'=>($examiner['examiners_id'])						
									));
			}
		}
		
		if(isset($specialisation) && count($specialisation)>0){			
			foreach($specialisation as $sp){
				$registerExams =$this->dbCon->PREPARE("INSERT INTO grades (period,academic_year,course_code,student_id) VALUES(:period,:academic_year,:course_code,:student_id)");
				$registerExams->execute(array(':period'=>($period),
									':academic_year'=>($year),
									':course_code'=>($sp),
									':student_id'=>($_SESSION['user']['username'])));
			}
		}
		
		if(isset($elective) && count($elective)>0){			
			foreach($elective as $el){
				$registerExams =$this->dbCon->PREPARE("INSERT INTO grades (period,academic_year,course_code,student_id) VALUES(:period,:academic_year,:course_code,:student_id)");
				$registerExams->execute(array(':period'=>($period),
									':academic_year'=>($year),
									':course_code'=>($el),
									':student_id'=>($_SESSION['user']['username'])));
			}
		}
		
	
		
		$_SESSION["exams-registered"] = true;
		
	} //end of registering Exams
	
	
	public function getRegisteredExams(){
		$getRegisteredExams = $this->dbCon->Prepare("SELECT academic_year,period,grades.course_code, courses.course_title FROM grades INNER JOIN courses ON (courses.course_code = grades.course_code) WHERE student_id=?");
		$getRegisteredExams->bindParam(1,$_SESSION['user']['username']);
		$getRegisteredExams->execute();
		
		if($getRegisteredExams->rowCount()>0){
			$row = $getRegisteredExams->fetchAll();
			return $row;
		}
	} //end of getting guardians
	
	public function getStudentsRegisteredPerECourse($course_code){
		$getStudentsRegisteredPerECourse = $this->dbCon->PREPARE("SELECT course_code, grades.student_id as student_id, student_no FROM grades 
		INNER JOIN student ON (grades.student_id=student.student_id) WHERE course_code=? AND examiners_id=?");
		$getStudentsRegisteredPerECourse->bindParam(1,$course_code);
		$getStudentsRegisteredPerECourse->bindParam(2,$_SESSION['user']['username']);
		$getStudentsRegisteredPerECourse->execute();
		
		if($getStudentsRegisteredPerECourse->rowCount()>0){
			$rows = $getStudentsRegisteredPerECourse->fetchAll();
			
			return $rows;
		}
		
	}
	
	public function getStudentsPerRegisteredExamsPerLevelPerAcademicYearPerExaminer($course_code,$level,$academic_year,$period){
		
		$getStudentsRegisteredPerECourse = $this->dbCon->PREPARE("SELECT course_code, grades.student_id as student_id, student_no FROM grades 
		INNER JOIN student ON (grades.student_id=student.student_id) WHERE course_code=? AND examiners_id=? AND academic_year=? AND period=?");
		$getStudentsRegisteredPerECourse->bindParam(1,$course_code);
		$getStudentsRegisteredPerECourse->bindParam(2,$_SESSION['user']['username']);
		$getStudentsRegisteredPerECourse->bindParam(3,$academic_year);
		$getStudentsRegisteredPerECourse->bindParam(4,$period);
		$getStudentsRegisteredPerECourse->execute();
		
		if($getStudentsRegisteredPerECourse->rowCount()>0){
			//echo 'dd'; die();
			$rows = $getStudentsRegisteredPerECourse->fetchAll();
			//var_dump($rows); die();
			return $rows;
		}
		
	}
	
	public function getExamResults($status){
		$getExamResults = $this->dbCon->PREPARE("SELECT student_id,course_code,academic_year,period,marks,status,examiners_id FROM grades WHERE status !=?");
		$getExamResults->bindParam(1,$status);
		$getExamResults->execute();
		
		if($getExamResults->rowCount()>0){
			$rows = $getExamResults->fetchAll();
			
			return $rows;
		}
		
	}
	
	public function addMarks($level,$course_code,$student_no,$marks,$status){
		if(isset($marks) && count($marks)>0){
			foreach($marks as $mark){
				foreach($student_no as $stud_id){					
					$addMarks =$this->dbCon->PREPARE("UPDATE grades SET marks=?,status=? WHERE student_id=? AND course_code=?");
					$addMarks->bindParam(1,$mark);
					$addMarks->bindParam(2,$status);
					$addMarks->bindParam(3,$stud_id);
					$addMarks->bindParam(4,$course_code);
					$addMarks->execute();
				}
				
			}
			
		}
		
		
		$_SESSION["marks-added"] = true;
	}

}



class Guardian{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getGuardians(){
		
		$getGuardians = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,primary_phone,secondary_phone,address,email,occupation,employer FROM guardians");
		$getGuardians->execute();
		
		if($getGuardians->rowCount()>0){
			$row = $getGuardians->fetchAll();
			return $row;
		}
	} //end of getting guardians
	
		//add Partner
	public function addGuardian($firstname,$middlename,$lastname,$primaryPhone,$secondaryPhone,$address,$email,$occupation,$employer){
				$addGuardian = $this->dbCon->prepare("INSERT INTO guardians (id,firstname,middlename,lastname,primary_phone,secondary_phone,address,email,occupation,employer)
				VALUES (:id,:firstname,:middlename,:lastname,:primary_phone,:secondary_phone,:address,:email,:occupation,:employer)" );
				$addGuardian->execute(array(
						  ':id'=>($primaryPhone),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':primary_phone'=>($primaryPhone),
						  ':secondary_phone'=>($secondaryPhone),
						  ':address'=>($address),
						  ':email'=>($email),
						  ':occupation'=>($occupation),
						  ':employer'=>($employer)
						  
						  ));
						  
						  $_SESSION['guardian-added']=true;
		
	}
	
	public function editGuardian($id,$firstname,$middlename,$lastname,$primary_phone,$secondary_phone,$address,$email,$occupation,$employer){
		$editGuardian =$this->dbCon->PREPARE("UPDATE guardians SET firstname =?,middlename=?,lastname=?,primary_phone=?,secondary_phone=?,address=?,email=?,occupation=?,employer=? WHERE id=?");
		$editGuardian->bindParam(1,$firstname);
		$editGuardian->bindParam(2,$middlename);
		$editGuardian->bindParam(3,$lastname);
		$editGuardian->bindParam(4,$primary_phone);
		$editGuardian->bindParam(5,$secondary_phone);
		$editGuardian->bindParam(6,$address);
		$editGuardian->bindParam(7,$email);
		$editGuardian->bindParam(8,$occupation);
		$editGuardian->bindParam(9,$employer);
		$editGuardian->execute();
		
		 $_SESSION['guardian-updated']=true;
		
	}

}

class Classes{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	
	public function getClasses(){
		$getClasses = $this->dbCon->Prepare("SELECT id,name FROM classes");
		$getClasses->execute();
		
		if($getClasses->rowCount()>0){
			$row = $getClasses->fetchAll();
			return $row;
		}
	} //end of getting classes
	
	public function getSubClasses(){
		$getSubClasses = $this->dbCon->Prepare("SELECT id,name, classes_id FROM sub_classes");
		$getSubClasses->execute();
		
		if($getSubClasses->rowCount()>0){
			$row = $getSubClasses->fetchAll();
			return $row;
		}
	} //end of getting classes
	
	public function getClassPerSubClass($sub_class){
		$getClassPerSubClass = $this->dbCon->PREPARE("SELECT classes_id FROM sub_classes WHERE id=?");
		$getClassPerSubClass->bindParam(1,$sub_class);
		$getClassPerSubClass->execute();
		
		if($getClassPerSubClass->rowCount()>0){
			$row = $getClassPerSubClass->fetch();
			
			return $row;
		}
	}
}


class Gender{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	
	public function getGender(){
		$getGender = $this->dbCon->Prepare("SELECT id,name FROM gender");
		$getGender->execute();
		
		if($getGender->rowCount()>0){
			$row = $getGender->fetchAll();
			return $row;
		}
	} //end of getting gender
	

}


class Blood{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	
	public function getBloodType(){
		$getBloodType = $this->dbCon->Prepare("SELECT id,name FROM blood_type");
		$getBloodType->execute();
		
		if($getBloodType->rowCount()>0){
			$row = $getBloodType->fetchAll();
			return $row;
		}
	} //end of getting blood_type
	

}

class Staff{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	
	public function getSpecificTeacher($teacher_id){
		$getSpecificTeacher = $this->dbCon->Prepare("SELECT id, CONCAT(firstname, ' ',middlename,' ',lastname) as name,
		dob,phone,email,address,qualifications,no_of_years_experience,date_joined,marital_status,name_of_spouse,number_of_children
		FROM staff WHERE id=?");
		$getSpecificTeacher->bindParam(1,$teacher_id);
		$getSpecificTeacher->execute();
		
		if($getSpecificTeacher->rowCount()>0){
			$row = $getSpecificTeacher->fetch();
			return $row;
		}
	} //end of getting teacher details



	public function getTeacherSublassID(){
		$getTeacherSublassID = $this->dbCon->PREPARE("SELECT sub_classes_id FROM sub_classes_has_subjects WHERE staff_id=?");
		$getTeacherSublassID->bindParam(1,$_SESSION['user']['username']);
		$getTeacherSublassID->execute();
		
		if($getTeacherSublassID->rowCount()>0){
			$row = $getTeacherSublassID->fetch();
			
			return $row;
		}
	}//end of getting Teacher Sublass ID
	
	
		public function getTeachers(){
		$getTeachers = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,
		dob,phone,email,address,qualifications,no_of_years_experience,date_joined,marital_status,name_of_spouse,number_of_children
		FROM staff");
		$getTeachers->execute();
		
		if($getTeachers->rowCount()>0){
			$row = $getTeachers->fetchAll();
			return $row;
		}
	} //end of getting teacher details
	
	
	public function addTeacher($firstname,$middlename,$lastname,$phone,$email,$address,$dob,$qualifications,$no_of_years_experience,$date_joined,$marital_status,$name_of_spouse,$number_of_children){
				$addTeacher = $this->dbCon->prepare("INSERT INTO staff (id,firstname,middlename,lastname,
		dob,phone,email,address,qualifications,no_of_years_experience,date_joined,marital_status,name_of_spouse,number_of_children)
				VALUES (:id,:firstname,:middlename,:lastname,
		:dob,:phone,:email,:address,:qualifications,:no_of_years_experience,:date_joined,:marital_status,:name_of_spouse,:number_of_children)" );
				$addTeacher->execute(array(
						  ':id'=>($phone),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':dob'=>$dob,
						  ':phone'=>($phone),
						  ':email'=>($email),
						  ':address'=>($address),
						  ':qualifications'=>($qualifications),
						  ':no_of_years_experience'=>($no_of_years_experience),
						  ':date_joined'=>($date_joined),
						  ':marital_status'=>($marital_status),
						  ':name_of_spouse'=>($name_of_spouse),
						  ':number_of_children'=>($number_of_children)
						  
						  ));
						  
			//add the teacher to users table for logins
			$role =20; //teacher role id
			$status = 1; //active status
			$username = $phone;
			$password = password_hash($phone, PASSWORD_DEFAULT)."\n"; 
			$addUser = new User();
			$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);
						  
		    $_SESSION['teacher-added']=true;
		
	}
	
	public function getClassesPerTeacher(){
		$getClassesPerTeacher = $this->dbCon->PREPARE("SELECT sub_classes_id as class_id, sub_classes.name as class_name FROM sub_classes_has_subjects
		INNER JOIN sub_classes ON (sub_classes.id=sub_classes_has_subjects.sub_classes_id) WHERE staff_id=?");
		$getClassesPerTeacher->bindParam(1,$_SESSION['user']['username']);
		$getClassesPerTeacher->execute();
		
		if($getClassesPerTeacher->rowCount()>0){
			$rows = $getClassesPerTeacher->fetchAll();
			
			return $rows;
			
		}
		
	}




	public function getAssignmentType(){
		$getAssignmentType = $this->dbCon->PREPARE("SELECT id, name, description FROM assignment_type LIMIT 2");
		$getAssignmentType->execute();
		
		if($getAssignmentType->rowCount()>0){
			$rows = $getAssignmentType->fetchAll();
			
			return $rows;
			
		}
		
	}


	public function getAllclasses(){
		$getAllclasses = $this->dbCon->PREPARE("SELECT id as class_id, name as class_name FROM classes");
		$getAllclasses->bindParam(1,$_SESSION['user']['username']);
		$getAllclasses->execute();
		
		if($getAllclasses->rowCount()>0){
			$rows = $getAllclasses->fetchAll();
			
			return $rows;
			
		}
		
	}



public function getAllSubclasses($class_id){
		$getAllSubclasses = $this->dbCon->PREPARE("SELECT id as sub_class_id, name as sub_class_name FROM sub_classes WHERE classes_id =?");
		$getAllSubclasses->bindParam(1,$class_id);
		$getAllSubclasses->execute();
		
		if($getAllSubclasses->rowCount()>0){
			$rows = $getAllSubclasses->fetchAll();
			
			return $rows;
			
		}
		
	}



public function getAllSubclassSubjects($sub_class_id){
		$getAllSubclassSubjects = $this->dbCon->PREPARE("SELECT subjects_id as subject_id, subjects.name as subject_name FROM sub_classes_has_subjects INNER JOIN subjects ON(sub_classes_has_subjects.subjects_id=subjects.id) WHERE Sub_classes_id =?");
		$getAllSubclassSubjects->bindParam(1,$sub_class_id);
		$getAllSubclassSubjects->execute();
		
		if($getAllSubclassSubjects->rowCount()>0){
			$rows = $getAllSubclassSubjects->fetchAll();
			
			return $rows;
			
		}
		
	}



	public function getClassPerStudent($sub_class_id){
		$getClassPerStudent = $this->dbCon->PREPARE(" SELECT sub_classes_id as class_id, sub_classes.name as class_name FROM sub_classes_has_subjects
		INNER JOIN sub_classes ON (sub_classes.id=sub_classes_has_subjects.sub_classes_id) WHERE sub_classes_id=? LIMIT 1");
		$getClassPerStudent->bindParam(1,$sub_class_id);
		$getClassPerStudent->execute();
		
		if($getClassPerStudent->rowCount()>0){
			$rows = $getClassPerStudent->fetchAll();
			
			return $rows;
			
		}
		
	}


	public function getSubjectsPerClassAndTeacher($class_id){
		$getSubjectsPerClassAndTeacher = $this->dbCon->Prepare("SELECT subjects_id as subjects_id, subjects.name as subject_name FROM sub_classes_has_subjects INNER JOIN subjects ON (sub_classes_has_subjects.subjects_id=subjects.id) WHERE sub_classes_id =? AND staff_id=?");
		$getSubjectsPerClassAndTeacher->bindParam(1,$class_id);
		$getSubjectsPerClassAndTeacher->bindParam(2,$_SESSION['user']['username']);
		$getSubjectsPerClassAndTeacher->execute();
		
		if($getSubjectsPerClassAndTeacher->rowCount()>0){
			$rows = $getSubjectsPerClassAndTeacher->fetchAll();
			return $rows;
		}
	} //end of getting subjects per class and teacher



	public function getSubjectsPerClassAndStudent($class_id){
		$getSubjectsPerClassAndStudent = $this->dbCon->Prepare("SELECT subjects_id as subjects_id, subjects.name as subject_name FROM sub_classes_has_subjects INNER JOIN subjects ON (sub_classes_has_subjects.subjects_id=subjects.id) WHERE sub_classes_id =?");
		$getSubjectsPerClassAndStudent->bindParam(1,$class_id);
		$getSubjectsPerClassAndStudent->execute();
		
		if($getSubjectsPerClassAndStudent->rowCount()>0){
			$rows = $getSubjectsPerClassAndStudent->fetchAll();
			return $rows;
		}
	} //end of getting subjects per class and Student

	public function getAssignments(){		
		$getAssignments = $this->dbCon->Prepare("SELECT assignments.id, title, due_date, subjects_id, terms_id as terms_id, assignment_type.name as assignment_type_name, terms.name as term_name, assignment_url, academic_year, subjects.name as subject_name, classes.name as class_name FROM assignments INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) INNER JOIN terms ON (assignments.terms_id=terms.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id) INNER JOIN sub_classes ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN classes ON(sub_classes.classes_id=classes.id) WHERE staff_id =?");
		$getAssignments->bindParam(1,$_SESSION['user']['username']);
		$getAssignments->execute();
		
		if($getAssignments->rowCount()>0){
			$rows = $getAssignments->fetchAll();
			return $rows;
		}
	} //end of getting assignments per teacher




	public function getStudentsUploadedAssignments($level, $subject_id){		
		$getStudentsUploadedAssignments = $this->dbCon->Prepare("SELECT submitted_assignment, assignments_id, assignment_type.name as assignment_type_name, subjects.name as subject_name,
		submissions.students_student_no as students_student_no, assignments.title as assignment_title, marks, students.firstname as student_firstname, students.lastname as student_surname, sub_classes.name as class_name 
		FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no)
		INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) 
		INNER JOIN subjects ON(assignments.subjects_id=subjects.id) WHERE sub_classes.id = ? AND subjects.id=?");
		$getStudentsUploadedAssignments->bindParam(1,$level);
		$getStudentsUploadedAssignments->bindParam(2,$subject_id);
		$getStudentsUploadedAssignments->execute();
		
		if($getStudentsUploadedAssignments->rowCount()>0){
			$rows = $getStudentsUploadedAssignments->fetchAll();
			return $rows;
		}
	} //end of getting assignments uploaded by students


	public function getFinalAssignmentMark($subject_id, $term){		
		$getFinalAssignmentMark = $this->dbCon->Prepare("SELECT students_student_no as student_no, marks as mark, subjects.name as subject_name, assignments.academic_year as academic_year, students.firstname as firstname, assignments.terms_id as term, assignment_type.name as assignment_type_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON(assignments.subjects_id=subjects.id) INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) WHERE students_student_no=? AND assignments.terms_id=? ");
		$getFinalAssignmentMark->bindParam(1,$_SESSION['user']['username']);
		$getFinalAssignmentMark->bindParam(2,$term);
		$getFinalAssignmentMark->execute();
		
		if($getFinalAssignmentMark->rowCount()>0){
			$rows = $getFinalAssignmentMark->fetchAll();
			return $rows;
		}
	} //end of getting Assignments Results



	public function getTrialMark($subject_id, $term){		
		$getTrialMark = $this->dbCon->Prepare("SELECT SUM(submissions.marks) as final_mark, exam_results.marks as exam_mark, exam_results.academic_year as academic_year, terms.name as term_name, subjects.name as subject_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) INNER JOIN exam_results ON(exam_results.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON(assignments.subjects_id=subjects.id) INNER JOIN terms ON (exam_results.terms_id=terms.id) WHERE submissions.students_student_no=? AND exam_results.students_student_no=? ");
		$getTrialMark->bindParam(1,$_SESSION['user']['username']);
		$getTrialMark->bindParam(2,$_SESSION['user']['username']);
		$getTrialMark->execute();
		
		if($getTrialMark->rowCount()>0){
			$row = $getTrialMark->fetchAll();
			return $row;
		}
	} //end of getting Assignments Results




/*public function getFinalExamPerTerm($academic_year, $term){		
		$getFinalExamPerTerm = $this->dbCon->Prepare("SELECT DISTINCT students_student_no as student_no, marks, subjects.name as subject_name, exam_type.name as assignment_type_name, terms.name as term_name, academic_year FROM exam_results INNER JOIN students ON(exam_results.students_student_no=students.student_no) INNER JOIN terms ON(exam_results.terms_id=terms.id) INNER JOIN exam_type ON(exam_results.exam_type_id=exam_type.id) INNER JOIN classes_has_subjects ON(exam_results.classes_has_subjects_subjects_id=classes_has_subjects.subjects_id) INNER JOIN subjects ON(classes_has_subjects.subjects_id=subjects.id) WHERE students_student_no=? AND terms.id=? ");
		$getFinalExamPerTerm->bindParam(1,$_SESSION['user']['username']);
		$getFinalExamPerTerm->bindParam(2,$term);
		$getFinalExamPerTerm->execute();
		
		if($getFinalExamPerTerm->rowCount()>0){
			$rows = $getFinalExamPerTerm->fetchAll();
			return $rows;
		}
	} */ //end of getting Exam Mark 


public function getAssignmentID($sub_class_id){
		$getAssignmentID = $this->dbCon->Prepare("SELECT assignments_id, sub_classes_id FROM sub_classes_has_assignments WHERE sub_classes_id=?");
		$getAssignmentID->bindParam(1,$sub_class_id);
		$getAssignmentID->execute();
		
		if($getAssignmentID->rowCount()>0){
			$rows = $getAssignmentID->fetch();
			return $rows;
		}

		//$assignments_id = $getAssignmentID['assignments_id'];
}




	public function uploadAssignment($title, $assignment_url, $due_date, $academic_year, $terms_id,$subjects_id,$level, $assignment_type){

				$getAssignmentCount = $this->dbCon->PREPARE("SELECT assignment_type_id, subjects_id FROM assignments WHERE assignment_type_id=? AND subjects_id=?");
				$getAssignmentCount->bindParam(1,$assignment_type);
				$getAssignmentCount->bindParam(2,$subjects_id);
				$getAssignmentCount->execute();
				
				if($getAssignmentCount->rowCount()>=1){
echo '<script language="javascript">';
echo 'alert("Assignment Type for this Class already Exists")';
echo '</script>';
				}else{

								$uploadAssignment = $this->dbCon->prepare("INSERT INTO assignments (title,assignment_url,due_date,academic_year,terms_id,staff_id,subjects_id, assignment_type_id)
				VALUES (:title,:assignment_url,:due_date,:academic_year,:terms_id,:staff_id,:subjects_id, :assignment_type_id)" );
				$uploadAssignment->execute(array(
						  ':title'=>($title),
						  ':assignment_url'=>($assignment_url),
						  ':due_date'=>($due_date),
						  ':academic_year'=>($academic_year),
						  ':terms_id'=>($terms_id),
						  ':staff_id'=>($_SESSION['user']['username']),
						  ':subjects_id'=>($subjects_id),
						  ':assignment_type_id'=>($assignment_type)					  
						  ));
						  
						  
				$assignments_id = $this->dbCon->lastInsertId();


				$addSubclassesHasAssignments = $this->dbCon->prepare("INSERT INTO sub_classes_has_assignments (sub_classes_id, assignments_id)
				VALUES (:sub_classes_id, :assignments_id)" );
				$addSubclassesHasAssignments->execute(array(
						  ':sub_classes_id'=>($level),
						  ':assignments_id'=>($assignments_id)					  
						  ));
						  
						  $_SESSION['uploaded']=true;

				}


		
	}





	public function deleteAssignment($id, $assignment_url){

		unlink($assignment_url);

		$deleteForeignTable =$this->dbCon->PREPARE("DELETE FROM sub_classes_has_assignments WHERE assignments_id='$id'");
		$deleteForeignTable->bindParam(1,$id);
		$deleteForeignTable->execute();

		$deleteAssignment =$this->dbCon->PREPARE("DELETE FROM assignments WHERE id='$id'");
		$deleteAssignment->bindParam(1,$id);
		$deleteAssignment->execute();
		
	}//End od deleting an assignment together with the linking table


public function deleteStudentAssignment($id, $assignment_url){

		unlink($assignment_url);

		$deleteStudentAssignment =$this->dbCon->PREPARE("DELETE FROM submissions WHERE assignments_id=?");
		$deleteStudentAssignment->bindParam(1,$id);
		$deleteStudentAssignment->execute();
		
	}//End of deleting a submitted student assignment


	public function getSpecificAssignment($id){
		$getSpecificAssignment = $this->dbCon->Prepare("SELECT assignment_url
		FROM assignments WHERE id=?");
		$getSpecificAssignment->bindParam(1,$id);
		$getSpecificAssignment->execute();
		
		if($getSpecificAssignment->rowCount()>0){
			$row = $getSpecificAssignment->fetch();
			return $row;
		}
	} //end of getting Assignment URL


	public function getSpecificStudentAssignmentURL($id){
		$getSpecificStudentAssignmentURL = $this->dbCon->Prepare("SELECT submitted_assignment FROM submissions WHERE assignments_id=?");
		$getSpecificStudentAssignmentURL->bindParam(1,$id);
		$getSpecificStudentAssignmentURL->execute();
		
		if($getSpecificStudentAssignmentURL->rowCount()>0){
			$row = $getSpecificStudentAssignmentURL->fetch();
			return $row;
		}
	} //end of getting Submitted Assignment URL

	public function getTerm(){
		$getTerm = $this->dbCon->Prepare("SELECT id, name FROM terms");
		$getTerm->execute();
		
		if($getTerm->rowCount()>0){
			$row = $getTerm->fetch();
			return $row;
		}
	} //end of getting subjects



	public function assignStudentMarks($marks, $assignments_id, $students_student_no){
		$assignStudentMarks =$this->dbCon->PREPARE("UPDATE submissions SET marks =? WHERE assignments_id=? AND students_student_no=? ");
		$assignStudentMarks->bindParam(1,$marks);
		$assignStudentMarks->bindParam(2,$assignments_id);
		$assignStudentMarks->bindParam(3,$students_student_no);
		$assignStudentMarks->execute();
		
		 $_SESSION['marked']=true;
		
	}



public function getAllStudentsPerClassSubject($sub_class_id){
		$getAllStudentsPerClassSubject = $this->dbCon->PREPARE("SELECT student_no, firstname, lastname, exam_results.marks as marks FROM students LEFT OUTER JOIN exam_results ON(exam_results.students_student_no=students.student_no) WHERE students.sub_classes_id=?");
		$getAllStudentsPerClassSubject->bindParam(1,$sub_class_id);
		$getAllStudentsPerClassSubject->execute();
		
		if($getAllStudentsPerClassSubject->rowCount()>0){
			$rows = $getAllStudentsPerClassSubject->fetchAll();
			
			return $rows;
			
		}
		
	}



public function getAllExamsPerClassSubject($class_id, $sub_class_id, $subject_id, $settings_id){
		$getAllExamsPerClassSubject = $this->dbCon->PREPARE("SELECT students_student_no as student_no, students.firstname as firstname, students.lastname as lastname, marks, terms.name as term_name, academic_year, subjects.name as subject_name  FROM exam_results INNER JOIN terms ON(exam_results.terms_id=terms.id) INNER JOIN students ON(exam_results.students_student_no=students.student_no) INNER JOIN classes_has_subjects ON(exam_results.classes_has_subjects_subjects_id=classes_has_subjects.subjects_id) INNER JOIN subjects ON(classes_has_subjects.subjects_id=subjects.id) WHERE students.sub_classes_id=? AND classes_has_subjects.classes_id=? AND classes_has_subjects.subjects_id=? AND terms_id=?");
		$getAllExamsPerClassSubject->bindParam(1,$sub_class_id);
		$getAllExamsPerClassSubject->bindParam(2,$class_id);
		$getAllExamsPerClassSubject->bindParam(3,$subject_id);
		$getAllExamsPerClassSubject->bindParam(4,$settings_id);
		$getAllExamsPerClassSubject->execute();
		
		if($getAllExamsPerClassSubject->rowCount()>0){
			$rows = $getAllExamsPerClassSubject->fetchAll();
			
			return $rows;
			
		}
		
	}



public function getStudentsPerExamType($sub_class_id, $subject_id, $exam_type_id, $academic_year){
		$getStudentsPerExamType = $this->dbCon->PREPARE("SELECT students_student_no as student_no, students.firstname as firstname, students.lastname as lastname, marks, academic_year FROM exam_results INNER JOIN students ON(exam_results.students_student_no=students.student_no) INNER JOIN exam_type ON(exam_results.exam_type_id=exam_type.id) WHERE students.sub_classes_id=? AND exam_type.id=? AND academic_year=?");
		$getStudentsPerExamType->bindParam(1,$sub_class_id);
		$getStudentsPerExamType->bindParam(2,$exam_type_id);
		$getStudentsPerExamType->bindParam(3,$academic_year);
		$getStudentsPerExamType->execute();
		
		if($getStudentsPerExamType->rowCount()>0){
			$rows = $getStudentsPerExamType->fetchAll();
			
			return $rows;
			
		}
		
	}




public function getSubjectById($subject_id){
		$getSubjectById = $this->dbCon->PREPARE("SELECT name as subject_name FROM subjects WHERE id=?");
		$getSubjectById->bindParam(1,$subject_id);
		$getSubjectById->execute();
		
		if($getSubjectById->rowCount()>0){
			$row = $getSubjectById->fetch();
			
			return $row;
			
		}
		
	}


public function getClassByID($sub_class_id){
		$getClassByID = $this->dbCon->PREPARE("SELECT name as sub_class_name FROM sub_classes WHERE id=?");
		$getClassByID->bindParam(1,$sub_class_id);
		$getClassByID->execute();
		
		if($getClassByID->rowCount()>0){
			$row = $getClassByID->fetch();
			
			return $row;
			
		}
		
	}



public function getClassesWithSubjects($sub_class_id, $subject_id){
		$getClassesWithSubjects = $this->dbCon->PREPARE("SELECT classes_has_subjects.classes_id as linked_classes_id, subjects_id FROM classes_has_subjects INNER JOIN classes ON(classes_has_subjects.classes_id=classes.id) INNER JOIN sub_classes ON(sub_classes.classes_id=classes.id) WHERE subjects_id=? AND classes_has_subjects.classes_id=sub_classes.classes_id");
		$getClassesWithSubjects->bindParam(1,$subject_id);
		$getClassesWithSubjects->execute();
		
		if($getClassesWithSubjects->rowCount()>0){
			$rows = $getClassesWithSubjects->fetch();
			
			return $rows;
			
		}
		
	}





public function getExamTypes(){
		$getExamTypes = $this->dbCon->PREPARE("SELECT id, name FROM exam_type");
		$getExamTypes->execute();
		
		if($getExamTypes->rowCount()>0){
			$rows = $getExamTypes->fetchAll();
			
			return $rows;
			
		}
		
	}



public function getUserUsingUsername(){
		$getUserUsingUsername = $this->dbCon->PREPARE("SELECT id FROM staff WHERE id=?");
		$getUserUsingUsername->bindParam(1,$_SESSION['user']['username']);
		$getUserUsingUsername->execute();
		
		if($getUserUsingUsername->rowCount()>0){
			$row = $getUserUsingUsername->fetch();
			
			return $row;
			
		}
		
	}



public function getUser(){
		$getUser = $this->dbCon->PREPARE("SELECT id, name FROM exam_type");
		$getUser->execute();
		
		if($getUser->rowCount()>0){
			$rows = $getUser->fetchAll();
			
			return $rows;
			
		}
		
	}



	public function recordStudentsExams($marks, $academic_year, $term, $students_student_no, $exam_type_id, $staff_id, $classes_has_subjects_classes_id, $classes_has_subjects_subjects_id){
			$exam_status_id = 1;
				$recordStudentsExams = $this->dbCon->prepare("INSERT INTO exam_results (marks,academic_year,terms_id,students_student_no,exam_type_id,staff_id,classes_has_subjects_classes_id, classes_has_subjects_subjects_id, exam_status_id)
				VALUES (:marks,:academic_year,:terms_id,:students_student_no,:exam_type_id,:staff_id,:classes_has_subjects_classes_id, :classes_has_subjects_subjects_id, :exam_status_id)" );
				$recordStudentsExams->execute(array(
						  ':marks'=>($marks),
						  ':academic_year'=>($academic_year),
						  ':terms_id'=>($term),
						  ':students_student_no'=>($students_student_no),
						  ':exam_type_id'=>($exam_type_id),
						  ':staff_id'=>($staff_id),
						  ':classes_has_subjects_classes_id'=>($classes_has_subjects_classes_id),
						  ':classes_has_subjects_subjects_id'=>($classes_has_subjects_subjects_id),
						  ':exam_status_id'=>($exam_status_id)				  
						  ));
						  						 		
	}




	public function updateStudentExamMark($marks, $student_no){
		$updateStudentExamMark =$this->dbCon->PREPARE("UPDATE exam_results SET marks =? WHERE students_student_no=? ");
		$updateStudentExamMark->bindParam(1,$marks);
		$updateStudentExamMark->bindParam(2,$student_no);
		$updateStudentExamMark->execute();
				
	}




}


class Contact{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function bookFlight($name,$email,$phone,$departure_date,$departure_time,$departure_passenger,$return_date,$return_time,$return_passenger,$origin,$destination){
				$recipient = 'traveloptions@globemw.net';
				$subject="FLIGHT BOOKING [FROM $origin to $destination]";
				$mailBody ="Dear Sir,\n\nI would like to book the flight and the following are my details;\n\nDERPATURE DETAILS\nName: $name\nEmail: $email\nPhone: $phone\nDeparture Date: $departure_date\nDeparture Time: $departure_time\nNumber of Passengers: $departure_passenger\n\nRERURN DETAIS\nReturn Date: $return_date\nReturn Time: $return_time\nReturn Passengers: $return_passenger\n\nRegards,\n\n $name";
				mail($recipient, $subject, $mailBody, "From: $name <$email>");
				$_SESSION["message-sent"] = true;
	} //end of sending message
	
	public function addNumbers($ar){
		foreach($ar as $a){
			//check number
			$checkNumber = $this->dbCon->PREPARE("SELECT number FROM numberstwo WHERE number =?");
		$checkNumber->bindParam(1,$a);
		$checkNumber->execute();
		
		if($checkNumber->rowCount()>0){
			//skip
			
		}else{
			if(!empty($a)){
				$addNumbers = $this->dbCon->prepare("INSERT INTO numberstwo (number) VALUES (:number)" );
				$addNumbers->execute(array(
						  ':number'=>($a)));
			}
			
		}
			
		}
		
						  
						  $_SESSION['numbers-added']=true;
	}
	
	public function sendMessage($subject,$message){
		
		//get all Contacts
		$status = 1;
		$getContacts = $this->dbCon->PREPARE("SELECT email FROM customer WHERE status =?");
		$getContacts->bindParam(1,$status);
		$getContacts->execute();
		
		if($getContacts->rowCount()>0){
			
			$rows = $getContacts->fetchAll();
			$name ="Travel Options";
			$email ="traveloptions@globemw.net";
			foreach($rows as $row){
				$recipient = $row['email'];	
				$headers  = "From: $recipient\r\n"; 
				$headers .= "Content-type: text/html\r\n";
				
				$mailBody ="$message";
				mail($recipient, $subject, $mailBody, $headers);
			}
			
		}
	}
	
	public function getCustomers(){
		$getCustomers = $this->dbCon->PREPARE("SELECT name, phone, email, address FROM customer");
		$getCustomers->execute();
		
		if($getCustomers->rowCount()>0){
			$rows = $getCustomers->fetchAll();
			return $rows;
		}
		
	}
}


?>