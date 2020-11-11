<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$fees = $settings['fees'];

if (isset($_POST['remind_guardian'])) {
	$fees_balance = $_POST['fees_balance'];

	if($fees_balance == 'Not Paid'){
		$balance = $fees;

	}else{
		$balance = $fees_balance;

	}

  $id = $_POST['student_no'];
  $getSpecificStudent = new Students();
  $specificStudent = $getSpecificStudent->getSpecificStudent($id);
  $email = $specificStudent['guardian_email'];
  $firstname = $specificStudent['firstname'];
  $lastname = $specificStudent['lastname'];

	    $recipient = $email;
	    $name = "School Fees Reminder";
	    $subject = "REMINDER ON FEES BALANCE";
	    $message = "Your Student ".$firstname.' '.$lastname." has a fees balance of K".number_format($balance).". Please settle this Balance as soon as possible.";
	    
	    
	    $mailBody =$message;
	    mail($recipient, $subject, $mailBody, "From: $name <$email>");

	    $_SESSION["reminder_sent"] = true;
	    header("location: accountant-index.php");


}


?>