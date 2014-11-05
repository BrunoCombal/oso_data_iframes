<?php
	drupal_add_js('/iframes/common/js/lmes.js');
?>
<script>
	//console.log(availableTags);
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
		jQuery('.main').height(Math.max(jQuery('.column-one').height(),jQuery('.column-two').height(),jQuery('.column-three').height(),jQuery('.column-four').height())+30);
		jQuery('#lmeNav').click(function(){
			if(jQuery('.main').css('display') == 'none'){
				//jQuery('.main').css('display','block');
				jQuery('.main').fadeIn();
				jQuery('.main').height(Math.max(jQuery('.column-one').height(),jQuery('.column-two').height(),jQuery('.column-three').height(),jQuery('.column-four').height())+40);
			}
			jQuery('#closeLmeNav').click(function(){
				jQuery('main').fadeOut();
			});
			jQuery('body').click(function(){
				if(jQuery('.main').isVisible){
					jQuery('.main').fadeOut();
				}
			});
		});
	});
</script>
<style>
.main{
	padding-left:2%;
	position:absolute;
	right: 0px;
	z-index:99999;
	width:850px;
	background-color: #5fbadd;
	/*border: 1px 1px 0px solid #CBCCCB;*/
	display:none;
}
.main div{
	background-color: #5fbadd;
}
.column-one{ position:absolute; left: 2%; width: 25%;}
.column-two{position:absolute; left:27%; width: 25%;}
.column-three{ position:absolute; left:52%; width: 25%;}
.column-four{ position:absolute; left:75%; width: 25%;}
.link{
	display:block;
	text-decoration: none !important;
	font-size:12px;
	font-family:"PT Sans", "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.title{
	padding:5px 0;
}
#lmeNav{
	position:absolute;
	right:0px;
	cursor: pointer;
}
#closeLmeNav{
	position:absolute;
	right:10px;
	cursor:pointer;
	font-size:24px;
}
</style>
<div id="lmeNav">Choose LME...</div>
<div class="main">
	<div id="closeLmeNav">&#215;</div>
	<a href="/lmes" class="link title">&larr; go back to the LME Portal</a>
	<div class="column-one"></div>
	<div class="column-two"></div>
	<div class="column-three"></div>
	<div class="column-four"></div>
</div>