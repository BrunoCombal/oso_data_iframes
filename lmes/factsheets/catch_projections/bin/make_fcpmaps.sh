#!/bin/bash

# author: Bruno Combal
# date: June 2014
# purpose: read original data and create well-formatted data files and create html template files

template='catchpotentialprojection.html'
polarNorthTemplate=${template}
polarSouthTemplate=${template}
outiframe='/data/iframes/lmes/factsheets/catch_projections/iframes'
mkdir -p $outiframe

declare -A nodeCodes=(["0"]="120" ["1"]="51" ["2"]="55" ["3"]="56" ["4"]="57" ["5"]="58" ["6"]="59" ["7"]="60" ["8"]="61" ["9"]="62" ["10"]="63" ["11"]="65" ["12"]="66" ["13"]="67" ["14"]="68" ["15"]="69" ["16"]="70" ["17"]="71" ["18"]="72" ["19"]="73" ["20"]="74" ["21"]="75" ["22"]="76" ["23"]="77" ["24"]="78" ["25"]="79" ["26"]="80" ["27"]="81" ["28"]="82" ["29"]="83" ["30"]="84" ["31"]="85" ["32"]="86" ["33"]="87" ["34"]="88" ["35"]="89" ["36"]="90" ["37"]="91" ["38"]="92" ["39"]="93" ["40"]="94" ["41"]="95" ["42"]="96" ["43"]="97" ["44"]="98" ["45"]="99" ["46"]="100" ["47"]="101" ["48"]="102" ["49"]="103" ["50"]="104" ["51"]="105" ["52"]="106" ["53"]="107" ["54"]="108" ["55"]="109" ["56"]="110" ["57"]="111" ["58"]="112" ["59"]="113" ["60"]="114" ["61"]="115" ["62"]="116" ["63"]="117" ["64"]="118" ["65"]="119" ["66"]="120")

# list of templates
declare -A LMETemplate
for ii in $(seq 1 66)
do
    LMETemplate[$ii]=${template}
done
for ii in 1 18 19 20 21 53 54 55 56 57 58 63 64 65 66
do
    LMETemplate[$ii]=${polarNorthTemplate}
done
LMETemplate[61]=${polarSouthTemplate}

# 1. create map for each lme number
for ii in $(seq 1 66)
do
    echo processing $ii
    outname=${outiframe}/${ii##*/}_fcp.html
    cp ${LMETemplate[$ii]} ${outname}
    # edit in place: outname
    sed -i 's/LMEIDTOREPLACE/'${ii}'/' ${outname}
    # edit in place: LME code

	# replace WIDTH and HEIGHT
    widthVal=600
    heightVal=300
	#exceptions
    case $ii in
	29) widthVal=200; heightVal=380;;
	30) widthVal=600; heightVal=350;;
	35) widthVal=300; heightVal=600;;
	61) widthVal=600; heightVal=400;;
    esac
    echo ${widthVal}  ${outname}
    sed -i 's/WIDTHTOREPLACE/'${widthVal}'/' ${outname}
    sed -i 's/HEIGHTTOREPLACE/'${heightVal}'/' ${outname}
    
done

