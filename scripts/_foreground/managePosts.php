<?php 
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	require_once '../background/_DbOperations.php';
	require_once '../background/_Response.php';
	require_once '../background/_validator.php';

	$responseObj = null;
		
	if($_SERVER['REQUEST_METHOD']=='POST'){	
	  if(isset($_POST['IDs']) && isset($_POST['operation'])){
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
			
			
			$responseObj = null;		
			$operation = strip_tags(trim($_POST['operation']));
			$IDs = strip_tags(trim($_POST['IDs']));
			switch($operation){
				
				case "delete":
					$responseObj = $dbOperationsObject->deleteMultiplePosts($IDs);
				break;
				
				case "unapprove":
					$responseObj = $dbOperationsObject->unApproveMultiple($IDs);
				break;
				
				case "approve":
					$responseObj = $dbOperationsObject->approveMultiple($IDs);
				break;
				
				case "deleteComments":
					$responseObj = $dbOperationsObject->deleteMultipleComments($IDs);
				break;
				
				case "feature":
					$responseObj = $dbOperationsObject->featureMultiplePosts($IDs);
				break;
				
				case "un_feature":
					$responseObj = $dbOperationsObject->un_featureMultiplePosts($IDs);
				break;
				
				case "removeUser": 
					$responseObj = $dbOperationsObject->deleteUsers($IDs);
				break;
				
				case "deleteCategory": 
					$responseObj = $dbOperationsObject->deleteCategories($IDs);
				break;  
				
				case "unapproveCategory":
					$responseObj = $dbOperationsObject->unApproveCategories($IDs);
				break; 
				
				case "approveCategories":
					$responseObj = $dbOperationsObject->approveCategories($IDs);
				break; 
				
				default:
					if($responseObj == null){
						$responseObj = new Response();
					}
						$responseObj->setError(true);	
						$responseObj->setMessage('Operation Invalid');	
				break;
				
			}
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