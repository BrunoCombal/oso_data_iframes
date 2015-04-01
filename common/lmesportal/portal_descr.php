<?php
drupal_add_js('sites/all/libraries/OpenLayers-2.13.1/OpenLayers.js');
drupal_add_js('sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.js');
drupal_add_js('/iframes/common/js/lmes.js');
drupal_add_css('sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.css');
include('/data/iframes/common/lmesnav/lmenav.php');
?>
<link rel="stylesheet" href="/iframes/common/lmesportal/lmesportal.css" type="text/css" />
<link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css" />
<?php
include('portal_ol_script.js');
?>

<div class="ui-widget" style="line-height:38px;">
  <input id="tags" style="position:relative; top:-2px; line-height:30px; width:370px; font-family:sans-serif; font-size:18px; border:1px solid #C0C0C0; color:#c0C0C0; vertical-align:middle" value="Search for an LME or click on the map"/>
  <img id="deleteTags" src="/iframes/lmes/images/delete_gray.png" title="Clear search box" style="display:none; vertical-align:middle;margin-right:20px" />
  <div id="buttonLMEs">read the LME factsheet</div>
  <div id="results" class="ui-front autocomplete; "></div>
</div>

<div class="mainTool">
  <div class="column-map">
    <div id="mapOL" style="width:750px; height:375px; cursor:pointer"></div>
    <div id="legOL" style="clear:both;"></div>
  </div>
  <div class="column-layers">
    <div id="accordion">
      <ul>
        <li class="l1" rel="lmes"><span class="selected">LMEs</span>
        </li>
        <li class="l1" rel="descProductivity"><span>Productivity</span>
          <ul>
            <li class="l2" rel="chla"><span>Chlorophyll-A</span></li>
            <li class="l2" rel="chlachange"><span class="double">Chlorophyll-A<br/>(% change)</span></li>
            <li class="l2" rel="pp_group"><span>Primary productivity group</span></li>
            <li class="l2" rel="pp_trend"><span class="double">Primary productivity<br/>(% change)</span></li>
            <li class="l2" rel="sst_net_change"><span>SST net change</li>
          </ul>
        </li>
        <li class="l1"><span>Fish &amp; Fisheries</span></li>
        <li class="l1"><span>Pollution</span>
          <ul>
            <li class="l2"><span>Nutrients</span>
              <ul>
                <li class="l3" rel="icep"><span>ICEP</span></li>
                <li class="l3" rel="icep2030"><span>ICEP (2030)</span></li>
                <li class="l3" rel="icep2050"><span>ICEP (2050)</span></li>
                <li class="l3" rel="ld_din"><span>DIN loading</span></li>
                <li class="l3" rel="ld_din2030"><span>DIN loading (2030)</span></li>
                <li class="l3" rel="ld_din2050"><span>DIN loading (2050)</span></li>
                <li class="l3" rel="merged_ind"><span>Merged indicator</span></li>
                <li class="l3" rel="merged_ind2030"><span>Merged indicator (2030)</span></li>
                <li class="l3" rel="merged_ind2050"><span>Merged indicator (2050)</span></li>
              </ul>
            </li>
            <li class="l2"><span>Plastics</span>
              <ul>
                <li class="l3" rel="plasticsmicro"><span>Micro Plastics</span></li>
                <li class="l3" rel="plasticsmacro"><span>Macro Plastics</span></li>
              </ul>
            </li>

            <li class="l2"><span>POPs</span>
              <ul>
                <li class="l3" rel="pops_ddt"><span>DDT score</span></li>
                <li class="l3" rel="pops_hch"><span>HCHs score</span></li>
                <li class="l3" rel="pops_pcb"><span>PCBs score</span></li>
              </ul>
            </li>
            <li class="l2"><span>Ecosystem health</span>
              <ul>
                <li class="l3" rel="coral"><span>Coral reefs</span></li>
                <li class="l3" rel="mangroves"><span>Mangroves</span></li>
                <li class="l2" rel="cumulImpact"><span>Cumulative Impact</span></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="l1"><span>Socio-economics</span>
          <ul>
            <li class="l2" rel="ohi"><span>Ocean Health Index</span></li>
            <li class="l2" rel="population"><span>Population density</span></li>
            <li class="l2" rel="hdi"><span>HDI</span></li>
            <li class="l2" rel="nldi"><span>NLDI</span></li>
            <li class="l2" rel="overfishing"><span>Overfishing</span></li>
          </ul>
        </li>
        <li class="l1"><span>Governance</span>
          <ul>
            <li class="l2" rel="govInt"><span>Integration</span></li>
            <li class="l2" rel="govEng"><span>Engagement</span></li>
            <li class="l2" rel="govCompl"><span>Completeness</span></li>
          </ul>
        </li>
        <li class="l1 lastItem"><span>General information</span>
          <ul>
            <li class="l2" rel="areas"><span>LMEs Area</span></li>
          </ul>
        </li>
        <li class="empty"></li>
        <li class="buttonIntroLMEs" onclick="jQuery('html, body').animate({scrollTop:jQuery('#topDescr').offset().top}, 1000);">Read about LMEs</li>
        <li class="empty"></li>
        <li class="buttonWP" onclick="window.open('/node/242');">Western Pacific Warm Pool</li>
        <li class="empty"></li>
        <li class="buttonGCA">Global Comparative Assessment</li>
      </ul>
    </div>
  </div>
</div>

<div style="clear:both"></div>

<div class="largeDescr">

  <div class="whatAreLMEs">
    <?php
    include('what_are_lmes.html');
    ?>
  </div>
</div>

<div style="clear:both"></div>
