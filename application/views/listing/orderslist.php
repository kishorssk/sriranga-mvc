<style>

</style>
<div class="container">
	<div class="row">
		<div class="col-md-12" style="margin-top: 200px;">
			<h1 class="text-center">List of Orders</h1>
			<h2>&nbsp;</h2>
		</div>
		<div clss="col-md-12">
			<?php foreach($data as $row) {?>
				<p>
					order id: <b><?=$row['order_id']?></b><br />
					username: <b><?=$row['username']?></b><br />
					Mobile No: <b><?=$row['user_mobile']?></b><br />
					Email: <b><?=$row['user_email']?></b><br />
					Quantity: <b><?=$row['order_quantity']?></b><br />
					Price: <b><?=$row['order_price']?></b><br />
					Razorpay order id: <b><?=$row['razorpay_order_id']?></b><br />
					Razorpay payment id: <b><?=$row['razorpay_payment_id']?></b><br />
					Date of order: <b><?=$row['order_created_at']?></b><br />
					Address1: <b><?=$row['user_address_1']?></b><br />
					Address2: <b><?=$row['user_address_2']?></b><br />
					City: <b><?=$row['user_city']?></b><br />
					State: <b><?=$row['user_state']?></b><br />
					Pincode: <b><?=$row['user_pincode']?></b><br />
					Country: <b><?=$row['user_country']?></b><br />
					Currency: <b><?=$row['currency']?></b><br />
					<hr>
				</p>				
			<?php } ?>
		</div>
	</div>
</div>			
