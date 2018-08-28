<?php 

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	require_once '../background/_DbOperations.php';
	require_once '../background/_Response.php';
	require_once '../background/_validator.php';

	$responseObj = null;
		
	if($_SERVER['REQUEST_METHOD']=='POST'){	
	  if(isset($_POST['id'])){
		  
		  $dbOperationsObject = new DbOperations(); 
		  $validationError = false;
		  
		    if(isset($_SESSION['isLogin'])){
				if($_SESSION['isLogin']){
					if( !$dbOperationsObject->isValidUserData($_SESSION['username'],$_SESSION['pass']) ){
						// error
						$validationError = true;
					}
					if($_SESSION['role'] != 'admin'){
						// error
						$validationError = true;
					}
				}
			}else{
				// error
				$validationError = true;
			}
		  
			if($validationError){
				if($responseObj == null){
					$responseObj = new Response();
					$responseObj->setMessage('User is not authorized to perform such action');
				}
				$responseObj->setError(true);	
				echo $responseObj->getResponse();
				exit();
			}
		  
			
			$responseObj = $dbOperationsObject->approvePost($_POST['id']);
			echo $responseObj->getResponse();
			exit();
	  }else{
			if($responseObj == null){
				$responseObj = new Response();
			}
			$responseObj->setError(true);	
			$responseObj->setMessage('Parameter missing');	
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