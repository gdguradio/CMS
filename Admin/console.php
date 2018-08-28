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
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class=" text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Posts</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">
							<?php 
								$result = $con->query("SELECT count(ID) from posts where approved = 'true'");
								$row = $result->fetch_assoc();
								echo $row['count(ID)'];
							?>
						</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class=" text-warning icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Comments</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">
							<?Php 
								$result = $con->query("SELECT count(ID) from comments");
								$row = $result->fetch_assoc();
								echo $row['count(ID)'];
							?>
						</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="text-success icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Categories</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">
							<?Php 
								$result = $con->query("SELECT count(ID) from category where approved = 'true'");
								$row = $result->fetch_assoc();
								echo $row['count(ID)'];
							?>
						</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <i class="text-info icon-lg"></i>
                    </div>
                    <div class="float-right">
                      <p class="mb-0 text-right">Users</p>
                      <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">
							<?Php 
								$result = $con->query("SELECT count(ID) from users where blocked = 'false'");
								$row = $result->fetch_assoc();
								echo $row['count(ID)'];
							?>
						</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <div class="row">
		   <div class="col-12 grid-margin">
		    <div class="card">
                <div class="card-body">
                  <h5 id="shower" class="card-title">Approval Required ( <span style="font-weight:normal">Approving Post will also approve the category</span> )</h5>
			    </div>
			</div>
		   </div>
		  </div>
		  
		  
          <div class="row">
		    <?php 
						$result = $con->query('SELECT p.ID,p.title,p.description,p.type,p.date,p.image_address,p.owner_id,p.cat_id,p.approved as "postApproved",u.name as "username",u.profile_address,cat.ID as "catID",cat.name"catName",cat.approved as "catApproved" FROM posts p join users u on p.owner_id = u.ID join category cat on p.cat_id = cat.ID where p.approved = "false" ORDER BY date DESC');
						$totalRows = mysqli_num_rows($result);
						while($row = $result->fetch_assoc()){				
					?>
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <div class="fluid-container">
				  
                  
					  <div class="row ">
						  <div class="ticket-details col-md-10">
							<div class="d-flex">
							  <p class="text-dark font-weight-semibold mr-2 mb-0 no-wrap"><?php echo $row['username']?></p>
							  <p class="mb-0 ellipsis proChecker link_styler" id="<?php echo $row['ID']?>"> : <?php echo $row['title']?></p>
							</div>
							<p class="text-gray ellipsis mb-2"><?php echo strip_tags($row['description']);?></p>
							<div class="row text-gray d-md-flex d-none">
							  <div class="col-4 d-flex">
								<small class="mb-0 mr-2 text-muted">Custom Category :</small>
								<small class="Last-responded mr-2 mb-0 text-muted"><?php echo $row['catName']?></small>
							  </div>
							  <div class="col-4 d-flex">
								<small class="mb-0 mr-2 text-muted">Posted on :</small>
								<small class="Last-responded mr-2 mb-0 text-muted">
									<script>
										now = new Date(parseInt('<?php echo $row['date']; ?>'));
										date = now.customFormat( "#DDD# #DD#/#MMM#/#YYYY# #h#:#mm# #AMPM#" );
										document.write(date);
									</script>
								</small>
							  </div>
							</div>
							
						  </div>
						  <div class="ticket-actions col-md-2">
							  <button type="button" class="buttonAprrove btn btn-success btn-sm" id="<?php echo $row['ID']?>">Approve Post</button><br>
						  </div>
						    </div>
				
                  </div>
                </div>
              </div>
            </div>
				<?php	  
						}
					?> 
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
       
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  
  <form action="../details.php" id="former" method="GET" hidden> 
	  <input type="text" name="post_id" id="postID">
  </form> 
  <!-- container-scroller -->
<script>

		if(<?php echo $totalRows;?> == 0){
			document.getElementById("shower").innerHTML = "No post for Approval";
		}
						
		$(document).on('click', '.proChecker', function() {
			id =  $(this).attr('id');
			//flager = $(this).parent().parent()
			document.getElementById('postID').value = id;
			$("#former").submit();
		});	
		
		$(document).on('click', '.buttonAprrove', function() {
			id =  $(this).attr('id');
			flager = $(this).parent().parent().parent().parent();
			
			
				$.ajax({
					type:'POST',
					url:'../scripts/_foreground/approvePost.php',
					dataType: "json",
					data:{'id':id},
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
							swal("Success",suspect, "success");	
							flager.remove();
						} 
					},
					error: function(ts) { }
				});	
		
		});	

</script>

  
  <?php
	include_once 'footer.php';
?>