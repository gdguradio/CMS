<?php 

		define('DB_NAME','');
		define('DB_USER','');
		define('DB_PASSWORD','');
		define('DB_HOST','');
		
	class DbConnect{

		private $con; 
		

		function connect(){
			$this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			if(mysqli_connect_errno()){
				echo "Failed to connect with database".mysqli_connect_err(); 
			}

			return $this->con; 
		}
	}
	
?>