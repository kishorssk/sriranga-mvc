<div class="container second-stage">
	<div class="row">
		<div class="col-sm-12 text-center"> 
            <div>Total Japa Count : </div>
            <div id="totalCount">  </div>
        </div>
	</div>
</div>
<script>
$(document).ready( function(){
    const BASE_URL = window.location.href;
    $.ajax({
        url: BASE_URL + "/totalcount",
        type:"GET",
        success: function(result){
            var obj = JSON.parse(result);
            console.log(obj);
            if(obj.result == 0){
                console.log(obj.data.Total);
                document.getElementById("totalCount").innerHTML = obj.data.Total;
            }else{
                alert(obj.data);
            }
        },error: function(error){
            console.log(error);
        }
    });
});
</script>