<?php
	drupal_add_js('/iframes/common/js/lmes.js');
?>
<script>
	jQuery(document).ready(function(){
		for(var i=0;i<availableTags.length-1;i++){
			var a = document.createElement('A');
			jQuery(a).html(availableTags[i]);
			jQuery(a).attr('href', '/'+lmeAliasList[i]);
			jQuery(a).addClass('link');
			var targetC = 'one';
			if(i+1 > 17){targetC = 'two';}
			if(i+1 > 33){targetC = 'three';}
			if(i+1 > 50){targetC = 'four';}
			jQuery('.column-'+targetC).append(a);
		}
		jQuery('#mainLmeNav').height(Math.max(jQuery('.column-one').height(),jQuery('.column-two').height(),jQuery('.column-three').height(),jQuery('.column-four').height())+30);
		jQuery('#lmeNav').click(function(){
			jQuery('#mainLmeNav').fadeIn(function(){
				jQuery('#mainLmeNav').addClass('visible');
			});
			jQuery('#mainLmeNav').height(Math.max(jQuery('.column-one').height(),jQuery('.column-two').height(),jQuery('.column-three').height(),jQuery('.column-four').height())+40);
			jQuery('#closeLmeNav').click(function(){
				jQuery('.visible').fadeOut(function(){
					jQuery('#mainLmeNav').removeClass('visible');
				});
			});
			jQuery('html').click(function(){
				jQuery('.visible').fadeOut(function(){
					jQuery('#mainLmeNav').removeClass('visible');
				});
			});
		});
		if(jQuery('.page-previous').attr('href') == '/lmes'){
			jQuery('.page-previous').css('display','none');
			
		}
		jQuery('.page-up').html('go to the LME portal').css({width:'15%', margin:0});
	});
</script>
<style>
#mainLmeNav{
	padding-left:2%;
	position:absolute;
	right: auto;
	left:50px;
	z-index:99999;
	width:850px;
	background-color: #5fbadd;
	display:none;
	line-height: 16px;
}
#mainLmeNav div{
	background-color: #5fbadd;
}
#mainLmeNav  .column-one{ position:absolute; left: 2%; width: 25%;}
#mainLmeNav  .column-two{position:absolute; left:27%; width: 25%;}
#mainLmeNav  .column-three{ position:absolute; left:52%; width: 25%;}
#mainLmeNav  .column-four{ position:absolute; left:75%; width: 25%;}
#mainLmeNav  .link{
	display:block;
	text-decoration: none !important;
	font-size:12px;
	font-family:"PT Sans", "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;
}
#mainLmeNav  .title{
	padding:5px 0;
	width:150px;
}
#lmeNav{
	float:right;
	padding:10px 10px;
	line-height:18px;
	background-color: #ededed;
	/*background: gray;
	color: white;*/
	position:relative;
	/*right:10px;*/
	top:5px;
	font-size:12px;
	cursor: pointer;
	display:inline;
}
#mainLmeNav #closeLmeNav{
	position:absolute;
	top:5px;
	right:10px;
	cursor:pointer;
	font-size:24px;
}
.page-links a{
	text-decoration: none !important;
	font-family: "PT Sans", "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 12px;
}
</style>
<div id="lmeNav">Choose an LME</div>
<div id="mainLmeNav">
	<div id="closeLmeNav">&#215;</div>
	<a href="/lmes" class="link title">&larr; go back to the LME Portal</a>
	<div class="column-one"></div>
	<div class="column-two"></div>
	<div class="column-three"></div>
	<div class="column-four"></div>
</div>