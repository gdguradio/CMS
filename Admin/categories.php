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
					  <button type="button" class="btn btn-danger btn-sm pull-right" onclick="managePosts('deleteCategory');" style="margin:5px">Delete Category</button>
					  <button type="button" class="btn btn-danger btn-sm pull-right" onclick="managePosts('unapproveCategory');" style="margin:5px">Un-Aprrove Category</button>
					  <button type="button" class="btn btn-success btn-sm pull-right" onclick="managePosts('approveCategories');" style="margin:5px">Aprrove Category</button>
				  </div>
				</div>
			</div>
		</div>
           
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
				 
                  <div class="table-responsive">
                    <table class="table ">
                      <thead>
                        <tr>
						  <th></th>
                          <th>Category</th>
                          <th>Aprroved</th>
                        </tr>
                      </thead>
                      <tbody>
					    <?php 
							$result = $con->query('SELECT * FROM category');
							while($row = $result->fetch_assoc()){				
				    	?>
                        <tr>
						  <td><input type="checkbox" onchange="checkboxEvent(this,<?php echo $row['ID'];?>)"></td>
						  <td><?php echo $row['name']?></td>
						  <td><?php echo $row['approved']?></td>
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
					case "deleteCategory":
						messageText = "Do you want to delete "+totalPost+" categories !";
						buttonText = "delete";
					break;
					
					case "unapproveCategory":
						messageText = "Do you want to dis-approve "+totalPost+" categories !";
						buttonText = "Dis approve";
					break; 
					
					case "approveCategories":
						messageText = "Do you want to approve "+totalPost+" categories !";
						buttonText = "approve";
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