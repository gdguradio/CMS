<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	include_once 'header.php';
	require_once 'scripts/background/_DBConnect_offline.php';
	$db = new DbConnect();
	$con = $db->connect();
?>
	
	
	 <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">



                            <!-- POST -->
                            <div class="post" style="padding-left:5px">
                                <form  class="form newtopic" name="former">
                                    <div class="postinfotop">
                                        <h2 style="color:#2F4F4F;font-weight:bold">We love hearing from people</h2>
                                    </div>

                                    <!-- acc section -->
                                    <div class="accsection">
                                        <div class="acccap">
                                            <div class="userinfo">&nbsp;</div>
                                            <div class="posttext"><h3>Required Fields</h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="topwrap">
                                           
                                            <div class="posttext">
                                              
												   <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <input type="text" placeholder="Full Name" class="form-control" name="namer" />
                                                    </div>
                                                </div>
												
                                                <div class="row" style="margin-top:20px">
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="text" placeholder="Email Address" class="form-control"  name="email" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>  
                                    </div><!-- acc section END -->





                                    <!-- acc section -->
                                    <div class="accsection survey">
                                     
                                        <div class="topwrap">
                                            
                                            <div class="posttext" >

                                                  <div>
													<textarea name="message" class="textareaStyle" placeholder="Your Message"  class="form-control" ></textarea>
												  </div>
												
                                            
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>  
                                    </div><!-- acc section END -->





                                   



                                    <div class="postinfobot">
                                        <div class="pull-right postreply">
                                          
                                            <div class="pull-left"><button style="background:#2F4F4F;color:white" type="submit" id="sendMessage" class="btn btn-sm">Send Message</button></div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div><!-- POST -->






                        </div>
                       <div class="col-lg-4">
			
			<div class="sidebarblock" style="padding:30px">
				<center><img src="images/user_12.jpg" class="rounded-circle" style="width:100px;height:auto"/>
					
					<div class="blocktxt">
						<strong style="font-weight:bolder;color:#2F4F4F">James Gosling<br>Android/Web Developer</strong><br><br>
						<ul class="list-inline mb-0">
							<li class="list-inline-item mr-3" title="facebook">
								<a href="https://www.facebook.com/intrusionloop">
									<i class="fa fa-facebook fa-2x fa-fw"></i>
								</a>
							</li>
							<li class="list-inline-item" title="Github">
								<a href="https://github.com/meetAhmed">
									<i class="fa fa-github fa-2x fa-fw"></i>
								</a>
							</li>
						</ul>
					</div></center>
				</div>
				
				<div class="sidebarblock">
					<h3 style="font-weight:bolder;color:#2F4F4F">Categories</h3>
					<div class="divline"></div>
					<div class="blocktxt">
						<ul class="cats" id="categoryContainer">
							<?php 
							$result = $con->query("SELECT * from category where approved = 'true'");
							while($row = $result->fetch_assoc()){
								
								
								$stmt = $con->prepare("SELECT count(ID) from posts WHERE cat_id = ?");
								$stmt->bind_param("i", $row['ID']);
								$stmt->execute();
								$resultSet = $stmt->get_result();
								$rowSet = $resultSet->fetch_array(MYSQLI_NUM);					
								?> 
								<li>
									<form action="category_view.php" method="GET"> 
										<input type="submit" class="link_styler text-left" style="width:90%;background:white;border:none;font-size:14px;padding:0px;color:grey" value="<?php echo $row['name']?>" />
										<input type="text" name="post_id" value="<?php echo $row['ID']?>" hidden >
										<input type="text" name="name" value="<?php echo $row['name']?>" hidden ><span class="badge pull-right"><?php echo $rowSet[0]?></span>
									</form>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
				
				<div class="sidebarblock">
					<h3 style="font-weight:bolder;color:#2F4F4F">Statistics</h3>
					<div class="divline"></div>
					<div class="blocktxt">
						<ul class="cats">
							
							<?php 
							
							$postsCount = $con->query("SELECT count(ID) from posts where approved = 'true'");
							$postsCount = $postsCount->fetch_assoc();
							//$temp['postsCount'] =  $row['count(ID)'];
							
							$commentsCount = $con->query("SELECT count(ID) from comments");
							$commentsCount = $commentsCount->fetch_assoc();
							//$temp['commentsCount'] =  $row['count(ID)'];
							
							$categoryCount = $con->query("SELECT count(ID) from category where approved = 'true'");
							$categoryCount = $categoryCount->fetch_assoc();
							//$temp['categoryCount'] =  $row['count(ID)'];
							?>

							<li>Posts <span class="badge pull-right" id="categoryCounter"><?php echo $postsCount['count(ID)']?></span></li>
							<li>Categories <span class="badge pull-right" id="postCounter"><?php echo $categoryCount['count(ID)']?></span></li>
							<li>Comments <span class="badge pull-right" id="commentCounter"><?php echo $commentsCount['count(ID)']?></span></li>
						</ul>
					</div>
				</div>
				

				<div class="sidebarblock">
					<h3 style="font-weight:bolder;color:#2F4F4F">Featured Posts</h3>
					<div class="divline"></div>
					<div class="blocktxt">
						<ul class="cats" id="featuredPosts">
							<?php 
							$featuredPosts = $con->query("SELECT ID,title from posts where approved = 'true' AND featured = 'true' ORDER BY date DESC");
							while($featuredPostsRow = $featuredPosts->fetch_assoc()){
								?>
								
								<li><form action="details.php" method="GET"> 
									<input type="submit" class="link_styler text-left" style="width:90%;background:white;border:none;font-size:14px;padding:0px;" value="<?php echo $featuredPostsRow['title']?>" />
									<input type="text" name="post_id" value="<?php echo $featuredPostsRow['ID']?>" hidden >
								</form></li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
				
				<div class="sidebarblock">
					<h3 style="font-weight:bolder;color:#2F4F4F" class="link_styler"><a href="all-images.php" title="view more" style="text-decoration:none"><p style="color:grey" id="images_from_post">Images from posts</p></a></h3>
					<div class="divline"></div>
					<div class="row" style="padding:20px">
					<?php 
						$featuredPosts = $con->query("select ID,image_address from posts where type = 'image' and approved = 'true'");
						while($featuredPostsRow = $featuredPosts->fetch_assoc()){
					?>
							<div class="col-6 gallery_item" style="margin-top:5px"> 
								<a href="http://localhost/xampp/ask-medicine/details.php?post_id=<?php echo $featuredPostsRow['ID']; ?>"><div class="card mx-auto text-center image"> 
							  <img class="card-img-top" src="<?php echo "uploadedImages/".$featuredPostsRow['image_address']?>" alt="Sample Title"> 
							  </div></a>
							</div> 
					<?php
						}
					?>
					</div>
				</div>
				
			</div>
                    </div>
                </div>

 <script>

		$(document).ready(function(){

			 $('#sendMessage').on('click',function(e){
				e.preventDefault();
				name = document.former.namer.value.trim();
				email = document.former.email.value.trim();
				message = document.former.message.value.trim();
				
				if(!name || !email || !message){
					swal("Ooopps","Please provide all required fields", "error");
					return;
				}
				
				
				 $.ajax({
					type:'POST',
					url:'scripts/_foreground/_sendMessage.php',
					dataType: "json",
					data:{'name':name, 'email':email , 'message':message },
					success:function(data){
						 console.log(data);
						 len = data['message'].length;
						 suspect = '';
						 for (i=0;i<len;i++) {
							 suspect += data['message'][i] + '\n';
						}
						
						if(data.error){
							swal("Ooopps",suspect, "error");
							return;
						}else{
							  document.former.reset();	
							 swal({ 
								   title: "Congratulations",
								   text: suspect,
								   icon: "success" 
								   
									});
						} 
					},
					error: function(ts) { console.log(ts.responseText);}
				});	
			});
		});	
</script>
    
	
<?php
	include_once 'footer.php';
?>