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

	public function UpdateSettingsTrail($current_id, $current_academic_year, $current_term, $current_fees, $current_status){	
			$UpdateSettingsTrail = $this->dbCon->prepare("INSERT INTO settings_trail (id, academic_year, term, fees, status)
				VALUES (:id, :academic_year, :term, :fees, :status)" );
				$UpdateSettingsTrail->execute(array(
						':id'=>($current_term),
					    ':academic_year'=>($current_academic_year),
					    ':term'=>($current_term),
					    ':fees'=>($current_fees),
					    ':status'=>($current_status)
						  ));

						 		
	}//End of adding Current settings into Settings Trial


	public function getSettings($status){
		$getSettings = $this->dbCon->PREPARE("SELECT id,academic_year,term,fees,status FROM settings WHERE status=?");
		$getSettings->bindParam(1,$status);
		$getSettings->execute();
		
		if($getSettings->rowCount()>0){
			$rows = $getSettings->fetchAll();
			
			return $rows;
		}
	}


	public function getTerms(){
		$getTerms = $this->dbCon->PREPARE("SELECT id, name FROM terms");
		//$getTerms->bindParam(1,$status);
		$getTerms->execute();
		
		if($getTerms->rowCount()>0){
			$rows = $getTerms->fetchAll();
			
			return $rows;
		}

		
	}


	public function getPaymentType(){
		$getPaymentType = $this->dbCon->PREPARE("SELECT id, name FROM payment_type");
		$getPaymentType->execute();
		
		if($getPaymentType->rowCount()>0){
			$rows = $getPaymentType->fetchAll();
			
			return $rows;
		}

		
	}// End of getting Payment Type


	public function getFeesPaymentType(){
		$id = 1;
		$getFeesPaymentType = $this->dbCon->PREPARE("SELECT id, name FROM payment_type WHERE id=?");
		$getFeesPaymentType->bindParam(1, $id);
		$getFeesPaymentType->execute();
		
		if($getFeesPaymentType->rowCount()>0){
			$rows = $getFeesPaymentType->fetchAll();
			
			return $rows;
		}

		
	}//End of getting fees Payment type


	public function updateSettings($academic_year, $term, $fees){
			$status = 1;
			$updateSettings = $this->dbCon->prepare("UPDATE settings SET academic_year=?,term=?, fees=?, status=?");
			$updateSettings->bindParam(1,$academic_year);
			$updateSettings->bindParam(2,$term);
			$updateSettings->bindParam(3,$fees);
			$updateSettings->bindParam(4,$status);
			$updateSettings->execute();

			$_SESSION['settings-added']=true;

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
				$roles_id =$row['roles_id'];
				$user_status_id =$row['user_status_id'];

				//verify password
				if (password_verify($password, $hash_pass) && $user_status_id == 1) {
					
					// Success!
					$_SESSION['user'] = $row;
					if ($roles_id == 10) {
						header("Location: index.php");

					} elseif ($roles_id == 20) {
						header("Location: teacher-index.php");

					}elseif ($roles_id == 30) {
						header("Location: student-index.php");

					}elseif ($roles_id == 40) {
						header("Location: librarian-index.php");
					
					}elseif ($roles_id == 50) {
						header("Location: guardian-index.php");

					}elseif($roles_id == 60){
						header("Location: accountant-index.php");

					}else{
						$_SESSION['invalidUser']=true;
					}
					
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
				$getUserProfile = $this->dbCon->prepare("SELECT username,firstname,middlename,lastname, roles.name as role FROM users INNER JOIN roles ON(users.roles_id=roles.id) WHERE username=? " );
				$getUserProfile->bindParam(1, $_SESSION['user']['username']);
				$getUserProfile->execute();

				if($getUserProfile->rowCount() ==1){
				$row = $getUserProfile -> fetch();

				return $row;
				//verify password



				}

		
	}


	public function checkPassword(){	
				$checkPassword = $this->dbCon->prepare("SELECT password FROM users WHERE username=?" );
				$checkPassword->bindParam(1, $_SESSION['user']['username']);
				$checkPassword->execute();

				if($checkPassword->rowCount() == 1){
				$row = $checkPassword -> fetch();

				$password = trim($row['password']);
				$username = trim($_SESSION['user']['username']);

				if (password_verify($username, $password)) {
					header("location: change-password.php");
				}
				
				

			}
		
	}//End of check Password


	public function getPassword(){	
				$getPassword = $this->dbCon->prepare("SELECT password FROM users WHERE username=?" );
				$getPassword->bindParam(1, $_SESSION['user']['username']);
				$getPassword->execute();

				if($getPassword->rowCount() == 1){
				$row = $getPassword -> fetch();
				return $row;
				}
					
		
	}//End of getting Password


	public function updatepassword($new_password){
		$password = password_hash($new_password, PASSWORD_DEFAULT)."\n";

		$updatepassword =$this->dbCon->PREPARE("UPDATE users SET password =? WHERE username=? ");
		$updatepassword->bindParam(1,$password);
		$updatepassword->bindParam(2,$_SESSION['user']['username']);
		$updatepassword->execute();

		$_SESSION['password-updated']= true;
				
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
	

	public function countAllUsers(){
		//get all users
		try{
			$countAllUsers = $this->dbCon->prepare("SELECT username, firstname FROM users");
			$countAllUsers->execute();
			if($countAllUsers->rowCount()>0){
				$rows = $countAllUsers->fetchAll();
				return $rows;
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
			$getUsers = $this->dbCon->prepare("SELECT roles.name as role_name,
			user_status.name as status_name, username, firstname,middlename,lastname, user_status_id, roles_id, date_added FROM users INNER JOIN roles ON(users.roles_id=roles.id) 
				INNER JOIN user_status ON(users.user_status_id=user_status.id) ORDER BY roles.name ASC" );
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

	public function disableSpecificUser($username){
			$status = '0';
			$disableSpecificUser = $this->dbCon->PREPARE("UPDATE users SET user_status_id=? WHERE username=?");
			$disableSpecificUser->bindParam(1,$status);
			$disableSpecificUser->bindParam(2,$username);
			$disableSpecificUser->execute();

			$_SESSION['user_deactivated'] = true;
		}//End of Disabling a User

	public function enableSpecificUser($username){
			$status = '1';
			$enableSpecificUser = $this->dbCon->PREPARE("UPDATE users SET user_status_id=? WHERE username=?");
			$enableSpecificUser->bindParam(1,$status);
			$enableSpecificUser->bindParam(2,$username);
			$enableSpecificUser->execute();

			$_SESSION['user_activated'] = true;
		}//End of Enabling a User
	



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
		$getStudents = $this->dbCon->Prepare("SELECT student_no, students.email as email, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname,dob, gender.name as gender, CONCAT(guardians.firstname, ' ' ,guardians.lastname)as guardian, place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type,
		student_status.name as student_status,sub_classes.name as sub_class
		FROM students INNER JOIN guardians ON(students.guardians_id=guardians.id) INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id)");
		$getStudents->execute();
		
		if($getStudents->rowCount()>0){
			$rows = $getStudents->fetchAll();
			return $rows;
		}
	} //end of getting students
	
	public function getSpecificStudent($id){
		$getSpecificStudent = $this->dbCon->Prepare("SELECT student_no, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname,dob, gender.name as gender, gender_id, guardians.email as guardian_email, CONCAT(guardians.firstname, ' ', guardians.middlename, ' ', guardians.lastname) as guardian_name, place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type, blood_type_id,
		student_status.name as student_status,sub_classes.name as sub_class, sub_classes.id as sub_class_id
		FROM students INNER JOIN guardians ON(students.guardians_id=guardians.id) INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id) WHERE student_no=?");
		$getSpecificStudent->bindParam(1,$id);
		$getSpecificStudent->execute();
		
		if($getSpecificStudent->rowCount()>0){
			$row = $getSpecificStudent->fetch();
			return $row;
		}
	} //end of getting Specific Student


	public function getAllStudentsPerSub_class($sub_class){
		$getAllStudentsPerSub_class = $this->dbCon->Prepare("SELECT student_no, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname,dob, gender.name as gender, gender_id, guardians.email as guardian_email, CONCAT(guardians.firstname, ' ', guardians.middlename, ' ', guardians.lastname) as guardian_name, place_of_birth,country_of_birth,nationality,home_language,
		year_of_entry,sporting_interests,musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,leaving_date,blood_type.name as blood_type, blood_type_id,
		student_status.name as student_status,sub_classes.name as sub_class, sub_classes.id as sub_class_id
		FROM students INNER JOIN guardians ON(students.guardians_id=guardians.id) INNER JOIN blood_type ON (blood_type.id=students.blood_type_id) INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN student_status 
		ON (student_status.id=students.student_status_id) INNER JOIN gender ON (gender.id=students.gender_id) WHERE students.sub_classes_id=?");
		$getAllStudentsPerSub_class->bindParam(1,$sub_class);
		$getAllStudentsPerSub_class->execute();
		
		if($getAllStudentsPerSub_class->rowCount()>0){
			$rows = $getAllStudentsPerSub_class->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Sub Class
	
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
	

	public function getStudentDetailsPerAdmin($id){
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
	public function addStudent($guardian_id,$sub_class,$student_picture,$firstname,$middlename,$lastname,$gender,$blood_type,$dob, $email, $place_of_birth,$country_of_birth,$nationality,$home_language,$year_of_entry,$sporting_interests,$musical_interests,$other_interests,$medical_information,$other_schools_attended,$home_doctor,$admission_date){
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
					
					$addStudent = $this->dbCon->prepare("INSERT INTO students (student_no,firstname,middlename,lastname,dob, email, place_of_birth,country_of_birth,nationality,home_language,year_of_entry,sporting_interests,
					musical_interests,other_interests,medical_information,other_schools_attended,student_picture,home_doctor,admission_date,blood_type_id,student_status_id,sub_classes_id,guardians_id, gender_id)
					VALUES (:student_no,:firstname,:middlename,:lastname,:dob, :email, :place_of_birth,:country_of_birth,:nationality,:home_language,:year_of_entry,:sporting_interests,
					:musical_interests,:other_interests,:medical_information,:other_schools_attended,:student_picture,:home_doctor,:admission_date,:blood_type_id,:student_status_id,:sub_classes_id,:guardian_id, :gender_id)" );
					$addStudent->execute(array(
						  ':student_no'=>($student_no),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),						  
						  ':dob'=>($dob),
						  ':email'=>($email),
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
						  ':sub_classes_id'=>($_SESSION['sub_class']),
						  ':guardian_id'=>($_SESSION['guardian_id']),
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
						  
						  //add the Student to users table for logins
							$role =30; //Students role id
							$status = 1; //active status
							$username = $student_no;
							$password = password_hash($student_no, PASSWORD_DEFAULT)."\n"; 
							$addUser = new User();
							$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);
			
						  $_SESSION['student-added']=true;
		
					
				}elseif($class_id==4 || $class_id==5 || $class_id==6 || $class_id==7){

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

				  $addFirstStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addFirstStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_a']
				  ));

				  $addSecondStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addSecondStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_b']
				  ));

				  $addThirdStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addThirdStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_c']
				  ));

				  $addFourthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addFourthStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_d']
				  ));

				  $addFifthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addFifthStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_e']
				  ));

				  $addSixthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
				  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
				  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
				  $addSixthStudentSubject->execute(array(
					':students_student_no'=>$student_no,
					':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
					':classes_has_subjects_subjects_id'=>$_SESSION['option_f']
				  ));

		  		  //update student count
				  $newCount = $count+1;
				  $updateStudentCount = new Students();
				  $updateStudentCount->updateStudentCount($newCount);
				  
				  //add the Student to users table for logins
					$role =30; //Students role id
					$status = 1; //active status
					$username = $student_no;
					$password = password_hash($student_no, PASSWORD_DEFAULT)."\n"; 
					$addUser = new User();
					$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);
	
				  $_SESSION['student-added']=true;

					
		}
				
	}
	

public function changeSubjectOptions(){

		$deleteOptions = $this->dbCon->Prepare("DELETE FROM students_has_classes_has_subjects WHERE students_student_no=?");
		$deleteOptions->bindParam(1,$_SESSION['student_id']);
		$deleteOptions->execute();


			  $addFirstStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addFirstStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_a']
			  ));

			  $addSecondStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addSecondStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_b']
			  ));

			  $addThirdStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addThirdStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_c']
			  ));

			  $addFourthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addFourthStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_d']
			  ));

			  $addFifthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addFifthStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_e']
			  ));

			  $addSixthStudentSubject = $this->dbCon->PREPARE("INSERT INTO students_has_classes_has_subjects 
			  (students_student_no,classes_has_subjects_classes_id,classes_has_subjects_subjects_id)
			  VALUES (:students_student_no,:classes_has_subjects_classes_id,:classes_has_subjects_subjects_id)");
			  $addSixthStudentSubject->execute(array(
				':students_student_no'=>$_SESSION['student_id'],
				':classes_has_subjects_classes_id'=>$_SESSION['class_id'],
				':classes_has_subjects_subjects_id'=>$_SESSION['option_f']
			  ));

		$_SESSION['subjects_changed'] = true;
	} //end of changing subject options
	
	
	public function editStudent($student_no,$sub_class, $student_picture,$firstname,$middlename,$lastname,$gender,$blood_type,$dob,$place_of_birth,$country_of_birth,$nationality,$home_language,$year_of_entry,$sporting_interests,$musical_interests,$other_interests,$medical_information,$other_schools_attended,$home_doctor,$admission_date){
		$editStudent = $this->dbCon->PREPARE("UPDATE students SET sub_classes_id=?, student_picture=?, firstname=?, middlename=?, lastname=?, gender_id=?, blood_type_id=?, dob=?, place_of_birth=?, country_of_birth=?, nationality=?, home_language=?, year_of_entry=?, sporting_interests=?, musical_interests=?, other_interests=?, medical_information=?, other_schools_attended=?, home_doctor=?, admission_date=?  WHERE student_no=?");
		$editStudent->bindParam(1,$sub_class);
		$editStudent->bindParam(2,$student_picture);
		$editStudent->bindParam(3,$firstname);
		$editStudent->bindParam(4,$middlename);
		$editStudent->bindParam(5,$lastname);
		$editStudent->bindParam(6,$gender);
		$editStudent->bindParam(7,$blood_type);
		$editStudent->bindParam(8,$dob);
		$editStudent->bindParam(9,$place_of_birth);
		$editStudent->bindParam(10,$country_of_birth);
		$editStudent->bindParam(11,$nationality);
		$editStudent->bindParam(12,$home_language);
		$editStudent->bindParam(13,$year_of_entry);
		$editStudent->bindParam(14,$sporting_interests);
		$editStudent->bindParam(15,$musical_interests);
		$editStudent->bindParam(16,$other_interests);
		$editStudent->bindParam(17,$medical_information);
		$editStudent->bindParam(18,$other_schools_attended);
		$editStudent->bindParam(19,$home_doctor);
		$editStudent->bindParam(20,$admission_date);
		$editStudent->bindParam(21,$student_no);
		$editStudent->execute();
		$_SESSION['student-edited'] = true;
	}


    public function deleteStudent($student_no){

    	$examResults = $this->dbCon->Prepare("DELETE FROM exam_results WHERE students_student_no=?");
		$examResults->bindParam(1,$student_no);
		$examResults->execute();

		$examResults = $this->dbCon->Prepare("DELETE FROM submissions WHERE students_student_no=?");
		$examResults->bindParam(1,$student_no);
		$examResults->execute();

		$users = $this->dbCon->Prepare("DELETE FROM users WHERE username=?");
		$users->bindParam(1,$student_no);
		$users->execute();

		$payments = $this->dbCon->Prepare("DELETE FROM payments WHERE students_student_no=?");
		$payments->bindParam(1,$student_no);
		$payments->execute();

		$payments = $this->dbCon->Prepare("DELETE FROM students_has_classes_has_subjects WHERE students_student_no=?");
		$payments->bindParam(1,$student_no);
		$payments->execute();


		$deleteStudent = $this->dbCon->Prepare("DELETE FROM students WHERE student_no=?");
		$deleteStudent->bindParam(1,$student_no);
		$deleteStudent->execute();

		$_SESSION['student_deleted'] = true;
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


	public function getAllStudentsAssignment($sub_class_id){

	$getAllStudentsAssignment = $this->dbCon->Prepare("SELECT DISTINCT assignments.id as assignment_id, title, due_date, subjects_id, assignment_type.name as assignment_type_name, terms_id, assignment_url, academic_year, subjects.name as subject_name FROM assignments INNER JOIN assignment_type ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id) INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) WHERE sub_classes_has_assignments.sub_classes_id=?");
		$getAllStudentsAssignment->bindParam(1, $sub_class_id);
		$getAllStudentsAssignment->execute();
		
		if($getAllStudentsAssignment->rowCount()>0){
			$rows = $getAllStudentsAssignment->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student
	
	
	public function getStudentAssignment($sub_class_id, $student_no){

	$getStudentAssignment = $this->dbCon->Prepare("SELECT assignments.id as assignment_id, title, due_date, submissions.marks as marks,
	subjects_id, assignment_type.name as assignment_type_name, terms_id, assignment_url, academic_year, subjects.name as subject_name
	FROM assignments INNER JOIN submissions ON(submissions.assignments_id=assignments.id) INNER JOIN assignment_type
	ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id)
	INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) 
	WHERE sub_classes_has_assignments.sub_classes_id=? AND students_student_no=?" );
		$getStudentAssignment->bindParam(1, $sub_class_id);
		$getStudentAssignment->bindParam(2, $student_no);
		$getStudentAssignment->execute();
		
		if($getStudentAssignment->rowCount()>0){
			$rows = $getStudentAssignment->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student

	public function getStudentAssignmentMarks($sub_class_id){

	$getStudentAssignment = $this->dbCon->Prepare("SELECT assignments.id as assignment_id, title, due_date, submissions.marks as marks,
	subjects_id, assignment_type.name as assignment_type_name, terms_id, assignment_url, academic_year, subjects.name as subject_name
	FROM assignments INNER JOIN submissions ON(submissions.assignments_id=assignments.id) INNER JOIN assignment_type
	ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id)
	INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) 
	WHERE sub_classes_has_assignments.sub_classes_id=? AND students_student_no=?" );
		$getStudentAssignment->bindParam(1, $sub_class_id);
		$getStudentAssignment->bindParam(2, $_SESSION['user']['username']);
		$getStudentAssignment->execute();
		
		if($getStudentAssignment->rowCount()>0){
			$rows = $getStudentAssignment->fetchAll();
			return $rows;
		}
	} //end of getting assignments per student


	public function getStudentAssignmentMarksPerAdmin($sub_class_id, $id){

	$getStudentAssignment = $this->dbCon->Prepare("SELECT assignments.id as assignment_id, title, due_date, submissions.marks as marks,
	subjects_id, assignment_type.name as assignment_type_name, terms_id, assignment_url, academic_year, subjects.name as subject_name
	FROM assignments INNER JOIN submissions ON(submissions.assignments_id=assignments.id) INNER JOIN assignment_type
	ON(assignments.assignment_type_id=assignment_type.id) INNER JOIN sub_classes_has_assignments ON (sub_classes_has_assignments.assignments_id=assignments.id)
	INNER JOIN sub_classes
	ON (sub_classes.id=sub_classes_has_assignments.sub_classes_id) INNER JOIN subjects ON (assignments.subjects_id=subjects.id) 
	WHERE sub_classes_has_assignments.sub_classes_id=? AND students_student_no=?" );
		$getStudentAssignment->bindParam(1, $sub_class_id);
		$getStudentAssignment->bindParam(2, $id);
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



	public function getMessages(){
		$status = 0;
		$getMessages = $this->dbCon->Prepare("SELECT id, message, subject, date_sent, status FROM messages WHERE student_no=? AND status=0 ORDER BY date_sent DESC");
		$getMessages->bindParam(1,$_SESSION['user']['username']);
		//$getMessages->bindParam(2,$status);
		$getMessages->execute();
		
		if($getMessages->rowCount()>0){
			$rows = $getMessages->fetchAll();
			return $rows;
		}
	} //end of getting Messages


	public function getReadMessages(){
		$status = 1;
		$getReadMessages = $this->dbCon->Prepare("SELECT id, message, subject, date_sent, status FROM messages WHERE student_no=? AND status=1 ORDER BY date_sent DESC");
		$getReadMessages->bindParam(1,$_SESSION['user']['username']);
		//$getReadMessages->bindParam(2,$status);
		$getReadMessages->execute();
		
		if($getReadMessages->rowCount()>0){
			$rows = $getReadMessages->fetchAll();
			return $rows;
		}
	} //end of getting Messages



public function updateReadMessage($id){
		$status = 1;
		$updateReadMessage = $this->dbCon->PREPARE("UPDATE messages SET status=? WHERE student_no=? AND id=?");
		$updateReadMessage->bindParam(1,$status);
		$updateReadMessage->bindParam(2,$_SESSION['user']['username']);
		$updateReadMessage->bindParam(3,$id);
		$updateReadMessage->execute();
	}//End of updating Read Messages

	
	
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
	
	public function getSubjectsPerSubClass($sub_class){
		$getSubjectsPerSubClass = $this->dbCon->Prepare("SELECT count(subjects_id) as subjects_count FROM sub_classes_has_subjects WHERE sub_classes_id=?");
		$getSubjectsPerSubClass->bindParam(1,$sub_class);
		$getSubjectsPerSubClass->execute();
		
		if($getSubjectsPerSubClass->rowCount()>0){
			$rows = $getSubjectsPerSubClass->fetch();
			return $rows;
		}
	} //end of getting subjects per Sub class
	
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
		try{
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
				} catch (PDOException $e){
			$_SESSION['duplicate_subject'] = true;
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
		
		$getGuardians = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,primary_phone, CONCAT(firstname, ' ' ,lastname) as fullname, secondary_phone,address,email,occupation,employer FROM guardians");
		$getGuardians->execute();
		
		if($getGuardians->rowCount()>0){
			$row = $getGuardians->fetchAll();
			return $row;
		}
	} //end of getting guardians


	public function getSpecificGuardian($id){
		
		$getSpecificGuardian = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,primary_phone,secondary_phone,address,email,occupation,employer FROM guardians WHERE id=?");
		$getSpecificGuardian->bindParam(1,$id);
		$getSpecificGuardian->execute();
		
		if($getSpecificGuardian->rowCount()>0){
			$row = $getSpecificGuardian->fetch();
			return $row;
		}
	} //end of getting Specific guardians

	
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
						  
			//add the Guardian to users table for logins
			$role =50; //teacher role id
			$status = 1; //active status
			$username = $primaryPhone;
			$password = password_hash($primaryPhone, PASSWORD_DEFAULT)."\n"; 
			$addUser = new User();
			$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);

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
		$editGuardian->bindParam(10,$id);
		$editGuardian->execute();
		
		 $_SESSION['guardian-updated']=true;
		
	}


	public function changeGuardian($guardian_id, $student_no){
		$changeGuardian = $this->dbCon->Prepare("UPDATE students SET guardians_id=? WHERE student_no=? ");
		$changeGuardian->bindParam(1, $guardian_id);
		$changeGuardian->bindParam(2, $student_no);
		$changeGuardian->execute();

		$_SESSION['guardian_changed'] = true;

	}//End of changing a guardian


	public function getMessagesPerGuardian($id){
		$status = 0;
		$getMessagesPerGuardian = $this->dbCon->Prepare("SELECT id, message, subject, date_sent, status FROM messages WHERE student_no=? AND status=0 ORDER BY date_sent DESC");
		$getMessagesPerGuardian->bindParam(1,$id);
		//$getMessagesPerGuardian->bindParam(2,$status);
		$getMessagesPerGuardian->execute();
		
		if($getMessagesPerGuardian->rowCount()>0){
			$rows = $getMessagesPerGuardian->fetchAll();
			return $rows;
		}
	} //end of getting Messages by Guardian



	public function getReadMessagesPerGuardian($id){
		$status = 1;
		$getReadMessagesPerGuardian = $this->dbCon->Prepare("SELECT id, message, subject, date_sent, status FROM messages WHERE student_no=? AND status=0 ORDER BY date_sent DESC");
		$getReadMessagesPerGuardian->bindParam(1,$id);
		//$getMessagesPerGuardian->bindParam(2,$status);
		$getReadMessagesPerGuardian->execute();
		
		if($getReadMessagesPerGuardian->rowCount()>0){
			$rows = $getReadMessagesPerGuardian->fetchAll();
			return $rows;
		}
	} //end of getting Messages


public function getStudentDetailsPerGuardian($id){
		$getStudentDetailsPerGuardian = $this->dbCon->PREPARE("SELECT student_no, CONCAT(firstname, ' ' ,lastname) as name FROM students WHERE student_no=?");
		$getStudentDetailsPerGuardian->bindParam(1,$id);
		$getStudentDetailsPerGuardian->execute();
		
		if($getStudentDetailsPerGuardian->rowCount()>0){
			$rows = $getStudentDetailsPerGuardian->fetch();
			
			return $rows;
			
		}
		
	}


public function getStudentCountPerGuardian(){
		$getStudentCountPerGuardian = $this->dbCon->PREPARE("SELECT student_no, CONCAT(firstname, ' ' ,lastname) as name FROM students WHERE guardians_id=?");
		$getStudentCountPerGuardian->bindParam(1,$_SESSION['user']['username']);
		$getStudentCountPerGuardian->execute();
		
		if($getStudentCountPerGuardian->rowCount()>0){
			$rows = $getStudentCountPerGuardian->fetchAll();
			
			return $rows;
			
		}
		
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
	

	public function getSpecificClass($class_id){
		$getSpecificClass = $this->dbCon->Prepare("SELECT id,name FROM classes WHERE id=?	");
		$getSpecificClass->bindParam(1, $class_id);
		$getSpecificClass->execute();
		
		if($getSpecificClass->rowCount()>0){
			$row = $getSpecificClass->fetch();
			return $row;
		}
	} //end of getting Specific classes
	


	public function getOptionA($class_id){
		$options_name = 'Option A'; //OPTION A
		$getOptionA = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionA->bindParam(1, $class_id);
		$getOptionA->bindParam(2, $options_name);
		$getOptionA->execute();
		
		if($getOptionA->rowCount()>0){
			$rows = $getOptionA->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option A

	public function getOptionB($class_id){
		$options_name = 'Option B'; //OPTION A
		$getOptionB = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionB->bindParam(1, $class_id);
		$getOptionB->bindParam(2, $options_name);
		$getOptionB->execute();
		
		if($getOptionB->rowCount()>0){
			$rows = $getOptionB->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option B


	public function getOptionC($class_id){
		$options_name = 'Option C'; //OPTION C
		$getOptionC = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionC->bindParam(1, $class_id);
		$getOptionC->bindParam(2, $options_name);
		$getOptionC->execute();
		
		if($getOptionC->rowCount()>0){
			$rows = $getOptionC->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option C

	public function getOptionD($class_id){
		$options_name = 'Option D'; //OPTION C
		$getOptionD = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionD->bindParam(1, $class_id);
		$getOptionD->bindParam(2, $options_name);
		$getOptionD->execute();
		
		if($getOptionD->rowCount()>0){
			$rows = $getOptionD->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option D


	public function getOptionE($class_id){
		$options_name = 'Option E'; //OPTION C
		$getOptionE = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionE->bindParam(1, $class_id);
		$getOptionE->bindParam(2, $options_name);
		$getOptionE->execute();
		
		if($getOptionE->rowCount()>0){
			$rows = $getOptionE->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option E

	public function getOptionF($class_id){
		$options_name = 'Option F'; //OPTION C
		$getOptionF = $this->dbCon->Prepare("SELECT students_options.id as id, subjects_id, options_id, students_options.classes_id as classes_id, subjects.name as subject_name FROM students_options INNER JOIN subjects ON(students_options.subjects_id=subjects.id) INNER JOIN options ON(students_options.options_id=options.id) WHERE students_options.classes_id=? AND options.name=? ");
		$getOptionF->bindParam(1, $class_id);
		$getOptionF->bindParam(2, $options_name);
		$getOptionF->execute();
		
		if($getOptionF->rowCount()>0){
			$rows = $getOptionF->fetchAll();
			return $rows;
		}
	} //end of getting Subject Option F


	public function getSubClasses(){
		$getSubClasses = $this->dbCon->Prepare("SELECT id,name, classes_id FROM sub_classes LIMIT 18");
		$getSubClasses->execute();
		
		if($getSubClasses->rowCount()>0){
			$row = $getSubClasses->fetchAll();
			return $row;
		}
	} //end of getting classes


	public function getDemotionSubClasses(){
		$getDemotionSubClasses = $this->dbCon->Prepare("SELECT id,name, classes_id FROM sub_classes");
		$getDemotionSubClasses->execute();
		
		if($getDemotionSubClasses->rowCount()>0){
			$row = $getDemotionSubClasses->fetchAll();
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
		$getSpecificTeacher = $this->dbCon->Prepare("SELECT id, CONCAT(firstname, ' ',middlename,' ',lastname) as name, firstname, lastname, middlename,
		dob,phone,email,address,qualifications,no_of_years_experience,date_joined,marital_status,name_of_spouse,number_of_children
		FROM staff WHERE id=?");
		$getSpecificTeacher->bindParam(1,$teacher_id);
		$getSpecificTeacher->execute();
		
		if($getSpecificTeacher->rowCount()>0){
			$row = $getSpecificTeacher->fetch();
			return $row;
		}
	} //end of getting teacher details



	public function editTeacher($id,$firstname,$middlename,$lastname,$phone,$email,$address,$dob,$qualifications,$no_of_years_experience,$date_joined,$marital_status,$name_of_spouse,$number_of_children){
		$editTeacher =$this->dbCon->PREPARE("UPDATE staff SET firstname=?, middlename=?, lastname=?, phone=?, email=?, address=?, dob=?, qualifications=?, no_of_years_experience=?, date_joined=?, marital_status=?, name_of_spouse=?, number_of_children=? WHERE id=? ");
		$editTeacher->bindParam(1,$firstname);
		$editTeacher->bindParam(2,$middlename);
		$editTeacher->bindParam(3,$lastname);
		$editTeacher->bindParam(4,$phone);
		$editTeacher->bindParam(5,$email);
		$editTeacher->bindParam(6,$address);
		$editTeacher->bindParam(7,$dob);
		$editTeacher->bindParam(8,$qualifications);
		$editTeacher->bindParam(9,$no_of_years_experience);
		$editTeacher->bindParam(10,$date_joined);
		$editTeacher->bindParam(11,$marital_status);
		$editTeacher->bindParam(12,$name_of_spouse);
		$editTeacher->bindParam(13,$number_of_children);
		$editTeacher->bindParam(14,$id);
		$editTeacher->execute();
		
		 $_SESSION['teacher-updated']=true;
		
	}


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
	

	public function getSubClassName($sub_class_id){
		$getSubClassName = $this->dbCon->Prepare("SELECT name 
		FROM sub_classes WHERE id=? ");
		$getSubClassName->bindParam(1, $sub_class_id);
		$getSubClassName->execute();
		
		if($getSubClassName->rowCount()>0){
			$row = $getSubClassName->fetch();
			return $row;
		}
	} //End of getting Sub Class Name


	public function changeClass($sub_class, $student_no){
		$changeClass = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=? ");
		$changeClass->bindParam(1, $sub_class);
		$changeClass->bindParam(2, $student_no);
		$changeClass->execute();

		$_SESSION['class_changed'] = true;

	}//End of changing a student Class



	public function AddNotice($notice, $deadline){
				$AddNotice = $this->dbCon->prepare("INSERT INTO notices (notice,deadline)
				VALUES (:notice,:deadline)" );
				$AddNotice->execute(array(
						  ':notice'=>($notice),
						  ':deadline'=>($deadline) 
						  ));
						 		
	}



	public function sendMessage($subject, $message, $student_no){
				$sendMessage = $this->dbCon->prepare("INSERT INTO messages (subject, message, student_no)
				VALUES (:subject, :message, :student_no)" );
				$sendMessage->execute(array(
						  ':subject'=>($subject),
						  ':message'=>($message),
						  ':student_no'=>($student_no),
						  ));

				$_SESSION['message-sent']=true;
						 		
	}



	public function getClassesPerTeacher(){
		$getClassesPerTeacher = $this->dbCon->PREPARE("SELECT DISTINCT sub_classes_id as class_id, sub_classes.name as class_name FROM sub_classes_has_subjects
		INNER JOIN sub_classes ON (sub_classes.id=sub_classes_has_subjects.sub_classes_id) WHERE staff_id=?");
		$getClassesPerTeacher->bindParam(1,$_SESSION['user']['username']);
		$getClassesPerTeacher->execute();
		
		if($getClassesPerTeacher->rowCount()>0){
			$rows = $getClassesPerTeacher->fetchAll();
			
			return $rows;
			
		}
		
	}



	public function getSubjectsPerTeacher(){
		$getSubjectsPerTeacher = $this->dbCon->PREPARE("SELECT subjects.name as subject_name FROM subjects INNER JOIN sub_classes_has_subjects ON(sub_classes_has_subjects.subjects_id=subjects.id) WHERE sub_classes_has_subjects.staff_id=?");
		$getSubjectsPerTeacher->bindParam(1,$_SESSION['user']['username']);
		$getSubjectsPerTeacher->execute();
		
		if($getSubjectsPerTeacher->rowCount()>0){
			$rows = $getSubjectsPerTeacher->fetchAll();
			
			return $rows;
			
		}
		
	}



	public function getAllSubclassesOnFilter(){
		$getAllSubclassesOnFilter = $this->dbCon->PREPARE("SELECT id as sub_class_id, name FROM sub_classes");
		//$getAllSubclassesOnFilter->bindParam(1,$_SESSION['user']['username']);
		$getAllSubclassesOnFilter->execute();
		
		if($getAllSubclassesOnFilter->rowCount()>0){
			$rows = $getAllSubclassesOnFilter->fetchAll();
			
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
		$getAllclasses = $this->dbCon->PREPARE("SELECT id as class_id, name as class_name FROM classes LIMIT 7");
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


	public function getFinalAssignmentMark($academic_year,$term){
		$general=3;
		$exam_status_id = 2;
		
		$getFinalAssignmentMark = $this->dbCon->Prepare("SELECT  student_no, SUM(mark) as mark, 
		subject_name,academic_year,term, subjects_id
			FROM (SELECT exam_results.students_student_no as student_no, exam_results.marks as mark, 
					subjects.name as subject_name,exam_results.academic_year,exam_results.terms_id as term, classes_has_subjects_subjects_id as subjects_id
					FROM exam_results INNER JOIN subjects ON (subjects.id=exam_results.classes_has_subjects_subjects_id)
				WHERE exam_results.exam_status_id=? AND exam_results.students_student_no=? AND exam_results.academic_year=? AND exam_results.terms_id=?
					GROUP BY classes_has_subjects_subjects_id,exam_results.students_student_no
			UNION ALL
			SELECT students_student_no as student_no, SUM(marks) as mark, 
					subjects.name as subject_name,academic_year,terms_id as term, subjects_id
					FROM submissions  INNER JOIN assignments ON (assignments.id=submissions.assignments_id) INNER JOIN subjects ON(subjects.id=assignments.subjects_id)
					WHERE students_student_no=? AND academic_year=? AND terms_id=? AND assignment_type_id !=?
					GROUP BY subjects_id,students_student_no
			) results
			group by subjects_id,student_no");
		$getFinalAssignmentMark->bindParam(1,$exam_status_id);
		$getFinalAssignmentMark->bindParam(2,$_SESSION['user']['username']);
		$getFinalAssignmentMark->bindParam(3,$academic_year);
		$getFinalAssignmentMark->bindParam(4,$term);
		$getFinalAssignmentMark->bindParam(5,$_SESSION['user']['username']);
		$getFinalAssignmentMark->bindParam(6,$academic_year);
		$getFinalAssignmentMark->bindParam(7,$term);
		$getFinalAssignmentMark->bindParam(8,$general);
		$getFinalAssignmentMark->execute();
		
		if($getFinalAssignmentMark->rowCount()>0){
			$rows = $getFinalAssignmentMark->fetchAll();
			return $rows;
		}else{
			return null;
		}
	} //end of getting exam Results



	public function getFinalAssignmentMarkPerAdmin($academic_year,$term, $id){
		$general=3;
		$exam_status_id = 2;
		
		$getFinalAssignmentMarkPerAdmin = $this->dbCon->Prepare("SELECT  student_no, SUM(mark) as mark, 
		subject_name,academic_year,term, subjects_id
			FROM (SELECT exam_results.students_student_no as student_no, exam_results.marks as mark, 
					subjects.name as subject_name,exam_results.academic_year,exam_results.terms_id as term, classes_has_subjects_subjects_id as subjects_id
					FROM exam_results INNER JOIN subjects ON (subjects.id=exam_results.classes_has_subjects_subjects_id)
				WHERE exam_results.exam_status_id=? AND exam_results.students_student_no=? AND exam_results.academic_year=? AND exam_results.terms_id=?
					GROUP BY classes_has_subjects_subjects_id,exam_results.students_student_no
			UNION ALL
			SELECT students_student_no as student_no, SUM(marks) as mark, 
					subjects.name as subject_name,academic_year,terms_id as term, subjects_id
					FROM submissions  INNER JOIN assignments ON (assignments.id=submissions.assignments_id) INNER JOIN subjects ON(subjects.id=assignments.subjects_id)
					WHERE students_student_no=? AND academic_year=? AND terms_id=? AND assignment_type_id !=?
					GROUP BY subjects_id,students_student_no
			) results
			group by subjects_id,student_no");
		$getFinalAssignmentMarkPerAdmin->bindParam(1,$exam_status_id);
		$getFinalAssignmentMarkPerAdmin->bindParam(2,$id);
		$getFinalAssignmentMarkPerAdmin->bindParam(3,$academic_year);
		$getFinalAssignmentMarkPerAdmin->bindParam(4,$term);
		$getFinalAssignmentMarkPerAdmin->bindParam(5,$id);
		$getFinalAssignmentMarkPerAdmin->bindParam(6,$academic_year);
		$getFinalAssignmentMarkPerAdmin->bindParam(7,$term);
		$getFinalAssignmentMarkPerAdmin->bindParam(8,$general);
		$getFinalAssignmentMarkPerAdmin->execute();
		
		if($getFinalAssignmentMarkPerAdmin->rowCount()>0){
			$rows = $getFinalAssignmentMarkPerAdmin->fetchAll();
			return $rows;
		}else{
			return null;
		}
	} //end of getting exam Results
	
	public function getFinalPositions($academic_year,$term, $sub_class){
	//get subjects count per sub_class
	$getSubjectsPerSubClass = new Subjects();
	$rows = $getSubjectsPerSubClass->getSubjectsPerSubClass($sub_class);
	$subjects_count = $rows['subjects_count'];
	$exam_status_id =1; //Active
	$general_assignment=3;
		$getFinalPositions = $this->dbCon->Prepare("SELECT  student_no, student_name,class_name,ROUND(SUM(mark) /?,2)  as mark ,academic_year,term
			FROM (SELECT exam_results.students_student_no as student_no, sub_classes.name as class_name,SUM(exam_results.marks) as mark, 
					exam_results.academic_year,exam_results.terms_id as term,concat(firstname,' ',middlename,' ',lastname) as student_name
					FROM exam_results INNER JOIN subjects ON (subjects.id=exam_results.classes_has_subjects_subjects_id)
                    INNER JOIN students ON (students.student_no=exam_results.students_student_no)
					 INNER JOIN sub_classes ON(sub_classes.id=students.sub_classes_id)
				WHERE exam_results.exam_status_id=? AND students.sub_classes_id=? AND exam_results.academic_year=? AND exam_results.terms_id=?
					GROUP BY exam_results.students_student_no
			UNION ALL
			SELECT students_student_no as student_no, sub_classes.name as class_name,SUM(marks) as mark,academic_year,terms_id as term,concat(firstname,' ',middlename,' ',lastname) as student_name
					FROM submissions  INNER JOIN assignments ON (assignments.id=submissions.assignments_id) 
                    INNER JOIN subjects ON(subjects.id=assignments.subjects_id)
                    INNER JOIN students ON(students.student_no=submissions.students_student_no)
					INNER JOIN sub_classes ON(sub_classes.id=students.sub_classes_id)
					WHERE students.sub_classes_id=? AND academic_year=? AND terms_id=? AND assignment_type_id !=?
					GROUP BY students_student_no
			) results
			group by student_no ORDER BY mark DESC");
		$getFinalPositions->bindParam(1,$subjects_count);
		$getFinalPositions->bindParam(2,$exam_status_id);
		$getFinalPositions->bindParam(3,$sub_class);
		$getFinalPositions->bindParam(4,$academic_year);
		$getFinalPositions->bindParam(5,$term);
		$getFinalPositions->bindParam(6,$sub_class);
		$getFinalPositions->bindParam(7,$academic_year);
		$getFinalPositions->bindParam(8,$term);
		$getFinalPositions->bindParam(9,$general_assignment);
		$getFinalPositions->execute();
		
		if($getFinalPositions->rowCount()>0){
			$rows = $getFinalPositions->fetchAll();
			return $rows;
		}else{
			return null;
		}
	} //end of getting Positions


	public function getFinalAssignmentMarkPerGuardian($academic_year,$term, $student_no){
		$general=3;
		$exam_status_id = 2;
		
		$getFinalAssignmentMarkPerGuardian = $this->dbCon->Prepare("SELECT  student_no, SUM(mark) as mark, 
		subject_name,academic_year,term, subjects_id
			FROM (SELECT exam_results.students_student_no as student_no, exam_results.marks as mark, 
					subjects.name as subject_name,exam_results.academic_year,exam_results.terms_id as term, classes_has_subjects_subjects_id as subjects_id
					FROM exam_results INNER JOIN subjects ON (subjects.id=exam_results.classes_has_subjects_subjects_id)
				WHERE exam_results.exam_status_id=? AND exam_results.students_student_no=? AND exam_results.academic_year=? AND exam_results.terms_id=?
					GROUP BY classes_has_subjects_subjects_id,exam_results.students_student_no
			UNION ALL
			SELECT students_student_no as student_no, SUM(marks) as mark, 
					subjects.name as subject_name,academic_year,terms_id as term, subjects_id
					FROM submissions  INNER JOIN assignments ON (assignments.id=submissions.assignments_id) INNER JOIN subjects ON(subjects.id=assignments.subjects_id)
					WHERE students_student_no=? AND academic_year=? AND terms_id=? AND assignment_type_id !=?
					GROUP BY subjects_id,students_student_no
			) results
			group by subjects_id,student_no");
		$getFinalAssignmentMarkPerGuardian->bindParam(1,$exam_status_id);
		$getFinalAssignmentMarkPerGuardian->bindParam(2,$student_no);
		$getFinalAssignmentMarkPerGuardian->bindParam(3,$academic_year);
		$getFinalAssignmentMarkPerGuardian->bindParam(4,$term);
		$getFinalAssignmentMarkPerGuardian->bindParam(5,$student_no);
		$getFinalAssignmentMarkPerGuardian->bindParam(6,$academic_year);
		$getFinalAssignmentMarkPerGuardian->bindParam(7,$term);
		$getFinalAssignmentMarkPerGuardian->bindParam(8,$general);
		$getFinalAssignmentMarkPerGuardian->execute();
		
		if($getFinalAssignmentMarkPerGuardian->rowCount()>0){
			$rows = $getFinalAssignmentMarkPerGuardian->fetchAll();
			return $rows;
		}else{
			return null;
		}
	} //end of getting Assignments Results per guardian


	public function getStudentsPerSubclass($sub_class_id, $subject_id, $term){		
		$getStudentsPerSubclass = $this->dbCon->Prepare("SELECT student_no, exam_results.academic_year as academic_year, terms.name as term_name, firstname, lastname FROM students LEFT OUTER JOIN exam_results ON(exam_results.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) LEFT OUTER JOIN terms ON (exam_results.terms_id=terms.id) WHERE sub_classes.id=? ORDER BY student_no ASC");
		//$getStudentsPerSubclass->bindParam(1,$_SESSION['user']['username']);
		//$getStudentsPerSubclass->bindParam(2,$term);
		$getStudentsPerSubclass->bindParam(1,$sub_class_id);
		$getStudentsPerSubclass->execute();
		
		if($getStudentsPerSubclass->rowCount()>0){
			$rows = $getStudentsPerSubclass->fetchAll();
			return $rows;
		}
	} //end of getting Assignments Results



	public function getTrialMark($subject_id, $term){		
		$getTrialMark = $this->dbCon->Prepare("SELECT SUM(submissions.marks) as final_mark, exam_results.marks as exam_mark, exam_results.academic_year as academic_year, terms.name as term_name, subjects.name as subject_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) LEFT OUTER JOIN exam_results ON(exam_results.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON(assignments.subjects_id=subjects.id) INNER JOIN terms ON (exam_results.terms_id=terms.id) WHERE submissions.students_student_no=? AND exam_results.students_student_no=? ");
		$getTrialMark->bindParam(1,$_SESSION['user']['username']);
		$getTrialMark->bindParam(2,$_SESSION['user']['username']);
		$getTrialMark->execute();
		
		if($getTrialMark->rowCount()>0){
			$row = $getTrialMark->fetchAll();
			return $row;
		}
	} //end of getting Assignments Results


	public function getTrialMarkPerGuardian($subject_id, $term, $student_no){		
		$getTrialMarkPerGuardian = $this->dbCon->Prepare("SELECT SUM(submissions.marks) as final_mark, exam_results.marks as exam_mark, exam_results.academic_year as academic_year, terms.name as term_name, subjects.name as subject_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) LEFT OUTER JOIN exam_results ON(exam_results.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON(assignments.subjects_id=subjects.id) INNER JOIN terms ON (exam_results.terms_id=terms.id) WHERE submissions.students_student_no=? AND exam_results.students_student_no=? ");
		$getTrialMarkPerGuardian->bindParam(1,$student_no);
		$getTrialMarkPerGuardian->bindParam(2,$student_no);
		$getTrialMarkPerGuardian->execute();
		
		if($getTrialMarkPerGuardian->rowCount()>0){
			$row = $getTrialMarkPerGuardian->fetchAll();
			return $row;
		}
	} //end of getting Assignments Results


	public function getStudentsMarkPerSUbject($subject_id, $term, $student_id){		
		$getStudentsMarkPerSUbject = $this->dbCon->Prepare("SELECT SUM(submissions.marks) as final_mark, exam_results.marks as exam_mark, exam_results.academic_year as academic_year, terms.name as term_name, submissions.students_student_no as stu_no, subjects.name as subject_name FROM submissions INNER JOIN students ON(submissions.students_student_no=students.student_no) INNER JOIN exam_results ON(exam_results.students_student_no=students.student_no) INNER JOIN assignments ON(submissions.assignments_id=assignments.id) INNER JOIN subjects ON(assignments.subjects_id=subjects.id) INNER JOIN terms ON (exam_results.terms_id=terms.id) WHERE submissions.students_student_no=? AND exam_results.students_student_no=? ");
		$getStudentsMarkPerSUbject->bindParam(1,$student_id);
		$getStudentsMarkPerSUbject->bindParam(2,$student_id);
		$getStudentsMarkPerSUbject->execute();
		
		if($getStudentsMarkPerSUbject->rowCount()>0){
			$row = $getStudentsMarkPerSUbject->fetchAll();
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



	public function deleteNotice($id){

		$deleteNotice =$this->dbCon->PREPARE("DELETE FROM notices WHERE id='$id'");
		$deleteNotice->bindParam(1,$id);
		$deleteNotice->execute();
		
	}//End od deleting Notices



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


	public function getNotices(){
		$status = 0;
		$getNotices = $this->dbCon->Prepare("SELECT id, notice, deadline FROM notices WHERE status=? ORDER BY date_added DESC");
		$getNotices->bindParam(1,$status);
		$getNotices->execute();
		
		if($getNotices->rowCount()>0){
			$rows = $getNotices->fetchAll();
			return $rows;
		}
	} //end of getting Notices



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


	public function adminApproveResults($subject_id, $sub_class_id){
		$exam_status_id = 2;
		$adminApproveResults =$this->dbCon->PREPARE("UPDATE exam_results SET exam_status_id =? WHERE classes_has_subjects_subjects_id=? AND classes_has_subjects_classes_id=? ");
		$adminApproveResults->bindParam(1,$exam_status_id);
		$adminApproveResults->bindParam(2,$subject_id);
		$adminApproveResults->bindParam(3,$sub_class_id);
		$adminApproveResults->execute();
		
		 $_SESSION['approved']=true;
		
	}//End of admin approving results per subject



public function getAllStudentsPerClassSubject($sub_class_id, $subjects_id){
		$getAllStudentsPerClassSubject = $this->dbCon->PREPARE(" SELECT  student_no,firstname,middlename,lastname,subjects.name as subject, subjects.id as subject_id, classes.id as classes_id
		FROM students INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN classes ON (classes.id=sub_classes.classes_id)
		INNER JOIN sub_classes_has_subjects ON (sub_classes_has_subjects.sub_classes_id=sub_classes.id) 
		INNER JOIN subjects ON (subjects.id=sub_classes_has_subjects.subjects_id)
		WHERE students.sub_classes_id =? AND sub_classes_has_subjects.subjects_id=?");
		$getAllStudentsPerClassSubject->bindParam(1,$sub_class_id);
		$getAllStudentsPerClassSubject->bindParam(2,$subjects_id);
		$getAllStudentsPerClassSubject->execute();
		
		if($getAllStudentsPerClassSubject->rowCount()>0){
			$rows = $getAllStudentsPerClassSubject->fetchAll();			
			return $rows;
			
		}
		
	}


	public function checkAllStudentsPerClassSubject($sub_class_id, $subjects_id){
		$checkAllStudentsPerClassSubject = $this->dbCon->PREPARE(" SELECT  COUNT(student_no) as student_no,firstname,middlename,lastname,subjects.name as subject, subjects.id as subject_id, classes.id as classes_id
		FROM students INNER JOIN sub_classes ON (sub_classes.id=students.sub_classes_id) INNER JOIN classes ON (classes.id=sub_classes.classes_id)
		INNER JOIN sub_classes_has_subjects ON (sub_classes_has_subjects.sub_classes_id=sub_classes.id) 
		INNER JOIN subjects ON (subjects.id=sub_classes_has_subjects.subjects_id)
		WHERE students.sub_classes_id =? AND sub_classes_has_subjects.subjects_id=?");
		$checkAllStudentsPerClassSubject->bindParam(1,$sub_class_id);
		$checkAllStudentsPerClassSubject->bindParam(2,$subjects_id);
		$checkAllStudentsPerClassSubject->execute();
		
		if($checkAllStudentsPerClassSubject->rowCount()>0){
			$row = $checkAllStudentsPerClassSubject->fetch();			
			return $row;
			
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
		$getStudentsPerExamType = $this->dbCon->PREPARE("SELECT students_student_no as student_no, students.firstname as firstname, students.lastname as lastname, marks, academic_year, exam_status_id
		FROM exam_results INNER JOIN students ON(exam_results.students_student_no=students.student_no)
		INNER JOIN exam_type ON(exam_results.exam_type_id=exam_type.id) WHERE students.sub_classes_id=? AND exam_type.id=? AND academic_year=? AND classes_has_subjects_subjects_id=?");
		$getStudentsPerExamType->bindParam(1,$sub_class_id);
		$getStudentsPerExamType->bindParam(2,$exam_type_id);
		$getStudentsPerExamType->bindParam(3,$academic_year);
		$getStudentsPerExamType->bindParam(4,$subject_id);
		$getStudentsPerExamType->execute();
		
		if($getStudentsPerExamType->rowCount()>0){
			$rows = $getStudentsPerExamType->fetchAll();
			
			return $rows;
			
		}
		
	}


	public function checkStudentsPerExamType($sub_class_id, $subject_id, $exam_type_id, $academic_year){
		$checkStudentsPerExamType = $this->dbCon->PREPARE("SELECT students_student_no as student_no, students.firstname as firstname, students.lastname as lastname, marks, academic_year, exam_status_id
		FROM exam_results INNER JOIN students ON(exam_results.students_student_no=students.student_no)
		INNER JOIN exam_type ON(exam_results.exam_type_id=exam_type.id) WHERE students.sub_classes_id=? AND exam_type.id=? AND academic_year=? AND classes_has_subjects_subjects_id=? LIMIT 1");
		$checkStudentsPerExamType->bindParam(1,$sub_class_id);
		$checkStudentsPerExamType->bindParam(2,$exam_type_id);
		$checkStudentsPerExamType->bindParam(3,$academic_year);
		$checkStudentsPerExamType->bindParam(4,$subject_id);
		$checkStudentsPerExamType->execute();
		
		if($checkStudentsPerExamType->rowCount()>0){
			$row = $checkStudentsPerExamType->fetch();
			
			return $row;
			
		}
		
	}// Checking if a class is already approved



public function promoteStudents(){
					
		//start transaction.
		//Form 7 to Graduation
		$this->dbCon->beginTransaction();
		try{
		$F7 ='18';
		$graduated ='19';
		//Graduation
		//remember to add graduation datetime
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$graduated);
		$promoteStudent->bindParam(2,$F7);
        $promoteStudent->execute();


        //Form 6 to Form 7
       	$F7 ='18';
		$F6 ='17';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F6);
		$promoteStudent->bindParam(2,$F7);
        $promoteStudent->execute();


        //Form 5 South to Form 6
       	$F5S ='16';
		$F6 ='17';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F6);
		$promoteStudent->bindParam(2,$F5S);
        $promoteStudent->execute();


        //Form 5 North to Form 6
       	$F5N ='15';
		$F6 ='17';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F6);
		$promoteStudent->bindParam(2,$F5N);
        $promoteStudent->execute();


        //Form 4 South to Form 4 South
       	$F4S ='14';
		$F5S ='16';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F5S);
		$promoteStudent->bindParam(2,$F4S);
        $promoteStudent->execute();


        //Form 4 North to Form 5 North
       	$F4N ='13';
		$F5N ='15';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F5N);
		$promoteStudent->bindParam(2,$F4N);
        $promoteStudent->execute();


        //Form 3 South to Form 4 South
       	$F3S ='12';
		$F4S ='14';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F4S);
		$promoteStudent->bindParam(2,$F3S);
        $promoteStudent->execute();


        //Form 3 North to Form 4 North
       	$F3N ='11';
		$F4N ='13';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F4N);
		$promoteStudent->bindParam(2,$F3N);
        $promoteStudent->execute();


        //Form 3 West to Form 4 North
       	$F3W ='9';
		$F4N ='13';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F4N);
		$promoteStudent->bindParam(2,$F3W);
        $promoteStudent->execute();


        //Form 3 East to Form 4 South
       	$F3E ='10';
		$F4S ='14';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F4S);
		$promoteStudent->bindParam(2,$F3E);
        $promoteStudent->execute();



        //Form 2 South to Form 3 South
       	$F2S ='8';
		$F3S ='12';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F3S);
		$promoteStudent->bindParam(2,$F2S);
        $promoteStudent->execute();


        //Form 2 North to Form 3 North
       	$F2N ='7';
		$F3N ='11';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F3N);
		$promoteStudent->bindParam(2,$F2N);
        $promoteStudent->execute();


        //Form 2 West to Form 3 West
       	$F2W ='5';
		$F3W ='9';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F3W);
		$promoteStudent->bindParam(2,$F2W);
        $promoteStudent->execute();


        //Form 2 East to Form 3 East
       	$F2E ='6';
		$F3E ='10';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F3E);
		$promoteStudent->bindParam(2,$F2E);
        $promoteStudent->execute();

		
        //Form 1 South to Form 2 South
       	$F1S ='4';
		$F2S ='8';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F2S);
		$promoteStudent->bindParam(2,$F1S);
        $promoteStudent->execute();


        //Form 1 North to Form 2 North
       	$F1N ='3';
		$F2N ='7';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F2N);
		$promoteStudent->bindParam(2,$F1N);
        $promoteStudent->execute();


        //Form 1 West to Form 2 West
       	$F1W ='1';
		$F2W ='5';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F2W);
		$promoteStudent->bindParam(2,$F1W);
        $promoteStudent->execute();


        //Form 1 East to Form 2 East
       	$F1E ='2';
		$F2E ='6';
		$promoteStudent =$this->dbCon->prepare("UPDATE students set sub_classes_id=? where sub_classes_id=?");
		$promoteStudent->bindParam(1,$F2E);
		$promoteStudent->bindParam(2,$F1E);
        $promoteStudent->execute();
		
		//Commit the changes to the database
		$this->dbCon->commit();

		$_SESSION['students_promoted']= true;
		}catch(Exception $e){
				
				echo $e->getMessage();
				//Rollback the transaction.
				$this->dbCon->rollBack();
			}

	
	}// end of promoting students



	public function demoteStudents($students){
		if(!empty($students)){
			$F1W = 1;
			$F1E = 2;
			$F1N = 3;
			$F1S = 4;
			$F2W = 5;
			$F2E = 6;
			$F2N = 7;
			$F2S = 8;
			$F3W = 9;
			$F3E = 10;
			$F3N = 11;
			$F3S = 12;
			$F4N = 13;
			$F4S = 14;
			$F5N = 15;
			$F5S = 16;
			$F6 = 17;
			$F7 = 18;
			$Graduation = 19;

			foreach($students as $student){

			switch ($_SESSION['sub_class']) {

				case '1': //Form 1 West
					$_SESSION['failed'] = true;
					break;

				case '2': //Form 1 East
					$_SESSION['failed'] = true;
					break;

				case '3': //Form 1 North
					$_SESSION['failed'] = true;
					break;

				case '4': //Form 1 South
					$_SESSION['failed'] = true;
					break;

				case '5': //Form 2 West
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F1W);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F1W) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '6': //Form 2 East
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F1E);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			      	$demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F1E) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '7': //Form 2 North
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F1N);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F1N) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '8': //Form 2 South
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F1S);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F1S) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '9': //Form 3 West
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F2W);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F2W) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '10': //Form 3 East
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F2E);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F2E) 
						));
			        $_SESSION['students_demoted']=true;
					break;

				case '11': //Form 3 North
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F2N);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F2N) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '12': //Form 3 South
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F2S);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F2S) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '13': //Form 4 North
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F3W);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F3W) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '14': //Form 4 South
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F3S);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F5S) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '15': //Form 5 North
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F4N);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F4N) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '16': //Form 5 South
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F4S);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F4S) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '17': //Form 6
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F5S);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F5S) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '18': //Form 7
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F6);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F6) 
						));
					$_SESSION['students_demoted']=true;
					break;

				case '19': //Graduation
					$demoteStudents = $this->dbCon->Prepare("UPDATE students SET sub_classes_id=? WHERE student_no=?	");
					$demoteStudents->bindParam(1,$F7);
					$demoteStudents->bindParam(2,$student);
			        $demoteStudents->execute();

			        $demotionRecord = $this->dbCon->prepare("INSERT INTO demotions 
					(year, students_student_no, class_from, class_to)
					VALUES (:year, :students_student_no, :class_from, :class_to)" );
					$demotionRecord->execute(array(
						':year'=>($_SESSION['academic_year']),
						':students_student_no'=>($student),
						':class_from'=>($_SESSION['sub_class']),
						':class_to'=>($F7) 
						));
					$_SESSION['students_demoted']=true;
					break;

				default:
					$_SESSION['failed'] = true;
					break;
			}

			}
			
			
		}
		
	} //end of demoting student(s)



public function getSubjectById($subject_id){
		$getSubjectById = $this->dbCon->PREPARE("SELECT name as subject_name FROM subjects WHERE id=?");
		$getSubjectById->bindParam(1,$subject_id);
		$getSubjectById->execute();
		
		if($getSubjectById->rowCount()>0){
			$row = $getSubjectById->fetch();
			
			return $row;
			
		}
		
	}



public function getStudentID($student_id){
		$getStudentID = $this->dbCon->PREPARE("SELECT student_no, firstname, lastname FROM students WHERE student_no=?");
		$getStudentID->bindParam(1,$student_id);
		$getStudentID->execute();
		
		if($getStudentID->rowCount()>0){
			$row = $getStudentID->fetchAll();
			
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
		
	}//getting class name


public function getClassAndSubjectName($sub_class_id, $subject_id){
		$getClassAndSubjectName = $this->dbCon->PREPARE("SELECT sub_classes.name as sub_class_name, subjects.name as subject_name FROM sub_classes INNER JOIN sub_classes_has_subjects ON(sub_classes_has_subjects.sub_classes_id=sub_classes.id) INNER JOIN subjects ON (sub_classes_has_subjects.subjects_id=subjects.id) WHERE sub_classes.id=? AND subjects.id=?");
		$getClassAndSubjectName->bindParam(1,$sub_class_id);
		$getClassAndSubjectName->bindParam(2,$subject_id);
		$getClassAndSubjectName->execute();
		
		if($getClassAndSubjectName->rowCount()>0){
			$row = $getClassAndSubjectName->fetch();
			
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



	public function recordStudentsExams($marks, $academic_year, $term, $students_student_no, $exam_type_id, $class_id, $subject_id){
		
			$exam_status_id = 1;
			//echo getType($exam_status_id); die();
		$checkExamResult = $this->dbCon->PREPARE("SELECT classes_has_subjects_classes_id, classes_has_subjects_subjects_id,academic_year,terms_id,students_student_no
		FROM exam_results 
		WHERE classes_has_subjects_classes_id=? AND classes_has_subjects_subjects_id=? AND academic_year=? AND terms_id=?" );
		$checkExamResult->bindValue(1, $class_id);
		$checkExamResult->bindValue(2, $subject_id);
		$checkExamResult->bindValue(3, $academic_year);
		$checkExamResult->bindValue(4, $term);		
		$checkExamResult->execute();
		if($checkExamResult->rowCount()>0){

			echo "<script>alert('You have already Recorded Exams for this Class and subject');</script>";
		}else{
			
			if(count($marks)>0){
				foreach(array_combine($students_student_no, $marks) as $student => $mark){
					
					$recordStudentsExams = $this->dbCon->prepare("INSERT INTO exam_results 
					(marks,academic_year,terms_id,students_student_no,exam_type_id,staff_id,classes_has_subjects_classes_id, classes_has_subjects_subjects_id)
				VALUES (:marks,:academic_year,:terms_id,:students_student_no,:exam_type_id,:staff_id,:classes_has_subjects_classes_id, :classes_has_subjects_subjects_id)" );
				$recordStudentsExams->execute(array(
						  ':marks'=>((float)$mark),
						  ':academic_year'=>($academic_year),
						  ':terms_id'=>($term),
						  ':students_student_no'=>($student),
						  ':exam_type_id'=>($exam_type_id),
						  ':staff_id'=>($_SESSION['user']['username']),
						  ':classes_has_subjects_classes_id'=>($class_id),
						  ':classes_has_subjects_subjects_id'=>($subject_id)  
						  ));
					
				}
				
			}
				
		$_SESSION['marks-added']=true;		  		
	}

	}




	public function updateStudentExamMark($marks, $student_no){
		$updateStudentExamMark =$this->dbCon->PREPARE("UPDATE exam_results SET marks =? WHERE students_student_no=? ");
		$updateStudentExamMark->bindParam(1,$marks);
		$updateStudentExamMark->bindParam(2,$student_no);
		$updateStudentExamMark->execute();
				
	}


	public function addBook($id, $title,$author,$year_of_publication, $count){

		$checkBookAvailability = $this->dbCon->PREPARE("SELECT id FROM books WHERE id=?" );
		$checkBookAvailability->bindValue(1, $id);
		$checkBookAvailability->execute();

		if ($checkBookAvailability->rowCount()>0) {
			
			echo "<script>alert('This Book already Exists');</script>";
		} else {
			
			$addBook = $this->dbCon->prepare("INSERT INTO books (id, title, author, year_of_publication, count)
				VALUES (:id, :title, :author, :year_of_publication, :count)" );
				$addBook->execute(array(
					      ':id'=>($id),
						  ':title'=>($title),
						  ':author'=>($author),
						  ':year_of_publication'=>($year_of_publication),
						  ':count'=>($count)
						  ));
				$_SESSION['book-added']=true;
		}

						 		
	}//End of Adding a BOOK


	public function getAllBooks(){
		$getAllBooks = $this->dbCon->Prepare("SELECT books.id as book_id, count, title, author, year_of_publication, book_status_id, book_status.name as status_name FROM books INNER JOIN book_status ON(books.book_status_id=book_status.id) ");
		//$getNotices->bindParam(1,$id);
		$getAllBooks->execute();
		
		if($getAllBooks->rowCount()>0){
			$rows = $getAllBooks->fetchAll();
			return $rows;
		}
	} //end of getting Notices


	public function getBorrowedBooks(){
		$getBorrowedBooks = $this->dbCon->Prepare("SELECT borrowed_books.id as id, books.id as book_id, books.title as title, due_date, students_student_no, date_borrowed, CONCAT(firstname, ' ', lastname) as student_name, sub_classes.name as sub_class_name FROM borrowed_books INNER JOIN books ON(borrowed_books.books_id=books.id) INNER JOIN students ON(borrowed_books.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE status=1 ");
		//$getBorrowedBooks->bindParam(1 ,1);
		$getBorrowedBooks->execute();
		
		if($getBorrowedBooks->rowCount()>0){
			$rows = $getBorrowedBooks->fetchAll();
			return $rows;
		}
	} //end of getting Borrowed Books




	public function getBorrowedBookPerStudent(){
		$getBorrowedBookPerStudent = $this->dbCon->Prepare("SELECT books.id as book_id, books.title as title, due_date, students_student_no, date_borrowed, CONCAT(firstname, ' ', lastname) as student_name, books.author as author, sub_classes.name as sub_class_name FROM borrowed_books INNER JOIN books ON(borrowed_books.books_id=books.id) INNER JOIN students ON(borrowed_books.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE students_student_no=? AND status=1");
		$getBorrowedBookPerStudent->bindParam(1 ,$_SESSION['user']['username']);
		$getBorrowedBookPerStudent->execute();
		
		if($getBorrowedBookPerStudent->rowCount()>0){
			$rows = $getBorrowedBookPerStudent->fetchAll();
			return $rows;
		}
	} //end of getting Borrowed Books


public function getBookCount($book_id){

		$getBookCount = $this->dbCon->Prepare("SELECT count FROM books WHERE id =?");
		$getBookCount->bindParam(1,$book_id);
		$getBookCount->execute();
		
		if($getBookCount->rowCount()>0){
			$row = $getBookCount->fetch();
			return $row;
		}
}




	public function getAllStudentsPerSubclass($level){		
		$getAllStudentsPerSubclass = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, sub_classes.name as sub_class_name FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE sub_classes_id=? ORDER BY student_no ASC");
		$getAllStudentsPerSubclass->bindParam(1,$level);
		$getAllStudentsPerSubclass->execute();
		
		if($getAllStudentsPerSubclass->rowCount()>0){
			$rows = $getAllStudentsPerSubclass->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Class


	public function getAllStudentsPerSubclassPerExams($level){		
		$getAllStudentsPerSubclassPerExams = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, sub_classes.name as sub_class_name FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE sub_classes_id=? ORDER BY student_no ASC");
		$getAllStudentsPerSubclassPerExams->bindParam(1,$level);
		$getAllStudentsPerSubclassPerExams->execute();
		
		if($getAllStudentsPerSubclassPerExams->rowCount()>0){
			$rows = $getAllStudentsPerSubclassPerExams->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Class


	public function getAllStudentsPerClassPerPayment(){		
		$getAllStudentsPerClassPerPayment = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, sub_classes.name as sub_class_name FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) ORDER BY student_no ASC");
		//$getAllStudentsPerClassPerPayment->bindParam(1,$level);
		$getAllStudentsPerClassPerPayment->execute();
		
		if($getAllStudentsPerClassPerPayment->rowCount()>0){
			$rows = $getAllStudentsPerClassPerPayment->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Class and Payment


		public function getStudentsPerSubClassName($sub_class_id){		
		$getStudentsPerSubClassName = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, middlename, sub_classes.name as sub_class_name, student_status.name as status_name FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id)  INNER JOIN student_status ON(students.student_status_id=student_status.id) WHERE sub_classes_id=? ORDER BY student_no ASC");
		$getStudentsPerSubClassName->bindParam(1,$sub_class_id);
		$getStudentsPerSubClassName->execute();
		
		if($getStudentsPerSubClassName->rowCount()>0){
			$rows = $getStudentsPerSubClassName->fetchAll();
			return $rows;
		}
	} //end of getting Students Per SUb Class


	// 	public function getStudentsWithFeesBalances($fees, $academic_year, $term){	
	// 	$payment_type_id = 1;	
	// 	$getStudentsWithFeesBalances = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, sub_class_name, SUM(amount) as amount
	// 		FROM (SELECT payments.students_student_no as student_no, SUM(amount) as amount, students.firstname as firstname, guardians.firstname as gfirstname, students.lastname as lastname, sub_classes.name as sub_class_name FROM payments INNER JOIN students ON (payments.students_student_no=students.student_no) INNER JOIN sub_classes ON (students.sub_classes_id=sub_classes.id) INNER JOIN guardians ON(students.guardians_id=guardians.id)
	// 			WHERE payment_type_id=? AND academic_year=? AND term=? 
	// 				GROUP BY payments.students_student_no HAVING (SUM(amount) <=?)
	// 		UNION ALL
	// 		SELECT student_no as student_no, firstname, lastname, sub_classes.name as sub_class_name, null
	// 		FROM students  INNER JOIN sub_classes ON (students.sub_classes_id=sub_classes.id) 
					
	// 				GROUP BY student_no
	// 		) results
	// 		group by student_no");
	// 	$getStudentsWithFeesBalances->bindParam(1,$payment_type_id);
	// 	$getStudentsWithFeesBalances->bindParam(2,$academic_year);
	// 	$getStudentsWithFeesBalances->bindParam(3,$term);
	// 	$getStudentsWithFeesBalances->bindParam(4,$fees);
	// 	$getStudentsWithFeesBalances->execute();
		
	// 	if($getStudentsWithFeesBalances->rowCount()>0){
	// 		$rows = $getStudentsWithFeesBalances->fetchAll();
	// 		return $rows;
	// 	}
	// } //end of getting Students Per Class and Payment



	public function getStudentsWithFeesBalances($fees, $academic_year, $term){	
		$payment_type_id = 1;	
		$getStudentsWithFeesBalances = $this->dbCon->Prepare("SELECT student_no, students.firstname as firstname, students.lastname as lastname, students.email as email, sub_classes.name as sub_class_name, SUM(amount) as amount, CONCAT(guardians.firstname,' ', guardians.lastname) as guardian_name, guardians.primary_phone as phone FROM payments INNER JOIN students ON (payments.students_student_no=students.student_no) INNER JOIN sub_classes ON (students.sub_classes_id=sub_classes.id) INNER JOIN guardians ON(students.guardians_id=guardians.id)  WHERE payment_type_id=? AND payments.academic_year=? AND payments.term=? GROUP BY student_no");
		$getStudentsWithFeesBalances->bindParam(1,$payment_type_id);
		$getStudentsWithFeesBalances->bindParam(2,$academic_year);
		$getStudentsWithFeesBalances->bindParam(3,$term);
		//$getStudentsWithFeesBalances->bindParam(4,$fees);
		$getStudentsWithFeesBalances->execute();
		
		if($getStudentsWithFeesBalances->rowCount()>0){
			$rows = $getStudentsWithFeesBalances->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Class and Payment


	public function getnonPaidStudents($fees, $academic_year, $term){	
		$getnonPaidStudents = $this->dbCon->Prepare("SELECT student_no, students.firstname as firstname, students.lastname as lastname, students.email as email, sub_classes.name as sub_class_name, CONCAT(guardians.firstname,' ', guardians.lastname) as guardian_name, guardians.primary_phone as phone FROM students INNER JOIN sub_classes ON (students.sub_classes_id=sub_classes.id) INNER JOIN guardians ON(students.guardians_id=guardians.id) WHERE student_no NOT IN (SELECT students_student_no FROM payments) ");
		//$getnonPaidStudents->bindParam(1,$payment_type_id);
		//$getnonPaidStudents->bindParam(2,$academic_year);
		//$getnonPaidStudents->bindParam(3,$term);
		//$getStudentsWithFeesBalances->bindParam(4,$fees);
		$getnonPaidStudents->execute();
		
		if($getnonPaidStudents->rowCount()>0){
			$rows = $getnonPaidStudents->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Class and Payment


	public function getFeesBalanceCount($fees, $academic_year, $term){	
		$payment_type_id = 1;	
		$getFeesBalanceCount = $this->dbCon->Prepare("SELECT DISTINCT count(student_no) as fees_count, student_no, firstname, lastname, SUM(payments.amount) as amount, sub_classes.name as sub_class_name, payments.academic_year as academic_year, payments.term as term FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) INNER JOIN payments ON(payments.students_student_no=students.student_no) WHERE payment_type_id=? AND academic_year=? AND term=? AND payments.amount <? ORDER BY student_no ASC");
		$getFeesBalanceCount->bindParam(1,$payment_type_id);
		$getFeesBalanceCount->bindParam(2,$academic_year);
		$getFeesBalanceCount->bindParam(3,$term);
		$getFeesBalanceCount->bindParam(4,$fees);
		$getFeesBalanceCount->execute();
		
		if($getFeesBalanceCount->rowCount()>0){
			$rows = $getFeesBalanceCount->fetch();
			return $rows;
		}
	} //end of getting Students Per Class and Payment


	public function getSpecificFeesPerStudent($student_no){	
		$payment_type_id = 1;	
		$getSpecificFeesPerStudent = $this->dbCon->Prepare("SELECT students_student_no, amount, ref_num, term, students.firstname as firstname, students.lastname as lastname, sub_classes.name as sub_class_name, date_paid, academic_year FROM payments INNER JOIN students ON(payments.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE students_student_no=? AND payment_type_id=? ORDER BY student_no ASC");
		$getSpecificFeesPerStudent->bindParam(1,$student_no);
		$getSpecificFeesPerStudent->bindParam(2,$payment_type_id);
		$getSpecificFeesPerStudent->execute();
		
		if($getSpecificFeesPerStudent->rowCount()>0){
			$rows = $getSpecificFeesPerStudent->fetchAll();
			return $rows;
		}
	} //end of getting Payments per Specific Student


	public function getAllStudentsPerGuardian(){		
		$getAllStudentsPerGuardian = $this->dbCon->Prepare("SELECT student_no, firstname, lastname, sub_classes.name as sub_class_name FROM students INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE guardians_id=? ORDER BY student_no ASC");
		$getAllStudentsPerGuardian->bindParam(1,$_SESSION['user']['username']);
		$getAllStudentsPerGuardian->execute();
		
		if($getAllStudentsPerGuardian->rowCount()>0){
			$rows = $getAllStudentsPerGuardian->fetchAll();
			return $rows;
		}
	} //end of getting Assignments Results



	public function lendBook($book_id, $student_no, $current_count){
		$date = DATE("Y-m-d h:i");

		$dt2 = new DateTime("+1 month"); // Add +1 day to increment the date by 1 day from today
		$due_date = $dt2->format("Y-m-d");

		$checkIfAlreadyBorrowed = $this->dbCon->PREPARE("SELECT books_id FROM borrowed_books WHERE books_id=? AND students_student_no=? AND status=?");
		$checkIfAlreadyBorrowed->bindValue(1, $book_id);
		$checkIfAlreadyBorrowed->bindValue(2, $student_no);
		$checkIfAlreadyBorrowed->bindValue(3, 1);
		$checkIfAlreadyBorrowed->execute();

		if ($checkIfAlreadyBorrowed->rowCount()>0) {
			
			echo ("<script LANGUAGE='JavaScript'>window.alert('This Student already has the Book');
    window.location.href='view-books.php';
    </script>");

		} else {

		$new_count = $current_count - 1;

		$updateBookCount =$this->dbCon->PREPARE("UPDATE books SET count =? WHERE id=? ");
		$updateBookCount->bindParam(1,$new_count);
		$updateBookCount->bindParam(2,$book_id);
		$updateBookCount->execute();
			
			$lendBook = $this->dbCon->prepare("INSERT INTO borrowed_books (date_borrowed, books_id, students_student_no, due_date)
				VALUES (:date_borrowed, :books_id, :students_student_no, :due_date)" );
				$lendBook->execute(array(
						  ':date_borrowed'=>($date),
					      ':books_id'=>($book_id),
						  ':students_student_no'=>($student_no),
						  ':due_date'=>($due_date),
						  ));
				$_SESSION['book-borrowed']=true;
		}

						 		
	}//End of Adding a BOOK



	public function returnBook($id,$current_count, $book_id){

		$new_count = $current_count + 1;

		$incrementBookCount =$this->dbCon->PREPARE("UPDATE books SET count =? WHERE id=? ");
		$incrementBookCount->bindParam(1,$new_count);
		$incrementBookCount->bindParam(2,$book_id);
		$incrementBookCount->execute();

		$returnBook =$this->dbCon->PREPARE("UPDATE borrowed_books SET status =0 WHERE id=? ");
		$returnBook->bindParam(1,$id);
		$returnBook->execute();

		$_SESSION['book-returned']=true;
				
	}//End of Returning a BOOK



	public function getAllStudentsPerMissingBook(){	
		$payment_type_id = 2;	
		$getAllStudentsPerMissingBook = $this->dbCon->Prepare("SELECT students_student_no, amount, date_paid, books_id, CONCAT(students.firstname, ' ',students.lastname) as student_name, books.title as book_title, books.author as author, sub_classes.name as sub_class_name, books.year_of_publication as year_of_publication FROM payments INNER JOIN students ON(payments.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) INNER JOIN books ON(payments.books_id=books.id) WHERE payment_type_id=?");
		$getAllStudentsPerMissingBook->bindParam(1,$payment_type_id);
		$getAllStudentsPerMissingBook->execute();
		
		if($getAllStudentsPerMissingBook->rowCount()>0){
			$rows = $getAllStudentsPerMissingBook->fetchAll();
			return $rows;
		}
	} //end of getting Students Per Missing Book Payment


	public function getAllBookCount(){	
		$getAllBookCount = $this->dbCon->Prepare("SELECT COUNT(id) as book_count FROM books");
		//$getAllBookCount->bindParam(1,id);
		$getAllBookCount->execute();
		
		if($getAllBookCount->rowCount()>0){
			$row = $getAllBookCount->fetch();
			return $row;
		}
	} //end of getting Book Count

	public function getSpecificBook($book_id){	
		$getSpecificBook = $this->dbCon->Prepare("SELECT id, title, author, year_of_publication, count, book_status_id FROM books WHERE id =?"	);
		$getSpecificBook->bindParam(1,$book_id);
		$getSpecificBook->execute();
		
		if($getSpecificBook->rowCount()>0){
			$row = $getSpecificBook->fetch();
			return $row;
		}
	} //end of getting Specific Book


	public function getStudentsFees($level, $academic_year, $term){	
		$payment_type_id = 1;	
		$getStudentsFees = $this->dbCon->Prepare("SELECT students_student_no, 
			CONCAT('K', FORMAT(amount, 2)) as amount, date_paid, CONCAT(students.firstname, ' ',students.lastname) as student_name, sub_classes.name as sub_class_name, academic_year, ref_num FROM payments INNER JOIN students ON(payments.students_student_no=students.student_no) INNER JOIN sub_classes ON(students.sub_classes_id=sub_classes.id) WHERE payment_type_id=? AND sub_classes.id=? AND academic_year=? AND term=?");
		$getStudentsFees->bindParam(1,$payment_type_id);
		$getStudentsFees->bindParam(2,$level);
		$getStudentsFees->bindParam(3,$academic_year);
		$getStudentsFees->bindParam(4,$term);
		$getStudentsFees->execute();
		
		if($getStudentsFees->rowCount()>0){
			$rows = $getStudentsFees->fetchAll();
			return $rows;
		}
	} //end of getting Students Who Paid Fees



	public function RecordFees($fees, $student_no, $academic_year, $term, $remarks, $ref_num, $payment_type){

		$date = DATE("Y-m-d h:i");
		$RecordFees = $this->dbCon->prepare("INSERT INTO payments (amount, students_student_no, academic_year, term, remarks, ref_num, payment_type_id, date_paid)
		VALUES (:amount, :students_student_no, :academic_year, :term, :remarks, :ref_num, :payment_type_id, :date_paid)" );
		$RecordFees->execute(array(
				  ':amount'=>($fees),
				  ':students_student_no'=>($student_no),
				  ':academic_year'=>($academic_year),
				  ':term'=>($term),
				  ':remarks'=>($remarks),
				  ':ref_num'=>($ref_num),
				  ':payment_type_id'=>($payment_type),
				  ':date_paid'=>($date)
				  ));

			$_SESSION['fees-recorded']=true;
						 		
	}



	public function recordMissingBookFee($fees, $student_no, $academic_year, $term, $ref_num, $book_id){

		$date = DATE("Y-m-d h:i");
		$payment_type_id = 2;
		$recordMissingBookFee = $this->dbCon->prepare("INSERT INTO payments (amount, students_student_no, academic_year, term, ref_num, payment_type_id, date_paid, books_id)
		VALUES (:amount, :students_student_no, :academic_year, :term, :ref_num, :payment_type_id, :date_paid, :books_id)" );
		$recordMissingBookFee->execute(array(
				  ':amount'=>($fees),
				  ':students_student_no'=>($student_no),
				  ':academic_year'=>($academic_year),
				  ':term'=>($term),
				  ':ref_num'=>($ref_num),
				  ':payment_type_id'=>($payment_type_id),
				  ':date_paid'=>($date),
				  ':books_id'=>($book_id)
				  ));

			$_SESSION['book-fee']=true;
						 		
	}


	public function editBook($book_id, $title,$author,$year_of_publication,$count){


		$editBook =$this->dbCon->PREPARE("UPDATE books SET title =?, author =?, year_of_publication =?, count =? WHERE id=? ");
		$editBook->bindParam(1,$title);
		$editBook->bindParam(2,$author);
		$editBook->bindParam(3,$year_of_publication);
		$editBook->bindParam(4,$count);
		$editBook->bindParam(5,$book_id);
		$editBook->execute();

		$_SESSION['book-edited']=true;
	}//End of Editing a book


	public function increaseBookCount($book_id, $count){
		$increaseBookCount =$this->dbCon->PREPARE("UPDATE books SET count=? WHERE id=? ");
		$increaseBookCount->bindParam(1,$count);
		$increaseBookCount->bindParam(2,$book_id);
		$increaseBookCount->execute();

		$_SESSION['count_increased']=true;
	}//End of Increasing book count




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
	
	
	public function getCustomers(){
		$getCustomers = $this->dbCon->PREPARE("SELECT name, phone, email, address FROM customer");
		$getCustomers->execute();
		
		if($getCustomers->rowCount()>0){
			$rows = $getCustomers->fetchAll();
			return $rows;
		}
		
	}
}




class Accountant{
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

	public function getAccountants(){
		
		$getAccountants = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,phone,experience,address,email,date_joined,address FROM accountants");
		$getAccountants->execute();
		
		if($getAccountants->rowCount()>0){
			$row = $getAccountants->fetchAll();
			return $row;
		}
	} //end of getting guardians


	public function getSpecificAccountant($id){
		
		$getSpecificAccountant = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,phone,experience,address,email,date_joined,address, qualifications FROM accountants WHERE id=?");
		$getSpecificAccountant->bindParam(1,$id);
		$getSpecificAccountant->execute();
		
		if($getSpecificAccountant->rowCount()>0){
			$row = $getSpecificAccountant->fetch();
			return $row;
		}
	} //end of getting Specific guardians

	
		//add Partner
	public function addAccountant($firstname,$middlename,$lastname,$phone,$address,$email,$qualifications,$experience,$date_joined){

				$addAccountant = $this->dbCon->prepare("INSERT INTO accountants (phone,firstname,middlename,lastname,qualifications,address,email,experience,date_joined)
				VALUES (:phone,:firstname,:middlename,:lastname,:qualifications,:address,:email,:experience,:date_joined)" );
				$addAccountant->execute(array(
						  ':phone'=>($phone),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':qualifications'=>($qualifications),
						  ':address'=>($address),
						  ':email'=>($email),
						  ':experience'=>($experience),
						  ':date_joined'=>($date_joined)
						  
						  ));
						  
			//add the Accountant to users table for logins
			$role =60; //teacher role id
			$status = 1; //active status
			$username = $phone;
			$password = password_hash($phone, PASSWORD_DEFAULT)."\n"; 
			$addUser = new User();
			$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);

			$_SESSION['accountant-added']=true;
		
	}


	
	public function editAccountant($id, $firstname,$middlename,$lastname,$phone,$address,$email,$qualifications,$date_joined,$experience){

					$editAccountant = $this->dbCon->prepare("UPDATE accountants SET firstname=?,middlename=?, lastname=?, phone=?, address=?, email=?, qualifications=?, date_joined=?, experience=? WHERE id=?");
					$editAccountant->bindParam(1,$firstname);
					$editAccountant->bindParam(2,$middlename);
					$editAccountant->bindParam(3,$lastname);
					$editAccountant->bindParam(4,$phone);
					$editAccountant->bindParam(5,$address);
					$editAccountant->bindParam(6,$email);
					$editAccountant->bindParam(7,$qualifications);
					$editAccountant->bindParam(8,$date_joined);
					$editAccountant->bindParam(9,$experience);
					$editAccountant->bindParam(10,$id);
					$editAccountant->execute();

		  $_SESSION['accountant-edited']=true;
		}


	public function getStudentsFeesBalancesPerClass($class_id, $fees, $term, $academic_year){
		
		$getStudentsFeesBalancesPerClass = $this->dbCon->Prepare("SELECT SUM(amount) as amount, academic_year, term, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname, students.student_no as student_no FROM payments INNER JOIN students ON (payments.students_student_no=students.student_no) WHERE students.sub_classes_id=? AND term=? AND academic_year=? GROUP BY student_no ");
		$getStudentsFeesBalancesPerClass->bindParam(1, $class_id);
		$getStudentsFeesBalancesPerClass->bindParam(2, $term);
		$getStudentsFeesBalancesPerClass->bindParam(3, $academic_year);
		$getStudentsFeesBalancesPerClass->execute();
		
		if($getStudentsFeesBalancesPerClass->rowCount()>0){
			$rows = $getStudentsFeesBalancesPerClass->fetchAll();
			return $rows;
		}
	} //end of getting Fees Per Class


		public function getStudentsWithNoPayment($class_id, $fees, $term, $academic_year){
		
		$getStudentsWithNoPayment = $this->dbCon->Prepare("SELECT amount, academic_year, term, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname, students.student_no as student_no FROM students LEFT JOIN payments ON (payments.students_student_no=students.student_no) WHERE students.sub_classes_id=? AND academic_year IS NULL GROUP BY student_no ");
		$getStudentsWithNoPayment->bindParam(1, $class_id);
		$getStudentsWithNoPayment->execute();
		
		if($getStudentsWithNoPayment->rowCount()>0){
			$rows = $getStudentsWithNoPayment->fetchAll();
			return $rows;
		}
	} //end of getting Fees Per Class



}




class Librarian{
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

	public function getLibrarians(){
		
		$getLibrarians = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,phone,address,email,date_joined,address FROM librarians");
		$getLibrarians->execute();
		
		if($getLibrarians->rowCount()>0){
			$rows = $getLibrarians->fetchAll();
			return $rows;
		}
	} //end of getting guardians


	public function getSpecificLibrarian($id){
		
		$getSpecificLibrarian = $this->dbCon->Prepare("SELECT id,firstname,middlename,lastname,phone,address,email,date_joined,address, qualifications FROM librarians WHERE id=?");
		$getSpecificLibrarian->bindParam(1,$id);
		$getSpecificLibrarian->execute();
		
		if($getSpecificLibrarian->rowCount()>0){
			$row = $getSpecificLibrarian->fetch();
			return $row;
		}
	} //end of getting Specific guardians

	
		//add Partner
	public function addLibrarian($firstname,$middlename,$lastname,$phone,$address,$email,$qualifications,$date_joined){

				$addLibrarian = $this->dbCon->prepare("INSERT INTO librarians (phone,firstname,middlename,lastname,qualifications,address,email,date_joined)
				VALUES (:phone,:firstname,:middlename,:lastname,:qualifications,:address,:email,:date_joined)" );
				$addLibrarian->execute(array(
						  ':phone'=>($phone),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':qualifications'=>($qualifications),
						  ':address'=>($address),
						  ':email'=>($email),
						  ':date_joined'=>($date_joined)
						  
						  ));
						  
			//add the Accountant to users table for logins
			$role =40; //teacher role id
			$status = 1; //active status
			$username = $phone;
			$password = password_hash($phone, PASSWORD_DEFAULT)."\n"; 
			$addUser = new User();
			$addUser->addUser($username,$firstname,$middlename, $lastname, $role,$password,$status);

			$_SESSION['librarian-added']=true;
		
	}


	
	public function editLibrarian($id, $firstname,$middlename,$lastname,$phone,$address,$email,$qualifications,$date_joined){

					$editLibrarian = $this->dbCon->prepare("UPDATE librarians SET firstname=?,middlename=?, lastname=?, phone=?, address=?, email=?, qualifications=?, date_joined=? WHERE id=?");
					$editLibrarian->bindParam(1,$firstname);
					$editLibrarian->bindParam(2,$middlename);
					$editLibrarian->bindParam(3,$lastname);
					$editLibrarian->bindParam(4,$phone);
					$editLibrarian->bindParam(5,$address);
					$editLibrarian->bindParam(6,$email);
					$editLibrarian->bindParam(7,$qualifications);
					$editLibrarian->bindParam(8,$date_joined);
					$editLibrarian->bindParam(9,$id);
					$editLibrarian->execute();

		  $_SESSION['librarian-edited']=true;
		}


	public function deleteBook($id){
		$deleteBook =$this->dbCon->PREPARE("DELETE FROM books WHERE id='$id'");
		$deleteBook->bindParam(1,$id);
		$deleteBook->execute();

		$_SESSION['book_deleted'] = true;
		
	}//End of deleting a Book



}


?>