<?php
	if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
		session_start();
	}
	
	if(isset($_SESSION['isLogin'])){
		if($_SESSION['isLogin']){
			if($_SESSION['role'] == 'admin'){
				header('location: Admin/console.php');
				exit();
			}
		}
	}	
	
	include_once 'header.php';
?>
	
	 <div class="container" style="margin-top:70px">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">
							
                            <!-- POST -->
                            <div class="post" style="padding-left:5px">
                                <form name="former" class="form newtopic">
                                   

                                    <!-- acc section -->
                                    <div class="accsection">
                                       
                                        <div class="topwrap">
                                           
                                            <center><div class="posttext">
                                              
											   <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <input type="text" placeholder="User name" required class="form-control" id="pass" name="username" />
                                                    </div>
                                                </div>
												
                                                <div class="row" style="margin-top:20px">
                                                    
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" placeholder="Password" required class="form-control" id="pass2" name="password" />
                                                    </div>
                                                </div>

                                            </div></center>
                                            <div class="clearfix"></div>
                                        </div>  
                                    </div><!-- acc section END -->

                                    <div class="postinfobot">

                                     
                                   
                                      
                                        <div class="pull-right postreply">
                                         
                                            <div class="pull-left"><button type="submit" id="accessAccount" class="btn btn-primary">Log in</button></div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div><!-- POST -->

						






                        </div>
                       
                    </div>
                </div>

 
	<script>
		$(document).ready(function(){

			 $('#accessAccount').on('click',function(e){
				
				username = document.former.username.value.trim();
				password = document.former.password.value.trim();
				
				e.preventDefault();
				
				if(!username || !password){
					swal("Ooopps","Please provide the all fields", "error");
				    return;
				}
				
				 $.ajax({
					type:'POST',
					url:'scripts/_foreground/_login.php',
					dataType: "json",
					data:{'email':username , 'password':password},
					success:function(data){
						
						 len = data['message'].length;
						 suspect = '';
						 for (i=0;i<len;i++) {
							 suspect += data['message'][i] + '\n';
						}
						
						if(data.error){
							swal("Ooopps",suspect, "error");
							 return;
						}else{
							 swal({ 
								   title: "Congratulations",
								   text: suspect,
								   icon: "success" 
								   
									}).then(function() {
									    document.former.reset();	
										window.open('Admin/console.php', '_self');
							});
							
						} 
					}
				}); // ajax calls ends here 	
			}); // button onclick ends here 
		});// jquery parent ends here 
	</script>
	
	
<?php include_once 'footer.php'; ?>
	
  </body>
</html>