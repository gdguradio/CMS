<?php

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	require_once '../background/_DbOperations.php';
	require_once '../background/_Response.php';
	require_once '../background/_validator.php';

	$responseObj = null;

  	if($_SERVER['REQUEST_METHOD']=='POST'){	
		
		if( isset($_POST['title']) && isset($_POST['desc']) && isset($_POST['category']) && isset($_POST['ID']) ){
			
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
			
			$title = trim($_POST['title']);
			$desc = trim($_POST['desc']);
			$category = trim($_POST['category']);
			$type = "";
			$approved = true;
			$ownerId = trim($_POST['ID']);
			$customCat = false;
			
			if($category == 'custom'){
				$category = $_POST['custom_cat'];
				$approved = false;
				$customCat = "true";
			}
			
			if( empty($_FILES['image']['name']) ){
				$type = "text";
				$responseObj = $dbOperationsObject->addTextPost($title , $desc  , $category , $type ,$approved,$ownerId,$customCat);
				echo $responseObj->getResponse();
				exit();
				
				
			}else{
				$type = "image";
			
				$image = $_FILES['image']['name'];
				$allowed_image_extension = array(
					"png",
					"jpg",
					"jpeg",
					"gif"
				);
    
				// Get image file extension
				$file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
				if (! in_array($file_extension, $allowed_image_extension)) {
					if($responseObj == null){
						$responseObj = new Response();
					}
					$responseObj->setError(true);	
					$responseObj->setMessage("Please select the image file");	
					echo $responseObj->getResponse();
					exit();
				}   

				$res = round(microtime(true) * 1000);
				$image_server_name = $res."-img-".basename($image);
				$target = "../../uploadedImages/".$image_server_name;

				if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
					$responseObj = $dbOperationsObject->addImagePost($title , $desc  , $category , $type ,$approved,$ownerId,$customCat,$image_server_name);
					echo $responseObj->getResponse();
					exit();
				}else{
					if($responseObj == null){
						$responseObj = new Response();
					}
					$responseObj->setError(true);	
					$responseObj->setMessage('Failed to upload the image = '.print_r($_FILES['image']));	
					echo $responseObj->getResponse();
					exit();
				}
			}
			
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