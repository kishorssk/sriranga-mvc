<div class="container-fluid" style="margin: 100px 0px 0px 0px; padding-left:0px;padding-right:0px;">
	<div class="row">
		<div class="col-md-12 mt-5">
			<h1 class="text-center" style="font-weight: 100;">SRIRANGA&nbsp; AI&nbsp; ML&nbsp; CLUB</h1>
			<p class="text-muted text-center">Register now for the technical session on most happening field of IT industry! on December 8th 2019</p>
		</div>	
	</div>	
</div>
<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<form action="<?=BASE_URL?>workshop/register" method="POST">
			  <div class="form-group">
				<label for="name">Name*</label>
				<input type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp" placeholder="Enter Your Name" required>
			  </div>
			  <div class="form-group">
				<label for="exampleInputCollege">College</label>
				<input type="text" class="form-control" name="college" id="college" placeholder="Name of the College">
			  </div>
			  <div class="form-group">
				<label for="exampleInputPhonenumber">Phone No.*</label>
				<input type="text" class="form-control" name="phonenumber" id="phonenumber" minlength="10" maxlength="10" placeholder="Enter 10 digit mobile umber" required>
			  </div>
			  <div class="text-right">
				<button type="reset" class="btn btn-primary">Clear</button>&nbsp;
				<button type="submit" class="btn btn-primary">Submit</button>
			  </div>		
			</form>
		</div>
	</div>
</div>	
