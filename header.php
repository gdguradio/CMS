<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Pseudo code</title>

	<!-- Bootstrap -->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="vendor/jquery/sweet_alert.min.js"></script>
	<script src="vendor/jquery/time.js"></script>
	<script src="vendor/jquery/pusher.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>	
	<script src="vendor/jquery/toaster.js"></script>
	
	<!-- icons -->
	<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- custom style -->
	<link href="vendor/css/css-template.css" rel="stylesheet" type="text/css">
	<link href="vendor/css/gallery_hover.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="images/fav_icon.png" />
</head>

<body>

	<!-- Navigation -->
	<nav class="navbar navbar-expand-lg fixed-top headernav">
		<div class="container">
			<a class="navbar-brand" href="index.php"><strong style="font-weight:bolder;color:#2F4F4F">Pseudo code</strong></a>
			<button class="navbar-toggler navbar-toggler-right"  type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon">   
					<i class="fa fa-navicon"></i>
				</span>
			</button>
			
			
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link js-scroll-trigger" href="index.php"><strong style="font-weight:bolder;color:#2F4F4F">BLOG</strong></a>
					</li>
					<li class="nav-item">
						<a class="nav-link js-scroll-trigger" href="contact.php"><strong style="font-weight:bolder;color:#2F4F4F">CONTACT</strong></a>
					</li>
					<li class="nav-item">
						<i class="nav-link js-scroll-trigger fa fa-shopping-cart fa-fw" id="cartButton"  data-toggle="modal" data-target="#myModal" style="color:white;font-size:22px;"></i>
					</li>
				</ul>
				
				<ul class="navbar-nav avt">
				<li>
					<form class="form" method="get" id="searchForm" action="search.php">
						<div class="pull-left txt search"><input type="text" id="target" name="keyword" class="form-control" style="fcolor:#2F4F4F" placeholder="Search"></div>
						<div class="clearfix"></div>
					</form>
				</li>
            </ul>
			</div>
		</div>

	</nav>
	
	<div style="margin-top:100px"></div>
	
	<script>
	$( "#target" ).keypress(function(e) {
		if(e.which == 13) {
			 $("#searchForm").submit();
		}
	});
</script>