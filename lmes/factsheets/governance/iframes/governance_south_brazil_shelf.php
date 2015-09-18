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
     .highcharts-tooltip span {
       background-color:white;
       z-index:9999!important;
       border: thin solid #333333;
       margin:0;
       padding: 7px;
       top: 0;
       left:0;
       position: relative;

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
     .eng, .comp, .inte {
       cursor: help;
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



       var thisLMECode = "15";
       var addedLMECode = -1;
       var plotCounter = -1;
       var categoryPlots = [thisLMECode];
       var outdata = '../'+"data"+'/data.csv';
       var lmesData = [];
       <?php if($zero){ ?>
       var title = "Governance";
       <?php } else { ?>
       var title = "Governance (South Brazil Shelf)";
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
         var sourceURL = "http://onesharedocean.org/data#331";
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
                   eng: items[2],
                   engRisk: items[3],
                   comp: items[4],
                   compRisk: items[5],
                   inte: items[6],
                   inteRisk: items[7]
                 };
                 //console.log(lme);
                 //console.log(items);
                 var tr = document.createElement('TR');
                 var td1 = document.createElement('TD');
                 $(td1).html(lme.lmeNumber+' '+lme.lmeName);
                 $(td1).addClass('lmeName');
                 var td2 = document.createElement('TD');
                 $(td2).html(lme.eng);
                 $(td2).addClass(riskClass(lme.engRisk));
                 $(td2).addClass('eng');
                 var td3 = document.createElement('TD');
                 $(td3).html(lme.comp);
                 $(td3).addClass(riskClass(lme.compRisk));
                 $(td3).addClass('comp');
                 var td4 = document.createElement('TD');
                 $(td4).html(lme.inte);
                 $(td4).addClass(riskClass(lme.inteRisk));
                 $(td4).addClass('inte');
                 $('table').append(tr);
                 $(tr).append(td1,td2,td3,td4);
                 if($('tbody').find('tr').length > 1){
                   <?php if(!$zero){ ?>
                   var td5 = document.createElement('TD');
                   var img = document.createElement('IMG');
                   $(img).attr('src', '/iframes/lmes/images/delete_gray.png');
                   $(img).addClass('deleteRow');
                   $(td5).append(img);
                   $(tr).append(td5);
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

                 $('.eng').mouseover(function(){showLegendTooltip('eng')});
                 $('.eng').mouseout(function(){hideLegendTooltip()});
                 $('.comp').mouseover(function(){showLegendTooltip('comp')});
                 $('.comp').mouseout(function(){hideLegendTooltip()});
                 $('.inte').mouseover(function(){showLegendTooltip('inte')});
                 $('.inte').mouseout(function(){hideLegendTooltip()});

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
           switch (parseInt(item)){
             case 1:
               item = 'l1';
               break;
             case 2:
               item = 'l2';
               break;
             case 3:
               item = 'l3';
               break;
             case 4:
               item = 'l4';
               break;
             case 5:
               item = 'l5';
               break;
           }
           return item;
         }

       } //end function



       function showLegendTooltip(item){
         var itemText = '';
         switch (item){
           case 'eng':
             itemText = 'Engagement: the level of engagement of countries in the LME participating in arrangements that address the suite of identified transboundary issues within the LME';
             break;
           case 'comp':
             itemText = 'Completeness: the level of completeness of the structure of arrangements to address a given issue or issues;';
             break;
           case 'inte':
             itemText = 'Integration: level of integration of institutions involved in addressing the suite of identified transboundary issues within the LME;';
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
                             var text = '/iframes/lmes/factsheets/governance/iframes/printAll.php';
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

        var availableTags=[ "01 East Bering Sea", "03 California Current", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "64 Central Arctic", "66 Canadian High Arctic North Greenland", "99 Pacific Warm Pool" ];

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
            <td class="eng">
              Engagement
            </td>
            <td class="comp">
              Completeness
            </td>
            <td class="inte">
              Integration
            </td>
            <?php if(!$zero){ ?>
              <td></td>
            <?php } ?>
          </tr>
        </thead>
      </table>

      <div id="legendRanges">
        <ul>
          <li><span class="legendText">Risk Level:&nbsp;&nbsp;&nbsp;</span></li>
          <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very Low</span></li>
          <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Low</span></li>
          <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Medium</span></li>
          <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">High</span></li>
          <li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very High</span></li>
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
