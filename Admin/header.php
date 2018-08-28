<?php

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	require_once '../scripts/background/_DBConnect_offline.php';
	$db = new DbConnect();
	$con = $db->connect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pseudo code</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
 <script src="../vendor/jquery/sweet_alert.min.js"></script>
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/fav_icon.png" />
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/dashboard.js"></script>
  <script src="../vendor/jquery/time.js"></script>
  <style>
	.link_styler:hover{
		text-decoration:underline;
		cursor:pointer
	}
 </style>
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
   
<!-- Navigation -->
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand" style="color:black;padding-top:7px">
         Pseudo code
        </a>
      </div>
     
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu" style="color:white">Menu
        </button>
      </div>
    </nav>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
	
      <!-- partial:partials/_sidebar.html -->
	
      <nav class="sidebar sidebar-offcanvas " id="sidebar">
	  
	 	
		
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="profile-image">
                  <img src="../images/user.png" alt="profile image">
                </div>
                <div class="text-wrapper">
                  <p class="profile-name">
					<?php 
						  if(isset($_SESSION['isLogin'])){
							if($_SESSION['isLogin']){
								echo $_SESSION['name'];
							}
						}
					?>
				  
				  </p>
                  <div>
                    <small class="designation text-muted">Admin</small>
                   
                  </div>
                </div>
              </div>
             
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="console.php">
              <i class="menu-icon mdi mdi-television"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
       
          <li class="nav-item">
            <a class="nav-link" href="posts.php">
              <i class="menu-icon mdi mdi-backup-restore"></i>
              <span class="menu-title">Posts</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="comments.php">
              <i class="menu-icon mdi mdi-backup-restore"></i>
              <span class="menu-title">Comments</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="users.php">
              <i class="menu-icon mdi mdi-table"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="categories.php">
              <i class="menu-icon mdi mdi-sticker"></i>
              <span class="menu-title">Categories</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="post.php">
              <i class="menu-icon mdi mdi-sticker"></i>
              <span class="menu-title">New Post</span>
            </a>
          </li>
		   <li class="nav-item">
            <a class="nav-link" href="../scripts/_foreground/log-out.php">
              <i class="menu-icon mdi mdi-sticker"></i>
              <span class="menu-title">Log out</span>
            </a>
          </li>
        </ul>
      </nav>
	  
	  