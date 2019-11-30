<style>

</style>
<div class="container">
	<div class="row">
		<div class="col-md-12" style="margin-top: 200px;">
			<h1 class="text-center">List of Registrants</h1>
			<h2>&nbsp;</h2>
		</div>
		<div clss="col-md-12">
			<?php foreach($data as $row) {?>
				<p>
					Name: <b><?=$row['name']?></b><br />
					College: <b><?=$row['college']?></b><br />
					Mobile No: <b><?=$row['phonenumber']?></b>
					<hr>
				</p>				
			<?php } ?>
		</div>
	</div>
</div>			
