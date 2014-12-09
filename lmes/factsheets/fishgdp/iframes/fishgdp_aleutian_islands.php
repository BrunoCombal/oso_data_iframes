<?php
$templateCache = true;
$sourceCache = true;
if($templateCache == true){
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
}
$zero = false;
if(substr(__FILE__, strrpos(__FILE__, '/')+1) == "printAll.php"){
	$zero = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LMES ICEP</title>
    <link rel="stylesheet" href="/sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.css" />
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1//jquery-ui.min.js"></script>
    <script type="text/javascript" src="/iframes/common/js/lmes.js"></script>
	<style>
      .ui-autocomplete{max-height:100px, overflow-y:auto;overflow-x:hidden;}
      html .ui-autocomplete{height:100px} /* IE6 does not support max-height */
      .ui-widget{font-size:12px;}
	  #viewData {
		font-family: Verdana;
		font-size: 10px;
		cursor: pointer;
	  }
	  
	#legendRanges{
		width: 600px;
		margin:auto auto;
	}
	#legendRanges ul{
		margin: 0;
		padding: 0;
		list-style-type: none;
		text-align: center;
	}
	#legendRanges ul li{
		float:left;
		padding-left:20px;
		font: 12px Verdana;
	}
	.legendText{
		position: relative;
		top: 4px;
	}
	#legendRanges ul li div{
		float:left;
		margin-right:10px;
	}
	#legendRanges ul li div span{
		position:relative;
		top:5px;
	}
	table{
		/*width:100%;*/
		font: 12px Verdana,sans-serif;
		margin: 20px auto;
		text-align:center;
		vertical-align:top;
	}
	thead tr{
		height:20px;
	}
	tbody tr{
		height:26px;
	}
	tbody .lmeName{
		text-align: left;
		padding-right:10px;
		/*width:315px;*/
	}
	.l1, .l2, .l3, .l4, .l5{
		color: #000000;
		font-weight: bold;
		border:1px #fff solid;
		cursor: help;
		width: 90px;
	}
	.over, .lkm2, .lval {
		cursor: help;
	}
	.over, .lkm2{
		border-right:5px solid white;
	}
	tbody .l1{
		background: #5FBADD;
	}
	tbody .l2{
		background: #78BB4B;
	}
	tbody .l3{
		background: #E4E344;
	}
	tbody .l4{
		background: #EE9F42;
	}
	tbody .l5{
		background:#D8232A;
	}
	#title{
		text-align:center;
		font: 14px sans-serif;
		margin-bottom:20px;
	}
    </style>
    <script type="text/javascript">
$(document).ready(function() {
	  
	  
  
        var thisLMECode = "65";
        var addedLMECode = -1;
		var plotCounter = -1;
		var categoryPlots = [thisLMECode];
		var outdata = '../'+"data"+'/data.csv';
		var lmesData = [];
		<?php if($zero){ ?>
			var title = "Fishing Revenue";
		<?php } else { ?>
			var title = "Fishing Revenue (Aleutian Islands)";
		<?php } ?>
		
		//Check if we have access to parent document (normally not if the iframe is loaded from a different host
	var sameHost = false;
	try{
		parent.document;
		sameHost = true;
	}catch(e){
		iFrame = null;
	}
	//Define the behaviour of the View Data link according to the host permissions
	$('#viewData').click(function(){
		var sourceURL = "http://onesharedocean.org/data#386";
		if(sameHost){
			window.parent.window.location = sourceURL;
		} else {
			copyToClipboard(sourceURL);
		}
	});
	//If host allows it, resize the frame from within
	if(sameHost){
		if (window.frameElement != null) {
			var iFrame = parent.document.getElementById(window.frameElement.getAttribute('id'));
			if(iFrame != null){
				iFrame.style.height = '550px';
			}
		}
	}
		
		
function genColumn(lmeNumber)
{
  
  
  
	plotCounter++;
  
	var rand = Math.floor(Math.random()*999999999);
	$.get(outdata<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
		var lines = data.split('\n');
		 var plotLME={};
				 iplot=0;
		$.each(lines, function(lineNo, line) {
			if (line) { // ignore empty line (else lines are not drawn)
				var items = line.split(',');
				<?php if(!$zero){ ?>
				if(items[0] == lmeNumber){
				<?php } ?>
					var lme = {
						lmeNumber: items[0],
						lmeName: items[1],
						lKm2: parseFloat(items[2]).toFixed(2),
						lVal: parseFloat(items[3]).toFixed(0),
						over: parseFloat(parseFloat(items[4])*100).toFixed(0)
					};
					//console.log(lme);
					//console.log(items);
					var tr = document.createElement('TR');
					var td1 = document.createElement('TD');
					$(td1).html(lme.lmeNumber+' '+lme.lmeName).addClass('lmeName');
					var td2 = document.createElement('TD');
					$(td2).html(lme.lKm2).addClass('lkm2');
					var td3 = document.createElement('TD');
					$(td3).html(lme.lVal).addClass('lval');
					var td4 = document.createElement('TD');
					$(td4).html(lme.over).addClass(riskClass(lme.over)).addClass('over');
					$('table').append(tr);
					$(tr).append(td1,td4,td2,td3);
					if($('tbody').find('tr').length > 1){
					<?php if(!$zero){ ?>
						var tdX = document.createElement('TD');
						var img = document.createElement('IMG');
						$(img).attr('src', '/iframes/lmes/images/delete_gray.png');
						$(img).addClass('deleteRow');
						$(tdX).append(img);
						$(tr).append(tdX);
						$(img).click(function(){
							$(this).parents('tr').remove();
							plotCounter--;
							$('#tags').attr('disabled', false);
							$( "#tags" ).css('color', '#c0c0c0');
							$('#tags').prop('value', comboText);
							if(iFrame != null){
								iFrame.style.height = $('#container').height()+200+'px';
							}
						});
						$(img).mouseenter(function(){
							$(this).attr('src', '/iframes/lmes/images/delete_red.png');
						});
						$(img).mouseleave(function(){
							$(this).attr('src', '/iframes/lmes/images/delete_gray.png');
						});
					<?php } ?>
					}else{
						var td5 = document.createElement('TD');
						$(td5).css('width','16.8');
						$(tr).append(td5);
					}
					
					$('.over').mouseover(function(){showLegendTooltip('over')});
					$('.over').mouseout(function(){hideLegendTooltip()});
					$('.lkm2').mouseover(function(){showLegendTooltip('lkm2')});
					$('.lkm2').mouseout(function(){hideLegendTooltip()});
					$('.lval').mouseover(function(){showLegendTooltip('lval')});
					$('.lval').mouseout(function(){hideLegendTooltip()});
					
					if(iFrame != null){
						iFrame.style.height = $('#container').height()+200+'px';
					}
					
				<?php if(!$zero){ ?>
				}
				<?php } ?>
			}
		});
		
	});
	
	
	function riskClass(item){
		if(item <=10){item = 'l1';}
		if(item >10 && item <= 20){item = 'l2';}
		if(item >20 && item <= 40){item = 'l3';}
		if(item >40 && item <= 50){item = 'l4';}
		if(item >50){item = 'l5';}
		return item;
	}
	
 } //end function
 

 
 function showLegendTooltip(item){
	var itemText = '';
	switch (item){
		case 'over':
			itemText = 'Collapsed and overexploited stocks, percentage of total catch-based stock biomass';
			break;
		case 'lkm2':
			itemText = 'Average of annual catch per shelf km<sup>2</sup> (tonnes/km<sup>2</sup>) from 2006 to 2010';
			break;
		case 'lval':
			itemText = 'Average of annual landed value per shelf km<sup>2</sup> (2005 USD) from 2006 to 2010';
			break;
		}
		$('#legendTooltip')
			.html(itemText)
			.css('display', 'block')
			.css('background', '#e2e2e2');
 
 }
 function hideLegendTooltip(){
	$('#legendTooltip').css('display', 'none');
 }
 
 
 
 
	    //add the jquery search
	    $(function() {
			genColumn(thisLMECode);
		<?php if(!$zero){ ?>
			$('#printPlot')
				.click(function(){
					var text = '/iframes/lmes/factsheets/fishgdp/iframes/printAll.php';
					window.open(text, '_blank');
				});
		<?php } ?>
		


			$('#addPlot')
			.click(function(){
				$('#resetPlot').attr('disabled', false);
				$('#tags').prop('value', '');
				$('#tags').focus();
				addedLMECode = $('#addPlot').attr('rel');
				genColumn(addedLMECode);
				
				if (plotCounter == 9) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					$('tbody').remove();
					genColumn(thisLMECode);
					
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
				});
			
			
			//console.log(availableTags);
			//console.log(maxComboText);
			
			$( "#tags" ).css('color', '#c0c0c0');
			$( "#tags" )
			
			.click(function(){
				if(this.value == comboText){
					this.value = "";
					$( "#tags" ).css('color', '#000000');
				}
			})
			.blur(function(){
				if(this.value ==""){
					$( "#tags" ).css('color', '#c0c0c0');
					$('#addPlot').attr('disabled', true);
					this.value = comboText;
				}
			})
			.autocomplete({
              source: availableTags,
			  open: function(e, ui) {
                    var list = '';
                    var results = $('ul.ui-autocomplete.ui-widget-content a');
                    results.each(function() {
                        list += $(this).html() + '<br />';
                    });
                    $('#results').html(list);
                },
              select: function(e, ui) {
                  var lmename = ui.item.value.split(' ');
                  var lmecode = lmename.shift();
                  $('#addPlot').attr('rel',lmecode);
				  //document.getElementById('addPlot').innerHTML=lmecode;
			  
				  document.getElementById('addPlot').disabled = false;
				  
				  
	          addedLMECode=lmecode;
              }
            });
	    });

 var availableTags=[ "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland" ];

$('#title').html(title);
		
      }); //get
   
  </script>

</head>
<body>
	<?php if(!$zero){ ?>
		<img id="printPlot" src="/sites/all/themes/oceanskeleton/images/download_24px.png" style="position:absolute; cursor:pointer; right:200px;top:0px" />
	<?php } ?>
	<div id="title"></div>
	<?php if(!$zero){ ?>
	<div class="ui-widget" style="margin:auto; width:500px">
		<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
		<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
		<input id="addPlot" type="button" value="add LME" disabled="disabled"></input>
		<input id="resetPlot" type="button" value="Reset" disabled="disabled"></input>
	</div>
	<?php } ?>
	<div id="container" style="width:600px; margin:0 auto">
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td>
						LME
					</td>
					<td class="over">
						Collapsed<br />Overexploited
					</td>
					<td class="lkm2">
						Annual catch<br/>tons/km<sup>2</sup>
					</td>
					<td class="lval">
						Landed catch value<br/>USD per km<sup>2</sup>
					</td>
					<?php if(!$zero){ ?>
					<td></td>
					<?php } ?>
				</tr>
			</thead>
		</table>
		
		<div id="legendRanges">
			<ul>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very high</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">High</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Medium</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Low</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very low</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#CBCCCB; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">no data</span></li>
			</ul>
		</div>
		<div style="clear:both; font: 12px Verdana, sans-serif; padding:5px; position:absolute" id="legendTooltip"></div>
	</div>
	<br/><br/>
	<?php if(!$zero){ ?>
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
	<?php } ?>
	</body>
</html>
