<?php 

class Response {
   
    private $error;
    private $message;
	private $content;
  
    function __construct() {
	  $this->message = array(); 
	  $this->error = false; 
    }
	
	function setError($err){
	  $this->error = $err;
	}
	function getError(){
	  return $this->error;	
	}
	function setMessage($msg){
	  array_push($this->message,$msg);
	}
	function setContent($cont){
	  $this->content = $cont;
	}
    function getContent(){
	  return $this->content;	
	}
	
    function getResponse() {
	  $res = array();
	  $res['error'] = $this->error;
	  $res['message'] = $this->message;
	  $res['data'] = $this->content;
	  return json_encode($res);
    }
	
	
    function getResponseData() {
	   return $this->content;
    }
}

?>