<?php 



	class DbOperations{

		private $con; 

		function __construct(){
			
			require_once '_Response.php';
			require_once '_DBConnect.php';
			
			$db = new DbConnect();

			$this->con = $db->connect();

		}


		public function createUser($name, $pass, $email , $address , $role){
			$responseObj = new Response();
			$role = 'customer';
			if($this->isUserExist($email)){
				$responseObj->setError(true);	
				$responseObj->setMessage('User already exits with this user name');		
			}else{
				$name = strip_tags($name);
				$pass = strip_tags($pass);
				$email = strip_tags($email);
				$address = strip_tags($address);
				$role = strip_tags($role);
				
				$password = md5($pass);
				$time = round(microtime(true) * 1000);
				$stmt = $this->con->prepare("INSERT INTO `users`(`name`, `username`, `password`, `about`, `role`,`joining_date`) VALUES (?,?,?,?,?,?);");
				$stmt->bind_param("ssssss",$name,$email,$password,$address,$role,$time);

				if($stmt->execute()){
					$responseObj->setError(false);	
					$responseObj->setMessage('Account created successfully');
					$responseObj->setContent($this->getLoggedInfo($email,$password));
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error occured while creating account');	
				}
			}
			return $responseObj;
		}
		
	
		public function getLoggedInfo($email, $pass){
			$email = strip_tags($email);
			$pass = strip_tags($pass);
			
			$temp = array();
			
			$stmt = $this->con->prepare("SELECT name,role,profile_address,ID,about FROM users WHERE username=? AND password=?");
			$stmt->bind_param("ss",$email,$pass);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name,$role,$profile_address,$ID,$about);
			if($stmt->num_rows >0) {
				$res = $stmt->fetch();
				$temp['name'] = $name;
				$temp['role'] = $role;
				$temp['profileAddress'] = $profile_address;
				$temp['ID'] = $ID;
				$temp['about'] = $about;
			}
			return $temp; 
		}
		
		public function getLoggedInfoById($ID){
			$ID = strip_tags($ID);
			$temp = array();
			
			$stmt = $this->con->prepare("SELECT name,role,profile_address,ID,about FROM users WHERE ID = ?");
			$stmt->bind_param("i",$ID);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name,$role,$profile_address,$id,$about);
			if($stmt->num_rows >0) {
				$res = $stmt->fetch();
				$temp['name'] = $name;
				$temp['role'] = $role;
				$temp['profileAddress'] = $profile_address;
				$temp['ID'] = $id;
				$temp['about'] = $about;
			}
			return $temp; 
		}
		
		
		public function userLogin($email, $pass){
			$email = strip_tags($email);
			$pass = strip_tags($pass);
			
			$password = md5($pass);
			$responseObj = new Response();
			
			$stmt = $this->con->prepare("SELECT name,role,profile_address,ID,about FROM users WHERE username=? AND password=?");
			$stmt->bind_param("ss",$email,$password);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name,$role,$profile_address,$ID,$about);
			if($stmt->num_rows >0) {
				$res = $stmt->fetch();
				$temp = array();
				$temp['name'] = $name;
				$temp['role'] = $role;
				$temp['profileAddress'] = $profile_address;
				$temp['ID'] = $ID;
				$temp['about'] = $about;
				$responseObj->setMessage('Welcome '.$name);	
				$responseObj->setMessage('Log in Successful');	
				$responseObj->setContent($temp);	
			}else{
				if($this->isUserExist($email)){
					$responseObj->setMessage('The password that you have entered is incorrect.');				
				}else{
					$responseObj->setMessage('The username does not match any account');				
				}
				$responseObj->setError(true);	
			}
			
			return $responseObj; 
		}
		
		private function isUserExist($email){
			$email = strip_tags($email);
	
			$stmt = $this->con->prepare("SELECT ID FROM users WHERE username = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}
		
		public function isValidUserData($username,$password){
			$username = strip_tags($username);
			$password = strip_tags($password);
			$password = md5($password);
			
			$stmt = $this->con->prepare("SELECT ID FROM users WHERE username = ? AND password = ?");
			$stmt->bind_param("ss", $username , $password);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}
		
		private function isPasswordGood($pass){
			$pass = strip_tags($pass);
	
			$stmt = $this->con->prepare("SELECT ID FROM users WHERE password = ?");
			$stmt->bind_param("s", $pass);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}
		
		private function isCategoryExit($name){
			$name = strip_tags($name);
			
			$stmt = $this->con->prepare("SELECT ID FROM category WHERE Category = ?");
			$stmt->bind_param("s", $name);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}
		
		private function validDateUser($email,$password){
			$email = strip_tags($email);
			$password = strip_tags($password);
			
			$password = md5($pass);
			$stmt = $this->con->prepare("SELECT ID FROM users WHERE email=? AND password=? AND role=admin");
			$stmt->bind_param("ss", $email,$password);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0; 
		}
		
		public function getAllUsers(){
			$responseObj = new Response();
			$res = array();
			$result = $this->con->query("select * from users order by role ASC");
			while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['name'] =  $row['name'];
				$temp['email'] =  $row['email'];
				$temp['address'] =  $row['address'];
				$temp['ID'] =  $row['ID'];
				$temp['role'] =  $row['role'];
				array_push($res , $temp);
			}
				$responseObj->setError(false);	
				$responseObj->setMessage('success');	
				$responseObj->setContent($res);
			return $responseObj;
		}
		
		
		
		public function get_10_posts(){
			$responseObj = new Response();
			$reser = array();
		    $res = array();
			$result = $this->con->query("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address FROM posts p left join users u on p.owner_id = u.ID where approved = 'true' ORDER BY date DESC LIMIT 10");
			 while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['title'] =  $row['title'];
				$temp['description'] =  $row['description'];
				$temp['type'] =  $row['type'];
				$temp['date'] =  $row['date'];
				$temp['image_address'] =  $row['image_address'];
				$temp['owner_id'] =  $row['owner_id'];
				$temp['name'] =  $row['name'];
				$temp['profile'] =  $row['profile_address'];
				$temp['commentCount'] = $this->getCommentCountByID($row['ID']);
				array_push($reser , $temp);
			 }
			$res['featuredPosts'] = $reser;
			$res['featuredImages'] = $this->getFeaturedImages();
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($res);
			
			return $responseObj;
		}
		
		public function getAllPosts(){
			$responseObj = new Response();
			$res = array();
			 
			$result = $this->con->query("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address FROM posts p left join users u on p.owner_id = u.ID where approved = 'true' ORDER BY date DESC");
			 while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['title'] =  $row['title'];
				$temp['description'] =  $row['description'];
				$temp['type'] =  $row['type'];
				$temp['date'] =  $row['date'];
				$temp['image_address'] =  $row['image_address'];
				$temp['owner_id'] =  $row['owner_id'];
				$temp['name'] =  $row['name'];
				$temp['profile'] =  $row['profile_address'];
				$temp['commentCount'] = $this->getCommentCountByID($row['ID']);
				array_push($res , $temp);
			 }
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($res);
			return $responseObj;
		}
		
		public function getPostsByUserID($id){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address,cat.name as cat_name FROM posts p left join users u on p.owner_id = u.ID left join category cat on p.cat_id = cat.ID where p.owner_id = ? ORDER BY date DESC");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$res = array();
			$temper = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp = array();
				$temp['ID'] =  $row[0];
				$temp['title'] =  $row[1];
				$temp['description'] =  $row[2];
				$temp['type'] =  $row[3];
				$temp['date'] =  $row[4];
				$temp['image_address'] =  $row[5];
				$temp['ownerID'] =  $row[6];
				$temp['cat_id'] = $row[7];
				$temp['approved'] =  $row[8];
				$temp['name'] = $row[9];
				$temp['profile_address'] = $row[10];
				$temp['cat_name'] = $row[11];
				array_push($temper , $temp);
			}
			
			$res['comments'] = $this->getCommentByUserID($id);
			$res['posts'] = $temper;
			
			$responseObj->setError(false);	
			$responseObj->setMessage('operation completed');	
			$responseObj->setContent($res);	
			return $responseObj;
		}
		
		
		public function getFeaturedImages(){
			$res = array();
			$result = $this->con->query("SELECT ID,title,image_address from posts where approved = 'true' AND feature_image = 'true' AND type = 'image' ORDER BY date DESC LIMIT 4");
			 while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['title'] =  $row['title'];
				$temp['image_address'] =  $row['image_address'];
				array_push($res , $temp);
			 }
			return $res;
		}
		
		public function getAllImagePosts(){
			$responseObj = new Response();
			$res = array();
			$result = $this->con->query("SELECT ID,title,image_address from posts where approved = 'true' AND type = 'image' ORDER BY date DESC");
			 while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['title'] =  $row['title'];
				$temp['image_address'] =  $row['image_address'];
				array_push($res , $temp);
			 }
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($res);
			return $responseObj;
		}
		
		public function getFeaturedPosts(){
			$res = array();
			$result = $this->con->query("SELECT ID,title from posts where approved = 'true' AND featured = 'true' ORDER BY date DESC");
			 while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['title'] =  $row['title'];
				array_push($res , $temp);
			 }
			return $res;
		}
		
		public function getAllPostsByCategory($id ){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address FROM posts p left join users u on p.owner_id = u.ID where approved = 'true' AND p.cat_id = ? ORDER BY date DESC");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temper = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp = array();
				$temp['ID'] =  $row[0];
				$temp['title'] =  $row[1];
				$temp['description'] =  $row[2];
				$temp['type'] =  $row[3];
				$temp['date'] =  $row[4];
				$temp['image_address'] =  $row[5];
				$temp['ownerID'] =  $row[6];
				$temp['cat_id'] = $row[7];
				$temp['approved'] =  $row[8];
				$temp['name'] = $row[9];
				$temp['profile_address'] = $row[10];
				$temp['commentCount'] = $this->getCommentCountByID($row[0]);
				array_push($temper , $temp);
			}
			$responseObj->setContent($temper);	
			return $responseObj;
		}
		
		
		public function getAllCategories(){
			$responseObj = new Response();
			$res = array();
			$result = $this->con->query("SELECT * from category where approved = 'true'");
			while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['name'] =  $row['name'];
				$temp['postCount'] = $this->getCategoryPostCount($row['ID']);
				array_push($res , $temp);
			}
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($res);
			return $responseObj;
		}
		
		public function updateUserData( $ID , $name , $about){
			$ID = strip_tags($ID);
			$name = strip_tags($name);
			$about = strip_tags($about);
	
			$responseObj = new Response();
			$stmt = $this->con->prepare("UPDATE users SET name = ? , about = ? WHERE ID=?");
			$stmt->bind_param('ssi', $name, $about , $ID);
			$status = $stmt->execute();
			if ($status === false) {
				$responseObj->setError(true);	
				$responseObj->setMessage('Failed to update data');	
				$responseObj->setContent(null);
			}else{
				$responseObj->setError(false);	
				$responseObj->setMessage('Updated successfully');	
				$responseObj->setContent($this->getLoggedInfoById($ID));
			}
			return $responseObj;
		}
		
		public function approvePost($postID){
			$postID = strip_tags($postID);

			$responseObj = new Response();
			$stmt = $this->con->prepare("select cat_id from posts where ID = ?");
			$stmt->bind_param("i", $postID);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			$catID = $row[0];
			
			$stmt = $this->con->prepare("UPDATE posts SET approved = 'true' WHERE ID=?");
			$stmt->bind_param('i', $postID);
			$statusPost = $stmt->execute();
			
			$stmt = $this->con->prepare("UPDATE category SET approved = 'true' WHERE ID=?");
			$stmt->bind_param('i', $catID);
			$statusCategory = $stmt->execute();
			
			if($statusCategory === true && $statusPost === true){
				$responseObj->setError(false);	
				$responseObj->setMessage('Post Approved');	
				$responseObj->setContent(null);
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error while approving the post');	
				$responseObj->setContent(null);
			}
			
			return $responseObj;
		}
		
		public function unApproveCategories($postID){
			$postID = strip_tags($postID);
			$responseObj = new Response();
			$stmt = $this->con->query("UPDATE category SET approved = 'false' WHERE ID IN ($postID)");
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Unapproved successfully');	
				$responseObj->setContent(null);
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error while dis-approving the categories');	
				$responseObj->setContent(null);
			}
			return $responseObj;
		}
		
		public function approveCategories($postID){
			$postID = strip_tags($postID);
			$responseObj = new Response();
			$stmt = $this->con->query("UPDATE category SET approved = 'true' WHERE ID IN ($postID)");
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Unapproved successfully');	
				$responseObj->setContent(null);
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error while dis-approving the categories');	
				$responseObj->setContent(null);
			}
			return $responseObj;
		}
		
		
		public function approvePostMultiple($postID){
			$postID = strip_tags($postID);

			$stmt = $this->con->prepare("select cat_id from posts where ID = ?");
			$stmt->bind_param("i", $postID);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			$catID = $row[0];
			
			$stmt = $this->con->prepare("UPDATE posts SET approved = 'true' WHERE ID=?");
			$stmt->bind_param('i', $postID);
			$statusPost = $stmt->execute();
			
			$stmt = $this->con->prepare("UPDATE category SET approved = 'true' WHERE ID=?");
			$stmt->bind_param('i', $catID);
			$statusCategory = $stmt->execute();
		}
		
		public function unApprovePost($postID){
			$postID = strip_tags($postID);
			
			$stmt = $this->con->prepare("select cat_id from posts where ID = ?");
			$stmt->bind_param("i", $postID);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			$catID = $row[0];
			
			$stmt = $this->con->prepare("UPDATE posts SET approved = 'false' WHERE ID=?");
			$stmt->bind_param('i', $postID);
			$statusPost = $stmt->execute();
			
			$stmt = $this->con->prepare("UPDATE category SET approved = 'false' WHERE ID=?");
			$stmt->bind_param('i', $catID);
			$statusCategory = $stmt->execute();
		}
		
		public function unApproveMultiple($IDs){
			$arr = explode(',', $IDs);
			$responseObj = new Response();
			
			for($i=0;$i<count($arr);$i++){
				$this->unApprovePost($arr[$i]);
			}
			$responseObj->setError(false);	
			$responseObj->setMessage('Approved');	
			return $responseObj;
		}
		
		public function approveMultiple($IDs){
			$arr = explode(',', $IDs);
			$responseObj = new Response();
			
			for($i=0;$i<count($arr);$i++){
				$this->approvePostMultiple($arr[$i]);
			}
			$responseObj->setError(false);	
			$responseObj->setMessage('Approved');	
			return $responseObj;
		}
		
		public function updatePassword($ID , $oldPassword , $newPassword){
			$ID = strip_tags($ID);
			$oldPassword = strip_tags($oldPassword);
			$newPassword = strip_tags($newPassword);
			
			$oldPassword = md5($oldPassword);
			$newPassword = md5($newPassword);
			$responseObj = new Response();
			
			if($this->isPasswordGood($oldPassword)){
				
				$stmt = $this->con->prepare("UPDATE users SET password = ? WHERE ID=?");
				$stmt->bind_param('si', $newPassword , $ID);
				$status = $stmt->execute();
				if ($status === false) {
					$responseObj->setError(true);	
					$responseObj->setMessage('Failed to update password');	
					$responseObj->setContent(null);
				}else{
					$responseObj->setError(false);	
					$responseObj->setMessage('password updated successfully');	
					$responseObj->setContent($this->getLoggedInfoById($ID));
				}
			
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Old password is incorrect');	
				$responseObj->setContent(null);
			}
	
			
			
			return $responseObj;
		}
		
		
		public function updateUserProfile_data( $ID , $name , $about , $image_address){
			$ID = strip_tags($ID);
			$name = strip_tags($name);
			$about = strip_tags($about);
			$image_address = strip_tags($image_address);
	
			$responseObj = new Response();
			$stmt = $this->con->prepare("UPDATE users SET name = ? , about = ? , profile_address = ? WHERE ID=?");
			$stmt->bind_param('sssi', $name, $about , $image_address ,  $ID);
			$status = $stmt->execute();
			if ($status === false) {
				$responseObj->setError(true);	
				$responseObj->setMessage('Failed to update data');	
				$responseObj->setContent(null);
			}else{
				$responseObj->setError(false);	
				$responseObj->setMessage('Updated successfully');	
				$responseObj->setContent($this->getLoggedInfoById($ID));
			}
			return $responseObj;
		}
		
		public function updateCategory($cat, $id){
			$cat = strip_tags($cat);
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("UPDATE category SET Category=? WHERE ID=?");
			$stmt->bind_param('si', $cat, $id);
			$status = $stmt->execute();
			if ($status === false) {
				$responseObj->setError(true);	
				$responseObj->setMessage('Failed to update category');	
				$responseObj->setContent($res);
			}else{
				$responseObj->setError(false);	
				$responseObj->setMessage('updated successfully');	
				$responseObj->setContent(null);
			}
			return $responseObj;
		}
		
		public function updateProduct($name, $price, $quantity , $image_server_name,$cat,$id ,  $visible , $featured){
			$name = strip_tags($name);
			$price = strip_tags($price);
			$quantity = strip_tags($quantity);
			$image_server_name = strip_tags($image_server_name);
			$cat = strip_tags($cat);
			$id = strip_tags($id);
			$visible = strip_tags($visible);
			$featured = strip_tags($featured);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("UPDATE products SET name=?,price=?,picture=?,quantity=?,visible=?,Category=?,featured=? WHERE ID=?");
			$stmt->bind_param("sssssssi",$name,$price,$image_server_name,$quantity,$visible,$cat,$featured,$id);
			$status = $stmt->execute();
			if ($status === false) {
				$responseObj->setError(true);	
				$responseObj->setMessage('Failed to update product');	
				$responseObj->setContent($res);
			}else{
				$responseObj->setError(false);	
				$responseObj->setMessage('updated successfully');	
				$responseObj->setContent(null);
			}
			return $responseObj;
		}
		
		public function getAllMessages(){
			$response = array();
			$res = array();
			$result = $this->con->query("SELECT * from messages");
			while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['name'] =  $row['name'];
				$temp['email'] =  $row['email'];
				$temp['subject'] =  $row['subject'];
				$temp['message'] =  $row['message'];
				$temp['timestamp'] =  $row['timestamp'];
				array_push($res , $temp);
			}
			$response['messages'] = $res;
			return $response;
		}
		
		public function getStats(){
			$responseObj = new Response();
			$temp = array();
			
			$result = $this->con->query("SELECT count(ID) from users where blocked = 'false'");
			$row = $result->fetch_assoc();
			$temp['usersCount'] =  $row['count(ID)'];
			
			$result = $this->con->query("SELECT count(ID) from posts where approved = 'true'");
			$row = $result->fetch_assoc();
			$temp['postsCount'] =  $row['count(ID)'];
			
			$result = $this->con->query("SELECT count(ID) from comments");
			$row = $result->fetch_assoc();
			$temp['commentsCount'] =  $row['count(ID)'];
			
			$result = $this->con->query("SELECT count(ID) from category where approved = 'true'");
			$row = $result->fetch_assoc();
			$temp['categoryCount'] =  $row['count(ID)'];
			
			$temp['posts'] = $this->getFeaturedPosts();
			
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($temp);
			return $responseObj;
		}
		
		public function getAllOrders(){
			$responseObj = new Response();
			$res = array();
			$result = $this->con->query("select o.ID , o.user_id , o.price , o.order_date , o.items , u.name , u.address from orders o join users u where o.user_id = u.ID");
			while($row = $result->fetch_assoc()){
				$temp = array();
				$temp['ID'] =  $row['ID'];
				$temp['user_id'] =  $row['user_id'];
				$temp['price'] =  $row['price'];
				$temp['order_date'] =  $row['order_date'];
				$temp['items'] =  $row['items'];
				$temp['user_name'] =  $row['name'];
				$temp['user_address'] =  $row['address'];
				array_push($res , $temp);
			}
			$responseObj->setError(false);	
			$responseObj->setMessage('success');	
			$responseObj->setContent($res);
			return $responseObj;
		}
		
		public function addTextPost($title , $desc  , $category , $type ,$approved,$ownerId,$customCat){
			$title = strip_tags($title);
			
			$category = strip_tags($category);
			$type = strip_tags($type);
			$approved = strip_tags($approved);
			$ownerId = strip_tags($ownerId);
			$customCat = strip_tags($customCat);
			
			$responseObj = new Response();
			
				if($customCat){
					$category = $this->addCategory($category);
				}
				$approv = "false";

				if($approved){
					$approv = "true";
				}
			
				$stmt = $this->con->prepare("INSERT INTO posts  (`title`, `description`, `type`, `date`, `owner_id`, `cat_id`, `approved`) VALUES (?,?,?,?,?,?,?);");
				$time = round(microtime(true) * 1000);
				$stmt->bind_param("ssssiis",$title,$desc,$type,$time,$ownerId,$category,$approv);

				if($stmt->execute()){
					$responseObj->setError(false);	
					$responseObj->setMessage('Posted successfully');	
					if(!$approved){
						$responseObj->setMessage('Approval Required from admin for custom category');	
					}
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error occured while adding to database');	
				}
			return $responseObj;
		}
		
		public function updateTextPost($title , $desc  , $category , $type ,$approved ,$customCat , $postID){
			$title = strip_tags($title);
			$desc = strip_tags($desc);
			$category = strip_tags($category);
			$type = strip_tags($type);
			$approved = strip_tags($approved);
			$postID = strip_tags($postID);
			$customCat = strip_tags($customCat);
			
			$responseObj = new Response();
			
				if($customCat){
					$category = $this->addCategory($category);
				}
				$approv = "false";

				if($approved){
					$approv = "true";
				}
				
				$stmt = $this->con->prepare("UPDATE `posts` SET `title`= ? , `description` = ? , `type` = ? , `cat_id` = ? , `approved` = ?  WHERE `ID` = ?");
			
				$stmt->bind_param("sssisi",$title,$desc,$type,$category,$approv,$postID);

				if($stmt->execute()){
					$responseObj->setError(false);	
					$responseObj->setMessage('Post updated successfully');	
					if(!$approved){
						$responseObj->setMessage('Approval Required from admin for custom category');	
					}
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error occured while updating the post');	
				}
			return $responseObj;
		}
		
		public function updateImagePost($title , $desc  , $category , $type ,$approved ,$customCat , $postID , $image_server_name){
			$title = strip_tags($title);
			$desc = strip_tags($desc);
			$category = strip_tags($category);
			$type = strip_tags($type);
			$approved = strip_tags($approved);
			$postID = strip_tags($postID);
			$customCat = strip_tags($customCat);
			$image_server_name = strip_tags($image_server_name);
			
			$responseObj = new Response();
			
				if($customCat){
					$category = $this->addCategory($category);
				}
				$approv = "false";

				if($approved){
					$approv = "true";
				}
				
				$stmt = $this->con->prepare("UPDATE `posts` SET `title`= ? , `description` = ? , `type` = ? , `cat_id` = ? , `approved` = ? , `image_address` = ?  WHERE `ID` = ?");
			
				$stmt->bind_param("sssissi",$title,$desc,$type,$category,$approv,$image_server_name,$postID);

				if($stmt->execute()){
					$responseObj->setError(false);	
					$responseObj->setMessage('Post updated successfully');	
					if(!$approved){
						$responseObj->setMessage('Approval Required from admin for custom category');	
					}
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error occured while updating the post');	
				}
			return $responseObj;
		}
		
		public function addImagePost($title , $desc  , $category , $type ,$approved,$ownerId,$customCat,$image_server_name){
			$title = strip_tags($title);
			$desc = strip_tags($desc);
			$category = strip_tags($category);
			$type = strip_tags($type);
			$approved = strip_tags($approved);
			$ownerId = strip_tags($ownerId);
			$customCat = strip_tags($customCat);
			$image_server_name = strip_tags($image_server_name);
			
			$responseObj = new Response();
			$approv = "false";
				if($customCat){
					$category = $this->addCategory($category);
				}
				if($approved){
					$approv = "true";
				}
					
				$stmt = $this->con->prepare("INSERT INTO posts  (`title`, `description`, `type`, `date`, `owner_id`, `cat_id`, `approved`,`image_address`) VALUES (?,?,?,?,?,?,?,?);");
				$time = round(microtime(true) * 1000);
				$stmt->bind_param("ssssiiss",$title,$desc,$type,$time,$ownerId,$category,$approv,$image_server_name);

				if($stmt->execute()){
					$responseObj->setError(false);	
					$responseObj->setMessage('Posted successfully');	
					if(!$approved){
						$responseObj->setMessage('Approval Required from admin for custom category');	
					}
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error occured while adding to database');	
				}
			return $responseObj;
		}
		
		public function addComment($text , $name  , $postID){
			$text = strip_tags($text);
			$name = strip_tags($name);
			$postID = strip_tags($postID);
			//$this->sendPush("working");
			$responseObj = new Response();
			$stmt = $this->con->prepare("INSERT INTO comments (`text`, `name`, `post_id`, `date`) VALUES (?,?,?,?)");
			$time = round(microtime(true) * 1000);
			$stmt->bind_param("ssis",$text , $name  , $postID , $time);

			if($stmt->execute()){
				$responseObj->setError(false);	
				$responseObj->setMessage('Comment added successfully');	
				$id = $stmt->insert_id;	
				$response = array();
				$response['text'] = $text;
				$response['name'] = $name;
				$response['date'] = $time;
				$this->sendPush($response);
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while posting comment');	
			}
			return $responseObj;
		}
		
		public function createMessage($name , $email , $message){
			$name = strip_tags($name);
			$email = strip_tags($email);
			$message = strip_tags($message);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("INSERT INTO `messages` (`name`, `date`, `email`, `message`) VALUES (?,?,?,?);");
			$time = round(microtime(true) * 1000);
			$stmt->bind_param("ssss",$name , $time  , $email , $message);

			if($stmt->execute()){
				$responseObj->setError(false);	
				$responseObj->setMessage('Message sent successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while sending message');	
			}
			return $responseObj;
		}
		
		public function deleteMultiplePosts($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("delete from posts where ID IN ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Removed successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while removing posts');	
			}
			return $responseObj;
		}
		
		public function deleteUsers($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("delete from users where ID IN ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Removed successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while removing users');	
			}
			return $responseObj;
		}
		
		public function featureMultiplePosts($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("update posts set featured = 'true' where ID in ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Featured successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while featuring posts');	
			}
			return $responseObj;
		}
		
		public function un_featureMultiplePosts($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("update posts set featured = 'false' where ID in ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Featured successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while featuring posts');	
			}
			return $responseObj;
		}
		
		public function deleteMultipleComments($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("delete from comments where ID IN ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Removed successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while removing comments');	
			}
			return $responseObj;
		}
		
		public function deleteCategories($IDs){
			$IDs = strip_tags($IDs);
			$stmt = $this->con->query("delete from category where ID IN ($IDs)");
			$responseObj = new Response();
			if($stmt){
				$responseObj->setError(false);	
				$responseObj->setMessage('Removed successfully');	
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('Error occured while removing categories');	
			}
			return $responseObj;
		}
		
		public function deleteProduct($id){
			$id = strip_tags($id);
			$stmt = $this->con->prepare("DELETE FROM products WHERE ID=?");
			$stmt->bind_param("i", $id);
			return $stmt->execute(); 
		}
		
		public function deleteCategory($id){
			$id = strip_tags($id);
			$stmt = $this->con->prepare("DELETE FROM category WHERE ID=?");
			$stmt->bind_param("i", $id);
			return $stmt->execute(); 
		}
		
		public function deleteOrder($id){
			$id = strip_tags($id);
			$stmt = $this->con->prepare("DELETE FROM orders WHERE ID=?");
			$stmt->bind_param("i", $id);
			return $stmt->execute(); 
		}
		
		public function deletePost($id , $owner){
			$responseObj = new Response();
			if($this->isPostOwner($id , $owner)){
				$id = strip_tags($id);
				$stmt = $this->con->prepare("DELETE FROM posts WHERE ID=?");
				$stmt->bind_param("i", $id);
				$res = $stmt->execute(); 
				
				if($res){
					$responseObj->setError(false);	
					$responseObj->setMessage('post deleted');	
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error while deleting the post');	
				}
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('you do not have permission to delete this post');	
			}
			return $responseObj;			
		}
		
		public function isPostOwner($id , $ownerID){
			$id = strip_tags($id);
			$ownerID = strip_tags($ownerID);
	
			$stmt = $this->con->prepare("SELECT ID FROM posts WHERE ID = ? AND owner_id = ?");
			$stmt->bind_param("ii", $id , $ownerID);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0;  
		}
		
		public function deleteComment($id , $owner){
			$responseObj = new Response();
			if($this->isCommentOwner($id , $owner)){
				$id = strip_tags($id);
				$stmt = $this->con->prepare("DELETE FROM comments WHERE ID=?");
				$stmt->bind_param("i", $id);
				$res = $stmt->execute(); 
				
				if($res){
					$responseObj->setError(false);	
					$responseObj->setMessage('comment deleted');	
				}else{
					$responseObj->setError(true);	
					$responseObj->setMessage('Error while deleting the comment');	
				}
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('you do not have permission to delete this comment');	
			}
			return $responseObj;			
		}
		
		public function isCommentOwner($id , $ownerID){
			$id = strip_tags($id);
			$ownerID = strip_tags($ownerID);
	
			$stmt = $this->con->prepare("SELECT ID FROM comments WHERE ID = ? AND owner_id = ?");
			$stmt->bind_param("ii", $id , $ownerID);
			$stmt->execute(); 
			$stmt->store_result(); 
			return $stmt->num_rows > 0;  
		}
		
		public function addCategory($name){
			$name = strip_tags($name);
			
			$responseObj = new Response();
			$approved = "false";
			$stmt = $this->con->prepare("INSERT INTO category (`name`,`approved`) VALUES (?,?);");
			$stmt->bind_param("ss",$name,$approved);
			$stmt->execute();
			$id = $stmt->insert_id;	
			return $id;
		}
		
		public function getUserOrders($email){
			$email = strip_tags($email);
			
			$id = $this->getUID($email);
			$responseObj = new Response();
			$res = array();
			$stmt = $this->con->prepare("SELECT * from orders where user_id=?");
			$stmt->bind_param("s",$id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($orderID,$userID,$price,$order_date,$items);

			
			if($stmt->num_rows >0) {
				while($row = $stmt->fetch()){
					$temp = array();
					$temp['orderID'] = $orderID;
					$temp['userID'] = $userID;
					$temp['price'] = $price;
					$temp['order_date'] = $order_date;
					$temp['items'] = $items;
					array_push($res , $temp);
				}
				$responseObj->setError(false);	
				$responseObj->setMessage('success');	
				$responseObj->setContent($res);
				return $responseObj;
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('none');	
				return $responseObj;
			}
		}
		
		public function getCommentByUserID($id){
			$id = strip_tags($id);
			
			$res = array();
			$temper = array();
			$stmt = $this->con->prepare("SELECT c.ID,c.text,c.owner_id,c.post_id,c.date,u.name,u.profile_address,p.title,p.description from comments c join users u on c.owner_id = u.ID join posts p on c.post_id = p.ID where c.owner_id = ? ORDER BY date DESC");
			$stmt->bind_param("i",$id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id,$text,$owner_id,$post_id,$date,$name,$profile,$title,$description);

			if($stmt->num_rows >0) {
				while($row = $stmt->fetch()){
					$temp = array();
					$temp['ID'] = $id;
					$temp['text'] = $text;
					$temp['owner_id'] = $owner_id;
					$temp['post_id'] = $post_id;
					$temp['date'] = $date;
					$temp['name'] = $name;
					$temp['profile'] = $profile;
					$temp['title'] = $title;
					$temp['description'] = $description;
					array_push($temper , $temp);
				}
				$res['error'] = false;
				$res['content'] = $temper;
			}else{
				$res['error'] = true;
				$res['content'] = null;
			}
			return $res;
		}
		
		public function getCommentByCommentID($id){
			$id = strip_tags($id);
			
			$res = array();
			$temper = array();
			$stmt = $this->con->prepare("SELECT c.ID,c.text,c.owner_id,c.post_id,c.date,u.name,u.profile_address from comments c join users u on c.owner_id = u.ID where c.ID = ? ORDER BY date DESC");
			$stmt->bind_param("i",$id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id,$text,$owner_id,$post_id,$date,$name,$profile);

			if($stmt->num_rows >0) {
				while($row = $stmt->fetch()){
					$temp = array();
					$temp['ID'] = $id;
					$temp['text'] = $text;
					$temp['owner_id'] = $owner_id;
					$temp['post_id'] = $post_id;
					$temp['date'] = $date;
					$temp['name'] = $name;
					$temp['profile'] = $profile;
					array_push($temper , $temp);
				}
				$res['error'] = false;
				$res['content'] = $temper;
			}else{
				$res['error'] = true;
				$res['content'] = null;
			}
			return $res;
		}
		
		public function getCommentsByID($id){
			$id = strip_tags($id);
			
			$res = array();
			$temper = array();
			$stmt = $this->con->prepare("SELECT c.ID,c.text,c.owner_id,c.post_id,c.date,u.name,u.profile_address from comments c join users u on c.owner_id = u.ID where c.post_id = ?");
			$stmt->bind_param("i",$id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id,$text,$owner_id,$post_id,$date,$name,$profile);

			
			if($stmt->num_rows >0) {
				while($row = $stmt->fetch()){
					$temp = array();
					$temp['ID'] = $id;
					$temp['text'] = $text;
					$temp['owner_id'] = $owner_id;
					$temp['post_id'] = $post_id;
					$temp['date'] = $date;
					$temp['name'] = $name;
					$temp['profile'] = $profile;
					array_push($temper , $temp);
				}
				$res['error'] = false;
				$res['content'] = $temper;
			}else{
				$res['error'] = true;
				$res['content'] = null;
			}
			return $res;
		}
		
		public function getProductByID($id){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT * from products WHERE ID=?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temp = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp = array();
				$temp['ID'] =  $row[0];
				$temp['name'] =  $row[1];
				$temp['price'] =  $row[2];
				$temp['picture'] =  $row[3];
				$temp['quantity'] =  $row[4];
				$temp['visible'] =  $row[5];
				$temp['featured'] =  $row[6];
				$temp['Category'] = $row[7];
			}
			 $responseObj->setContent($temp);	
			return $responseObj;
		}
		
		public function getPostByID($id){
			$id = strip_tags($id);
			
			$stmt = $this->con->prepare("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address FROM posts p left join users u on p.owner_id = u.ID WHERE p.ID=? ORDER BY date DESC");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temper = array();
			$result = $stmt->get_result();
			$id = null;
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp = array();
				$temp['ID'] =  $row[0];
				$id = $row[0];
				$temp['title'] =  $row[1];
				$temp['description'] =  $row[2];
				$temp['type'] =  $row[3];
				$temp['date'] =  $row[4];
				$temp['image_address'] =  $row[5];
				$temp['owner_id'] =  $row[6];
				$temp['cat_id'] = $row[7];
				$temp['name'] = $row[9];
				$temp['user_image'] = $row[10];	
				array_push($temper,$temp);
			}	
			$response = array();
			if(count($temper) == 0){
				$response['post'] = 'nothing';
			}else{
				$response['post'] = $temper;
			}
			$response['comments'] = $this->getCommentsByID($id);
			return $response;
		}
		
		public function getPostForEditing($id , $owner){
			$id = strip_tags($id);
			$owner = strip_tags($owner);
			
			$responseObj = new Response();
			
			if($this->isPostOwner($id , $owner)){
				$stmt = $this->con->prepare("SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address,cat.name,cat.approved as category_name FROM posts p left join users u on p.owner_id = u.ID left join category cat on p.cat_id = cat.ID WHERE p.ID=?");
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$temp = array();
				$result = $stmt->get_result();
				$id = null;
				while ($row = $result->fetch_array(MYSQLI_NUM)) {
					
					$temp['ID'] =  $row[0];
					$id = $row[0];
					$temp['title'] =  $row[1];
					$temp['description'] =  $row[2];
					$temp['type'] =  $row[3];
					$temp['date'] =  $row[4];
					$temp['image_address'] =  $row[5];
				
					$temp['cat_id'] = $row[7];
					$temp['name'] = $row[9];
					$temp['user_image'] = $row[10];	
					$temp['cat_name'] = $row[11];
					$temp['cat_approved'] = $row[12];					
				}
				$responseObj->setError(false);	
				$responseObj->setMessage('found');	
				$responseObj->setContent($temp);
				
			}else{
				$responseObj->setError(true);	
				$responseObj->setMessage('You are not authorized to edit this post');	
				$responseObj->setContent(null);
			}			
			return $responseObj;
		}
		
		
		public function getCommentCountByID($id){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT count(ID) from comments where post_id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temp = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				return $row[0];
			}
			return -99;
		}
		
		public function getCategoryPostCount($id){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT count(ID) from posts WHERE cat_id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temp = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				return $row[0];
			}
			return -99;
		}
		
		public function getSpecificProductByID($id){
			$id = strip_tags($id);
			
			$responseObj = new Response();
			$stmt = $this->con->prepare("SELECT * from products WHERE ID=?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp = array();
				$temp['ID'] =  $row[0];
				$temp['name'] =  $row[1];
				$temp['price'] =  $row[2];
				$temp['picture'] =  $row[3];
				$temp['quantity'] =  $row[4];
				$temp['visible'] =  $row[5];
				$temp['Category'] =  $row[6];
				$temp['featured'] = $row[7];
			}
			 $responseObj->setContent($temp);	
			return $responseObj;
		}
		
		public function getUserDataById($email){
			$email = strip_tags($email);
			
			$id = $this->getUID($email);
			$responseObj = new Response();
			$res = array();
			$stmt = $this->con->prepare("SELECT * from users WHERE ID=?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$temp = array();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$temp['profile'] = $row[6];
			}
			 $responseObj->setContent($temp);	
			return $responseObj;
		}
		
		public function getUID($email){
			$email = strip_tags($email);
			
			$stmt = $this->con->prepare("SELECT ID FROM users WHERE email=?");
			$stmt->bind_param("s",$email);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id);
			if($stmt->num_rows >0) {
				$res = $stmt->fetch();
				return $id;
			}else{
				return null;
			}
		}
	}