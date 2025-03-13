<style>
   .select2-container--open .select2-dropdown--below {
      z-index: 9999999999
   }
</style>

<?php
$order_status = $ArrFieldData['order_status'];
$editAllow = "No";
if ($order_status == 'Pending Payment' || $order_status == 'Processing') {
   $editAllow = "Yes";
}

unset($ArrOrderStatus['Payment Processed']);
$is_payment_received = false;
if(isset($ArrFieldData['amount_received_status']) && $ArrFieldData['amount_received_status']=='succeeded')
{

	// $is_payment_received = true;
	
   // $editAllow = "No";
	
   // Pending Payment and Processing status delete
	
   /* unset($ArrOrderStatus['Pending Payment']);
	
   unset($ArrOrderStatus['Processing']); */
}
if($order_status == 'Pending Payment')
{
$is_payment_received = true;
}
?>
<a href="<?php echo SITE_URL ?>orders" class="btn btn-primary" style="float:right">Back</a>
<form class="form-horizontal" action="" id="banner_frm" role="form" enctype="multipart/form-data" method="post"
   accept-charset="utf-8">
   <input type="hidden" name="order_id" value="<?php echo $ArrFieldData['order_id']; ?>" />
   <input type="hidden" name="order_total_tax" value="<?php echo $ArrFieldData['order_total_tax']; ?>">
   <input type="hidden" name="state_tax_percentage" id="state_tax_percentage"
      value="<?php echo $ArrFieldData['state_tax']; ?>">
   <input type="hidden" name="product_counter" id="product_counter" value="0" />
   <input type="hidden" name="order_total_amount" id="order_total_amount"
      value="<?php echo $ArrFieldData['order_total_amount']; ?>" />
   <input type="hidden" name="newtotal" id="newtotal" value="" />
   <input type="hidden" name="newpaymentcapture" id="newpaymentcapture"
      value="<?php echo $ArrFieldData['order_total_amount']; ?>">
   <input type="hidden" name="order_amt_without_tax" id="order_amt_without_tax"
      value="<?php echo $ArrFieldData['order_amount']; ?>">
   <input type="hidden" id="NewTotalA" name="NewTotalA">
   <input type="hidden" id="total_order_without_taxw" name="total_order_without_taxw">
   
      

   <div class="row">
      <div class="col-sm-3">
         <label>Order ID : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['order_id']; ?>
         </label>
      </div>
      <div class="col-sm-3">
         <label>Order Date : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['created_datetime']; ?>
         </label>
      </div>
      <div class="col-sm-3">
         <label>Order Amount : </label>
         <label style="font-weight:400; line-height: 20px;">$
            <span id="labelOrderAmount"><?php echo $ArrFieldData['order_total_amount']; ?></span>
         </label>
      </div>
      <?php if ($ArrFieldData['order_notes'] != '') { ?>
         <div class="col-sm-4">
            <label>Order Notes : </label>
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['order_notes']; ?>
            </label>
         </div>
      <?php } ?>
      <?php if ($ArrFieldData['delivery_comments'] != '') { ?>
      <div class="col-sm-4">
         <label>Delivery Notes : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['delivery_comments']; ?>
         </label>
      </div>
      <?php } ?>
      
      <?php $cal_per_amt = 0; $total_disp_amt = 0;?>
      <?php if ($ArrFieldData['promotional_code'] != '') { 
         $promo_dis_val = $ArrFieldData['promo_dis_val'];
         $dis_type=$ArrFieldData['promo_dis_type'];
         $total_diff_amt = $ArrFieldData['DiffAmount'];
         if($dis_type=='%'){
            $cal_per_amt = ($promo_dis_val/100) * $total_diff_amt;
            $total_disp_amt = $total_diff_amt - $cal_per_amt;
            $total_disp_amt = number_format($total_disp_amt,1);
         }else{
            $total_prod = count($ArrOrderProduct);
            $total_order_sum = $ArrFieldData['TotalCaptureAmount'];
            $find_perc = ($total_prod / $total_order_sum) * 100;
            $cal_per_amt = ($find_perc/100) * $total_diff_amt;
            $total_disp_amt = $total_diff_amt - $cal_per_amt;
            $total_disp_amt = number_format($total_disp_amt,1);
         }
      ?>
     
      <div class="col-sm-4">
         <label>Coupon Code : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['promotional_code']; ?>
         </label>
      </div>
      <?php } else{
         $total_disp_amt = $ArrFieldData['DiffAmount'];
         }?>
      <div class="col-sm-12">

         <label style="font-weight:400; line-height: 20px;">

            <?php
            if ($ArrFieldData['is_replace_item'] == 0) {

               echo 'No, please refund for unavailable items';

            } else if($ArrFieldData['is_replace_item'] == 1){

               echo 'Yes, please substitute unavailable items with similar products';

            } else if($ArrFieldData['is_replace_item'] == 2){
               echo 'Substitute on selected products only';
            } else if($ArrFieldData['is_replace_item'] == 3){
               echo 'Substitute on entire order';
            } else {
               echo 'No, please refund';
            }

            ?>

         </label>

      </div>

      <?php if (!empty($ArrFieldData['substitution_products'])) { ?>
      <div class="col-sm-12">
         <strong>Substitution Products: </strong>
         <label style="font-weight:400; line-height: 20px;">
            <?php 
            $substitution_products = $ArrFieldData['substitution_products'];
            $substitution_product_string = "";
            for($i = 0; $i < count($substitution_products); $i++){
               if($substitution_product_string != ""){
                  $substitution_product_string .= ", " . $substitution_products[$i]->product_name;
               } else {
                  $substitution_product_string = $substitution_products[$i]->product_name;
               }
            }
            echo $substitution_product_string; ?>
         </label>
      </div>
      <?php } ?>

      <div class="col-sm-4">
         <label>Total Amount : </label>
         <label style="font-weight:400; line-height: 20px;" id="total_amount_final">$
            <?php echo $ArrFieldData['order_total_amount']; ?>
         </label>
      </div>


   </div>

   <div class="row">
      <div class="col-sm-12">
         <label>
            <h4>Order Products</h4>
         </label>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12">
         <?php
         if (is_array($ArrOrderProduct) && count($ArrOrderProduct) > 0) {
            ?>
            <table border="1" width="100%" id="product">
               <thead>
                  <tr>
                     <th>Product Name</th>
                     <th>Quantity</th>
                     <th>Price (in $)</th>
                     <th>Product Tax (in $)</th>
                     <th>Total (in $)</th>
                     <?php if ($editAllow == 'Yes') { ?>
                        <th>
                           
                              <a href="javascript:void(0);" onClick="addProductRow()" title="Click here to add new product">
                                 <img src="<?php echo admin_media(); ?>dist/img/plus.png">
                              </a>
                        
                        </th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  $total_qty = $net_total = $total_tax = 0;
                  //  print_r($ArrOrderProduct);
                  foreach ($ArrOrderProduct as $arr) {
                     $total_qty += $arr['qty'];
                     $net_total += $arr['total_amount'] + $arr['product_tax_amount'];
                     $total_tax += $arr['product_tax_amount'];
                     // echo $net_total;
                     ?>
                     <tr>
                        <td>
                           <input type="hidden" name="ArrOrderProductIds[]" value="<?php echo $arr['order_product_id']; ?>">
                          
						<?php if ($arr['product_variant_size'] == "") {
                                echo $arr['product_name'];
                            } else {
                                echo $arr['product_name'] . ' (' . $arr['product_variant_size'] . ' LB)';
                            } ?>
                        </td>
                        <td>
                           <input type="text" placeholder="QTY" class="form-control qty only_number"
                              id="qty<?php echo $arr['order_product_id']; ?>"
                              name="qty[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['qty']; ?>" required
                              onChange="updateProductTotalAmount(this.value,<?php echo $arr['order_product_id']; ?>);" <?php if ($editAllow != 'Yes') { echo 'readonly'; }?>>
							<input type="hidden" id="oldqty<?php echo $arr['order_product_id']; ?>" name="oldqty[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['qty']; ?>">
                        </td>

                        <td>
                           <input type="text" placeholder="Unit Price" class="form-control"
                              id="unit_price<?php echo $arr['order_product_id']; ?>"
                              name="unit_price[<?php echo $arr['order_product_id']; ?>]"
                              value="<?php echo $arr['unit_price']; ?>" required readonly>
                        </td>
                        <?php if(!empty($arr['product_tax_amount'])):
                              $ptax_amount = $arr['product_tax_amount'];
                           else:
                              $ptax_amount = 0.00;
                           endif; ?>
                        <td>
                           <input type="text" placeholder="Product Tax" class="form-control total_taxamount"
                              id="product_tax<?php echo $arr['order_product_id']; ?>"
                              name="product_tax[<?php echo $arr['order_product_id']; ?>]"
                              value="<?php echo $ptax_amount; ?>" required readonly>
                        </td>

                        <td>
                           <input type="text" placeholder="Total Amount" class="form-control total_amount"
                              id="total_amount<?php echo $arr['order_product_id']; ?>"
                              name="total_amount[<?php echo $arr['order_product_id']; ?>]"
                              value="<?php echo $arr['total_amount'] + $arr['product_tax_amount']; ?>" required readonly>

                              <input type="hidden" class="form-control total_new_amount"
                              id="total_new_amount<?php echo $arr['order_product_id']; ?>"
                              value="<?php echo $arr['total_amount'];?>">
                        </td>
                        
                           <?php if ($editAllow == 'Yes') { ?>
                              <td>
                                 <a href="javascript:void(0);" class="remove-button" title="Click here to remove product"
                                    data="<?php echo $arr['order_product_id']; ?>">
                                    <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                                 </a>
                              </td>
                           <?php } ?>
                       
                     </tr>
                  <?php } ?>
               </tbody>
               <tfoot>
                  <tr>
                     <td>Total:</td>
                     <td id="divQTYTotal" style="padding: 6px 13px;">
                        <?php echo $total_qty; ?>
                     </td>
                     <td style="padding: 6px 13px;">--</td>
                     <td id="divTaxTotal" style="padding: 6px 13px;">
                     <?php echo $total_tax; ?>
                     </td>
                     <td id="divNetTotal" style="padding: 6px 13px;">
                        <?php echo $net_total; ?>
                     </td>
                  </tr>
               </tfoot>
            </table>

         <?php } ?>
      </div>
   </div>
   <?php //echo'<pre>';print_r($ArrFieldData);exit; ?>
   <div class="row">
   <input type="hidden" name="total_or_qty" id="total_or_qty">
      <div class="col-sm-4">
         <!--<select class="form-control select_product_ajax" style="width: 100%;" name="ddIsActive" id="ddIsActive">
                  <option value="1">one</option>
                  <option value="2">two</option>
                  <option value="3">three</option>
                  </select>-->
         <label>Shipping Charge : </label>

         <input type="text" placeholder="Shipping Charge" class="form-control" name="shipping_charge"
            id="shipping_charge" value="<?php echo $ArrFieldData['fedex_shipping_charge']; ?>"
            onChange="updateFooterTotal();" required <?php if ($editAllow == 'No') {
               echo 'readOnly';
            } ?>>

      </div>

      <div class="col-sm-4">
         <label>Tip Amount : </label>
         <input type="text" placeholder="Order Tip" class="form-control" name="order_tip" id="order_tip"
            value="<?php echo $ArrFieldData['order_tip']; ?>" onChange="updateFooterTotal();" required <?php if ($editAllow == 'No') {
                   echo 'readOnly';
                } ?>>
      </div>


      <div class="col-sm-4">
         <label>Order Discount : </label>
         <input type="text" placeholder="Order Discount" class="form-control" name="discount_amount"
            id="discount_amount" value="<?php echo $ArrFieldData['discount_amount']; ?>"
            onChange="updateFooterTotal();" required <?php if ($editAllow == 'No') {
               echo 'readOnly';
            } ?>>
      </div>

      <div class="col-sm-4">
         <label>Preparation Cost: </label>
         <input type="text" placeholder="Preparation Cost" class="form-control" id="preparation_cost"
            name="preparation_cost" value="<?php echo $ArrFieldData['preparation_cost']; ?>"
            onChange="updateFooterTotal();" required readonly>
      </div>

      <div class="col-sm-4">
         <label>Packaging Cost: </label>
         <input type="text" placeholder="Packaging Cost" class="form-control" id="packaging_cost" name="packaging_cost"
            id="discount_amount" value="<?php echo $ArrFieldData['packaging_cost']; ?>" onChange="updateFooterTotal();"
            required readonly>
      </div>

      <div class="col-sm-4" style="display:none;">
         <label>State Tax:(in %)</label>
         <input type="text" placeholder="State Tax" class="form-control" name="order_total_tax" id="order_total_tax"
            value="<?php echo $ArrFieldData['state_tax']; ?>" onChange="updateFooterTotal();" required readonly>
      </div>

      <div class="col-sm-4">
         <label>Order Status : </label>
         <?php
		 if($order_status=='Payment Processed')
		 {
			 echo "Payment Processed";
		 }
		 else
		 {
		 echo form_dropdown('order_status', $ArrOrderStatus, $order_status, 'id="order_status" class="form-control"');
		 }
         ?>
      </div>

      <div class="col-sm-4">

         <label>Delivery Date : </label>

         <input type="text"

            value="<?php echo (isset($ArrFieldData['delivery_datetime']) && $ArrFieldData['delivery_datetime']!='0000-00-00 00:00:00') ? date('Y-m-d H:i:s', strtotime($ArrFieldData['delivery_datetime'])) : ''; ?>"

            placeholder="Delivery Date" name="delivery_datetime" id="delivery_datetime"

            class='date_time_picker_bottom_left form-control' autocomplete="false">

         <?php //echo fornm_error('password'); ?>

      </div>

      <div class="col-sm-4">
         <label>Earned Credit: </label>
         <input type="hidden" name="order_credit_per" value="<?php echo $ArrFieldData['order_credit_per']; ?>">
         <input type="text" placeholder="Earned Credit" class="form-control" id="earned_credit" name="earned_credit"
           value="<?php echo $ArrFieldData['earned_trans_credit']; ?>"required readonly>
      </div>

      <div class="col-sm-4">
         <label>Used Credit: </label>
         <input type="text" placeholder="Used Credit" class="form-control" id="used_credit" name="used_credit"
           value="<?php echo $ArrFieldData['used_trans_credit']; ?>"
            required readonly>
      </div>
   </div>

   <div class="row">
      <div class="col-sm-6">
         <label>Billing Address : </label>
         <div>
            <?php echo 'Name:' . $ArrFieldData['shipping_first_name'] . ' ' . $ArrFieldData['shipping_last_name'] . '<br>' . $ArrFieldData['billing_street_name'] . ' ' . $ArrFieldData['billing_apartment_name'] . ' , ' . $ArrFieldData['billing_city'] . ' , ' . $ArrFieldData['billing_state_name'] . ' , ' . $ArrFieldData['billing_zipcode'] . '<br>Contact No. ' . $ArrFieldData['billing_phone']; ?>
         </div>
      </div>

      <div class="col-sm-6">
         <label>Shipping Address : </label>
         <div>
            <?php echo 'Name:' . $ArrFieldData['shipping_first_name'] . ' ' . $ArrFieldData['shipping_last_name'] . '<br>' . $ArrFieldData['shipping_street_name'] . ' ' . $ArrFieldData['shipping_apartment_name'] . ' , ' . $ArrFieldData['shipping_city'] . ' , ' . $ArrFieldData['shipping_state_name'] . ' , ' . $ArrFieldData['shipping_zipcode'] . '<br> Email: ' . $ArrFieldData['shipping_email'] . '<br>Contact No. ' . $ArrFieldData['shipping_phone']; ?>
         </div>
      </div>
   </div>


   <div class="row">
      <div class="col-sm-12">
         <br>
         <button type="button" class="btn btn-default" name="submit" id="submit" value="Submit">Update Order</button>
         <button type="button" class="btn btn-default print_label_btn">Print Label</button>
      </div>
   </div>

</form>
<form class="form-horizontal print_label_form" action="<?php echo base_url(); ?>adminpanel/controller_order/order_label_pdf" role="form" method="post"
      accept-charset="utf-8" style="display:none;">
      <br>
      <div class="row">
         <div class="col-sm-4">
            <label>No of Boxes : </label>
            <input type="hidden" name="order_id" class="lorder_id" value="<?php echo $ArrFieldData['order_id']; ?>">
            <select name="no_of_box" id="no_of_box" class="form-control">
               <option value="">--Select Boxes--</option>
               <option value="1" selected="selected">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
               <option value="7">7</option>
               <option value="8">8</option>
               <option value="9">9</option>
               <option value="10">10</option>
               <option value="11">11</option>
               <option value="12">12</option>
               <option value="13">13</option>
               <option value="14">14</option>
               <option value="15">15</option>
               <option value="16">16</option>
               <option value="17">17</option>
               <option value="18">18</option>
               <option value="19">19</option>
               <option value="20">20</option>
            </select>
         </div>
      </div>
      <div class="row">
      <div class="col-sm-12">
         <br>
         <button type="submit" class="btn btn-default" name="submit" id="submit" value="Submit">Generate Label PDF</button>
      </div>
   </div>
</form>
<?php if (!$is_payment_received) { ?>


    <script type="text/javascript">
      //set your publishable key
      Stripe.setPublishableKey('<?php echo STRIPE_PUBLISHABLE_KEY ?>');

      //callback to handle the response from stripe
      function stripeResponseHandler(status, response) {
        if (response.error) {
          //enable the submit button
          $('#payBtn').removeAttr("disabled");
		  
          //display the errors on the form
          $('#payment-errors').addClass('alert alert-danger');
          $("#payment-errors").html(response.error.message);
        } else {
          var form$ = $("#paymentFrm");
          //get token id
          var token = response['id'];
		  
          //insert the token into the form
          form$.append("<input type='hidden' name='stripeTempToken' value='" + token + "' />");
          //submit form to the server
          form$.get(0).submit();
        }
      }
	  
	  
		
    </script>
   <!-- <form class="form-horizontal" action='' id="paymentFrm" role="form" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
      <br>
      <div class="row">
         <div class="col-sm-4">
            <input type="hidden" name="payment_intent_id" value="<?php echo $ArrFieldData['payment_intent_id']; ?>" />
            <input type="hidden" name="order_id" value="<?php echo $ArrFieldData['order_id']; ?>" />
            <input type="hidden" name="stripeToken" value="<?php echo $ArrFieldData['payment_intent_id'] . "-" . $ArrFieldData['order_id']; ?>" />
            <input type="hidden" name="amount_to_capture" id="amount_to_capture"  value="<?php echo $ArrFieldData['order_total_amount']; ?>" />
            <input type="hidden" name="blocked_amount" id="blocked_amount"  value="<?php echo $ArrFieldData['blocked_amount']; ?>" />
			   
			<input type="hidden" name="name" id="name" value="<?php echo $ArrFieldData['shipping_first_name'] . ' ' . $ArrFieldData['shipping_last_name']; ?>">
			<input type="hidden" name="email" id="email" value="<?php echo $ArrFieldData['shipping_email']; ?>">
			<input type="hidden" name="zip_code" id="zip_code" value="<?php echo $ArrFieldData['billing_zipcode']; ?>">
			<input type="hidden" name="card_num" id="card_num" value="<?php echo $ArrUser['card_num']; ?>">
			<input type="hidden" name="exp_month" id="card-expiry-month" value="<?php echo $ArrUser['card_exp_month']; ?>">
			<input type="hidden" name="exp_year" id="card-expiry-year" value="<?php echo $ArrUser['card_exp_year']; ?>">
			<input type="hidden" name="cvc" id="card-cvc" value="<?php echo $ArrUser['card_cvc']; ?>">
         </div>
         <div class="col-sm-4">
            <button type="button" id="payBtn" class="btn btn-success">Capture Payment</button>
         </div>
      </div>
   </form> -->
<?php } ?>

<?php if($ArrFieldData['is_revert'] == 1 || $ArrFieldData['is_capture'] == 1){ ?>
   <form class="form-horizontal" action='<?php echo base_url('order/diffrence/payment/'.$ArrFieldData['order_id']); ?>' id="diffpaymentFrm" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
      <br>
      <div class="row">         
         <div class="col-sm-4">
            <?php if(!empty($total_disp_amt)):
               $diff_am = $total_disp_amt;
            else:
               $diff_am = $ArrFieldData['DiffAmount'];
            endif;
            ?>

<?php if($ArrFieldData['is_capture'] == 1){
         if(!empty($ArrFieldData['diff_coupon_discount'])){     
            $tval = $ArrFieldData['DiffAmount'] ;
         }else{
            $tval = $ArrFieldData['DiffAmount'];
         }
    }  else {
      $tval = $ArrFieldData['DiffAmount'];
    }

    ?>
            <input hidden="diffamount" name="diffamount" id="diffamount" value="<?php echo $tval; ?>" />
            
            <?php if($ArrFieldData['is_capture'] == 1) { ?>
               <input hidden="difftype" id="difftype" name="difftype" value="capture" />
              
            <button type="button" id="orderdiffBtn" class="btn btn-success">Capture Payment - $ <?php echo $tval; ?></button>
            <?php } else { ?>
               <input hidden="difftype" id="difftype" name="difftype" value="revert" />
               <button type="button" id="orderdiffBtn" class="btn btn-success">Refund Payment - $ <?php echo $tval; ?></button>
            <?php }?>
         </div>
      </div>
   </form>
   <br>
<?php } ?>

<script>
   $(document).ready(function () {

      $("#orderdiffBtn").click(function () {       
         var type = $("#difftype").val();         
         $.ajax({
            url: $('#diffpaymentFrm').attr('action'),
            type: 'POST',
            dataType: 'json',
            data: $('form#diffpaymentFrm').serialize(),
            success: function (data) {

               $(".close").click();
               
               if(data.is_successful == "1"){
                  
                  /* toastr.success('Order payment captured successfully!!!!');
                     
                  } else if(type == "revert"){
                     toastr.success('Order payment refunded successfully!!!!');
                     toastr.success('Order payment refunded successfully!!!!');
                  } */
                  toastr.success(data.success_message);
                  location.reload();
               } else {
                  toastr.error(data.errors);
               }
            },
            error: function (xhr, status, error) {
               $(".close").click();
               var response = JSON.parse(xhr.responseText);
               toastr.error('Error: ' + response.errors || 'An unexpected error occurred.');
            }
         });
      });
      $("#product").on('click', '.remove-button', function (e) {
         if (confirm("Do you want to remove order product?") == true) {
            RemoveProductFromOrder($(this).attr("data"));
            var whichtr = $(this).closest("tr");
            whichtr.remove();
            var product_counter = parseInt($("#product_counter").val());
            product_counter = product_counter - 1;
            $("#product_counter").val(product_counter);
            updateFooterTotal();
         }
      });
   });
   $("#submit").click(function () {
      $.ajax({
         url: '<?php echo site_url(); ?>update-order-process/',
         type: 'POST',
         data: $('form#banner_frm').serialize(),
         success: function (data) {
            toastr.success('Order has been updated successfully!');
            $("#searchSubmit").click();
            $(".close").click();
            location.reload();
         }
      });
   });
   $("#payBtn").click(function () {
	   //alert('payBtn');
	   //create single-use token to charge the user
          Stripe.createToken({
            number: $('#card_num').val(),
            cvc: $('#card-cvc').val(),
            exp_month: $('#card-expiry-month').val(),
            exp_year: $('#card-expiry-year').val()
          }, stripeResponseHandler);
		 //alert('tettt');
      $.ajax({
         url: '<?php echo site_url(); ?>order-payment-capture/',
         type: 'POST',
         data: $('form#paymentFrm').serialize(),
         success: function (data) {
			 console.log(data);
			//alert('data');
             location.reload();
            toastr.success('Order payment captured successfully!!!!');
            $(".close").click();
            
         }
      });
   });
   function addProductRow() {
      var product_counter = parseInt($("#product_counter").val());
      product_counter = product_counter + 1;
      $("#product_counter").val(product_counter);
     // var data = '<tr><td><select name="ArrNewProducts[]" id="" onChange="SetPrice(this.value,' + product_counter + ');" class="form-control select_product_ajax" required></select></td><td><input type="text" placeholder="QTY" class="form-control" id="qty' + product_counter + '" name="ArrNewQty[]" value="" required onChange="updateProductTotalAmount(this.value,' + product_counter + ');"></td><td><input type="text" placeholder="Unit Price" class="form-control" id="unit_price' + product_counter + '" name="ArrNewUnit_price[]" value="" required readonly></td><td><input type="text" placeholder="Product Tax" class="form-control" id="product_tax' + product_counter + '" name="ArrNewproduct_tax[]" value="" required readonly></td><td><input type="text" placeholder="Total Amount" class="form-control total_amount" id="total_amount' + product_counter + '" name="ArrNewTotal_amount[]" value="" required readonly></td><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove product"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td></tr>';
      //$('#product').append(data);
      $.ajax({
         type: "POST",
         url: "<?php echo site_url(); ?>getCartProductRow/" + product_counter,
         success: function (data) {
            $('#product').append(data);
            $('.select_product_ajax').select2();
            $('.only_number').keypress(function(event) {
               if (!(event.which >= 48 && event.which <= 57)) {   
                  event.preventDefault();
               }
            });
            //ajax_page_drop_down();
         }
      });
   }

   function RemoveProductFromOrder(order_product_id) {
      $.ajax({
         type: "POST",
         url: "<?php echo site_url(); ?>remove-order-process/" + order_product_id,
         success: function (data) {
            updateFooterTotal();
         }
      });
   }

   function SetPrice(val, product_counter) {
      console.log(val);
      const ArrProductDetails = val.split("|");
      //Format: product_id-variant_id-variant_price-product_name
      $("#qty" + product_counter).val(1);
      $("#unit_price" + product_counter).val(ArrProductDetails[2]);
      var product_tax = ArrProductDetails[4];
      
      var state_tax_percentage = $("#state_tax_percentage").val();
      var product_tax_amount = 0;
      //if flag - product_tax = 0 then calculate product tax
      if (product_tax == 1) {
         product_tax_amount = parseFloat((1 * ArrProductDetails[2] * state_tax_percentage) / 100).toFixed(2);
         $("#product_tax" + product_counter).val(product_tax_amount);
      } else {
         $("#product_tax" + product_counter).val('0.00');
      }
      if (product_tax == 1) {
         product_tax_amount = parseFloat((1 * ArrProductDetails[2] * state_tax_percentage) / 100);
      }

      $("#total_amount" + product_counter).val(parseFloat((1 * ArrProductDetails[2]) + product_tax_amount).toFixed(2));
      $("#total_new_amount" + product_counter).val(parseFloat((1 * ArrProductDetails[2])).toFixed(2));
      updateFooterTotal();
   }

   function updateProductTotalAmount(val, product_counter) {
      var product_tax_amount = 0;
      var state_tax_percentage = $("#state_tax_percentage").val();
      if ($("#product_tax" + product_counter).val() > 0) {
         product_tax_amount = parseFloat((val * $("#unit_price" + product_counter).val() * state_tax_percentage) / 100);
        // product_tax_amount = parseFloat((val * $("#unit_price" + product_counter).val() * $("#product_tax" + product_counter).val()) / 100);
      }
      var temp_total = parseFloat(val * $("#unit_price" + product_counter).val() + product_tax_amount);
      var temp_total_without_tax = parseFloat(val * $("#unit_price" + product_counter).val());
      $("#total_amount" + product_counter).val(temp_total.toFixed(2));
      $("#total_new_amount" + product_counter).val(temp_total_without_tax.toFixed(2));
      $("#product_tax" + product_counter).val(product_tax_amount.toFixed(2));
      updateFooterTotal();
   }
   var NewTotalA = 0;
   $('.total_new_amount').each(function () {
         NewTotalA = NewTotalA + parseFloat($(this).val());
         // alert($(this).val());
      });
      $('#total_order_without_taxw').val(NewTotalA);
      var QTYTotals = 0;
      $('.qty').each(function () {
         QTYTotals = QTYTotals + parseInt($(this).val());
      });
      $('#total_or_qty').val(QTYTotals);
   function updateFooterTotal() {
      
      var NetTotal = 0;
      var TaxTotal = 0;
      var NewTotalA = 0;
      $('.qty').each(function () {
         QTYTotal = QTYTotal + parseInt($(this).val());
      });
      $('.total_amount').each(function () {
         NetTotal = NetTotal + parseFloat($(this).val());
         // alert($(this).val());
      });
      $('.total_taxamount').each(function () {
         TaxTotal = TaxTotal + parseFloat($(this).val());
         // alert($(this).val());
      });
      $('.total_new_amount').each(function () {
         NewTotalA = NewTotalA + parseFloat($(this).val());
         // alert($(this).val());
      });

      $("#divQTYTotal").html(QTYTotal);
      
      //$("#divNetTotal").html(NetTotal);
      //NetTotal = Math.round(NetTotal).toFixed(2);
      $("#divNetTotal").html(NetTotal.toFixed(2));
      $("#NewTotalA").val(NewTotalA.toFixed(2));
      $("#divTaxTotal").html(TaxTotal.toFixed(2));
      var totalordrvalue = $("#order_total_amount").val();
      //update capture amount
      var total = (parseFloat(NetTotal.toFixed(2)) + parseFloat($("#shipping_charge").val()) + parseFloat($("#order_tip").val()) + parseFloat($("#preparation_cost").val()) + parseFloat($("#packaging_cost").val())).toFixed(2);
      console.log(total);

      var capture_total_extra = (parseFloat(total) - parseFloat(totalordrvalue)).toFixed(2);

      var newtotal = $("#newtotal").val(total);
      $("#total_amount_final").html('$' + total);
      $("#labelOrderAmount").html(total);
      console.log(capture_total_extra);

      var capture_total = total - parseFloat($("#discount_amount").val());

      $("#newpaymentcapture").val(capture_total_extra);

      $("#amount_to_capture").val(capture_total_extra);

   }

   $(document).on('click','.print_label_btn',function(){
      $('.print_label_form').toggle();
   });

   $('.only_number').keypress(function(event) {
      if (!(event.which >= 48 && event.which <= 57)) {  
            event.preventDefault();
      }
   });
</script>

<?php if(is_array($ArrOrderTransactions) && count($ArrOrderTransactions)>0) { ?>
<label>Transactions : </label>
   <table border="1" width="100%" id="transaction">
      <thead>
         <th>Transaction ID</th>
         <th>Method</th>
         <th>Type</th>
         <th>Status</th>
         <th>Amount ($)</th>
         <th>Date & Time</th>
      </thead>
      <tbody>
         <?php foreach($ArrOrderTransactions as $data) { ?>
         <tr>
            <td><?php echo $data['transaction_id']; ?></td>
            <td><?php echo $data['payment_process_type']; ?></td>
            <td><?php echo ucfirst($data['payment_type'] ?? ''); ?></td>
            <td><?php echo $data['payment_intent_status']; ?></td>
            <td><?php echo $data['transaction_amount']; ?></td>
            <td><?php echo $data['transaction_datetime']; ?></td>
         </tr>
         <?php } ?>
      </tbody>
   </table>
<?php } ?>