<link rel="stylesheet" href="/iframes/common/data/frontend/front.css" />
<link rel="stylesheet" href="/misc/ui/jquery.ui.autocomplete.css" />
<style>
 .ui-autocomplete{max-height:100px, overflow-y:auto;overflow-x:hidden;}
 html .ui-autocomplete{height:400px} /* IE6 does not support max-height */
 .ui-widget{font-size:12px;}
 .ui-menu{background:white !important;}
</style>

<?php
drupal_add_library('system', 'ui.autocomplete');

if(user_is_logged_in()){
  if(isset($_POST['cache'])){
    include('/data/iframes/common/data/frontend/engine.php');
  }
?>
<form method="post" id="formCache" action="" enctype="multipart/form-data">
  <input id="formCacheBtn" type="submit" name="cache" value="Rebuild cache" />
</form>
<?php
}

$oo_file = '/data/iframes/common/data/frontend/oo.json';
$ooArray = json_decode(file_get_contents($oo_file),true);
$lmes_file = '/data/iframes/common/data/frontend/lmes.json';
$counterID = 0;
$lmesArray = json_decode(file_get_contents($lmes_file), true);
$nodata = "empty";
$fileC = "/data/iframes/common/data/services/downcount.json";
	$arrayC = array();
	if(file_exists($fileC)){
		$arrayC = json_decode(file_get_contents($fileC),true);
	}
?>

<script type="text/javascript">
jQuery(document).ready(function(){

	jQuery('#formCache').submit(function(e){
		jQuery('#formCacheBtn').attr('value', 'Rebuilding cache...');
		var el = document.createElement('IMG');
		jQuery(el).attr('src','/iframes/common/data/images/ajax-loader.gif');
		jQuery(el).insertAfter('#formCacheBtn');
		return;
	});

	jQuery(".accordionData .categoryData").click(function(){
		if(jQuery(this).parents('legend').siblings().is(":visible"))
		{
			jQuery(this).parents('legend').siblings().slideUp();
			jQuery(this).addClass('up');
			jQuery(this).removeClass('down');
		} else {
			jQuery(this).parents('legend').siblings().slideDown();
			jQuery(this).addClass('down');
			jQuery(this).removeClass('up');
		}
	});

	jQuery(".accordionData .itemData").click(function(){

		if(!jQuery(this).parents('.fsItem').find('.bodyData').is(':visible')){
			jQuery(this).removeClass('up');
			jQuery(this).addClass('down');
			jQuery(this).parents('.fsItem').find('.summaryNoDG').slideDown();
			jQuery(this).parents('.fsItem').find('.bodyData').slideDown();
			if ( jQuery(this).parents('.fsItem').find('.bodyData').children('p').html() == '' ){
				var uuid = jQuery(this).attr('name');
                if(uuid != undefined){
					jQuery(this).parents('.fsItem').find('.bodyData').children('p').load('http://onesharedocean.org/iframes/common/data/services/parser.php?uuid='+uuid, function(){
						unscrambleEmails();
					});
				}
			}
			jQuery(this).parents('.fsItem').find(".readMore").html("| hide details |");
		} else {
			jQuery(this).removeClass('down');
			jQuery(this).addClass('up');
			jQuery(this).parents('.fsItem').find('.bodyData').slideUp();
			jQuery(this).parents('.fsItem').find('.summaryNoDG').slideUp();
			jQuery(this).parents('.fsItem').find(".readMore").html("| show details |");
		}
	});

	jQuery(".readMore").click(function(){
           jQuery(this).parents('.fsItem').find('.itemData').click();
       })
		jQuery(".readMore").html("| show details |");
		jQuery('.viewData, .googleearthData, .downloadData').css('text-decoration', 'none');
		jQuery('.viewData').html("| view data |");
		jQuery('.googleearthData').html("| google earth |");
		jQuery('.googleearthData').attr('title', 'download a KMZ file to your computer, it opens in Google Earth');
		jQuery('.viewData').attr('title', 'click to display a map');
		jQuery('.viewData').attr('target', '_blank');
		jQuery('.downloadData').html("| download data |");
		jQuery('.mainUl').each(function(){
			jQuery(this).first().css('margin-top', '10px');
			jQuery(this).last().css('margin-top', '10px');
		});

		jQuery('.downloadData').click(function(c){
			var id = jQuery(this).closest('li').find('a').first().attr('name');
			jQuery.get("/iframes/common/data/services/downlog.php?id="+id, function(response) {
				jQuery('a[name="'+id+'"]').parent().find('.counterD').find('em').html(response);
			});
			return true;
		});


	//When coming from a referenced page with a given bookmark move to it and show it's contents
	var hash = document.location.hash;

	if ( hash != "" ) {
		var anchor = jQuery('a[name=\''+hash.substring(1, hash.length)+'\']');
		anchor = anchor[0];


		jQuery(anchor).parents('.collapsible').find('.categoryData').click();
		jQuery(anchor).siblings('.fsItem').find('.itemData').click();
		jQuery('html, body').animate({
				scrollTop: jQuery(anchor).siblings('.fsItem').find('.itemData').offset().top
			}, 1000);
	}

	jQuery('.numberNodes').each(function(){
		jQuery(this).parents('.collapsible').find('.categoryData').html(jQuery(this).parents('.collapsible').find('.categoryData').html()+' ('+jQuery(this).attr('ref')+')');
	});

	function unscrambleEmails(){
		var a = jQuery('.email').each(function(){
			var b = jQuery(this).html();
			jQuery(this).html(b.replace(/[a-zA-Z]/g, function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);}));
			jQuery(this).removeClass('email');
		});

	}
	unscrambleEmails();

	var searchTags = [];
	var searchIDs = [];
	var searchMoreText = [];
	jQuery('.fsItem .itemData').not('.center').each(function(){
		var div = document.createElement("div");
		div.innerHTML = jQuery(this).html();
		div.id="tempDiv";
		var text = div.textContent || div.innerText || "";
		var moreText = jQuery(this).parents('li').find('.searchable');
		var moreTextGlobal = [];
		jQuery(moreText).each(function(el){
			moreTextGlobal.push(stripCommonWords(this.textContent));
		});
		if(searchTags.indexOf(text)==-1){
			searchIDs.push(jQuery(this).parents('li').attr('id'));
			searchTags.push(text);
			searchMoreText.push(moreTextGlobal);
		}
		jQuery('#tempDiv').remove();
	});

	function stripCommonWords(input){
	input = input.replace(/\,/g, "");
	input = input.replace(/\_/g, "");
	input = input.replace(/\(/g, "");
	input = input.replace(/\)/g, "");
	var common = ["the","of","and","a","to","in","is","you","that","it","he","was","for","on","are","as","with","his","they","I","at","be","this","have","from","or","one","had","by","word","but","not","what","all","were","we","when","your","can","said","there","use","an","each","which","she","do","how","their","if","will","up","other","about","out","many","then","them","these","so","some","her","would","make","like","him","into","time","has","look","two","more","write","go","see","number","no","way","could","people","my","than","first","water","been","call","who","oil","its","now","find","long","down","day","did","get","come","made","may","part","TBD","_","tbd"];

		var strArray = input.split(" ");
		strArray = strArray.filter( function( el ) {
			return common.indexOf( el.toLowerCase() ) < 0;
		});
		return strArray;
	}

	var comboText = "Search available datasets";
	jQuery( "#resetSearch" ).css('color', '#c0c0c0');
	jQuery("#resetSearch")
		.click(function(){
			unhighlighter();
			//location.reload();
			jQuery('#mainResults').slideDown(400,function(){
				jQuery('#searchUL>li').each(function(){
					if(jQuery(this).find('.itemData').hasClass('down')){
						jQuery(this).find('.itemData').click();
					}
					jQuery(this).insertBefore(jQuery('#'+jQuery(this).attr('tempid')));
					jQuery('#'+jQuery(this).attr('tempid')).remove();
					jQuery( "#tags" ).css('color', '#c0c0c0');
					jQuery( "#tags" ).attr('value', comboText);
					jQuery('#resetSearch').attr('disabled', true);
					jQuery( "#resetSearch" ).css('color', '#c0c0c0');
				});
			});

	});
	jQuery( "#tags" ).css('color', '#c0c0c0');
	jQuery("#tags")
		.click(function(){
			this.value = "";
			jQuery( "#tags" ).css('color', '#000000');
		})
		.blur(function(){
			if(this.value ==""){
				jQuery( "#tags" ).css('color', '#c0c0c0');
				jQuery('#resetSearch').attr('disabled', true);
				this.value = comboText;
			}
		})
		.autocomplete({
			delay: 500,
			minLength: 3,
			source: function(request, response){
				var req = request.term.split(' ');
				//response(request.term);
				unhighlighter();
				var res =  [];
				for(var i=0;i<searchTags.length; i++){
					var flag = false;
					var flagM = [];
					for(var o=0;o<searchMoreText[i].length;o++){
						for(var u=0; u<searchMoreText[i][o].length;u++){
							for(var y=0;y<req.length;y++){
								if(searchMoreText[i][o][u].toLowerCase().indexOf(req[y].toLowerCase()) != -1){
									flagM.push(req[y]);
								}
								if(flagM.length == req.length){
									flag = true;
								}
							}
						}
					}
					if((flag == true) || (searchTags[i].toLowerCase().indexOf(request.term.toLowerCase()) != -1)){
						res.push(searchTags[i]);
						if(flag == true){
							jQuery('#'+searchIDs[i]+' .searchable').each(function(){
								for(var y=0;y<req.length;y++){
									highlighter(req[y], this);
								}
							});
						}
					}
				}
				response(res);


			},
			open: function(e, ui) {
				var list = '';
				var results = jQuery('.ui-autocomplete.ui-widget-content a');
				results.each(function() {
					list += jQuery(this).html() + '<br />';
				});
				jQuery('#results').html(list);
			},
			select: function(e, ui) {
				var li = document.createElement("li");
				var id = makeid();
				jQuery(li).attr('id', id);
				jQuery(li).css('display:none');
				var el = jQuery('#'+searchIDs[searchTags.indexOf(ui.item.value)]);
				el.attr('tempId', id);
				jQuery(li).insertAfter(el);
				jQuery('#searchUL>li').each(function(){
					if(jQuery(this).find('.itemData').hasClass('down')){
						jQuery(this).find('.itemData').click();
					}
				});
				jQuery('#searchUL').append(el);
				el.find('.itemData').click();
				jQuery('#searchResults').slideDown(400,function(){
					jQuery('#mainResults').slideUp();
				});
				jQuery('#resetSearch').attr('disabled', false);
				jQuery( "#resetSearch" ).css('color', '#000000');



			}
		});
		jQuery('#ooTotal').html(jQuery('#ooTotal_temp').html());
		jQuery('#lmesTotal').html(jQuery('#lmesTotal_temp').html());



function highlighter(word, element) {
    var rgxp = new RegExp(word, 'gi');
    var repl = '<span style="background:blue">' + word + '</span>';
	element.innerHTML = element.innerHTML.replace(rgxp, repl);
}
function unhighlighter(){
	for(var i=0;i<searchIDs.length; i++){
		jQuery('#'+searchIDs[i]).find('.searchable').each(function(){
			jQuery(this).find('span').each(function() {
				var nEl = document.createTextNode(this.innerHTML);
				jQuery(nEl).insertBefore(this);
				jQuery(this).remove();
			});
		});
	}
}

function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

}); //document ready
</script>

<div class="ui-widget" style="margin:auto auto; width:440px;">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; display:inline;" value="Search available datasets"/>
	<input id="resetSearch" type="button" value="Reset" disabled="disabled" style="display:inline;"></input>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important; background:white !important;" ></div>

	</div>
<div id="mainResults">


<span class="summarySection">Data are provided “as is”, without warranty of any kind.<br/>To contact the data originator, please see the contact point list attached to each dataset (click on <span style="font-variant: small-caps; font-weight: bold">| show details |</span> under a dataset name).</span>
<div class="accordionData">

<span class="titleSection">Open Ocean</span>
<span class="summarySection">See individual data contact point list to contact the data originator.<br/>For general purpose, the contact point at IOC-UNESCO is Albert Fischer, <span class="email"><?php echo utf8_encode(str_rot13('<a href="mailto://'.'a.fischer@unesco.org'.'">'.'a.fischer@unesco.org'.'</a>')); ?></span>.<br/>This section features a total of <span id="ooTotal" style="font-weight:bold"></span> unique datasets.</span>
<span id="ooTotal_temp" style="display:none"><?php echo count($ooArray[2]); ?></span>

<div id="accordion_oo">
<?php
$categoriesList = $ooArray[0];
$categoriesList_temp = $ooArray[1];
$tempOrder = $ooArray[2];
//var_dump($tempOrder[0][1]['vid']);
for($i=0;$i<count($categoriesList_temp);$i++) {
	echo "<div>";
	echo "<fieldset class='collapsible'><legend><span class='categoryData fieldset-legend up'>".$categoriesList[$i]."</span></legend>";
	echo "<div style='display:none'>";
	echo "<ul class='mainUl'>";
	$datagroup = '';
	$datagroupflag=false;
	$lastnodeingroup=false;
	$numberNodes = 0;
	foreach ($tempOrder as $node) {
		$node = $node[1];
		//$node = node_load($node['nid']);
		if ($node['status'] == 1) {
			foreach($arrayC as $arr){
				if($arr['id'] == $node['nid']){
					$node['counter'] = (string) $arr['counter'];
				}
			}
			$categories = trim(strtolower(str_replace(' ','',$node['categories'])));
			$nodeCats = explode(';',$categories);
			if(in_array($categoriesList_temp[$i], $nodeCats)){
				$numberNodes++;

				if(isset($node['group'])){
					$lastnodeingroup=true;
					if(($datagroupflag == false) || ($datagroup != $node['group'])){
						if($datagroupflag == true){
							echo '<li>&nbsp;</li>';
						}
						echo '<li><fieldset class="collapsible fsItem"><legend><span class="itemData fieldset-legend center">'.$node['group'].'</span></legend></fieldset></li>';
						$datagroupflag = true;
					}
					$datagroup = $node['group'];
				} else {
					$datagroup = '';
					$datagroupflag=false;
				}
				?>
				<li id="<?php $counterID++; echo 'item_'.$counterID; ?>" <?php if(($lastnodeingroup == true) && ($datagroup == '')){echo 'style="margin-top:10px;"';} ?>><a name="<?php echo $node['nid']; ?>"></a>
			<fieldset class="collapsible fsItem"><legend><span class="itemData fieldset-legend up <?php  if($datagroup != ''){ ?>datagroup<?php } ?>" <?php if(isset($node['xml'])){echo 'name="'.$node['xml'].'"';} ?>><?php echo $node['title']; ?></span>


			<span class="itemBtns">
				<span class="readMore"></span>
				<?php if(isset($node['view'])){ ?><a href="<?php echo $node['view']; ?>" class="viewData"></a><?php } ?>
				<?php if(isset($node['google_earth'])){ ?><a href="<?php echo $node['google_earth']; ?>" class="googleearthData"></a><?php } ?>
				<?php if(isset($node['package'])){ ?><a title="Download dataset from <?php echo $node['title']; ?>" href="<?php echo $node['package']; ?>" class="downloadData"></a><?php } ?>
			</span>
			</legend>
			<div class="summaryData <?php  if($datagroup != ''){ ?>summaryNoDG<?php } ?>"><?php  if(isset($node['summary'])){echo $node['summary'];}else{echo $nodata;} ?>
			<?php if(user_is_logged_in()){?><br/><span class="counterD">Dataset downloaded <em style="font-weight:bold"><?php if(isset($node['counter'])){ echo $node['counter']; }else{ echo "0";} ?></em> time(s)</span><?php } ?></div>
			<?php
				if(!isset($node['group'])){
					$lastnodeingroup=false;
				}
			?>


			<div class="clearfix"></div>
			<div class="bodyData">
				<div class="idData">
<ul class="xml">
 <li>
  <div class="fset"><span class="lgend">Indicator Description</span>
   <ul>
	<?php if(isset($node['category'])){	?><li class="divValue"><span class="title">Themes</span><span class="value"><?php echo $node['category']; ?></span></li><?php } ?>
    <?php if(isset($node['definition'])){ ?><li><span class="title">Definition</span><span class="value searchable"><?php echo $node['definition']; ?></span></li><?php } ?>
    <?php if(isset($node['relevance'])){ ?><li><span class="title">Relevance</span><span class="value"><?php echo $node['relevance']; ?></span></li><?php } ?>
    <?php if(isset($node['methodology'])){ ?><li><span class="title">Methodology</span><span class="value"><?php echo $node['methodology']; ?></span></li><?php } ?>
    <?php if(isset($node['data_source'])){ ?><li><span class="title">Data sources</span><span class="value"><?php echo $node['data_source']; ?></span></li><?php } ?>
    <?php if(isset($node['partners'])){ ?>
	<li><span class="title">Partners</span><span class="value"><?php echo $node['partners']; ?></span></li><?php } ?>

   </ul>
  </div>
 </li>
 </div>
  <p></p>
  </div>
  </fieldset>
</li>
<?php
			}
		}
	}
?>
</ul>
<span class="numberNodes" ref="<?php echo $numberNodes; ?>"></span>
</div>
</fieldset></div>
<?php
} //end Open Ocean
?>

</div>
</div>

<div class="accordionData" style="padding-top:10px">
<span class="titleSection">Large Marine Ecosystems</span>
<span class="summarySection">See individual data contact point list to contact the data originator.<br/>For General purpose, the contact point at IOC-UNESCO is Julian Barbière, <span class="email"><?php echo utf8_encode(str_rot13('<a href="mailto://'.'j.barbiere@unesco.org'.'">'.'j.barbiere@unesco.org'.'</a>')); ?></span><br/>This section features a total of <span id="lmesTotal" style="font-weight:bold"></span> unique datasets.</span>
<span id="lmesTotal_temp" style="display:none"><?php echo count($lmesArray[2]); ?></span>
<?php
$categoriesList = $lmesArray[0];
$categoriesList_temp = $lmesArray[1];
$tempOrder = $lmesArray[2];
for($i=0;$i<count($categoriesList_temp);$i++) {
	echo "<div>";
	echo "<fieldset class='collapsible'><legend><span class='categoryData fieldset-legend up'>".$categoriesList[$i]."</span></legend>";
	echo "<div style='display:none'>";
	echo "<ul class='mainUl'>";
	$datagroup = '';
	$datagroupflag=false;
	$lastnodeingroup=false;
	$numberNodes = 0;
	foreach ($tempOrder as $node) {
		$node = $node[1];
		if ($node['status'] == 1) {
			if(isset($node['categories'])){
				foreach($arrayC as $arr){
					if($arr['id'] == $node['nid']){
						$node['counter'] = (string) $arr['counter'];
					}
				}
				$categories = trim(strtolower(str_replace(' ','',$node['categories'])));
				$nodeCats = explode(';',$categories);
				if(in_array($categoriesList_temp[$i], $nodeCats)){

					$numberNodes++;
					?>
					<?php
				if(isset($node['group'])){
					$lastnodeingroup=true;
					$datagroup = $node['group'];
					if($datagroupflag == false){
						echo '<li><fieldset class="collapsible fsItem"><legend><span class="itemData fieldset-legend center">'.$datagroup.'</span></legend></fieldset></li>';
						$datagroupflag = true;
					}
				} else {
					$datagroup = '';
					$datagroupflag=false;
				}
				?>
				<li id="<?php $counterID++; echo 'item_'.$counterID; ?>" <?php if(($lastnodeingroup == true) && ($datagroup == '')){echo 'style="margin-top:10px;"';} ?>><a name="<?php echo $node['nid']; ?>"></a>
			<fieldset class="collapsible fsItem"><legend><span class="itemData fieldset-legend up <?php  if($datagroup != ''){ ?>datagroup<?php } ?>" <?php if(isset($node['xml'])){echo 'name="'.$node['xml'].'"';} ?>><?php echo $node['title']; ?></span>


			<span class="itemBtns">
				<span class="readMore"></span>
				<?php if(isset($node['view'])){ ?><a href="<?php echo $node['view']; ?>" class="viewData"></a><?php } ?>
				<?php if(isset($node['google_earth'])){ ?><a href="<?php echo $node['google_earth']; ?>" class="googleearthData"></a><?php } ?>
				<?php if(isset($node['package'])){ ?><a title="Download dataset from <?php echo $node['title']; ?>" href="<?php echo $node['package']; ?>" class="downloadData"></a><?php } ?>
			</span>
			</legend>
			<div class="summaryData <?php  if($datagroup != ''){ ?>summaryNoDG<?php } ?>"><?php  if(isset($node['summary'])){echo $node['summary'];}else{echo $nodata;} ?>
			<?php if(user_is_logged_in()){?><br/><span class="counterD">Dataset downloaded <em style="font-weight:bold"><?php if(isset($node['counter'])){ echo $node['counter']; }else{ echo "0";} ?></em> time(s)</span><?php } ?></div>


			<div class="clearfix"></div>
			<div class="bodyData">
				<div class="idData">
<ul class="xml">
 <li>
  <div class="fset"><span class="lgend">Indicator Description</span>
   <ul>
	<?php if(isset($node['module'])){	?> <li class="divValue"><span class="title">Module</span><span class="value"><?php echo $node['module']; ?></span></li><?php } ?>
	<?php if(isset($node['category'])){ ?><li><span class="title">Category</span><span class="value"><?php echo $node['category']; ?></span></li><?php } ?>
	<?php if(isset($node['definition'])){ ?><li><span class="title">Definition</span><span class="value searchable"><?php echo $node['definition']; ?></span></li><?php } ?>
    <?php if(isset($node['unit'])){ ?><li><span class="title">Unit</span><span class="value"><?php echo $node['unit']; ?></span></li><?php } ?>
    <?php if(isset($node['rationale'])){ ?><li><span class="title">Rationale for inclusion</span><span class="value"><?php echo $node['rationale']; ?></span></li><?php } ?>
    <?php if(isset($node['interlink'])){ ?><li><span class="title">Interlinkages with other transboundary water systems</span><span class="value"><?php echo $node['interlink']; ?></span></li><?php } ?>
	<?php if(isset($node['measurements'])){ ?><li><span class="title">Measurement methods and calculation</span><span class="value"><?php echo render($field); ?></span></li><?php } ?>
    <?php if(isset($node['scale'])){ ?><li><span class="title">Scale</span><span class="value"><?php echo $node['scale']; ?></span></li><?php } ?>
    <?php if(isset($node['data_source'])){ ?><li><span class="title">Data sources</span><span class="value"><?php echo $node['data_source']; ?></span></li><?php } ?>
    <?php if(isset($node['agencies'])){ ?><li><span class="title">Agencies &amp; contacts</span><span class="value"><?php echo $node['agencies']; ?></span></li><?php } ?>
    <?php if(isset($node['references'])){ ?><li><span class="title">References</span><span class="value"><?php echo $node['references']; ?></span></li><?php } ?>

	</ul>
  </div>
 </li>
 </div>
  <p></p>
  </div>
  </fieldset>
</li>

<?php
				}
			}
		}
	}
?>
	</ul>
	<span class="numberNodes" ref="<?php echo $numberNodes; ?>"></span>
	</div>
	</fieldset></div>
<?php
} //end LMEs
?>
</div>
<div style="height:20px;">&nbsp;</div>
</div>


<div id="searchResults" style="display:none">
<div class="accordionData">
<ul id="searchUL"></ul>
</div>
</div>
