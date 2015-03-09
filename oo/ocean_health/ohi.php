<?php
drupal_add_js('sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.js');
drupal_add_js('sites/all/libraries/Highcharts-4.0.4/js/highcharts.js');
drupal_add_js('sites/all/libraries/Highcharts-4.0.4/js/highcharts-more.js');
?>

<style>
 .ohi_illustrations {
   width:300px;
   font-family:Verdana, sans-serif;
   font-size:9px;
   margin:0;
   padding:0;
 }
 .ohi_illustrations tr, td{
   height:10px;
   font-size:10px;
   background-color:#FFFFFF;
   max-width:75px;
   width:75px;
   line-height:2em;
   padding:0;
   margin:0;
   text-align:center;
 }
</style>

<script type="text/javascript" class=".skipping-this">
 jQuery(document).ready(function() {

   var goals=[ "Food provision (fisheries)", "Artisanal fishing opportunity", "Natural products", "Carbon storage" , "Coastal protection", "Tourism & recreation", "Coastal livelihoods & economies", "Sense of place (iconic species)", "Clean Water", "Biodiversity (Species)"];
   var indexMap=[0, 1, 8,10]
   var colorRisk=['#5FBADD','#78bb4b','#e4e344','#ee9f42','#d8232a'];

   function getColor( name ){
     thisName = name.split(',',1)[0].split(' ',1);
     colors={'Arctic':'#ff231d', 'Atlantic':'#2a2dff', 'Mediterranean':'#76FF00', 'Indian':'#ffb41d', 'Pacific':'#29b11b'};
     return colors[ thisName ];
   };


   function displayChart(areaCode) {

     thisData = dataCumul[codeMapping[areaCode]];
     if(thisData){
       coloredData=[];
       for (ii=1; ii<thisData.length; ii++){coloredData.push({y:thisData[ii], color:'#808080'})}
       var options=({
         credits:{enabled:false},
         title:{text:codeMapping[areaCode]},
         subtitle:{text:'Ocean Health Index: '+thisData[0].toFixed(0), html:true},
         chart:{type:'bar', polar:true, renderTo:'divChart'},
         xAxis:{categories:goals, title:{text:null}},
         yAxis:{min:0, max:100, tickInterval:25, title:{text:null}},
         legend:{enabled:false},
         tooltip:{formatter:function(){
           return this.x+': '+this.y;
         }},
         series:[{
           data:coloredData,
           animation:false
         }]
       });

       var chart = new Highcharts.Chart(options);
     }
   };

   function displayData(areaCode) {
     thisData = dataCumul[codeMapping[areaCode]];

     if (thisData){
       /*
          text='<br/><strong>'+dataCumulHeader[0]+'</strong>: '+thisData[0]+'<br/><br/>';
          for (ii=1; ii< thisData.length; ii++) {
          text += '<strong>'+dataCumulHeader[ii]+'</strong>: '+thisData[ii]+'<br/>';
          }
          jQuery("#cumulDetail").html("<span style='font-size:1.2em;'>FAO Fishing Area "+areaCode+": "+codeMapping[        areaCode]+"</span>"+text);
        */
       displayChart(areaCode);
     } else if (areaCode=="37") {
       jQuery("#divChart").html('<big style="color:red"><b>The <a href="/node/80">Mediterranean</a> and <a href="/node/116">Black Sea</a> are evaluated in the LME assessment. Please visit those sections for further information.</b></big>');
     } else if (areaCode=="18") {
       jQuery("#divChart").html('<big style="color:red"><b>The Arctic Sea</b></big>');
     } else if (areaCode=="48") {
       jQuery("#divChart").html('<big style="color:red"><b>The Atlantic, Antarctic</b></big>');
     } else if (areaCode=="58"){
       jQuery("#divChart").html('<big style="color:red"><b>The Indian Ocean, Antarctic and Southern</b></big>');
     } else if (areaCode=="88") {
       jQuery("#divChart").html('<big style="color:red"><b>The Pacific, Antarctic</b></big>');
     }
   };

   function displayDataFromName(Name) {
     thisData = dataCumul[Name];
     text='<br/><strong>'+dataCumulHeader[0]+'</strong>: '+thisData[0]+'<br/><br/>';
     for (ii=1; ii< thisData.length; ii++) {
       text += '<strong>'+dataCumulHeader[ii]+'</strong>: '+thisData[ii]+'<br/>';
     }
     jQuery("#cumulDetail").html("<span style='font-size:1.2em;'>FAO Fishing Area "+reverseMapping[Name]+": "+Name+"</span>"+text);

   };

   var dataCumul={};
   var dataCumulHeader=[];
   var cumul={name:'Cumulative Index', data:[], type:'column'};
   var codeMapping={18:'Arctic Sea',
                    21:'Atlantic, Northwest', 27:'Atlantic, Northeast', 31:'Atlantic, Western-Central', 34:'Atlantic, Eastern Central',
                    41:'Atlantic, Southwest', 47:'Atlantic, Southeast', 48:'Atlantic, Antarctic',
                    37:'Mediterranean and Black Sea',
                    51:'Indian Ocean, Western', 57:'Indian Ocean, Eastern', 58:'Indian Ocean, Antarctic And Southern',
                    61:'Pacific, Northwest',
                    67:'Pacific, Northeast', 71:'Pacific, Western Central', 77:'Pacific, Eastern Central',
                    81:'Pacific, Southwest', 87:'Pacific, Southeast', 88:'Pacific, Antarctic'};

   var reverseMapping={'Arctic Sea':18,
                       'Atlantic, Northwest':21, 'Atlantic, Northeast':27, 'Atlantic, Western-Central':31, 'Atlantic, Eastern Central':34,
                       'Atlantic, Southwest':41, 'Atlantic, Southeast':47, 'Atlantic, Antarctic':48,
                       'Mediterranean and Black Sea':37,
                       'Indian Ocean, Western':51, 'Indian Ocean, Eastern':57, 'Indian Ocean, Antarctic And Southern':58,
                       'Pacific, Northwest':61,
                       'Pacific, Northeast':67, 'Pacific, Western Central':71, 'Pacific, Eastern Central':77,
                       'Pacific, Southwest':81, 'Pacific, Southeast':87, 'Pacific, Antarctic':88
   };



   jQuery.get('/public_store/oo_ohi/oo_ohi.csv', function(data) {
     var lines = data.split('\n');
     var ipos=0;
     jQuery.each(lines, function (lineNo, line) {
       if (ipos > 0) {
         var items = line.split(';');
         thisData=[null, null, null, null, null, null, null, null, null, null];
         for (ii=2; ii<items.length; ii++) {
           if (isNaN(parseFloat(items[ii]))==false) {
//             thisData.push(parseFloat(items[ii]));
	     thisData[indexMap[ii-2]]=parseFloat(items[ii]);
	     console.log(ii);
           }
         }
         if (thisData.length > 0) {dataCumul[items[1]] = thisData; }
         if ( isNaN(parseFloat(items[1])) == false) {
           cumul.data.push({y:parseFloat(items[1]), color:getColor(items[0]), name:items[0]});
           faoCode = '';
           for (code in codeMapping) {
             if (codeMapping[code]==items[0]) {faoCode=code}
           }

         }
       } else {
         var head=line.split(';');
         dataCumulHeader.push('Ocean Health');
         for (ii=2; ii<head.length; ii++) {
           dataCumulHeader.push(head[ii]);
         }
       }
       ipos+=1;
     });

   });

   //Bind the click event to the areas according to the number on their title
   jQuery('area').hover(function(){
     //if(this.title.substring(this.title.length-2) != 37){
     displayData(this.title.substring(this.title.length-2));
     //}
   });
 });
</script>


<div class="oo_illustration">
  <map name="IMap" >
    <area shape="poly" coords="64,0,64,23,47,27,42,29,51,35,64,41,99,35,115,27,156,34,212,37,255,38,260,39,278,42,289,36,374,42,393,41,387,53,412,64,423,66,426,53,440,54,442,49,439,45,427,39,427,34,419,30,413,29,414,21,424,15,433,20,442,20,478,16,481,13,482,1" title="Arctic Sea - FAO Fishing Area 18" />
    <area shape="poly" coords="255,40,258,43,257,117,141,116,141,124,127,125,124,114,143,104,144,83,151,74,173,72,172,63,175,52,186,46,227,43" title="Pacific, Northwest - FAO Fishing Area 61" />
    <area shape="poly" coords="259,43,259,84,333,83,333,82,348,82,347,63,340,53,325,48,311,47,297,44,290,46,289,50,279,48,283,42,273,42,262,41" title="Atlantic, Northeast - FAO FIshing Area 27" />
    <area shape="poly" coords="258,85,258,190,349,190,349,141,416,141,421,137,418,135,415,137,405,128,392,121,375,116,360,96,346,84" title="Pacific, Eastern Central - FAO Fishing Area 77" />
    <area shape="poly" coords="421,138,418,142,351,142,351,249,437,249,437,241,431,235,427,231,430,208,434,179,424,173,417,158,423,143" title="Pacific, Southeast - FAO Fishing Area 87"  />
    <area shape="poly" coords="438,249,466,250,466,232,516,232,516,150,499,150,499,141,463,141,458,142,457,152,468,158,484,159,486,163,481,170,480,177,477,183,468,187,461,199,454,202,450,207,449,211,440,213,437,219,434,228,433,234,439,240,440,243,438,245" title="Atlantic, Southwest - FAO Fishing Area 41"  />
    <area shape="poly" coords="433,250,433,265,416,278,405,281,429,286,442,286,502,285,532,282,540,271,593,270,599,267,599,234,467,234,467,251" title="Atlantic, Antarctic - FAO Fishing Area 48"  />
    <area shape="poly" coords="572,160,517,160,516,232,599,231,599,201,585,205,582,205,576,193,570,176,575,171" title="Atlantic, Southeast - FAO Fishing Area 47" />
    <area shape="poly" coords="540,90,483,90,483,140,500,139,500,149,517,149,517,158,574,159,567,141,560,137,536,138,526,127,528,110,538,98" title="Atlantic, Eastern Central - FAO Fishing Area 34" />
    <area shape="poly" coords="419,92,479,92,479,90,481,90,481,140,450,140,439,135,434,134,427,135,424,135,420,135,412,134,409,131,409,125,400,124,403,116,401,115,397,120,391,118,385,106,388,100,403,98,412,98,417,95" title="Atlantic, Western-Central - FAO Fishing Area 31" />
    <area shape="poly" coords="421,91,478,91,479,87,479,51,475,50,469,44,466,31,458,23,439,21,432,21,422,19,417,23,415,26,431,32,438,36,446,40,436,39,441,43,442,49,455,61,455,64,439,65,431,70,438,70,440,73,430,79,420,85" title="Atlantic, Northwest - FAO Fishing Area 21"   />
    <area shape="poly" coords="483,1,483,12,507,13,514,15,519,15,513,20,510,27,505,29,502,32,501,35,494,36,486,40,481,42,477,49,480,50,480,88,538,89,538,88,535,88,535,78,547,77,547,70,554,65,569,60,587,59,593,53,598,50,587,48,585,46,593,41,590,38,579,45,566,49,570,43,584,36,599,34,598,0" title="Atlantic, Northeast - FAO Fishing Area 27"  />
    <area shape="poly" coords="199,250,431,250,432,263,414,274,386,273,383,279,313,277,307,286,322,290,318,294,227,292,210,285,216,278,222,270,200,264" title="Pacific, Antarctic - FAO Fishing Area 88" />
    <area shape="poly" coords="209,192,349,191,348,249,200,249,200,212,204,203,205,197,209,197" title="Pacific, Southwest - FAO Fishing Area 81" />
    <area shape="poly" coords="0,225,83,225,83,242,198,242,199,267,175,263,123,263,94,264,66,273,58,272,61,264,41,264,23,265,12,268,0,268,0,267,1,267" title="Indian Ocean, Antarctic And Southern - FAO Fishing Area 58" />
    <area shape="poly" coords="83,223,0,223,1,200,4,195,3,189,9,189,9,181,16,175,15,158,33,133,21,133,13,123,2,100,9,99,25,126,46,115,43,110,37,111,28,101,31,97,40,102,47,104,63,107,73,110,78,132,78,150,83,150" title="Indian Ocean, Western - FAO Fishing Area 51" />
    <area shape="poly" coords="79,134,79,149,84,149,84,241,199,240,198,211,187,210,179,203,168,200,145,206,142,189,159,180,164,177,165,163,140,164,127,162,122,153,123,145,115,135,115,124,100,107,82,120,80,133" title="Indian Ocean, Eastern - FAO Fishing Area 57" />
    <area shape="poly" coords="129,126,142,126,143,117,256,118,257,191,209,190,207,195,201,193,188,171,188,181,179,180,174,174,174,171,170,173,166,176,166,161,130,161,126,159,125,153,123,151,124,142,116,135,118,127,126,132,132,130" title="Pacific, Western Central - FAO Fishing Area 71" />
    <area shape="poly" coords="63,0,63,22,46,24,37,29,43,31,50,38,14,45,3,39,0,34,0,1" title="Atlantic, Northeast - FAO Fishing Area 27" />
    <area shape="poly" coords="572,72,553,76,543,85,541,89,557,94,565,96,577,101,593,101,599,99,599,73" title="Mediterranean and Black Sea - FAO Fishing Area 37" />

  </map>
  <div style="float:left; width:600px">
    <h1>Ocean Health Index by FAO Fishing Area</h1>
  </div>
  <div style="float:right;"> <!-- empty for centering the title above -->
  </div>
  <div style="float:left; width:600px">
    <img usemap="#IMap" title="FAO Fishing Area Map" src="/private_store/ocean_health/ohi_fao_oceans.png"/>
  </div>
  <div id="cumulIndex" style="float:right; " ></div>

  <div style="clear:both"></div>

  <div id="toolinfo" style="font-family:Verdana, sans-serif; width:600px; padding:0; margin:0; font-size:11px; text-align:justify">
    <!-- legend -->

    <table>
      <tbody style="border:none; line-height:1.1em; border:0; padding:0; font-family:Verdana, sans-serif; font-size:10px">
        <table>
          <tbody style="border:none; line-height:1.1em; border:0; padding:0; font-family:Verdana, sans-serif; font-size:10px">
            <tr>
              <td style="background-color:#d7191c; padding:3px;width:20px;"></td><td style="padding:3px;">0-10</td>
              <td style="background-color:#e75b3a; padding:3px;width:20px;"></td><td style="padding:3px;">10-20</td>
              <td style="background-color:#f89d59; padding:3px;width:20px;"></td><td style="padding:3px;">20-30</td>
              <td style="background-color:#fdc980; padding:3px;width:20px;"></td><td style="padding:3px;">30-40</td>
              <td style="background-color:#feedaa; padding:3px;width:20px;"></td><td style="padding:3px;">40-50</td>
              <td style="background-color:#ecf6c8; padding:3px;width:20px;"></td><td style="padding:3px;">50-60</td>
              <td style="background-color:#c7e5db; padding:3px;width:20px;"></td><td style="padding:3px;">60-70</td>
              <td style="background-color:#9ccee3; padding:3px;width:20px;"></td><td style="padding:3px;">70-80</td>
              <td style="background-color:#64a4cc; padding:3px;width:20px;"></td><td style="padding:3px;">80-90</td>
              <td style="background-color:#2c7bb6; padding:3px;width:20px;"></td><td style="padding:3px;">90-100</td>
              <td style="background-color:#ececec; padding:3px;width:20px;"></td><td style="padding:3px;">NA</td>
            </tr>
          </tbody>
        </table>

        <h2 style="margin-bottom:0; padding-bottom:0">Ocean Health Index score by FAO fishing areas.</h2>
        <div id="divChart" style="width:600px"></div>

        <h2 style="margin-bottom:0; padding-bottom:0">Hover the pointer over each FAO Fishing Area (colored polygons on the map) to display the values of their sub-goals in a bar chart.</h2>
        <h2 style="margin-bottom:0; padding-bottom:0">Note: the <a href="/node/80">Mediterranean</a> and <a href="/node/116">Black Sea</a> are evaluated in the LME assessment. Please visit those sections for further information.</h2>
  </div>


  <div id="cumulDetail" style="font-family:Verdana, sans-serif; font-size:11px; width:600px; margin:auto; text-align:justify"> </div>


  <div style="clear:both"></div>

</div>