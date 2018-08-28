<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	if(isset($_SESSION['isLogin'])){
		if($_SESSION['isLogin']){
			if($_SESSION['role'] == 'customer'){
				header('location: index.php');
				exit();
			}else if($_SESSION['role'] == 'admin'){
				header('location: index.php');
				exit();
			}
		}
	}	
	
	include_once 'header.php';
?>
	
	
	 <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">



                            <!-- POST -->
                            <div class="post" style="padding-left:5px">
                                <form  class="form newtopic" name="former">
                                    <div class="postinfotop">
                                        <h2>Create New Account</h2>
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
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="text" placeholder="User name" class="form-control"  name="username" />
                                                    </div>
                                                </div>
												
                                                <div class="row" style="margin-top:20px">
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" placeholder="Password" class="form-control"  name="password" />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" placeholder="Retype Password" class="form-control" name="repass" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>  
                                    </div><!-- acc section END -->





                                    <!-- acc section -->
                                    <div class="accsection survey">
                                        <div class="acccap">
                                            <div class="userinfo">&nbsp;</div>
                                          <h3>Small Survey ( Optional )</h3>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="topwrap">
                                            
                                            <div class="posttext" >

                                                  <div>
													<textarea name="about" class="textareaStyle" placeholder="Tell us about yourself"  class="form-control" ></textarea>
												  </div>
												
                                            
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>  
                                    </div><!-- acc section END -->





                                   



                                    <div class="postinfobot">

                                       

                                        <div class="pull-left lblfch">
                                            <label for="note"> I agree with the Terms and Conditions of this site</label>
                                        </div>

                                        <div class="pull-right postreply">
                                          
                                            <div class="pull-left"><button type="submit" id="registerAccount" class="btn btn-primary">Sign Up</button></div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div><!-- POST -->






                        </div>
                        <?php include_once 'side_bar.php';?>
                    </div>
                </div>

 <script>

		$(document).ready(function(){

			 $('#registerAccount').on('click',function(e){
				e.preventDefault();
				name = document.former.namer.value.trim();
				email = document.former.username.value.trim();
				password = document.former.password.value.trim();
				repass = document.former.repass.value.trim();
				about = document.former.about.value.trim();
				
				if(!name || !email || !password || !repass){
					swal("Ooopps","Please provide all required fields", "error");
					return;
				}else {
					if(password != repass){
						swal("Ooopps","Passwords do not match", "error");
						return;
					}
				}
				
				if(!about){
					about="none";
				}
				
				 $.ajax({
					type:'POST',
					url:'scripts/foreground/_reg.php',
					dataType: "json",
					data:{'name':name, 'username':email , 'password':password , 're-pass':repass , 'role':'customer','about':about},
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
						
							$.ajax({
							  type:'POST',
							  url: "scripts/foreground/sessionSetter.php",
							  dataType: "json",
							  data: { 'username': 'undefined' , 'name':data['data']['name'] , 'role':data['data']['role'] , 'profileAddress':data['data']['profileAddress'] , 'ID':data['data']['ID'] , 'about':data['data']['about']   }
							});
							
							 swal({ 
								   title: "Congratulations",
								   text: suspect,
								   icon: "success" 
								   
									}).then(function() {
									    document.former.reset();		
										text = '<?php  
												if(isset($_SESSION["log_in_redirect"])) { 
													echo $_SESSION["log_in_redirect"]; 
													unset($_SESSION["log_in_redirect"]);
												}else{
													echo "hide";
												}
												?>';

										if(text.trim() == 'post'){
											window.open('post.php', '_self');
											return;
										}else{
											window.open('index.php', '_self');
											return;
										}
							});
						} 
					},
					error: function(ts) { console.log(ts.responseText);}
				});	
			});
		});	
</script>
    
	

<?php include_once 'header.php'; ?>