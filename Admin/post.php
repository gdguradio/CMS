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
     <div class="col-6 col-sm-6 col-md-6 col-lg-6">
      <div class="card">
        <div class="card-body">
          <form name="former" enctype="multipart/form-data">

           <div class="form-group">
             <select name="category" id="category" required class="form-control" >
              
              <?php 
              $result = $con->query("SELECT * from category where approved = 'true'");
              while($row = $result->fetch_assoc()){			
               ?> 
               <option value="<?php echo $row['ID']?>"><?php echo $row['name']?></option>
               <?php
             }
             ?>
             <option value="custom">Custom Category</option>
           </select>
         </div>
         <div class="form-group" id="cus-category-div" style="visibility:hidden;height:0px">

          <input type="text" name="custom_cat" id="custom_cat" class="form-control" placeholder="Enter your own Category">
        </div>
        <div class="form-group">

          <input type="text" class="form-control" name="title" required  placeholder="Post Title">
        </div>
        <div class="form-group">
<input type="text" name="ID" hidden />
          <textarea name="desc" id="desc" required class="form-control" rows="6" placeholder="Post Text"></textarea>
        </div>
        <div class="custom-file">
         <input id="logo" name="image" type="file" class="custom-file-input">
         <label for="logo" name="cust_label" class="custom-file-label text-truncate">Choose Image (Optional)</label>
       </div>

       <div class="form-group" style="margin-top:10px">
        <button type="submit" class="btn btn-primary" id="uploadProduct">Post</button>
       </div>

     </form>
     
   </div>
 </div>
 <center><img id="selected-image" class="img-responsive"  style="" src="" /></center>
</div>

<script>
	$(document).ready(function(){
    $('.custom-file-input').on('change', function() { 
     let fileName = $(this).val().split('\\').pop(); 
     $(this).next('.custom-file-label').addClass("selected").html(fileName); 
     readURL(this);
     console.log('123123123');
   });
    
    $('#category').on('change', function() { 
      
     if($(this).val() == 'custom'){
      document.getElementById('cus-category-div').setAttribute("style","visibility:visible;");
      $("#custom_cat").attr("required", true);
    }else{
      document.getElementById('cus-category-div').setAttribute("style","visibility:hidden;height:0px");
      $("#custom_cat").attr("required", false);
    }
    
  });

    function readURL(input) {
     if (input.files && input.files[0]) {
       
      imagefile = input.files[0].type;
      match = ["image/jpeg","image/png","image/jpg"];
      if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))) {
       swal("Ooopps",'Please only choose the image file', "error");
       return;
     }
     
     var reader = new FileReader();
     
     reader.onload = function (e) {
       console.log(e);
       $('#selected-image').attr('src', e.target.result);
       $('#selected-image').attr('style', 'width:90%;height:auto;margin-top:20px');
       
     }
     reader.readAsDataURL(input.files[0]);
   }
 }

	 $("form").submit(function(e) {
		  
			e.preventDefault();
			//showLoading();
			//return;
			userID = <?php echo $_SESSION['ID'];?>;
			if(userID == 'none'){
				$_SESSION['log_in_redirect'] = 'post';
				$_SESSION['log_in_redirect_message'] = "You must be log in again.";
				header('location: log-in.php');
				exit();
			}else{				
				document.former.ID.value = userID;
			}
			
			
			$.ajax({
			url: "../scripts/_foreground/updateProduct.php", 
			type: "POST",             
			data: new FormData(this), 
			contentType: false,  
			dataType: "json",						
			cache: false,           
			processData:false,       
			success: function(data)  {
			   //hideLoading();
			   len = data['message'].length;
				 suspect = '';
				 for (i=0;i<len;i++) {
					 suspect += data['message'][i] + '\n';
				}
				
				if(data.error){
					//document.getElementById("error_shower").innerHTML = suspect;
					swal("Ooopps",suspect, "error");
				}else{
					
					//document.getElementById("error_shower").innerHTML = suspect;
					swal({ 
						   title: "Success",
						   text: suspect,
						   icon: "success" 
						  });
						  
					swal({ 
					   title: "Success",
					   text: suspect,
					   icon: "success" 
					  }).then(function() {
						location.reload();
					}); 
				} 
			  
			  
			  
			},
			error: function(ts) {
				//hideLoading();				
				console.log(ts.responseText);}
			});
	});


});

</script>
</div>
</div>
</div>
</div>
</div>
<?php
include_once 'footer.php';
?>