<!DOCTYPE html>
<html lang="en">

<head><meta https-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin Panel - Signin</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
   <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style type="text/css">
     input.parsley-success
 {
   color: #468847;
   background-color: #DFF0D8;
   border: 1px solid #D6E9C6;
 }

 input.parsley-error
 {
   color: #B94A48;
   background-color: #F2DEDE;
   border: 1px solid #EED3D7;
 }
  .parsley-errors-list {
   margin: 2px 0 3px;
   padding: 0;
   list-style-type: none;
   font-size: 0.9em;
   line-height: 0.9em;
   opacity: 0;
   color:#ff0000;

   transition: all .3s ease-in;
   -o-transition: all .3s ease-in;
   -moz-transition: all .3s ease-in;
   -webkit-transition: all .3s ease-in;
 }

 .parsley-errors-list.filled {
   opacity: 1;
 }
 
 .parsley-type, .parsley-required, .parsley-equalto{
  font-size: 14px;
  margin-top: 5px;  
  color:#ff0000;
 }
 label{
    float: left;
 }
</style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="auth-bg">
                <span class="r"></span>
                <span class="r s"></span>
                <span class="r s"></span>
                <span class="r"></span>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <?php  
                if(isset($_GET["action"]) == "register")  
                {  
                ?>  
                <h3 align="center">Register</h3>  
                <br />  
                <div class="message">
                    <h3 id="register_message" class="alert alert-success text-center" style="display: none;"></h3>
                    
                </div>
                <form class="register_form" data-parsley-validate>  
                    <div class="form-group">
                     <label for="username">Enter User Name</label>  
                     <input type="text" name="username" id="username" placeholder="Enter Username" data-parsley-pattern="^[a-zA-Z]+$" data-parsley-trigger="blur"  class="form-control" required />  
                     </div>
                    
                     <div class="form-group">
                     <label for="emailid">Enter Email ID</label>  
                     <input type="text" name="emailid" id="emailid" data-parsley-type="email" data-parsley-trigger="blur" placeholder="Enter a valid e-mail" class="form-control" required/>  
                     </div>

                     <div class="form-group">
                        <label for="password">Enter Password</label>  
                        <input type="password" name="password" id="password" placeholder="Password"  data-parsley-length="[4, 20]" data-parsley-trigger="blur" class="form-control" required/>  
                     </div>

                     <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>  
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"data-parsley-equalto="#password" data-parsley-trigger="blur"  class="form-control" required />  
                     </div>
                     
                   
                     <button  name="register" class="btn btn-info" />Register</button> 
                    
                     <p align="center">Login <a href="index.php" style="text-decoration: underline;color: blue;">(Click HERE)</a></p>  
                </form>  
                <?php       
                }  
                else  
                {  
                ?>  
                <h3 align="center">Login</h3>  
                <br />  
                <form class="login_form" data-parsley-validate>
                  <div class="message">
                       <h3 id="login_error_message" class="alert alert-danger text-center" style="display: none;"></h3>
                  </div>  
                     <div class="form-group">
                     <label for="login_email">Enter Email</label>  
                     <input type="text" name="login_email" id="login_email" data-parsley-type="email" data-parsley-trigger="blur" class="form-control" placeholder="Your Email ID" required 
                     data-parsley-type="email" 
                     />
                     </div> 

                     <div class="form-group">  
                     <label for="login_password">Enter Password</label>  
                     <input type="password" name="login_password" id="login_password"  class="form-control" placeholder="Your Password" required 
                     data-parsley-trigger="blur"
                      data-parsley-length="[4, 20]"  
                     />  
                     </div>
                     <br/>
                     <button  name="login" class="btn btn-info" />Login</button>   
                     <br />  
                     <p align="center">To Register <a href="index.php?action=register" style="text-decoration: underline;color: blue;">(Click HERE)</a></p>  
                </form>  
                <?php  
                }  
                ?> 
         </div>
         </div>
  




</div><!-- auth-content end -->
</div>


    <!-- Required Js -->
    
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
     <script src="https://parsleyjs.org/dist/parsley.js"></script>
 
    <script type="text/javascript">
       $(document).ready(function(){
      $('.register_form').parsley();

$('.register_form').on("submit", function(e){
    // alert('okay register_form');
    e.preventDefault();

    if($('.register_form').parsley().isValid())
     {
       $.ajax({
             url:"backend.php",
             method:"post",
             data:$(this).serialize(),
             success:function(data)
             {
                 setTimeout(function(){
                                 location.href="index.php"; 
                            }, 2000);
                          $(".register_form")[0].reset();
                          $("#register_message").show();
                          $('#register_message').fadeIn().html(data);  
                          setTimeout(function(){  
                              $('#regiser_message').fadeOut("Slow");  
                          }, 2000);  

                // alert(data);
                // $("#register_message").html(data);

             }
       });
     }
});

$('.login_form').on("submit", function(e){
         e.preventDefault();
  if($('.login_form').parsley().isValid())
     {     
         $.ajax({
            url:'backend.php',
            method:"post",
            data:$(this).serialize(),
            success:function(data){
                
                 if (data==1) 
                 {
                    $("#login_error_message").hide();
                    location.href="medical_1.php";
                 }
                 else
                 {
                           
                          $(".login_form")[0].reset();
                          $("#login_error_message").show();
                          $('#login_error_message').fadeIn().html(data);  
                          setTimeout(function(){  
                              $('#login_error_message').fadeOut("Slow");  
                          }, 2000);  
                            setTimeout(function(){
                                 location.href="index.php"; 
                            }, 3000);
                        //  alert(data);
                         
                 }
            }
         })
     }
})
 
   });
  </script>
</body>
</html>





              