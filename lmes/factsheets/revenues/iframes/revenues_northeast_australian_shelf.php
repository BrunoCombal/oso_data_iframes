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
    <title>LMES Revenues and economic actitiy</title>
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
       text-align: left;
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
     #legendRanges ul li {
       width:160px;
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
       vertical-align:middle;
     }
     thead tr{
       height:20px;
     }
     tbody tr{
       height:26px;
     }
     tbody .lmeName{
       text-align: left;
       padding-right:25px;
     }
     .l0, .l1, .l2, .l3, .l4, .l5{
       color: #000000;
       border:1px #fff solid;
       padding-left:5px;
       padding-right:5px;
       text-align:right;
       vertical-align:top;
       font-family: "Courier New", Courier, monospace;
       font-size:14px;
     }
     .l0 span, .l1 span, .l2 span, .l3 span, .l4 span, .l5 span{
       font-size:12px;
       display:block;
       text-align:center;
     }
     tbody .l0{
       background: #CBCCCB;
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
       background: #D8232A;
     }
     #title{
       text-align:center;
       font: 14px sans-serif;
       margin-bottom:20px;
     }
    </style>
    <script type="text/javascript">
     $(document).ready(function() {

       var thisLMECode = "40";
       var addedLMECode = -1;
       var plotCounter = -1;
       var categoryPlots = [thisLMECode];
       var outdata = "/iframes/lmes/factsheets/revenues/data"+'/data.csv';
       var lmesData = [];
       <?php if($zero){ ?>
       var title = "Revenues and economic activity";
       <?php } else { ?>
       var title = "Revenues (Northeast Australian Shelf)";
       <?php } ?>

       //Check if we have access to parent document (normally not if the iframe is loaded from a different host
       var sameHost = false;
       try {
         parent.document;
         sameHost = true;
       } catch(e) {
         iFrame = null;
       }
       //Define the behaviour of the View Data link according to the host permissions
       $('#viewData').click(function(){
         var sourceURL = "http://onesharedocean.org/data#385";
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
             iFrame.style.height = '250px';
           }
         }
       }

       function numberWithCommasNoData(value){
         if (value == "NaN") {return "No data"} else {return(numberWithCommas(value));};
       }

       function genColumn(lmeNumber) {

         plotCounter++;

         var rand = Math.floor(Math.random()*999999999);
         $.get(outdata<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
           var lines = data.split('\n');
           var plotLME={};
           iplot=0;
           $.each(lines, function(lineNo, line) {
             if (line) { // ignore empty line (else lines are not drawn)
               var items = line.split(',');
               <?php if (!$zero){ ?>
               if(parseInt(items[0]) == lmeNumber){
               <?php } ?>
                 var lme = {
                   lmeNumber: parseInt(items[0]),
                   lmeName: items[1],
                   shelfArea: parseFloat(items[2]).toFixed(0),
                   avgAnnualCatch: parseFloat(items[3]).toFixed(0),
                   avgAnnualLV: parseFloat(items[4]).toFixed(0),
                   propStock: parseFloat(items[5]).toFixed(0),
                   avgTourism: parseFloat(items[6]).toFixed(0),
                   nldi: parseFloat(items[7]).toFixed(3),
                 };

                 var tr = document.createElement('TR');
                 var td1 = document.createElement('TD');
                 $(td1).html(lme.lmeNumber+' '+lme.lmeName).addClass('lmeName');
                 var td2 = document.createElement('TD');
                 $(td2).html( numberWithCommasNoData( lme.avgAnnualCatch) );
                 var td3 = document.createElement('TD');
                 $(td3).html( numberWithCommasNoData( lme.avgTourism) );
                 var td4 = document.createElement('TD');
                 $(td4).html( lme.nldi );

                 //assign color classes
                 $(td4).addClass(nldiClass(lme.nldi));

                 $('table').append(tr);
                 $(tr).append(td1,td2,td3,td4);
                 if ($('tbody').find('tr').length > 1){
                   <?php if (!$zero){ ?>
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
                     $("#tags").css('color', '#c0c0c0');
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
                 } else {
                   var tdX = document.createElement('TD');
                   $(tdX).css('width','16.8');
                   $(tr).append(tdX);
                 }

                 <?php if(!$zero){ ?>
                 }
                 <?php } ?>
             }
             if(iFrame != null){
               iFrame.style.height = $('#container').height()+200+'px';
             }
           });
         });

	 function nldiClass(value){
           if (value > 0.855) {
             item='l5';
           } else if (value > 0.8) {
             item='l4';
           } else if (value > 0.705) {
             item='l3';
           } else if (value > 0.65) {
             item='l2';
           } else if (value > 0) {
             item='l1';
           } else {
             item='l0';
           }
           return item;
	 }

       } //end function

       //add the jquery search
       $(function() {
         genColumn(thisLMECode);
         $('#printPlot').click(function(){
           var text = '/iframes/lmes/factsheets/revenues/iframes/printAll.php';
           window.open(text, '_blank');
         });
         $('#addPlot').click(function(){
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
         $('#resetPlot').click(function(){
           plotCounter = -1;
           $('tbody').remove();
           genColumn(thisLMECode);

           $('#tags').attr('disabled', false);
           $('#tags').prop('value','');
           $('#tags').focus();
           $(this).attr('disabled', true);
         });

         $( "#tags" ).css('color', '#c0c0c0');
         $( "#tags" ).click(function(){
           if(this.value == comboText){
             this.value = "";
             $( "#tags" ).css('color', '#000000');
           }
         }).blur(function(){
           if(this.value ==""){
             $( "#tags" ).css('color', '#c0c0c0');
             $('#addPlot').attr('disabled', true);
             this.value = comboText;
           }
         }).autocomplete({
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

var availableTags=[ "01 East Bering Sea", "02 Gulf of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Newfoundland-Labrador Shelf", "10 Insular Pacific - Hawaiian", "11 Pacific Central - American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic - West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic-Biscay Shelf", "25 Iberian Coastal ", "26 Mediterranean", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay of Bengal", "35 Gulf of Thailand", "36 South China Sea", "37 Sulu - Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 Southwest Australian Shelf", "44 West - Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio", "50 Sea of Japan / East Sea", "51 Oyashio Current", "52 Sea of Okhotsk", "53 West Bering", "54 Northern Bering - Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf and Sea", "60 Faroe Plateau", "61 Antarctic", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Is", "66 Canadian High Arctic - North Greenland" ];

       $('#title').html(title);

     }); //get

    </script>

  </head>
  <body>
    <?php if(!$zero){ ?>
      <img id="printPlot" src="/sites/all/themes/oceanskeleton/images/download_24px.png" style="position:absolute; cursor:pointer; right:200px;" />
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
    <div id="container" style="width:800px; margin:0 auto">
      <table cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <td></td>
            <td>Fisheries</td>
            <td>Tourism</td>
	    <td>Economic activity</td>

            <?php if(!$zero){ ?>
              <td></td>
            <?php } ?>
          </tr>
          <tr>
            <td>LME</td>
            <td>Annual landed value</td>
            <td>Annual revenues</td>
            <td>NLDI</td>

            <?php if(!$zero){ ?>
              <td></td>
            <?php } ?>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div id="legendRanges">
      <ul>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Least developed</span></li>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Modestly developed</span></li>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Medium</span></li>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Highly developed</span></li>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very highly developed</span></li>
        <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#CBCCCB; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">no data</span></li>
      </ul>
    </div>

    <br/><br/>
    <?php if(!$zero){ ?>
      <div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
    <?php } ?>
  </body>
</html>
