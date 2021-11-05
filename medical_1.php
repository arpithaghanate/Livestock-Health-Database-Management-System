<!DOCTYPE html>
<?php 
       session_start();
       error_reporting();
       if (!isset($_SESSION['user'])) 
        {
           header("location:index.php");
        }
      
    include 'wdc/dbconfig.php';




?>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>veterintary</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <!-- <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css"> -->
    <!-- animation css -->
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <link rel='stylesheet' href='assets/css/sweet-alert.css'>
    <link rel="stylesheet" href="assets/css/bootstrap4.min.css">
      <!-- Page level plugin CSS-->
  <link href="assets/css/dataTables.bootstrap4.css" rel="stylesheet">
      <!-- date picker -->
   <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css" /> 
   <link rel="stylesheet" href="assets/css/font-awesome4.min.css">    

    <style type="text/css">
 
    </style>
</head>

<body class="layout-6" style="background-image: url('banner.jpg')">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <!-- <div class="loader-track">
            <div class="loader-fill"></div>
        </div> -->
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ navigation menu ] start -->
   <?php
include("wdc/function.php");
include("wdc/navigation.php");

?>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
   <?php

include("wdc/header.php");

?>
    <!-- [ Header ] end -->

    <!-- [ chat user list ] start -->
      <?php

include("wdc/chatheader.php");

?>
    <!-- [ chat user list ] end -->

    <!-- [ chat message ] start -->
       <?php

include("wdc/chat.php");

?>
    <!-- [ chat message ] end -->

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
 
                
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Veterintary</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="Addproduct.php"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#!">Pharma_1</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
      
                                <!-- [ date-form ] start -->
                                <div class="col-md-12">
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Add Diseases</h5>
 <?php

$msg='';
if(isset($_POST['btn_import']))
{
  
   

    $target_file=$_FILES['importfile']['name'];
    $target_temp=$_FILES['importfile']['tmp_name'];


    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $allowedType=array('csv');

    if(!in_array($imageFileType, $allowedType))
    {


echo '<div class="alert alert-danger alert-dismissible text-center text-uppercase" style="">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<span style="font-weight:bold">not supported format...</span> 
</div>&nbsp;';
    }

    // if ($uploadOk != 0) 
    else
    {
       

                // Reading file
                $file = fopen($target_temp,"r");
                $i = 0;

                $importData_arr = array();
               

                while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($data);

                    for ($c=0; $c < $num; $c++) {
                        $importData_arr[$i][] = $data[$c];
                    }
                    $i++;
                }
                fclose($file);

                $skip = 0;
                // insert import data
                foreach($importData_arr as $data){
                    if($skip != 0){
                        
                     



                        $d_name=$data[0]; 
                        $m_name = $data[1];
                        $desc_md = $data[2];
                  
                   

     

                        // Checking duplicate entry      count(*) as allcount
                      //   $sql = $connect->prepare("select * from tbl_order where item_name='" . $item_name . "' and price='" . $price. "'  ");


                      //  $sql->execute();
                      //  $sql->setFetchMode(PDO :: FETCH_ASSOC);
                      //  $row=$sql->rowCount();
                   
                      //  echo $row;

                        // if($row == 0){
                            // Insert record
                            // $insert_query = $con->prepare("insert into add_items(item_name,price) values('".$item_name."','".$price."')");
                            $insert_query=$connect->prepare("insert into tbl_m(disease_name, medicine_name, description) values('".$d_name."', '".$m_name."', '".$desc_md."') ");
                            $insert_query->execute();
                          
                           
                          
                            


                        // }

                    }
                    $skip ++;
                }
                // $msg="inserted";
                                   echo '<div class="alert alert-success alert-dismissible text-center text-uppercase" style="width:50%;margin:auto">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<span style="font-weight:bold">file inserted...</span> 
</div>';
    
    }
}


   ?>
                                            <form action="" method="post" enctype="multipart/form-data">
                                               <input type="file" name="importfile">
                                               <button class="btn btn-primary" name="btn_import" id="btn_import">Submit</button>
                                            </form>

                                            <!-- <span class="float-right">GST NO- <b>36AFYPA4899L1ZG</b></span> -->
                                        </div>
                <div class="card-block">
                                            <!-- H,hFQrVh?QP; -->
    <!-- <div id="message">
<h3 id="import_message" class="alert alert-success text-center text-uppercase" style="display: none;"></h3>
    </div>                            -->



                            <form class="form-inline"  method="post" action="">
                              <div class="form-group">
                                <label>Diesease</label>
                                <select class="form-control" id="disease_id">
                                <option>Select Diesease</option>
                                  <?php
                                  $fetch_diesease=$connect->prepare("select * from tbl_m");
                                  $fetch_diesease->execute();
                                  $data=$fetch_diesease->fetchAll(); 
                                  foreach($data as $rows)
                                  {
                                ?>
                                     <option><?php echo $rows['disease_name']?></option>
                                <?php
                                  }
                                  ?>
                                   
                                </select>
                              </div>&nbsp;     

                              <div class="form-group" >
                              <label>Medicine Name</label>
                                <input type="text" class="form-control medicine_name" readonly>
                              </div>&nbsp; 

                              <div class="form-group">
                              <label>Description</label>
                              <textarea rows="2" class="form-control description_md" readonly></textarea>
                              </div>&nbsp;        
            

                                    </form>
                                        </div>
                                    </div>
                                
                                </div>

                                <!-- [ currency ] end -->
                            </div>  
                            <!-- [ Main Content ] end ROW-->
                       
                    
     

                                     <!-- table div end -->
                    </div>
                    </div>       <!--Main-BODY end     -->


                </div>
                          <?php

// include("wdc/footer.php");

                    ?>
            </div>
        </div>
    </div>
 

                                          <!-- Update Modal -->
 <div class="modal fade"  id="update_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="update_form">
                <div class="modal-header">
                     <h4 class="modal-title">UPDATE DATA</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 
                </div>

                <div class="modal-body">
                                  <!--  <div class="form-group">
                                        <label class="form-label">Update Items</label>
                                        <input type="text" id="up_add_items"   class="form-control" name="up_add_items" placeholder="Name of Items" data-parsley-pattern="^[a-zA-Z0-9]+$" data-parsley-trigger="keyup" required>
                                    </div> -->
                            
                         
                                    <div class="form-group">
                                        <label class="form-label">Update Sale Invoice</label>
                                        <input type="text" id="up_sale_invoice"  class="form-control" name="up_sale_invoice" placeholder="Enter Sale Inovice" data-parsley-pattern="^[a-zA-Z0-9]+$" data-parsley-trigger="keyup" required>
                                    </div>
                    
 
                                     <div class="form-group">
                                        <label class="form-label">Update Day Book of Sale</label>
                                        <input type="date" id="up_date"  class="form-control" name="up_date" placeholder="" data-parsley-type="date" required>
                                    </div>
                                
                                   <div class="form-group">
                                        <label class="form-label">Update Tax Wise Sale</label>
                                        <select class="form-control" id="up_tax_sale" name="up_tax_sale" placeholder="" required>
                                             <option value="" selected="selected">Select Tax Slab</option>
                                             <option>0%</option>
                                             <option>5%</option>
                                             <option>12%</option>
                                             <option>18%</option>
                                             <option>28%</option>
                                        </select>    
                                    </div>
                </div>

                                                           
         
        
                <div class="modal-footer">
      <button type="submit" class="btn btn-success"  onclick="update_data()">Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      <input type="hidden" name="" id="hidden_user_id">              
                </div>

            </form>                         
        </div>    
      </div>
    </div>
                                   <!-- ENd Update Modal -->    
                     
         <!-- <script src="assets/js/vendor-all.min.js"></script> -->
       <script src="assets/js/jquery3.min.js"></script>
         <!-- Popper JS -->
<script src="assets/js/popper.min.js"></script>
     <script src="assets/js/parsley.js"></script>
     <script src='assets/js/sweet-alert.min.js'></script>


    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="assets/js/pcoded.min.js"></script> -->

                <!-- Page level plugin JavaScript-->
            <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->
            <script src="assets/js/jquery.dataTables.js"></script>
            <script src="assets/js/dataTables.bootstrap4.js"></script>
            <script src="assets/js/csvExport.js"></script>
            <!-- <script src="assests/js/datatables/csvExport.js"></script> -->

    <!-- Input mask Js -->
    <script src="assets/plugins/inputmask/js/inputmask.min.js"></script>
    <script src="assets/plugins/inputmask/js/jquery.inputmask.min.js"></script>
    <script src="assets/plugins/inputmask/js/autoNumeric.js"></script>

    <!-- form-picker-custom Js -->
    <script src="assets/js/pages/form-masking-custom.js"></script>
    <!-- date pickter -->
       <script src="assets/js/bootstrap-datepicker.js"></script>
       <!--excel file  -->
     <script src="assets/js/custom_excel.js"></script>  

<script type="text/javascript">
  $(document).ready(function(){
    $('.dataTable').DataTable();
    // {

    //    "ajax" : {
    //  url:"backend_2.php",
    //  type:"POST"
    // },
    // drawCallback:function(settings)
    // {
    //  $('#total_order').html(settings.json.total);
    // }

    
    // };

    // $('#dataTable input')
    // .unbind('keypress keyup')
    // .bind('keypress keyup', function(e){
    //   if ($(this).val().length < 3 && e.keyCode != 13) return;
    //   myTable.fnFilter($(this).val());
    // });

    $('.sale_form').parsley();

     $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  // format:"dd-mm-yyyy",
  autoclose: true
 });
    // alert('loading');
 
 });
            // document END 

 // Delete

function deleteuser(deleteid)
      {
        // alert('ok');
        var cnf=confirm("Are you sure to Delete");
        if(cnf==true)
        $.ajax({
          url:"backend.php",
          type:"post",
          data:{deleteid:deleteid},
          success:function(data,status)
          {
         
            setTimeout(function(){
           location.href="Sale_Report.php"; 
                  }, 2000);
            $("#delete_message").show();
             $('#delete_message').fadeIn().html(data);  
                          setTimeout(function(){  
                               $('#delete_message').fadeOut("Slow");  
                          }, 2000); 
          }
        });
      }         

// Update

   function getuserdetails(id)
   {
       $('#hidden_user_id').val(id);
       $.post("backend.php", {
               id:id
       },function(data, status)
            {
              var user = JSON.parse(data);
              $('#up_add_items').val(user.add_items);
              $('#up_sale_invoice').val(user.sale_invoice);  
              $('#up_date').val(user.day_book_sale);
              $('#up_tax_sale').val(user.tax_sale);
         
            }
            );
              $('#update_modal').modal('show');
   }
    


$('#update_form').on("submit", function(event){  
          event.preventDefault();  
     var up_add_items=$('#up_add_items').val();
     var up_sale_invoice=$('#up_sale_invoice').val();
     var up_date=$('#up_date').val();
     var up_tax_sale=$('#up_tax_sale').val();


     var hidden_user_id=$('#hidden_user_id').val();

         
     $.post("backend.php",{
      hidden_user_id:hidden_user_id,    
      up_add_items:up_add_items,
      up_sale_invoice:up_sale_invoice,
      up_date:up_date,
      up_tax_sale:up_tax_sale

                         },
                         function(data)
                         {  
                            $('#update_modal').modal('hide');
                            // alert(data);
                           
                             setTimeout(function(){
                                 location.href="Sale_Report.php"; 
                            }, 2000);
                          $("#update_message").show();
                          $('#update_message').fadeIn().html(data);  
                          setTimeout(function(){  
                              $('#update_message').fadeOut("Slow");  
                          }, 2000);  

                         }
          );
            
        });    
              //  Date selected

 $(function(){
  // alert('ok');
    $("#start_date").datepicker();
    $("#end_date").datepicker();
  });
  $('#search').click(function(){
    // alert('ok');
    var From = $('#start_date').val();
    var to = $('#end_date').val();
    if(From != '' && to != '')
    {
      $.ajax({
        url:"backend.php",
        method:"POST",
        data:{From:From, to:to},
        success:function(data)
        {
          $('.date_order').html(data);
        }
      });
    }
    else
    {
      alert("Please Select the Date");
    }
  });             

                //  DELETE SELECTED

 
      $('#checkAll').click(function(){
         if(this.checked){
             $('.checkbox').each(function(){
                this.checked = true;
             });   
         }else{
            $('.checkbox').each(function(){
                this.checked = false;
             });
         } 

       });
    


    $('#delete').click(function(){
       var dataArr  = new Array();
       if($('input:checkbox:checked').length > 0){
          $('input:checkbox:checked').each(function(){
              dataArr.push($(this).attr('id'));
              $(this).closest('tr').remove();
          });
          sendResponse(dataArr)
       }else{
         alert('No record selected ');
       }

    });  


    function sendResponse(dataArr)
     {
        $.ajax({
            type    : 'post',
            url     : 'backend.php',
            data    : {'data' : dataArr},
            success : function(response){
                          setTimeout(function(){
           location.href="Sale_Report.php"; 
      }, 2000);
                        $('#selected_message').show();   
                        $('#selected_message').fadeIn().html(response);  
                          setTimeout(function(){  
                               $('#selected_message').fadeOut("Slow");  
                          }, 2000); 
                      },
            error   : function(errResponse){
                      window.location="Sale_Report.php";
                      }                     
        });
    }
 // $('#search').click(function(){

 //  var start_date = $('#start_date').val();
 //  var end_date = $('#end_date').val();
 //  if(start_date != '' && end_date !='')
 //  {
 //   $('#order_data').DataTable().destroy();
 //   fetch_data('yes', start_date, end_date);
 //  }
 //  else
 //  {
 //   alert("Both Date is Required");
 //  }
 // }); 

 // fetch_data('no');

 // function fetch_data(is_date_search, start_date='', end_date='')
 // {
 //   // alert('okay');
 //  var dataTable = $('#dataTabl').DataTable({
 //   "processing" : true,
 //   "serverSide" : true,
 //   "order" : [],
 //   "ajax" : {
 //    url:"Sale_Report.php",
 //    type:"POST",
 //    data:{
 //     is_date_search:is_date_search, start_date:start_date, end_date:end_date
 //    }
 //   }
 //  });
 // }
     $( "#export" ).click(function() {
          $('table').csvExport();
        });


// pharama

$("#disease_id").on("change", function(){

var disease_name=$(this).val();
// alert(disease_name);
$.ajax({
type: "POST",
url: "backend.php",
data:{disease_name:disease_name},

success: function(data){
// alert(data);
var user=JSON.parse(data);

$(".medicine_name").val(user.medicine_name);
$(".description_md").val(user.description);
}
});

})

</script>


</body>
</html>
