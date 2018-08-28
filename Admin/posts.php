<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	if(isset($_SESSION['isLogin'])){
		if($_SESSION['isLogin']){
			if($_SESSION['role'] != 'admin'){
				header('location: ../index.php');
				exit();
			}
		}
	}else{
		header('location: ../index.php');
		exit();
	}		
	
	
	include_once 'header.php';
?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
          
		  <div class="col-12 grid-margin stretch-card">
		    <div class="card">
                <div class="card-body">
				 <div>
					  <button type="button" class="btn btn-success btn-sm pull-right" onclick="managePosts('feature');" style="margin:5px">Feature Posts</button>
					  <button type="button" class="btn btn-success btn-sm pull-right" onclick="managePosts('approve');" style="margin:5px">Approve Posts</button>
					  <button type="button" class="btn btn-danger btn-sm pull-right"  onclick="managePosts('un_feature')" style="margin:5px">unfeature Posts</button>
					  <button type="button" class="btn btn-danger btn-sm pull-right" onclick="managePosts('unapprove');" style="margin:5px">Dis approve Posts</button>
					  <button type="button" class="btn btn-danger btn-sm pull-right" onclick="managePosts('delete');" style="margin:5px">Delete Posts</button>
				  </div>
				</div>
			</div>
		</div>
           
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
				  <h4 class="card-title">All Posts</h4>
                  <div class="table-responsive">
                    <table class="table ">
                      <thead>
                        <tr>
						  <th></th>
                          <th>Post</th>
                          <th>User</th>
						  <th>Category</th>
						  <th>Approved</th>
						  <th>Featured</th>
						  <th>Featured Image</th>
                        </tr>
                      </thead>
                      <tbody>
					    <?php 
							$result = $con->query('SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.featured,p.feature_image,p.approved as "postApproved",u.name as "username",u.profile_address,cat.ID as "catID",cat.name"catName",cat.approved as "catApproved" FROM posts p join users u on p.owner_id = u.ID join category cat on p.cat_id = cat.ID ORDER BY date DESC');
							while($row = $result->fetch_assoc()){				
				    	?>
                        <tr>
						  <td><input type="checkbox" onchange="checkboxEvent(this,<?php echo $row['ID'];?>)"></td>
                          <td class="link_styler"><?php echo substr($row['title'], 0,32);?><br><br>  
								<script>
									now = new Date(parseInt('<?php echo $row['date']; ?>'));
									date = now.customFormat( "#DD# #MMM# #YYYY# #h#:#mm# #AMPM#" );
									document.write(date);
								</script> </td>
                          <td><?php echo $row['username'];?></td>
                          <td><?php echo $row['catName'];?> <br><br>(<?php echo $row['type'];?> Post)</td>
                          <td><?php echo $row['postApproved'];?></td>
						  <td><?php echo $row['featured'];?></td>
						  <td><?php echo $row['feature_image'];?></td>
                        </tr>
						<?php	  
						 }
						?> 
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
       
         
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  
  <script>
		
		arr = new Array();
		
		function containElement(id){
			for(i=0;i<arr.length;i++){
				if(arr[i] == id){
					return true;
				}
			}
			return false;
		}
		function removePostID(id){
			for(i=0;i<arr.length;i++){
				if(arr[i] == id){
					arr[i] = -99;
				}
			}
		}
		
		function checkboxEvent(item,id){
			
			if(item.checked){
				if(!containElement(id)){
					arr.push(id);
				}
			}else{
				removePostID(id);
			}	
			suspect = "";
			for(i=0;i<arr.length;i++){
				suspect += arr[i];
			}
			console.log(suspect);		
		}
		
		function managePosts(operation){
			totalPost = 0;
			IDs = "";
			for(i=0;i<arr.length;i++){
				if(arr[i] != -99){
					if(i == arr.length-1){
						IDs += arr[i];
					}else{
						IDs += arr[i] + ",";
					}
					totalPost += 1;
				}
			}
			console.log(IDs);
			if(totalPost == 0){
				swal("Ummmm","No selection is made", "info");
			}else{
				buttonText = "";
				messageText = "";
				switch(operation){
					case "delete":
						messageText = "Do you want to delete "+totalPost+" posts !";
						buttonText = "delete";
					break;
					
					case "unapprove":
						messageText = "Do you want to unApprove "+totalPost+" posts !";
						buttonText = "unApprove";
					break;
					
					case "approve":
						messageText = "Do you want to approve "+totalPost+" posts !";
						buttonText = "Approve";
					break;
					
					case 'feature':
						messageText = "Do you want to feature "+totalPost+" posts !";
						buttonText = "Feature";
					break;
					
					case 'un_feature':
						messageText = "Do you want to un-feature "+totalPost+" posts !";
						buttonText = "unfeature";
					break;
					
					default:
					break;
				}
				
				
				swal({
				  text: messageText,
				  buttons: ["Cancel", buttonText],
				}).then(function(value) {
					if(value!=null){
						console.log(IDs);
					 $.ajax({
							type:'POST',
							url:'../scripts/_foreground/managePosts.php',
							dataType: "json",
							data:{'IDs':IDs , 'operation':operation},
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
									 swal({ 
									   title: "success",
									   text: suspect,
									   icon: "success" 	 
									}).then(function() {
									   location.reload();
									});
								}
							},
							error: function(ts) { console.log(ts.responseText);}
						});
					}// if ends here 					 
				});
			}
		}
		
  </script>
  
 <?php
	include_once 'footer.php';
?>