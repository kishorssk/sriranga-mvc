<div class="container second-stage">
	<div class="row">
        <div class="col-sm-12 text-center">
            <h4>Order Details:</h4>
        </div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            <?php $details = json_decode($data,true);?>
            <div class="container">
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="name">Name: </label>   
                    </div>
                    <div class="col-sm-8" style="text-align: left;">
                        <label><?php echo $details['prefill']['name']; ?></label>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="mobile"> Mobile no : </label>     
                    </div>
                    <div class="col-sm-8" style="text-align: left;">
                        <label><?php echo $details['prefill']['contact']; ?></label>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="email"> Email ID : </label>   
                    </div>
                    <div class="col-sm-8" style="text-align: left;">
                        <label><?php echo $details['prefill']['email']; ?></label>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="quantity"> Quantity :</label>     
                    </div>
                    <div class="col-sm-8" style="text-align: left;">
                        <label><?php echo $details['prefill']['quantity']; ?></label>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="quantity"> Amount :</label>     
                    </div>
                    <div class="col-sm-8" style="text-align: left;">

                        <label><?php echo ($details['prefill']['currency'] == 'INR') ? '₹' :'$'; echo $details['amount']/100; ?></label>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col-sm-4">
                        <label for="address1"> Address : </label>  
                    </div>
                    <div class="col-sm-8" style="text-align: left;">
                        <label><?php echo $details['notes']['address1']; ?></label>
                        <br>
                        <label><?php echo $details['notes']['address2']; ?></label>
                        <br>
                        <label><?php echo $details['notes']['city']; ?> - </label>
                        <label><?php echo $details['notes']['pincode']; ?></label>
                        <br>
                        <label><?php echo $details['notes']['state']; ?></label>
                        <br>
                        <label><?php echo $details['notes']['country']; ?></label>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-sm-12 text-center"> 
            <button id="rzp-button1">Continue to Pay</button>
        </div>
	</div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="verify" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
</form>
<script>
// Checkout details as a json
var options = <?php echo $data?>;

/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>