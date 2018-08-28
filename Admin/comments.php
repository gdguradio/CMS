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
					  <button type="button" class="btn btn-danger btn-sm pull-right"  onclick="managePosts('deleteComments');"  style="margin:5px">Delete Comment</button>
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
                          <th>Comment</th>
                          <th>Made by</th>
						  <th>Post</th>
                        </tr>
                      </thead>
                      <tbody>
					    <?php 
							$result = $con->query('SELECT c.ID,c.text,c.name,c.post_id,c.date,p.title,p.description,p.date as "postDate" from comments c join posts p on c.post_id = p.ID ORDER BY date DESC');
							while($row = $result->fetch_assoc()){				
				    	?>
                        <tr>
						  <td><input type="checkbox" onchange="checkboxEvent(this,<?php echo $row['ID'];?>)"></td>
                          <td class="link_styler"><?php echo strip_tags(substr($row['text'], 0,60));?><br><br><br>  
								<script>
									now = new Date(parseInt('<?php echo $row['date']; ?>'));
									date = now.customFormat( "#DD# #MMM# #YYYY# #h#:#mm# #AMPM#" );
									document.write(date);
								</script> </td>
                          <td><?php echo $row['name'];?></td>
                          <td class="link_styler"><?php echo substr($row['title'], 0,60);?><br><br><br>  
								<script>
									now = new Date(parseInt('<?php echo strip_tags($row['postDate']); ?>'));
									date = now.customFormat( "#DD# #MMM# #YYYY# #h#:#mm# #AMPM#" );
									document.write(date);
								</script> </td>
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
					case "deleteComments":
						messageText = "Do you want to delete "+totalPost+" comments !";
						buttonText = "delete";
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