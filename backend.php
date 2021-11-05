<?php
      include 'wdc/dbconfig.php';
  
     session_start();

//  error_reporting(0);
     // Deelete SIngle by JQ records

// pharma_V start

if(!empty($_REQUEST["disease_name"])) 
    {
         $option='';
        $disease_name=$_POST["disease_name"];
     

    $query =$connect->prepare("SELECT * FROM tbl_m WHERE disease_name='".$disease_name."' ");
    $query->execute(); 

    // $result = $query->rowCount();



    while($row2=$query->fetch(PDO::FETCH_ASSOC))
    {
       $response=$row2;
     
   }
          //   $option_1=$row2['medicine_name'];
          //   $option_2=$row2['description'];
   
          //   $output[]=array(
          //     "op1" => $option_1,
          //     "op2" => $option_2
          // );
      echo json_encode($response);
     
}
// pharma_V end






    if(isset($_POST["deleteid"]))
     { 
        // echo "<script>alert('ok')</script>";
        $userid=$_POST["deleteid"];
        $sale_del="delete from sale_report where id='$userid' ";
        $sale_prepare=$connect->prepare($sale_del);
        $sale_st=$sale_prepare->execute();
        if ($sale_st==true) {
            echo "Deleted Successfully";
        }
     }   

     // Get user id for update
    if (isset($_POST['id']) && isset($_POST['id']) !="") 
    {
        $user_id=$_POST['id'];
        $query=$connect->prepare("select * from sale_report where id='$user_id' ");
        if(!$query->execute())
        {
            exit(mysqli_error());
        }
        $response=array();
        if(count($query)>0)
        {
            while ($row=$query->fetch(PDO::FETCH_ASSOC)) 
            {
                $response=$row;
            }
        }
        else
           {
            $response['status']=200;
            $response['message']='Data not found..!';
           } 
           echo json_encode($response);
    }
    else
    {
        $response['status']=200;
        $response['message']='Invalid request';
    }

    // Update TAble
    
    if (isset($_POST['hidden_user_id'])) 
    { 

        $hidden_user_id=$_POST['hidden_user_id'];
            $up_add_items = $_POST['up_add_items']; 
        $up_sale_invoice = $_POST['up_sale_invoice'];
        $up_date = $_POST['up_date']; 
        $up_tax_sale = $_POST['up_tax_sale']; 

     
        $sql=$connect->prepare("update sale_report set add_items='".$up_add_items."', sale_invoice='".$up_sale_invoice."', day_book_sale='".$up_date."', tax_sale='".$up_tax_sale."' where id='".$hidden_user_id."' ");
        // $sql="update agency set type='$upttype', name='$uptname', email='$uptemail', mobile='$uptmobile', con_person='$uptper', con_per_mb='$uptpermb', address='$uptaddr', notes='$uptnotes', status='$upstatus' where id='$hidden_user_id'";
          $sql->execute();
         
         echo "Updated Successfully";
        // if(!$result=mysqli_query($con, $sql))
        // {
        //     exit(mysql_error());
        // }
    }

                 // Date search
 if(isset($_POST["From"], $_POST["to"]))
{

  $result = '';
  $total=0;

 $sql=$connect->prepare("SELECT order_no, order_date, order_item_tax1_rate, order_item_final_amount, order_item_final_amount 
FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From"]."' AND '".$_POST["to"]."' ");
    

     $sql->execute();
  $result .='
  <table class="table table-bordered">
  <tr>
    

      
        <th>Sale Invoice</th>
        <th>Day Book of Sale</th>
        <th>Tax Sale (%)</th>
        <th>Total</th>
  
    </tr>';
    $row=$sql->rowCount();
  if($row > 0)
  {
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      $result .='
      <tr>
    
      <td>'.$row["order_no"].'</td>
      <td>'.$row["order_date"].'</td>
      <td>'.$row["order_item_tax1_rate"].'</td>
      <td>'.$row["order_item_final_amount"].'</td>

      </tr>
     
      ';
    }
   
  }
  // <th id="total_order" colspan="1">'.$row[''].'</th>
  else
  {
    $result .='
    <tr>
    <td colspan="5">No Data Found</td>
    </tr>';
  }

 $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From"]."' AND '".$_POST["to"]."' ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
      <tfoot>
       <tr>
        <th colspan="3" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
      </tfoot>
    ';

  $result .='</table>';
  echo $result;
}  
                               // SEARCH ADD ITEMS
if (isset($_POST["query"])) {

  $request=$_POST["query"];
$query = $connect->prepare("SELECT * FROM add_items WHERE item_name LIKE '%".$request."%' ");
$query->execute();

$row = $query->rowCount();

$data = array();

if($row > 0)
{
 while($row = $query->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row["item_name"];
 }
 echo json_encode($data);
  }
}
                                 // END SEARCH ADD ITEMS


                                  // LOGIN AND REGISTRATION 
 if (isset($_POST['emailid']))
       {
            $sql = $connect->prepare("SELECT email FROM users WHERE email = '".$_POST['emailid']."' ");
            $sql->execute();
           

          $row = $sql->rowCount();
      $fetch = $sql->fetch();
      if(!$row > 0) 
      {
               $firstname = $_POST['username'];
               $emailid=$_POST['emailid'];
               $password=md5($_POST['password']);
               // $pass=$_POST['password'];
               // $password=password_hash($pass, PASSWORD_DEFAULT);
             
               $confirm_password=md5($_POST['confirm_password']);
               
               $insert=$connect->prepare("insert into users(user_name, email, password, confirm_password) values('$firstname', '$emailid', '$password', '$confirm_password') ");
               $insert->execute();
               echo "Register Successfully...";

      }
      else
      {
        echo "Email ID already taken Not DONE";
      }
   }


       if(isset($_POST['login_email']))
       {
          $login_email=$_POST['login_email'];
          // echo $login_email;
        $login_password=md5($_POST['login_password']);

          $query=$connect->prepare("select * from users where email='$login_email'and password='$login_password' ");
          $query->execute(array($login_email, $login_password));
         $row = $query->rowCount();
      $fetch = $query->fetch();
      
      if($row > 0) 
      {
        $_SESSION['user'] = $fetch['user_name'];

        echo true;
       }
      else
      {
        echo 'Invalid User Name and Password';
      }
   }
                       // END LOGIN and REGISTRATION 

           // Multiple records deleted         
if(isset($_POST['data'])){
    $dataArr = $_POST['data'] ; 

    foreach($dataArr as $id){


 $result=$connect->prepare("DELETE FROM tbl_order where order_id='$id';DELETE FROM tbl_order_item where order_id='$id' ");

        $result->execute();
    }
    echo 'Deleted Selected Records';
} 

                   // PURCHASE START


                                            // Date search in PURCHASE
 if(isset($_POST["From_p"], $_POST["to_p"]))
{
  // $conn = mysqli_connect("localhost", "root", "", "tut");
  $result = '';
  // $sql = $connect->prepare("select * from sale_report  WHERE day_book_sale BETWEEN '".$_POST["From"]."' AND '".$_POST["to"]."'");

$search=$_POST['search_pcompany_name'];

 $sql=$connect->prepare("SELECT  p_no, p_company_name, p_date, item_name, p_item_quantity, p_item_tax1_rate, order_item_final_amount
FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_p"]."' AND '".$_POST["to_p"]."' and p_company_name like '$search' ");
    

     $sql->execute();

  $total_rows = $sql->rowCount();

  $result .='
  <table class="table table-bordered">
  <tr>
    
                                            <th>Purchase Invoice</th>
                                            <th>Company Name</th>
                                            <th>Day Book of Purchase</th>
                                            <th>Item Name</th>
                                            <th>Item Quantity</th>
                                            <th>Tax Sale (%)</th>
                                            <th>TOTAL</th>
  
    </tr>';
  if($total_rows > 0)
  {
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      $result .='
      <tr>
    
      <td>'.$row["p_no"].'</td>
      <td>'.$row["p_company_name"].'</td>
      <td>'.$row["p_date"].'</td>
      <td>'.$row['item_name'].'</td>
      <td>'.$row["p_item_quantity"].'</td>
      <td>'.$row["p_item_tax1_rate"].'</td>
      <td>'.$row["order_item_final_amount"].'</td>

      </tr>';


    }
  }
  else
  {
    $result .='
    <tr>
    <td colspan="6">No Data Found</td>
    </tr>';
  }

  $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_p"]."' AND '".$_POST["to_p"]."' and p_company_name like '$search' ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
      <tfoot>
       <tr>
        <th colspan="6" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
      </tfoot>
    ';

  $result .='</table>';
  echo $result;
} 



// DELETE MULTIPLE COLUMS OF PURCHASE

if(isset($_POST['data_purchase'])){
    $dataArr = $_POST['data_purchase'] ; 

    foreach($dataArr as $id){


 $result=$connect->prepare("DELETE FROM tbl_purchase where p_id='$id';DELETE FROM tbl_purchase_item where p_id='$id' ");

        $result->execute();
    }
    echo 'Deleted Selected Records';
} 

                                   //Get gst number by company name of Purchase 
if(!empty($_REQUEST["company_name"])) 
    {
         $option='';
        $company_name=$_POST["company_name"];
        // echo "<script>alert($region_id)</script>";

    $query =$connect->prepare("SELECT gst FROM add_company WHERE company_name='".$company_name."' ");
    $query->execute(); 

    // $result = $query->rowCount();



    while($row2=$query->fetch(PDO::FETCH_ASSOC))
    {
            $option=$row2['gst'];
    // $option .= '<option value="'.$row2['gst'].'">'.$row2['gst'].'</option>';
      // echo '<option value="'.$row2['gst'].'">' . $row2['gst'] . '</option>';
            // $result="<option value='" . $row2['gst'] . "'>" . $row2['gst'] . "</option>";

     
   }
      echo($option);
        // echo json_encode($result);
}

                  //B2B REPORT
if(isset($_POST["From_b2b"], $_POST["to_b2b"]))
{

  $result = '';
  $total=0;
   // $b2b_tax=$_POST["b2b_tax"];

 $sql=$connect->prepare("SELECT order_no, company_b2b, order_gst, order_date, order_item_actual_amount, order_item_tax1_rate, order_item_final_amount, order_item_final_amount 
FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_b2b"]."' AND '".$_POST["to_b2b"]."' and tbl_order.company_b2b !=' ' ");
    

     $sql->execute();
  // $result .='
  // <table class="table table-bordered">
  // <tr>
    

      
  //       <th>Sale Invoice</th>
  //       <th>Day Book of Sale</th>
  //       <th>Tax Sale (%)</th>
  //       <th>Total</th>
  
  //   </tr>';
    $row=$sql->rowCount();
  if($row > 0)
  {
    while($rows = $sql->fetch(PDO::FETCH_ASSOC))
    {
      // if (!empty($rows->company_b2b)) 
      // {
        
     
      $result .='
      <tr>
    
      <td>UJ-'.$rows["order_no"].'</td>
      <td>'.$rows["company_b2b"].'</td>
      <td>'.$rows["order_gst"].'</td>
      <td>'.$rows["order_date"].'</td>
      <td>'.$rows["order_item_actual_amount"].'</td>
      <td>'.$rows["order_item_tax1_rate"].'</td>
      <td>'.$rows["order_item_final_amount"].'</td>

      </tr>
      ';
       }
    // }
   
  }
  // <th id="total_order" colspan="1">'.$row[''].'</th>
  else
  {
    $result .='
    <tr>
    <td colspan="5">No Data Found</td>
    </tr>';
  }

 $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_b2b"]."' AND '".$_POST["to_b2b"]."' and tbl_order.company_b2b !=' ' ");
        $total_sql->execute();  
        $rows=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
        <th colspan="5" style="text-align:right">Total Amount</th>
        <th>'.$rows['total'].'<th> 
       </tr>';

  // $result .='</table>';
  echo $result;
}  

   
                // final report

              
 if(isset($_POST["From_fs"], $_POST["to_fs"]))
{

  $result = '';
  $total=0;
   // $fs_tax=$_POST["fs_tax"];

 $sql=$connect->prepare("SELECT order_no, order_date, order_item_quantity,order_item_actual_amount, order_item_tax1_rate, order_item_final_amount, order_item_final_amount 
FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_fs"]."' AND '".$_POST["to_fs"]."'  ");
    

     $sql->execute();
  // $result .='
  // <table class="table table-bordered">
  // <tr>
    

      
  //       <th>Sale Invoice</th>
  //       <th>Day Book of Sale</th>
  //       <th>Tax Sale (%)</th>
  //       <th>Total</th>
  
  //   </tr>';
    $row=$sql->rowCount();
  if($row > 0)
  {
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      $result .='
      <tr>
    
      <td>UJ-'.$row["order_no"].'</td>
      <td>'.$row["order_date"].'</td>
      <td>'.$row["order_item_quantity"].'</td>
      <td>'.$row['order_item_actual_amount'].'</td>
      <td>'.$row["order_item_tax1_rate"].'</td>
      <td>'.$row["order_item_final_amount"].'</td>

      </tr>
     
      ';
    }
   
  }
  // <th id="total_order" colspan="1">'.$row[''].'</th>
  else
  {
    $result .='
    <tr>

    <td colspan="5">No Data Found</td>
    </tr>';
  }

 $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total, sum(order_item_quantity) as quantity FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_fs"]."' AND '".$_POST["to_fs"]."'  ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
       <th colspan="2" style="text-align:right">Total Quantity</td>
        <th>'.$row['quantity'].'</th>
        <th colspan="1" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
    ';

  // $result .='</table>';
  echo $result;
}  

// final sales sum of all taxes
if(isset($_POST["From_fs_sum"], $_POST["to_fs_sum"]))
{

  $result = '';
  $total=0;
   // $fs_tax=$_POST["fs_tax"];
// SUM(order_item_final_amount) as tax_12

$tax_rate=array('0.00','5.00','12.00','18.00');
$totals=array();

// echo count($tax_rate);
 
for($i=0;$i<count($tax_rate);$i++)
{
   // $result=$tax_rate[$i];
   // echo $result;

   $sql = $connect->prepare("SELECT SUM(order_item_final_amount) as tax_5 FROM tbl_order_item
INNER JOIN tbl_order ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_fs_sum"]."' AND '".$_POST["to_fs_sum"]."' and order_item_tax1_rate='$tax_rate[$i]' ");
 $sql->execute();
 
  

 $total_rows=$sql->rowCount();

 if($total_rows > 0)
  {
    // $result.='<tr><td>';
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      // $totals[$i]=$row["tax_5"];
    
      
               $result.='<tc>
                          <td>'.$row["tax_5"].'</td>
                          </tc>';
                
     
    }
        // $result.='</td></tr>';
  }
  else
  {
    $result .='
    <tr>
    <td colspan="5">No Data Found</td>
    </tr>';
  }

  // foreach ($totals as $single)
  //  {
  //   $result.='<tr>
  //            <td>'.$single.'<td> 
  //           </tr>';
  // }


}

 $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_order
RIGHT JOIN tbl_order_item ON tbl_order.order_id= tbl_order_item.order_id  WHERE order_date BETWEEN '".$_POST["From_fs_sum"]."' AND '".$_POST["to_fs_sum"]."' ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
        <th colspan="3" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
    ';

  // $result .='</table>';
  echo $result;

}  

// end final sales sum of all taxes




 if(isset($_POST["From_fp"], $_POST["to_fp"]))
{
  // $fp_tax=$_POST["fp_tax"];
  // echo $fp_tax;

  // $conn = mysqli_connect("localhost", "root", "", "tut");
  $result = '';
  // $sql = $connect->prepare("select * from sale_report  WHERE day_book_sale BETWEEN '".$_POST["From"]."' AND '".$_POST["to"]."'");


 $sql=$connect->prepare("SELECT p_company_name, p_date, p_item_quantity,p_item_actual_amount, p_item_tax1_rate, order_item_final_amount
FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fp"]."' AND '".$_POST["to_fp"]."'  ");
    

     $sql->execute();

  $total_rows = $sql->rowCount();

  // $result .='
  // <table class="table table-bordered" id="dataTable">
  // <tr>
    

      
  //       <th>Company Name</th>
  //       <th>Quantity</th>
  //       <th>Tax Sale (%)</th>
  //       <th>Total</th>
  
  //   </tr>';
  if($total_rows > 0)
  {
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      $result .='
      <tr>
    
      <td>'.$row["p_company_name"].'</td>
      <td>'.$row["p_date"].'</td>
      <td>'.$row["p_item_quantity"].'</td>
      <td>'.$row["p_item_actual_amount"].'</td>
      <td>'.$row["p_item_tax1_rate"].'</td>
      <td>'.$row["order_item_final_amount"].'</td>

      </tr>';
    }
  }
  else
  {
    $result .='
    <tr>
    <td colspan="5">No Data Found</td>
    </tr>';
  }

  $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total, SUM(p_item_quantity) as quantity FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fp"]."' AND '".$_POST["to_fp"]."'  ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
       <th colspan="2" style="text-align:right">Total quantity</th>
       <th>'.$row['quantity'].'</th>
        <th colspan="1" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
        </tr>';

  // $result .='</table>';
  echo $result;
} 



// final purchase sum of all taxes
if(isset($_POST["From_fs_pur"], $_POST["to_fs_pur"]))
{

  $result = '';
  $total=0;
   // $fs_tax=$_POST["fs_tax"];
// SUM(order_item_final_amount) as tax_12

$tax_rate=array('0.00','5.00','12.00','18.00');
$totals=array();

// echo count($tax_rate);
 
for($i=0;$i<count($tax_rate);$i++)
{
   // $result=$tax_rate[$i];
   // echo $result;

   $sql = $connect->prepare("SELECT SUM(order_item_final_amount) as tax_5 FROM tbl_purchase_item
INNER JOIN tbl_purchase ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fs_pur"]."' AND '".$_POST["to_fs_pur"]."' and p_item_tax1_rate='$tax_rate[$i]' ");
 $sql->execute();
 
//   $sql=$connect->prepare("SELECT p_company_name, p_item_quantity, p_item_tax1_rate, order_item_final_amount
// FROM tbl_purchase
// RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fp"]."' AND '".$_POST["to_fp"]."' and p_item_tax1_rate='$fp_tax' ");

 $total_rows=$sql->rowCount();

 if($total_rows > 0)
  {
    // $result.='<tr><td>';
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
      // $totals[$i]=$row["tax_5"];
    
      
               $result.='<tc>
                          <td>'.$row["tax_5"].'</td>
                          </tc>';
                
     
    }
        // $result.='</td></tr>';
  }
  else
  {
    $result .='
    <tr>
    <td colspan="5">No Data Found</td>
    </tr>';
  }

  // foreach ($totals as $single)
  //  {
  //   $result.='<tr>
  //            <td>'.$single.'<td> 
  //           </tr>';
  // }


}

 $total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fs_pur"]."' AND '".$_POST["to_fs_pur"]."' ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
        <th colspan="3" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
    ';

  // $result .='</table>';
  echo $result;

}  

// end final purchase sum of all taxes




                                     // final company purchase report

if(isset($_POST["From_fp_comp"], $_POST["to_fp_comp"]))
{

  $result = '';
  $total=0;

 
 $sql = $connect->prepare("SELECT p_company_name, p_item_tax1_rate, order_item_final_amount  FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fp_comp"]."' AND '".$_POST["to_fp_comp"]."'  ");
 $sql->execute();
 


 $total_rows=$sql->rowCount();


 if($total_rows > 0)
  {
    // $result = $sql -> fetchAll();
    // print_r($result);

// foreach( $result as $row )
//  {
//     $result=$row['p_company_name'];
//     print_r($result);
 
// }

    while($row = $sql->fetch(PDO::FETCH_ASSOC))
    {
       $result.='<tr>
                    <td>'.$row["p_company_name"].'</td>
                    <td>'.$row["p_item_tax1_rate"].'</td>
                    <td>'.$row["order_item_final_amount"].'</td>
                 </tr>';
                     

      
    
               // $result.='<tc>
               //              <td>'.$row['p_company_name'].'</td>
               //              <td>done</td>
               //           </tc>';
         
                
     
    }
    // while($row = $sql->fetch(PDO::FETCH_ASSOC))
    // {
    //    $result.='<tr>
    //                 <td>'.$row["tax_5"].'</td>
    //              </tr>';
                    
         
                
     
    // }
    
  }
  else
  {
    $result .='
    <tr>
    <td colspan="2">No Data Found</td>
    </tr>';
  }


$total_sql = $connect->prepare("SELECT SUM(order_item_final_amount) as total FROM tbl_purchase
RIGHT JOIN tbl_purchase_item ON tbl_purchase.p_id= tbl_purchase_item.p_id  WHERE p_date BETWEEN '".$_POST["From_fp_comp"]."' AND '".$_POST["to_fp_comp"]."' ");
        $total_sql->execute();  
        $row=$total_sql->fetch(PDO::FETCH_ASSOC);
 
    
    $result.='
       <tr>
        <th colspan="" style="text-align:right">Total Amount</th>
        <th>'.$row['total'].'<th> 
          
       </tr>
    ';

  // $result .='</table>';
 echo $result;

}  

// end final purchase sum of all taxes

?>