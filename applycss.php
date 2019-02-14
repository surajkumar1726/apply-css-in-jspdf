<?php 
date_default_timezone_set('Asia/Kolkata');
require_once ('lib/dbconnect.php'); ?>

<?php include("lib/top.inc.php"); ?>  

<?php require_once('phpmailer/class.phpmailer.php');?>
<?php 

     
        // Get invoice Data
       $id = $_GET['id'];
      $sid = $_GET['sid'];
        $query_sale = "SELECT * FROM sales WHERE refrence_no = '$id' AND bill_created ='1'";
      if (!empty($sid)) {
      $query_sale.=("AND id = '$sid'");
      }
      $get_sales_details=mysqli_query($con,$query_sale);
      $sale_details = mysqli_fetch_array($get_sales_details);
      $customer_id = $sale_details['customer_id'];
      $serviceId = $sale_details['ser_id'];
      $saleDuration = $sale_details['duration'];
      $saleCost = $sale_details['cost'];
      $saleAdvance = $sale_details['advance'];
      $saleStartdate = $sale_details['startdate'];
      // $saleDate = $sale_details['enddate'];
      $saleEnddate = $sale_details['enddate'];
      $saleRenewal = $sale_details['renewal'];
      $saleDescription = $sale_details['description'];
      $saleRefrence_no = $sale_details['refrence_no'];
      $saleDate = $sale_details['sale_date'];

?>
<style>
.error{
    color:#F00;

    font-size: 11px;

    display: block !important;

    text-align: center;

    padding: 0px !important;

    margin: 0px !important;
}
</style>

<body>

<?php include("lib/header.inc.php"); ?>

<?php if (!empty($email)) { ?>

<div class="middle"> 

  <div class="container">

    <div class="row">


  
   <div class="col-md-6 new_clients" id="chargeable_print" style="float: none; margin: auto;">
        <div class="chargeable_top_content">
          <figure><img style="width: 265px;" src="<?php echo $rowlogo['login_logopng']; ?>" alt=""></figure>
          <p>353/6, Dr Hirasingh Road, Civil Lines, Ludhiana - 141001<br>Ph: +91 161 4656799, 9814406799 | Email: sales@cyberxel.com</p>
          <h3>TAX INVOICE</h3>
          <h5>GSTIN: 03AFFPJ5392C1ZS</h5>
        </div><!--chargeable_top_content end-->
                <div class="table_content">
      <table width="100%" border="0" class="table_04">
    <?php
     $date = date('Y-m-d');
   $getClient = mysqli_query($con,"SELECT * FROM sales WHERE refrence_no  = '$id' AND bill_created= 1 GROUP BY refrence_no ");
    $ClientDetalis=mysqli_fetch_array($getClient);
    $_SESSION['old_refrenceNo'] = $ClientDetalis['refrence_no'];
    $customer_id = $ClientDetalis['customer_id'];
    $get_client_data = mysqli_query($con,"SELECT * FROM client WHERE customer_id ='$customer_id' ");
    $get_user = mysqli_fetch_array($get_client_data);
    ?>
  <tr>
    <td colspan="4" rowspan="2">
      <div class="table_sales_content">
        <h2>Bill to</h2>

        <h3><?php echo $get_user['company'] ?></h3>
        <p><?php echo $get_user['address'] ?></p>
        <span>GSTIN : <?php echo $get_user['gst'] ?></span>
      </div><!--table_sales_content end-->
    </td>
    <td><b>Refrence No</b></td>
    <td><b>DATED</b></td>
  </tr>
  <tr>
    <td><?php echo $ClientDetalis['refrence_no']; ?></td>
    <td><?php echo $ClientDetalis['sale_date']; ?></td>
  </tr>
  <tr>
    <td><b>Description of Goods/Services</b></td>
    <td><b>HSN CODE</b></td>
    <td><b>QTY</b></td>
    <td><b>YEAR</b></td>
    <td><b>RATE</b></td>
    <td><b>Amount</b></td>
  </tr>
  <?php
      $rows=mysqli_query($con,"SELECT * FROM sales WHERE  refrence_no = '$id' AND bill_created= 1 ");
      while($get_info = mysqli_fetch_assoc($rows)){
  ?>
  <?php
      $cost=number_format($get_info['cost'],2);
      $sum += $get_info['cost'];
      $sumadvance += $get_info['advance'];
      $total=number_format($sum,2);
      $advance = number_format($sumadvance,2);
      $GrandSum = $sum - $sumadvance;
      $grandTotal = number_format($GrandSum,2);
      $SalesTax = ($sum*9)/100;
      $SGST = number_format($SalesTax,2);
      $CentralTax = ($sum*9)/100;
      $CGST = number_format($CentralTax,2);
      $AmountToPay = number_format(($GrandSum+$SalesTax+$CentralTax),2,".",",");
      

      list($month, $day, $year) =explode("/",$get_info['startdate']);
      $month_name = date("F", mktime(0, 0, 0, $get_info['startdate'], 12));
      $split_month=substr($month_name,0,3);
      $split_year = substr($year,2,4);


      $ones = array(
      0 =>"ZERO", 
      1 => "ONE", 
      2 => "TWO", 
      3 => "THREE", 
      4 => "FOUR", 
      5 => "FIVE", 
      6 => "SIX", 
      7 => "SEVEN", 
      8 => "EIGHT", 
      9 => "NINE", 
      10 => "TEN", 
      11 => "ELEVEN", 
      12 => "TWELVE", 
      13 => "THIRTEEN", 
      14 => "FOURTEEN", 
      15 => "FIFTEEN", 
      16 => "SIXTEEN", 
      17 => "SEVENTEEN", 
      18 => "EIGHTEEN", 
      19 => "NINETEEN",
      "014" => "FOURTEEN" 
      ); 
      $tens = array( 
      0 => "ZERO",
      1 => "TEN",
      2 => "TWENTY",
      3 => "THIRTY", 
      4 => "FORTY", 
      5 => "FIFTY", 
      6 => "SIXTY", 
      7 => "SEVENTY", 
      8 => "EIGHTY", 
      9 => "NINETY" 
      ); 
      $hundreds = array( 
      "HUNDRED", 
      "THOUSAND", 
      "MILLION", 
      "BILLION", 
      "TRILLION", 
      "QUARDRILLION" 
      ); /*limit t quadrillion */
      $num = $AmountToPay ;
      $num_arr = explode(".",$num);
//print_r($num_arr);echo"<br/";     
      $wholenum = $num_arr[0]; 
      $decnum = $num_arr[1]; 
      $whole_arr = array_reverse(explode(",",$wholenum)); 
      krsort($whole_arr,1);
//print_r($whole_arr);echo "<br/>";
      $rettxt = ""; 
      foreach($whole_arr as $key => $i)
      {
        
        while(substr($i,0,1)=="0")
            $i=substr($i,1,5);
//print($i);echo "<br/>";
        if($i < 20){ 
        /* echo "getting:".$i; */
        $rettxt .= $ones[$i]; 
//print($rettxt);
        }elseif($i < 100){ 
        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
        }else{ 
        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
        } 
        if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
        }
      } 
//print_r($whole_arr);echo "<br/>";
      if($decnum > 0)
      { 
        $rettxt .= " and "; 
        if($decnum < 20){ 
        $rettxt .= $ones[$decnum]; 
        }elseif($decnum < 100){ 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
        } 
      } 
      $Amnt_in_Words = ucwords(strtolower($rettxt)) ; 
      
  
  ?>
  <tr>
    <td><?php echo $get_info['description'] ?></td>
    <td>9983</td>
    <td>1</td>
    <td><?php echo $split_month.'-'.$split_year ?></td>
    <td><?php echo $get_info['cost'].'/-' ?></td>
    <td class="edit_invoice_sale"><?php echo $cost ?></td>
  </tr>
  <?php }?>
  <tr>
    <td>Total</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?php echo $total ?></td>
  </tr>
  <tr>
    <td>Advance</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  <td><?php echo $advance ?></td>
  </tr>
  <tr>
    <td><b>Grand Total</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><b><?php echo $grandTotal ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ADD SGST (9%)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>9%</td>
    <td><?php echo $SGST ?></td>
  </tr>
  <tr>
    <td>ADD CGST (9%)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>9%</td>
    <td><?php echo $CGST ?></td>
  </tr>
  <tr>
    <td>ADD IGST (18%)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>0%</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><b>TOTAL WITH TAX</b></td>
    <td><b><?php echo $AmountToPay; ?></b></td>
   </tr>
   
  <tr>
    <td>
      <div class="table_ammount">
        <p>Amount Chargeable (in words)</p>
        <p><?php echo $Amnt_in_Words." Only" ?></p>
      </div><!--table_ammount end-->
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" rowspan="2">
      <div class="chargeable_content">
        <h3>For CYBERXEL</h3>
        
        <p>Authorised Sign</p>
      </div><!--chargeable_content end-->
    </td>
  </tr>
  <tr>
    <td>
      <div class="table_ammount">
        <p style="margin-bottom:5px;"><b>PAN - AFFPJ5392C</b></p>
        <p>Note-Please make cheques in favor of "CYBERXEL"</p>
      </div><!--table_ammount end-->
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>


                </div><!--table_content end-->

                <div class="terms_conditions">
                  <h3>Terms & Conditions</h3>
                  <p>* Price is subject to current market rates</p>
                  <p>All disputes are subject to Ludhiana Jurisdiction</p>
                  <p>Money once paid is not refundable</p>
                  <p>If the renewal fee is not paid or any dues are not cleared for the work done, cyberxel can close the due services with a prior information</p>
                  <p>All replacements are subject to inspection and approval.</p>
                  <p>Our responsibility ceases once the passwords of any service is delivered.</p>
                  <p>Interest of 5% per month will be charged on the amount remaining unpaid after 15 days of invoice date.</p>
                  <p>All the above mention amount/prices are subject to change on any time without any notice.</p>
                </div><!--terms_conditions end-->

                 <div class="print_button">
                <a onclick="window.print();"><i class="fa fa-print" aria-hidden="true"></i>Print</a>
                </div><!--print_button end 
               
           
      </div><!--col-md-8 end-->

      </div> 

    </div>

  </div>

</div>



<?php }else{ ?>

<div class="col-md-12">   <!-- Next Month Dues -->

        <div class="box">

                <div class="box-header">

                  <h3 class="box-title"><a href="index.php"> LOGIN TO SEE </a></h3>

                </div>

                </div>

            </div>

<?php } ?>




<?php include("lib/footer.inc.php"); ?>



<script type="text/javascript">

// Show Sub Fileds

  // $(".dperiod").hide();

  //   $(".domain").click(function() {

  //     if($(this).is(":checked")) {

  //         $(".dperiod").show(300);

  //     } else {

  //         $(".dperiod").hide(200);

  //     }

  // });



// Get Radio Button Value

$('#contact_form input[type=radio]').on('change', function(event) {

  var result = $(this).val();

  var _desc=$(this).data('desc');

  $('#service_result').html(_desc);

  // $("#service_result").val(_desc);
})



// // Get Value

// $('#renewal').on('change',function(e){

//     var selectedValue = $(this).val();

//     if(selectedValue==28)

//     {

//         alert('You select: Monthly 28');

//     }

//     if (selectedValue==365) {

//      alert('You select: Yearly 365');

//     }

//     if (selectedValue==64) {

//      alert('You select: Quartly 64');

//     }

//     else{

//      alert('Bi-Yearly ?');

//     } 

// });

// $('#renewal').on('change', function() {

//     var days = $(this).val();

//     $('#days').html(days);

    

// });

</script>

<script type="text/javascript">

  (function($, window, document, undefined){
    $("#days").on("change", function(){
       var date = new Date($("#start_date").val()),
           days = parseInt($("#days").val(), 10);
          if(!isNaN(date.getTime())){
            date.setDate(date.getDate() + days);
            $("#end_date").val(date.toInputFormat());
        } else {
            alert("Invalid Date");  
        }
    });
    //From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
    Date.prototype.toInputFormat = function() {
       var yyyy = this.getFullYear().toString();
       var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
       var dd  = this.getDate().toString();
       return  (mm[1]?mm:"0"+mm[0]) + "/" + (dd[1]?dd:"0"+dd[0]) + "/" + yyyy; // padding
    };
})(jQuery, this, document);
</script>
</body>
</html>
 <script src="js/jquery.validate.js"></script>  
<script type="text/javascript">
$(document).ready(function(e) {
$('#contact_form').validate({
rules: {
    c_name: {
            required:true,
          },
    email: {
      required: true,
        email: true  
    },
    company: {
      required: true,
    },      
    gst: {
      minlength: 15,
        maxlength: 15
    },
    pan: {
      minlength: 10,
      maxlength: 10
    },
    area_code: {
      required: true,
      maxlength: 8
    },
    phone: {
      required: true,
      maxlength: 10,
      minlength: 10
    }
      }
});
});
</script>  
<style type="text/css">
  @media print {
  body * {
    visibility: hidden;
  }
  #chargeable_print, #chargeable_print * {
    visibility: visible;
  }
  #chargeable_print {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>