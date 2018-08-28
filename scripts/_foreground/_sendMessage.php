<?php 

require_once '../background/_DbOperations.php';
require_once '../background/_Response.php';
require_once '../background/_validator.php';

	$responseObj = null;

	if($_SERVER['REQUEST_METHOD']=='POST'){	

		if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) ){
			
			$name = trim($_POST['name']);
			$email = trim($_POST['email']);
			$message = trim($_POST['message']);
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			 	if($responseObj == null){
					$responseObj = new Response();
				}
				if(!$responseObj->getError()){
					$responseObj->setError(true);
				}
				$responseObj->setMessage('Please provide the valid email Address');
				echo $responseObj->getResponse();
				exit();
			}

			$dbOperationsObject = new DbOperations(); 
			$responseObj = $dbOperationsObject->createMessage($name , $email , $message);
			echo $responseObj->getResponse();
			exit();
		}else{
			if($responseObj == null){
				$responseObj = new Response();
			}
			$responseObj->setError(true);	
			$responseObj->setMessage('Required parameters are missing');	
			echo $responseObj->getResponse();
			exit();
		}
	
	}else{
		if($responseObj == null){
			$responseObj = new Response();
		}
		$responseObj->setError(true);	
		$responseObj->setMessage('Invalid Request');	
		echo $responseObj->getResponse();
		exit();
	}
	
?>