	<?php
	if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
		session_start();
	}
	include_once 'header.php';
	
	if($_SERVER['REQUEST_METHOD']=='GET'){	
		
		if(isset($_GET['post_id'])){
			
			$_SESSION['suspectID'] = $_GET['post_id'];
			
		}
	}else{
		//echo '<script>alert("wrong method");</script>';	
	}
	
	// user your own puser file - i have removed mine - require('scripts/background/pusher_config.php');

	require_once 'scripts/background/_DBConnect_offline.php';
	$db = new DbConnect();
	$con = $db->connect();
	?>
	
	
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12" id="post_container">
				
				
				<?php 
				$stmt = $con->prepare('SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved,u.name,u.profile_address FROM posts p left join users u on p.owner_id = u.ID WHERE p.ID=? AND p.approved = "true" ORDER BY date DESC');
				$stmt->bind_param("i", $_SESSION['suspectID']);
				$stmt->execute();
				$result = $stmt->get_result();
				$postStatus = mysqli_num_rows($result);
				
				
				while ($row = $result->fetch_array(MYSQLI_NUM)) {	

					
					if(trim($row[3]) == 'text'){
						
						?>
						
						<div class="post"> 
							<div class="topwrap"> 
								
								<div class="posttext pull-left"> 
									<h2 style="font-weight:bold;color:#2F4F4F"><?php echo $row[1] ?></h2><br><br> 
									<p><?php echo $row[2] ?></p> 
								</div>  
								<div class="clearfix"></div>  
							</div>                            
							<div class="postinfobot">  
								<div class="posted pull-left"><i class="fa fa-clock-o"></i> Posted on : 
									<script>
										now = new Date(parseInt('<?php echo strip_tags($row[4]); ?>'));
										date = now.customFormat( "#DD#,#MMM# #YYYY# at #h#:#mm# #AMPM#" );
										document.write(date);
									</script> 
								</div>  
								<div class="next pull-right">                                         
									<a href="#"><i class="fa fa-share"></i></a>
								</div> 
								<div class="clearfix"></div> 
							</div> 
						</div>
						
						<?php	  
					}else{
						?>
						
						<div class="post"> 
							<div class="topwrap"> 						
								<div class="posttext pull-left"> 
									<h2 style="font-weight:bolder;color:#2F4F4F"><?php echo $row[1] ?></h2><br><br> 
									<p><?php echo $row[2] ?></p>				
									<div class="col-sm-5 col-md-5 col-lg-4 pull-right"> 
										<div class="card mx-auto d-flex text-center image"> 
											<img class="card-img-top" src="<?php echo "uploadedImages/".$row[5]?>" alt="Sample Title"> 
										</div> 
									</div> 
								</div>  
								<div class="clearfix"></div>  
							</div>                       
							<div class="postinfobot">  
								<div class="posted pull-left"><i class="fa fa-clock-o"></i> Posted on : 
									<script>
										now = new Date(parseInt('<?php echo strip_tags($row[4]); ?>'));
										date = now.customFormat( "#DD#,#MMM# #YYYY# at #h#:#mm# #AMPM#" );
										document.write(date);
									</script> 
								</div>  
								<div class="next pull-right">                                        
									<a href="#"><i class="fa fa-share"></i></a> 
								</div> 
								<div class="clearfix"></div> 
							</div> 	
						</div>							 
						
						<?php
					}
				}			
				?> 
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div id="comments_container">
					<?php
					
					if($postStatus>0){
						
						$commentStmt = $con->prepare("SELECT * FROM `comments` where post_id = ?");
						$commentStmt->bind_param("i",$_SESSION['suspectID']);
						$commentStmt->execute();
						$commentStmt->store_result();
						$commentStmt->bind_result($id,$text,$name,$post_id,$date);

						if($commentStmt->num_rows >0) {
							echo "Comments";
							
							while($commentStmt->fetch()){
								?>
								<div class="post"> 
									<div class="topwrap"> 
										<div class="posttext"> 
											<p style="padding-left:10px"><?php echo strip_tags($text); ?></p> 
										</div>  
										<div class="clearfix"></div>  
									</div>                          
									<div class="postinfobot"> 
										
										<p class="posted pull-left" style="font-weight:bolder;color:#2F4F4F;margin-right:5px"><?php echo strip_tags($name); ?></p> 
										<div class="posted pull-left"><i class="fa fa-clock-o"></i> Posted	 on : 
											<script>
												now = new Date(parseInt('<?php echo strip_tags($date); ?>'));
												date = now.customFormat( "#DD#,#MMM# #YYYY# at #h#:#mm# #AMPM#" );
												document.write(date);
											</script> 
										</div>
										
										<div class="clearfix"></div> 
									</div> 
								</div> 
								<?php
							}
						}
					}
					?>
				</div>
			</div>
		</div>
		<div class="row" id="commentAdderArea" style="visibility:hidden">
			<?php  
			
			include_once 'comment_section.php';
			
			?>
		</div>
	</div>

	<script>

		if(<?php echo $postStatus;?> == 0){
			document.getElementById('post_container').innerHTML = "Post not found";
		}else{
			document.getElementById('commentAdderArea').setAttribute("style","visibility:visible;");
		}		
	
	/*  USE YOUR OWN PUSHER FILE AND UPDATE THIS CODE 
	
		var APP_KEY = 'key';
		var pusher = new Pusher(APP_KEY, {
			cluster: 'ap2',
			encrypted: true
		});
		
		var channel = pusher.subscribe('comments');
		
		channel.bind('pusher:subscription_succeeded', function(members) {
			
		});

		channel.bind('comment-added', function(data) {
			console.log(data);
			document.getElementById('comments_container').setAttribute("style","visibility:visible;");
			toast = new iqwerty.toast.Toast();
			toast.setText("New Comment");
			toast.show();
						
			data = data['pushContents'];		
			postContent = data['text'];
			name = data['name'];
			nowC = new Date(parseInt(data['date']));
			dateC = nowC.customFormat( "#DDD# #DD#/#MMM#/#YYYY# #h#:#mm# #AMPM#" );
			console.log(dateC);
			
			commentFormatter = '<div class="post">'
					+' <div class="topwrap"> '
						+'<div class="posttext"> '
							+'<p style="padding-left:10px">'+postContent+'</p> '
						+'</div>  '
						+'<div class="clearfix"></div>  '
					+'</div> '                       
					+'<div class="postinfobot"> '
						+'<p class="posted pull-left" style="margin-right:5px">'+name+'</p> '
						+'<div class="posted pull-left"><i class="fa fa-clock-o"></i> Posted on : '+dateC+'</div> '
						+'<div class="clearfix"></div> '
					+'</div> '
				+'</div> ';
			

			$("#comments_container").append(commentFormatter);

		});
       */
		id = '<?php  
		if(isset($_SESSION["suspectID"])) { 
			echo $_SESSION["suspectID"]; 
		}else{
			echo "none";
		}
		?>';
		if(id == "none"){
			window.open('index.php', '_self');
		}


		$('#addComment').on('click',function(e){
			e.preventDefault();
			showLoading();
			message = document.former.text.value.trim();
			username = document.former.username.value.trim();
			if(!message){
				hideLoading();		
				toast = new iqwerty.toast.Toast();
				toast.setText("Please enter comment text");
				toast.show();
				return;
			}
			if(!username){
				username = "Anonymous";
			}
			$.ajax({
				type:'POST',
				url:'scripts/_foreground/addComment.php',
				dataType: "json",
				data:{'text':message, 'name':username , 'postID':id},
				success:function(data){
					hideLoading();		
					console.log(data);
					len = data['message'].length;
					suspect = '';
					for (i=0;i<len;i++) {
						suspect += data['message'][i] + '\n';
					}

					if(data.error){
						toast = new iqwerty.toast.Toast();
						toast.setText(suspect);
						toast.show();
						return;
					}else{
						document.former.reset();		
					} 
				},
				error: function(ts) { hideLoading();}
			});	
		});


		function showLoading(){
			document.getElementById("loadingGIF").style = "visibility: visible";
		}
		function hideLoading(){
			document.getElementById("loadingGIF").style = "visibility: hidden";
		}

	</script>

		<?php
			include_once 'footer.php';
		?>