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
	
	public function getCurrentSettings(){
		$getCurrentSettings = $this->dbCon->PREPARE("SELECT id,academic_year,term,fees,status FROM settings WHERE status=?");
		$getCurrentSettings->bindParam(1,$status);
		$getCurrentSettings->execute();
		
		if($getCurrentSettings->rowCount()>0){
			$row = $getCurrentSettings->fetch();
			
			return $row;
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
						  ':user_status_id'=>($status),
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
			$getUsers = $this->dbCon->prepare("SELECT username, fname, lname, email, phone, users.role_id AS role_id, roles.name AS role from users INNER JOIN roles ON (roles.role_id = users.role_id) WHERE username = ?" );
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
		student_status.name as student_status,sub_classes.name as sub_class
		FROM students INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id) WHERE student_no=?");
		$getSpecificStudent->bindParam(1,$id);
		$getSpecificStudent->execute();
		
		if($getSpecificStudent->rowCount()>0){
			$row = $getSpecificStudent->fetch();
			return $row;
		}
	} //end of getting news
	
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
					echo "ff"; die();
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
		$getClassesPerTeacher = $this->dbCon->PREPARE(" SELECT sub_classes_id as class_id, sub_classes.name as class_name FROM sub_classes_has_subjects
		INNER JOIN sub_classes ON (sub_classes.id=sub_classes_has_subjects.sub_classes_id) WHERE staff_id=?");
		$getClassesPerTeacher->bindParam(1,$_SESSION['user']['username']);
		$getClassesPerTeacher->execute();
		
		if($getClassesPerTeacher->rowCount()>0){
			$rows = $getClassesPerTeacher->fetchAll();
			
			return $rows;
			
		}
		
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