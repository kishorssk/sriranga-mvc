<div class="container second-stage">
	<div class="row">
		<div class="col-sm-12 text-center"> 
			<?php if($data['result']){ ?>
				<label> Thank you for ordering Sri Shankara Granthavali by T.K. Balasubrahmanyam.</label>
				<br>
				<label>Your paymnet was successful and Your Order id is <b style="color: red;"><?=$data['orderId']?> </b></label>
				<br>
				<label>Please make a note of order id for future reference.</label>
			<?php }else{?>
				<label>Sorry your payment was not successful.... Please Try again.</label>
				<br>
				<label>If amount is deducted then please contact Sriranga Digital.</label>
			<?php }?>
		</div>
	</div>
</div>