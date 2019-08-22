<div class="container dynamic-page" id="describeWord">
    <div class="row">
		<div class="col-md-12">
			<p id="prevNextWord">&nbsp;</p>
<?php foreach ($data as $row) { ?>
			<div class="word">
				<?php if($row['word']) { ?><h1 class="head-word"><?=$row['word']?></h1><?php } ?>
				<?php if($row['wordNote']) { ?><h3 class="head-word-note"><?=$row['wordNote']?></h3><?php } ?>
				<?php if($row['alias']) { ?><h2 class="alias-word"><?=$row['alias']?></h2><?php } ?>
				<?php if($row['aliasNote']) { ?><h3 class="alias-word-note"><?=$row['aliasNote']?></h3><?php } ?>
				<?php if($row['description']) { ?><div class="description"><?=$row['description']?></div><?php } ?>
			</div>
<?php } ?>
		</div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	// Next and previous links ajax requests
	var word = $('.word h1.head-word').html();
	$.get( '<?=BASE_URL?>api/getNeighbours/' + word, function( data ) {
		
		data = JSON.parse(data);
		
		var prevNextContent = '';

		prevNextContent += (data['prev']) ? '<a href="<?=BASE_URL?>describe/word/' + data['prev'] + '">&lt; prev</a>' : '<span>&lt; prev</span>';
		prevNextContent += ' | ';
		prevNextContent += (data['next']) ? '<a href="<?=BASE_URL?>describe/word/' + data['next'] + '">next &gt;</a>' : '<span>next &gt;</span>';
		
		$('#prevNextWord').html(prevNextContent);
	});

	// Word highlighting
    var searchText = decodeURIComponent(getUrlParameter('search'));

    $('.word .description').each(function(){

	    var html = $(this).html();
	    var re = new RegExp("\\b" + '(' + searchText + ')' + "\\b", "gi");
	    html = html.replace(re, '<span class="highlight">' + "$1" + '</span>');
    	$(this).html(html);
    });


    var highlight = $('.highlight');
    if(highlight.length) {
		var jumpLoc = $('.highlight').offset().top - $('#mainNavBar').height() - 50;
		$("html, body").animate({scrollTop: jumpLoc}, 500);
	}
});
</script>