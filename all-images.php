<?php
	if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
		session_start();
	}
	include_once 'header.php';
	require_once 'scripts/background/_DBConnect_offline.php';
	$db = new DbConnect();
	$con = $db->connect();
?>

<div class="container">
		<div class="row">
			<div class="col-lg-8" > 
				<div class="row" id="post_container">
				
					<?php 
						$featuredPosts = $con->query("select ID,image_address from posts where type = 'image' and approved = 'true'");
						while($featuredPostsRow = $featuredPosts->fetch_assoc()){
					?>
						<div class="col-sm-4 col-md-4 col-lg-4 mb-4 gallery_item"> 
							  <a href="http://localhost/xampp/ask-medicine/details.php?post_id=<?php echo $featuredPostsRow['ID']; ?>"><div class="card mx-auto text-center image"> 
							  <img class="card-img-top" src="<?php echo "uploadedImages/".$featuredPostsRow['image_address']?>" alt="Sample Title"> 
							  </div></a>
						</div> 
					<?php
						}
					?>
				
				</div>
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
				
			</div>
		</div><!-- row -->
	</div><!-- container -->
<?php
	include_once 'footer.php';
?>
