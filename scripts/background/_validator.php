<?php 

class validator {
	
   
   public static function isValidName($name){
		if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
			return false;
		}
		return true;
   }
   
   public static function isValidEmail($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
   }
   
    public static function isValidField($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
   }
}

?>