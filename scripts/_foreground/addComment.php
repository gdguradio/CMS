<?php 

require_once '../background/_DbOperations.php';

	$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){	

		if(isset($_POST['text']) && isset($_POST['name']) && isset($_POST['postID']) ){
			$dbOperationsObject = new DbOperations(); 
			$responseObj = $dbOperationsObject->addComment(trim($_POST['text']), trim($_POST['name']), trim($_POST['postID']));
			echo $responseObj->getResponse();
			exit();
		}else{
			$response['error'] = true;
			$response['message'] = 'Required parameters are missing';
		}
	
	}else{
		$response['error'] = true;
		$response['message'] = 'invalid request';
	}
	
	echo json_encode($response);

?>