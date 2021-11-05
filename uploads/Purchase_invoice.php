<?php
  //invoice.php  

 // session_start();
     // if (!isset($_SESSION['user'])) 
     //  {
     //     header("location:index.php");
     //  }

 include 'wdc/dbconfig.php';
 error_reporting(E_ALL);
 $statement = $connect->prepare("
    SELECT * FROM tbl_purchase 
    ORDER BY p_id DESC
  ");

  $statement->execute();

  $all_result = $statement->fetchAll();

  $total_rows = $statement->rowCount();

  if(isset($_POST["create_invoice"]))
  { 
    $p_total_before_tax = 0;
    $p_total_tax1 = 0;
    $order_total_tax2 = 0;
    $order_total_tax3 = 0;
    $p_total_tax = 0;
    $p_total_after_tax = 0;
    $statement = $connect->prepare("
      INSERT INTO tbl_purchase 
        (p_no, p_date, p_company_name, p_company_gst, p_total_before_tax, p_total_tax1,  p_total_tax, p_total_after_tax, p_datetime)
        VALUES (:p_no, :p_date, :p_company_name, :p_company_gst, :p_total_before_tax, :p_total_tax1, :p_total_tax, :p_total_after_tax, :p_datetime)
    ");
    $statement->execute(
      array(
          ':p_no'               =>  trim($_POST["p_no"]),
          ':p_date'             =>  trim($_POST["p_date"]),
          ':p_company_name'          =>  trim($_POST["p_company_name"]),
          ':p_company_gst'       =>  trim($_POST["p_company_gst"]),
          ':p_total_before_tax'       =>  $p_total_before_tax,
          ':p_total_tax1'           =>  $p_total_tax1,
          ':p_total_tax'            =>  $p_total_tax,
          ':p_total_after_tax'        =>  $p_total_after_tax,
          ':p_datetime'           =>  date("Y-m-d")
      )
    );

      $statement = $connect->query("SELECT LAST_INSERT_ID()");
      $p_id = $statement->fetchColumn();

      for($count=0; $count<$_POST["total_item"]; $count++)
      {
        $p_total_before_tax = $p_total_before_tax + floatval(trim($_POST["p_item_actual_amount"][$count]));

        $p_total_tax1 = $p_total_tax1 + floatval(trim($_POST["p_item_tax1_amount"][$count]));


        $p_total_after_tax = $p_total_after_tax + floatval(trim($_POST["order_item_final_amount"][$count]));

        $statement = $connect->prepare("
          INSERT INTO tbl_purchase_item 
          (p_id, item_name, p_item_quantity, p_item_actual_amount, p_item_tax1_rate, p_item_tax1_amount,  order_item_final_amount)
          VALUES (:p_id, :item_name, :p_item_quantity, :p_item_actual_amount, :p_item_tax1_rate, :p_item_tax1_amount,  :order_item_final_amount)
        ");

        $statement->execute(
          array(
            ':p_id'               =>  $p_id,
            ':item_name'              =>  trim($_POST["item_name"][$count]),
            ':p_item_quantity'          =>  trim($_POST["p_item_quantity"][$count]),
            // ':p_item_price'           =>  trim($_POST["p_item_price"][$count]),
            ':p_item_actual_amount'       =>  trim($_POST["p_item_actual_amount"][$count]),
            ':p_item_tax1_rate'         =>  trim($_POST["p_item_tax1_rate"][$count]),
            ':p_item_tax1_amount'       =>  trim($_POST["p_item_tax1_amount"][$count]),
            ':order_item_final_amount'        =>  trim($_POST["order_item_final_amount"][$count])
          )
        );
      }
      $p_total_tax = $p_total_tax1 + $order_total_tax2 + $order_total_tax3;

      $statement = $connect->prepare("
        UPDATE tbl_purchase 
        SET p_total_before_tax = :p_total_before_tax, 
        p_total_tax1 = :p_total_tax1, 
        p_total_tax = :p_total_tax, 
        p_total_after_tax = :p_total_after_tax 
        WHERE p_id = :p_id
      ");
      $statement->execute(
        array(
          ':p_total_before_tax'     =>  $p_total_before_tax,
          ':p_total_tax1'         =>  $p_total_tax1,
          ':p_total_tax'          =>  $p_total_tax,
          ':p_total_after_tax'      =>  $p_total_after_tax,
          ':p_id'             =>  $p_id
        )
      );
      header("location:Purchase_invoice.php");
  }

  if(isset($_POST["update_invoice"]))
  {
    $p_total_before_tax = 0;
      $p_total_tax1 = 0;
      $p_total_tax = 0;
      $p_total_after_tax = 0;
      
      $p_id = $_POST["p_id"];
      
      
      
      $statement = $connect->prepare("
                DELETE FROM tbl_purchase_item WHERE p_id = :p_id
            ");
            $statement->execute(
                array(
                    ':p_id'       =>      $p_id
                )
            );
      
      for($count=0; $count<$_POST["total_item"]; $count++)
      {
        $p_total_before_tax = $p_total_before_tax + floatval(trim($_POST["p_item_actual_amount"][$count]));
        $p_total_tax1 = $p_total_tax1 + floatval(trim($_POST["p_item_tax1_amount"][$count]));
        $p_total_after_tax = $p_total_after_tax + floatval(trim($_POST["order_item_final_amount"][$count]));
        $statement = $connect->prepare("
          INSERT INTO tbl_purchase_item 
          (p_id, item_name, p_item_quantity, p_item_actual_amount, p_item_tax1_rate, p_item_tax1_amount,  order_item_final_amount) 
          VALUES (:p_id, :item_name, :p_item_quantity, :p_item_actual_amount, :p_item_tax1_rate, :p_item_tax1_amount, :order_item_final_amount)
        ");
        $statement->execute(
          array(
            ':p_id'                 =>  $p_id,
            ':item_name'                =>  trim($_POST["item_name"][$count]),
            ':p_item_quantity'          =>  trim($_POST["p_item_quantity"][$count]),
            // ':p_item_price'            =>  trim($_POST["p_item_price"][$count]),
            ':p_item_actual_amount'     =>  trim($_POST["p_item_actual_amount"][$count]),
            ':p_item_tax1_rate'         =>  trim($_POST["p_item_tax1_rate"][$count]),
            ':p_item_tax1_amount'       =>  trim($_POST["p_item_tax1_amount"][$count]),
            ':order_item_final_amount'      =>  trim($_POST["order_item_final_amount"][$count])
          )
        );
        $result = $statement->fetchAll();
      }
      $p_total_tax = $p_total_tax1 + $order_total_tax2 + $order_total_tax3;
      
      $statement = $connect->prepare("
        UPDATE tbl_purchase
        SET p_no = :p_no, 
        p_date = :p_date, 
        p_company_name = :p_company_name, 
        p_company_gst = :p_company_gst, 
        p_total_before_tax = :p_total_before_tax, 
        p_total_tax1 = :p_total_tax1, 
        p_total_tax = :p_total_tax, 
        p_total_after_tax = :p_total_after_tax 
        WHERE p_id = :p_id 
      ");
      
      $statement->execute(
        array(
          ':p_no'               =>  trim($_POST["p_no"]),
          ':p_date'             =>  trim($_POST["p_date"]),
          ':p_company_name'        =>  trim($_POST["p_company_name"]),
          ':p_company_gst'     =>  trim($_POST["p_company_gst"]),
          ':p_total_before_tax'     =>  $p_total_before_tax,
          ':p_total_tax1'          =>  $p_total_tax1,
          ':p_total_tax'           =>  $p_total_tax,
          ':p_total_after_tax'      =>  $p_total_after_tax,
          ':p_id'               =>  $p_id
        )
      );
      
      $result = $statement->fetchAll();
            
      header("location:Purchase_invoice.php");
  }

  if(isset($_GET["delete"]) && isset($_GET["id"]))
  {
    $statement = $connect->prepare("DELETE FROM tbl_purchase WHERE p_id = :id");
    $statement->execute(
      array(
        ':id'       =>      $_GET["id"]
      )
    );
    $statement = $connect->prepare(
      "DELETE FROM tbl_purchase_item WHERE p_id = :id");
    $statement->execute(
      array(
        ':id'       =>      $_GET["id"]
      )
    );
    header("location:Purchase_invoice.php");
  }

  ?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>purchase_invoice</title>

     
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

      <script src="assets/js/jquery3.min.js"></script>
     <script src="assets/js/jquery.dataTables.js"></script>
     <script src="assets/js/dataTables.bootstrap4.js"></script> 
      <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="assets/js/bootstrap-datepicker.js"></script>
       <!-- <script src="assets/js/typeahead.min.js"></script> -->
       <script src="assets/js/bootstrap3-typeahead.min.js"></script>

       <link href="assets/css/dataTables.bootstrap4.css" rel="stylesheet">
       <!-- fontawesome icon -->
          <link rel="stylesheet" href="assets/css/font-awesome4.min.css">
       <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">

  <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css" /> 

    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- animation css -->
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
   <link rel='stylesheet' href='assets/css/sweet-alert.css'>


 <style type="text/css">

 </style>
  </head>
  <body class="layout-6" style="background-image: url('banner.jpg')">
  
    <!-- <link rel="stylesheet" href="css/datepicker.css"> -->
    <!-- <script src="js/bootstrap-datepicker1.js"></script> -->

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


    <div class="container-fluid">
      <?php
      if(isset($_GET["add"]))
      {
      ?>
      <form method="post" id="invoice_form">
        <div class="card">
          <div class="card-header">
         
            <h4 style="margin-top:10.5px">Make Purchase</h4>
           
          </div>
          <div class="card-body">
            <div class="table-responsive">
          <table class="table table-bordered">
          
            <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-6">
                      From,<br />
                        <b>Purchase (BILL From)</b><br />
                        
                           <select name="p_company_name" id="p_company_name" class="form-control input-sm" >
                           <option>Select Company Name</option>
                           <?php
                                include 'wdc/dbconfig.php';
                               $fetch_c=$connect->prepare("select * from add_company");
                               $fetch_c->execute();
                               $numr=$fetch_c->rowCount();
                         
                                  if ($numr >0 )
                                   {
                                 
                               
                                  while ($row=$fetch_c->fetch(PDO::FETCH_ASSOC)) 
                                      {
                                    // $row_data=$row_data['company_name'];
                                       
             echo "<option value='" . $row['company_name'] . "'>" . $row['company_name'] . "</option>";    

                                       }
                                     }  
                                 ?>      
                     

                           </select><br/>


                   <input type="text" value="" name="p_company_gst" id="p_company_gst" class="form-control gst_list" palceholder="Auto Display GST Number"  readonly/>  

  
                    </div>
                    <div class="col-md-6">
                      <!-- Reverse Charge<br /> --><br /><br />
                      <input type="text" name="p_no" id="p_no" class="form-control input-sm" placeholder="Enter Invoice No." /><br/>
                      <input type="text" name="p_date" id="p_date" class="form-control input-sm" readonly placeholder="Select Invoice Date" />
                    </div>
                  </div>
                  <br />
                  <table id="invoice-item-table" class="table table-bordered">
                    <tr>
                      <th width="7%">Sr No.</th>
                      <th width="15%">Purchase Item Name</th>
                      <th width="5%">Quantity</th>
                      <!-- <th width="10%">Price</th> -->
                      <th width="10%">Actual Amt.</th>
                      <th width="12.5%" colspan="2">GST (%)</th>
   <!--                    <th width="12.5%" colspan="2">Tax2 (%)</th>
                      <th width="12.5%" colspan="2">Tax3 (%)</th> -->
                      <th width="12.5%" rowspan="2">Total</th>
                      <th width="3%" rowspan="2"></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <!-- <th></th> -->
                      <th></th>
                      <th>Rate</th>
                      <th>Amt.</th>
                   <!--    <th>Rate</th>
                      <th>Amt.</th>
                      <th>Rate</th>
                      <th>Amt.</th>  item_name[] -->
                    </tr>
                    <tr>
                      <td><span id="sr_no">1</span></td>
                      <td><input type="text" name="item_name[]" id="item_name1" class="form-control input-sm item_name_class "  placeholder="type query" autocomplete="off" /></td>
                      <!-- <div id="result"></div> -->

                      <td><input type="text" name="p_item_quantity[]" id="p_item_quantity1" data-srno="1" class="form-control input-sm p_item_quantity" /></td>

                      <td><input type="hidden" name="p_item_price[]" id="p_item_price1" data-srno="1" class="form-control input-sm number_only p_item_price" value="0" readonly />
                      <input type="text" name="p_item_actual_amount[]" id="p_item_actual_amount1" data-srno="1" class="form-control input-sm p_item_actual_amount" /></td>

                      <td><input type="text" name="p_item_tax1_rate[]" id="p_item_tax1_rate1" data-srno="1" class="form-control input-sm number_only p_item_tax1_rate" /></td>
                      <td><input type="text" name="p_item_tax1_amount[]" id="p_item_tax1_amount1" data-srno="1" readonly class="form-control input-sm p_item_tax1_amount" /></td>
                      <td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount1" data-srno="1" readonly class="form-control input-sm order_item_final_amount" /></td>
                      <td></td>
                    </tr>
                  </table>
                  <div align="right">
                    <button type="button" name="add_row" id="add_row" class="btn btn-success btn-xs">+</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td align="right"><b>Total</td>
                <td align="right"><b><span id="final_total_amt"></span></b></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="hidden" name="total_item" id="total_item" value="1" />
                  <input type="submit" name="create_invoice" id="create_invoice" class="btn btn-info" value="Save" />
                </td>
              </tr>
          </table>
        </div>
          </div>
        </div>
        
      </form>
      <script>
      $(document).ready(function(){
        var final_total_amt = $('#final_total_amt').text();
        var count = 1;    
        $(document).on('click', '#add_row', function(){
          count++;
          $('#total_item').val(count);
          var html_code = '';
          html_code += '<tr id="row_id_'+count+'">';
          html_code += '<td><span id="sr_no">'+count+'</span></td>';
          
          html_code += '<td><input type="text" name="item_name[]" id="item_name'+count+'" class="form-control input-sm item_name_class gst_list" placeholder="aaaaa" autocomplete="off" data-provide="typeahead"/></td>';
          
          html_code += '<td><input type="text" name="p_item_quantity[]" id="p_item_quantity'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_quantity "  /></td>';

          html_code += '<td><input type="hidden" name="p_item_price[]" id="p_item_price'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_price" value="0" /><input type="text" name="p_item_actual_amount[]" id="p_item_actual_amount'+count+'" data-srno="'+count+'" class="form-control input-sm p_item_actual_amount"  /></td>';

            // html_code += '<td><input type="hidden" name="p_item_price[]" id="p_item_price'+count+'" data-srno="1" class="form-control input-sm number_only p_item_price" value="0" readonly />';
            // html_code += '<input type="text" name="p_item_actual_amount[]" id="p_item_actual_amount'+count'" data-srno="1" class="form-control input-sm p_item_actual_amount" /></td>';
          
          html_code += '<td><input type="text" name="p_item_tax1_rate[]" id="p_item_tax1_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_tax1_rate" /></td>';
          html_code += '<td><input type="text" name="p_item_tax1_amount[]" id="p_item_tax1_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm p_item_tax1_amount" /></td>';
         
          html_code += '<td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_final_amount" /></td>';
          html_code += '<td><button type="button" name="remove_row" id="'+count+'" class="btn btn-danger btn-xs remove_row">X</button></td>';
          html_code += '</tr>';
          $('#invoice-item-table').append(html_code);
  
 $('.item_name_class').typeahead({
           source: function(query, result)
  {
   $.ajax({
    url:"backend.php",
    method:"POST",
    data:{query:query},
    dataType:"json",

    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }

    });


        });
        
        $(document).on('click', '.remove_row', function(){
          var row_id = $(this).attr("id");
          var total_item_amount = $('#order_item_final_amount'+row_id).val();
          var final_amount = $('#final_total_amt').text();
          var result_amount = parseFloat(final_amount) - parseFloat(total_item_amount);
          $('#final_total_amt').text(result_amount);
          $('#row_id_'+row_id).remove();
          count--;
          $('#total_item').val(count);
        });

        function cal_final_total(count)
        {
          var final_item_total = 0;
          for(j=1; j<=count; j++)
          {
            var quantity = 0;
            var price = 0;
            var actual_amount = 100;
            var tax1_rate = 0;
            var tax1_amount = 0;
            var item_total = 0;

            quantity = $('#p_item_quantity'+j).val();
            actual_amount=$("#p_item_actual_amount"+j).val();
            console.log(actual_amount);
            if(quantity > 0)
            {
              price = $('#p_item_price'+j).val();
              // if(price > 0)
              // {
                actual_amount = parseFloat(price) + parseFloat(actual_amount);
                // actual_amount=$("#p_item_actual_amount"+j).val();
                // $('#p_item_actual_amount'+j).val(actual_amount);
                tax1_rate = $('#p_item_tax1_rate'+j).val();
                if(tax1_rate > 0)
                {
                  tax1_amount = parseFloat(actual_amount)*parseFloat(tax1_rate)/100;
                  $('#p_item_tax1_amount'+j).val(tax1_amount);
                }
               
                item_total = parseFloat(actual_amount) + parseFloat(tax1_amount);
                final_item_total = parseFloat(final_item_total) + parseFloat(item_total);
                $('#order_item_final_amount'+j).val(item_total);
              // }
            }
          }
          $('#final_total_amt').text(final_item_total);
        }

        $(document).on('blur', '.p_item_actual_amount', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.p_item_tax1_rate', function(){
          cal_final_total(count);
        });


        $('#create_invoice').click(function(){
          if($.trim($('#p_company_name').val()).length == 0)
          {
            alert("Please Enter Company Name");
            return false;
          }

          if($.trim($('#p_no').val()).length == 0)
          {
            alert("Please Enter Invoice Number");
            return false;
          }

          if($.trim($('#p_date').val()).length == 0)
          {
            alert("Please Select Invoice Date");
            return false;
          }

          for(var no=1; no<=count; no++)
          {
            if($.trim($('#item_name'+no).val()).length == 0)
            {
              alert("Please Enter Item Name");
              $('#item_name'+no).focus();
              return false;
            }

            if($.trim($('#p_item_quantity'+no).val()).length == 0)
            {
              alert("Please Enter Quantity");
              $('#p_item_quantity'+no).focus();
              return false;
            }

            if($.trim($('#p_item_price'+no).val()).length == 0)
            {
              alert("Please Enter Price");
              $('#p_item_price'+no).focus();
              return false;
            }

          }

          $('#invoice_form').submit();

        });

      });
      </script>
      <?php
      }
      elseif(isset($_GET["update"]) && isset($_GET["id"]))
      {
        $statement = $connect->prepare("
          SELECT * FROM tbl_purchase 
            WHERE p_id = :p_id
            LIMIT 1
        ");
        $statement->execute(
          array(
            ':p_id'       =>  $_GET["id"]
            )
          );
        $result = $statement->fetchAll();
        foreach($result as $row)
        {
        ?>
        <script>
        $(document).ready(function(){
          $('#p_no').val("<?php echo $row["p_no"]; ?>");
          $('#p_date').val("<?php echo $row["p_date"]; ?>");
          $('#p_company_name').val("<?php echo $row["p_company_name"]; ?>");
          $('#p_company_gst').val("<?php echo $row["p_company_gst"]; ?>");
        });
        </script>
        <form method="post" id="invoice_form">
          <div class="card">
         
            <div class="card-header">
                   <h3>Edit Inovice</h3>
            </div> 
              <div class="card-body">
                       <div class="table-responsive">
          <table class="table table-bordered">
        
            <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-8">
                      From,<br />
                        <b>Purchase (BILL from)</b><br />
                     <input type="" name="p_company_name" id="p_company_name" class="form-control" placeholder="Enter Company Name"><br/>
                        <input type="text" name="p_company_gst" id="p_company_gst" class="form-control" placeholder="Enter Company GST Number"/> 
                   
                    </div>
                    <div class="col-md-4">
                      <!-- Reverse Charge<br /> --><br/><br/>
                      <input type="text" name="p_no" id="p_no" class="form-control input-sm" placeholder="Enter Invoice No." /><br/>
                      <input type="text" name="p_date" id="p_date" class="form-control input-sm" readonly placeholder="Select Invoice Date" />
                    </div>
                  </div>
                  <br />
                  <table id="invoice-item-table" class="table table-bordered">
                    <tr>
                      <th width="7%">Sr No.</th>
                      <th width="20%">Item Name</th>
                      <th width="5%">Quantity</th>
                      <!-- <th width="5%">Price</th> -->
                      <th width="10%">Actual Amt.</th>
                      <th width="12.5%" colspan="2">GST (%)</th>
                      <th width="12.5%" rowspan="2">Total</th>
                      <th width="3%" rowspan="2"></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Rate</th>
                      <th>Amt.</th>
       
                    </tr>
                    <?php
                    $statement = $connect->prepare("
                      SELECT * FROM tbl_purchase_item 
                      WHERE p_id = :p_id
                    ");
                    $statement->execute(
                      array(
                        ':p_id'       =>  $_GET["id"]
                      )
                    );
                    $item_result = $statement->fetchAll();
                    $m = 0;
                    foreach($item_result as $sub_row)
                    {
                      $m = $m + 1;
                    ?>
                    <tr>
                      <td><span id="sr_no"><?php echo $m; ?></span></td>
                      <td><input type="text" name="item_name[]" id="item_name<?php echo $m; ?>" class="form-control input-sm item_name_class" value="<?php echo $sub_row["item_name"]; ?>"/></td>

                      <td><input type="text" name="p_item_quantity[]" id="p_item_quantity<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm p_item_quantity" value = "<?php echo $sub_row["p_item_quantity"]; ?>"/></td>
                   <!--    <td><input type="text" name="p_item_price[]" id="p_item_price<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm number_only p_item_price" value="<?php echo $sub_row["p_item_price"]; ?>" /></td> -->
                      <td><input type="text" name="p_item_actual_amount[]" id="p_item_actual_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm p_item_actual_amount" value="<?php echo $sub_row["p_item_actual_amount"];?>" readonly /></td>
                      <td><input type="text" name="p_item_tax1_rate[]" id="p_item_tax1_rate<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm number_only p_item_tax1_rate" value="<?php echo $sub_row["p_item_tax1_rate"]; ?>" /></td>
                      <td><input type="text" name="p_item_tax1_amount[]" id="p_item_tax1_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" readonly class="form-control input-sm p_item_tax1_amount" value="<?php echo $sub_row["p_item_tax1_amount"];?>" /></td>
                                    <td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" readonly class="form-control input-sm order_item_final_amount" value="<?php echo $sub_row["order_item_final_amount"]; ?>" /></td>
                      <td></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td align="right"><b>Total</td>
                <td align="right"><b><span id="final_total_amt"><?php echo $row["p_total_after_tax"]; ?></span></b></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="hidden" name="total_item" id="total_item" value="<?php echo $m; ?>" />
                  <input type="hidden" name="p_id" id="p_id" value="<?php echo $row["p_id"]; ?>" />
                  <input type="submit" name="update_invoice" id="create_invoice" class="btn btn-info" value="Edit" />
                </td>
              </tr>
          </table>
        </div> 

              </div>
     
          </div>

      </form>
      <script>
      $(document).ready(function(){
        var final_total_amt = $('#final_total_amt').text();
        var count = "<?php echo $m; ?>";
        
        $(document).on('click', '#add_row', function(){
          count++;
          $('#total_item').val(count);
          var html_code = '';
          html_code += '<tr id="row_id_'+count+'">';
          html_code += '<td><span id="sr_no">'+count+'</span></td>';
          
          html_code += '<td><input type="text" name="item_name[]" id="item_name'+count+'" class="form-control input-sm" /></td>';
          
          html_code += '<td><input type="text" name="p_item_quantity[]" id="p_item_quantity'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_quantity"/></td>';

          // html_code += '<td><input type="text" name="p_item_price[]" id="p_item_price'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_price" /></td>';
          html_code += '<td><input type="text" name="p_item_actual_amount[]" id="p_item_actual_amount'+count+'" data-srno="'+count+'" class="form-control input-sm p_item_actual_amount" readonly /></td>';
          
          html_code += '<td><input type="text" name="p_item_tax1_rate[]" id="p_item_tax1_rate'+count+'" data-srno="'+count+'" class="form-control input-sm number_only p_item_tax1_rate" /></td>';
          html_code += '<td><input type="text" name="p_item_tax1_amount[]" id="p_item_tax1_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm p_item_tax1_amount" /></td>';

         
          html_code += '<td><input type="text" name="order_item_final_amount[]" id="order_item_final_amount'+count+'" data-srno="'+count+'" readonly class="form-control input-sm order_item_final_amount" /></td>';
          html_code += '<td><button type="button" name="remove_row" id="'+count+'" class="btn btn-danger btn-xs remove_row">X</button></td>';
          html_code += '</tr>';
          $('#invoice-item-table').append(html_code);
        });
        
        $(document).on('click', '.remove_row', function(){
          var row_id = $(this).attr("id");
          var total_item_amount = $('#order_item_final_amount'+row_id).val();
          var final_amount = $('#final_total_amt').text();
          var result_amount = parseFloat(final_amount) - parseFloat(total_item_amount);
          $('#final_total_amt').text(result_amount);
          $('#row_id_'+row_id).remove();
          count--;
          $('#total_item').val(count);
        });

        function cal_final_total(count)
        {
          var final_item_total = 0;
          for(j=1; j<=count; j++)
          {
            var quantity = 0;
            var price = 0;
            var actual_amount = 0;
            var tax1_rate = 0;
            var tax1_amount = 0;
            var tax2_rate = 0;
            var tax2_amount = 0;
            var tax3_rate = 0;
            var tax3_amount = 0;
            var item_total = 0;
            quantity = $('#p_item_quantity'+j).val();
            if(quantity > 0)
            {
              price = $('#p_item_price'+j).val();
              if(price > 0)
              {
                actual_amount = parseFloat(quantity) * parseFloat(price);
                $('#p_item_actual_amount'+j).val(actual_amount);
                tax1_rate = $('#p_item_tax1_rate'+j).val();
                if(tax1_rate > 0)
                {
                  tax1_amount = parseFloat(actual_amount)*parseFloat(tax1_rate)/100;
                  $('#p_item_tax1_amount'+j).val(tax1_amount);
                }
               
                item_total = parseFloat(actual_amount) + parseFloat(tax1_amount);
                final_item_total = parseFloat(final_item_total) + parseFloat(item_total);
                $('#order_item_final_amount'+j).val(item_total);
              }
            }
          }
          $('#final_total_amt').text(final_item_total);
        }

        $(document).on('blur', '.p_item_price', function(){
          cal_final_total(count);
        });

        $(document).on('blur', '.p_item_tax1_rate', function(){
          cal_final_total(count);
        });

  

        $('#create_invoice').click(function(){
          if($.trim($('#p_company_name').val()).length == 0)
          {
            alert("Please Enter Company Name");
            return false;
          }

          if($.trim($('#p_no').val()).length == 0)
          {
            alert("Please Enter Invoice Number");
            return false;
          }

          if($.trim($('#p_date').val()).length == 0)
          {
            alert("Please Select Invoice Date");
            return false;
          }

          for(var no=1; no<=count; no++)
          {
            if($.trim($('#item_name'+no).val()).length == 0)
            {
              alert("Please Enter Item Name");
              $('#item_name'+no).focus();
              return false;
            }

            if($.trim($('#p_item_quantity'+no).val()).length == 0)
            {
              alert("Please Enter Quantity");
              $('#p_item_quantity'+no).focus();
              return false;
            }

            if($.trim($('#p_item_price'+no).val()).length == 0)
            {
              alert("Please Enter Price");
              $('#p_item_price'+no).focus();
              return false;
            }

          }

          $('#invoice_form').submit();

        });

      });
      </script>
        <?php 
        }
      }
      else
      {
      ?>
      <div class="card"> 
        <div class="card-header">
            <h3 align="center">Ujwala Paints Purchase</h3>
             <span class="float-right">GST NO- <b>36AFYPA4899L1ZG</b></span>
            <div class="add_company_list">
               <div id="message">
<h3 id="import_message" class="alert alert-success text-center text-uppercase" style="display: none;"></h3>
    </div> 
<?php                              
if(isset($_POST['c_submit']))
{
    $sql='';
    $filename=$_FILES['excel_file']['name'];
    $filetmpname=$_FILES['excel_file']['tmp_name'];
    
    $fileExtension=pathinfo($filename, PATHINFO_EXTENSION);
    
    $allowedType=array('csv');
    
    if(!in_array($fileExtension, $allowedType))
    {
?>
    <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong> Invalid File extension...</strong> 
  </div>
    
     <?php
    }
      else
      {
          $handle=fopen($filetmpname,'r');
          while(($mydata=fgetcsv($handle,1000,','))!==FALSE)
          {
                $item_name_c1=$mydata[0];
                $item_name_c2=$mydata[1];
                $check=$connect->prepare("select * from add_company where company_name='$item_name_c1' and gst='$item_name_c2' ");
          
                $check->execute();
                $row = $check->rowCount();
                // $fetch=$check->fetch();
               
               if($row==0) 
               {
                 $sql = $connect->prepare("INSERT into add_company(company_name, gst) values('".$item_name_c1."', '".$item_name_c2."')");
                 $sql->execute();
               }
          }
          if($sql)
          {
     ?>
              <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>File Uploaded successfully.....!</strong> 
            </div>
         <?php     
          }
      }
    }

   ?>

              <form class=""  method="post" action="" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                    
                                                        <div class="form-group">
                                                          <!-- <label>Select Excel file</label> -->
                                                          <input type="file" name="excel_file" id="excel_file" >
                                                        </div>
                                                    </div>
                                                     </div>
                                                    <div class="form-group">
                <button type="submit" name="c_submit" id="c_submit" class="btn btn-primary">Add Company list</button>
    
                                                    </div>
            

                </form>
            </div>
        </div>
        <div class="card-body">
           <br />
      <div align="right">
         <h3 id="selected_message" class="alert alert-danger text-center text-uppercase" style="display: none;"></h3>

        <button type="button" class="btn btn-danger mr-2" id="delete">Delete Selected </button>
        <a href="Purchase_invoice.php?add=1" class="btn btn-info btn-xs">Create Purchase</a>
      </div>
      <br />
      <table id="data-table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><input type="checkbox" id="checkAll"/> Select All</th> 
            <th>Invoice No.</th>
            <th>Invoice Date</th>
            <th>Company Name</th>
            <th>Invoice Total</th>
            <!-- <th>PDF</th> -->
            <th>Edit</th>
            <th>Delete</th>
           <!--  <td><a href="print_invoice_purchase.php?pdf=1&id='.$row["p_id"].'"><span><i class="fa fa-file-pdf-o" style="color:brown;font-size:28px"></i></span></a></td> -->
          </tr>
        </thead>
        <?php
        if($total_rows > 0)
        {
          foreach($all_result as $row)
          {
            echo '
              <tr>
              <td width="100px"><input class="checkbox" type="checkbox" id="'.$row["p_id"].'" name="id[]"></td>
                <td>'.$row["p_no"].'</td>
                <td>'.$row["p_date"].'</td>
                <td>'.$row["p_company_name"].'</td>
                <td>'.$row["p_total_after_tax"].'</td>
                
                <td><a href="Purchase_invoice.php?update=1&id='.$row["p_id"].'"><span><i class="fa fa-edit fa-lg" style="color:green"></i></span></a></td>
                <td><a href="#" id="'.$row["p_id"].'" class="delete"><span><i class="fa fa-trash-alt fa-lg" style="color:red"></i></span></a></td>
              </tr>
            ';
          }
        }
        ?>
      </table>
        </div>
      </div>
      <?php
      }
      ?>
    </div>
    <br>
    <!-- <footer class="container-fluid text-center">
      <p>Footer Text</p>
    </footer> -->
     </div>
                          <?php

// include("wdc/footer.php");

                    ?>
            </div>
        </div>
    </div>
  </body>
</html>



<script type="text/javascript">
  $(document).ready(function(){
   $('#data-table').DataTable({
 "dom": '<"pull-right"f><"pull-left"l>tip'

        });

    $(document).on('click', '.delete', function(){
      var id = $(this).attr("id");
      if(confirm("Are you sure you want to remove this?"))
      {
        window.location.href="Purchase_invoice.php?delete=1&id="+id;
      }
      else
      {
        return false;
      }
    });
  });

</script>
 <script>
      $(document).ready(function(){
        $('#p_date').datepicker({
          format: "yyyy-mm-dd",
          autoclose: true,
          endDate: "today",
          todayHighlight: true
        });
        // $( "#p_date" ).datepicker("option", "defaultDate", +2);
      });
      
    </script>
<script>
$(document).ready(function(){
$('.number_only').keypress(function(e){
return isNumbers(e, this);      
});
function isNumbers(evt, element) 
{
var charCode = (evt.which) ? evt.which : event.keyCode;
if (
(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
(charCode < 48 || charCode > 57))
return false;
return true;
}
});
</script>


<script type="text/javascript">
   $(document).ready(function(){
 
 $('.item_name_class').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"backend.php",
    method:"POST",
    data:{query:query},
    dataType:"json",

    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
                 });


});




                                  // Multiple selection delete 

 
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
            data    : {'data_purchase' : dataArr},
            success : function(response){
                          setTimeout(function(){
           location.href="Purchase_invoice.php"; 
      }, 2000);
                        $('#selected_message').show();   
                        $('#selected_message').fadeIn().html(response);  
                          setTimeout(function(){  
                               $('#selected_message').fadeOut("Slow");  
                          }, 2000); 
                      },
            error   : function(errResponse){
                      window.location="Purchase_invoice.php";
                      }                     
        });
    }


                                           // get GST number by COMPANY NAME

$("#p_company_name").on("change", function(){

         var company_name=$(this).val();
         // alert(company_name);
  $.ajax({
    type: "POST",
    url: "backend.php",
    data:{company_name:company_name},

   success: function(data){
    // alert(data);
    $(".gst_list").val(data);
     // $("#gst_list").append(data);
  }
  });

})
</script>