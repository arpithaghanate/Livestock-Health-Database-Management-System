<?php

function user_reg()
{
 if(isset($_POST['addusers'])){

$random_digit=rand(0000,99999);

include("wdc/dwb.php");

$u_name=$_POST['name'];
$u_pnumber=$_POST['pnumber'];
$u_age=$_POST['age'];
$u_adhar=$_POST['adharnumber'];

      $pro_pic=$_FILES['propic']['name'];
      $pro_pic_tmp=$_FILES['propic']['tmp_name'];
      $propic=$random_digit.$pro_pic;
      move_uploaded_file($pro_pic_tmp,"useri/$propic");

$u_address=$_POST['address'];
$u_itemtype=$_POST['itemtype'];


       $itempic=$_FILES['itempic']['name'];
       $itempic_tmp=$_FILES['itempic']['tmp_name'];
       $proitempic=$random_digit.$itempic;
       move_uploaded_file($itempic_tmp,"useri/$proitempic");

$u_lamount=$_POST['lamount'];
$u_lintrest=$_POST['lintrest'];
$u_month=$_POST['permonth'];
$u_annum=$_POST['perannum'];
$u_weight=$_POST['weight'];

       date_default_timezone_set("Asia/Kolkata"); 
  	    $date = date_default_timezone_set('Asia/Kolkata');     
$indiatime = date("Y-m-d H:i:s");

       $worksheeter=$con->prepare("insert into users

    (name,pnumber,age,adharnumber,pro_pic,address,weight,itemtype,itempic,lamount,lintrest,permonth,perannum,date)values
    ('$u_name','$u_pnumber','$u_age','$u_adhar','$propic','$u_weight','$u_address','$u_itemtype','$itempic','$u_lamount','$u_lintrest','$u_month','$u_annum','$indiatime')");
  
   if($worksheeter->execute()){

  echo "<script>alert('Added successfully !');</script>";
  echo"<script>window.open('Addproduct.php','_self');</script>"; 
    
   }
   else{
     echo "<script>alert('Not Added successfully !');</script>";
   }
  	
  	}

}
?>