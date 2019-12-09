$(document).ready(function() {

	$("#quantity").change(function(){
		var quantity = $('#quantity').val();
		var unitPrice = parseInt($('#unitprice').text());
		var currency = $('#currency').val();
		var totalPrice = quantity * unitPrice;
		$('#totalprice').text(totalPrice);

	});

});
