#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formatted data files and create html template files

template='templates/lme_centered_template.php'
polarNorthTemplate='templates/lme_centered_polar_north_template.php'
polarSouthTemplate='templates/lme_centered_polar_south_template.php'
pwpTemplate='templates/pwp_centered_template.php'
outiframe='../iframe_test'
csv="/data/private_store/lmes/lmes66_data/lmes_factsheet.csv"
mkdir -p $outiframe

# sequence of inodes, for navigation from the side area
declare -A nodeCodes=(["0"]="242" ["1"]="51" ["2"]="55" ["3"]="56" ["4"]="57" ["5"]="58" ["6"]="59" ["7"]="60" ["8"]="61" ["9"]="62" ["10"]="63" ["11"]="65" ["12"]="66" ["13"]="67" ["14"]="68" ["15"]="69" ["16"]="70" ["17"]="71" ["18"]="72" ["19"]="73" ["20"]="74" ["21"]="75" ["22"]="76" ["23"]="77" ["24"]="78" ["25"]="79" ["26"]="80" ["27"]="81" ["28"]="82" ["29"]="83" ["30"]="84" ["31"]="85" ["32"]="86" ["33"]="87" ["34"]="88" ["35"]="89" ["36"]="90" ["37"]="91" ["38"]="92" ["39"]="93" ["40"]="94" ["41"]="95" ["42"]="96" ["43"]="97" ["44"]="98" ["45"]="99" ["46"]="100" ["47"]="101" ["48"]="102" ["49"]="103" ["50"]="104" ["51"]="105" ["52"]="106" ["53"]="107" ["54"]="108" ["55"]="109" ["56"]="110" ["57"]="111" ["58"]="112" ["59"]="113" ["60"]="114" ["61"]="115" ["62"]="116" ["63"]="117" ["64"]="118" ["65"]="119" ["66"]="120" ["99"]="242")

# list of templates: assign the right template to the right node
declare -A projType
declare -A LMETemplate
for ii in $(seq 1 66)
do
    LMETemplate[$ii]=${template}
    projType[$ii]=4326
done
for ii in 1 18 19 20 21 53 54 55 56 57 58 63 64 65 66
do
    LMETemplate[$ii]=${polarNorthTemplate}
    projType[$ii]=3995
done
LMETemplate[61]=${polarSouthTemplate}
projType[61]=1000
LMETemplate[99]=${pwpTemplate}
projType[99]=0

# 1. create maps for each lme number and for PWP (code=99)
for ii in $(seq 1 66) 99
do
    echo processing $ii
    outname=${outiframe}/${ii##*/}_referencemap.html
    cp ${LMETemplate[$ii]} ${outname}

    # edit in place: outname
    sed -i 's/REPLACELMEID/'${ii}'/' ${outname}
    # edit in place: LME code

    if [ $ii -le 66 ]; then
	nextLME=$(echo "(${ii} -1 + 1)%66+1" | bc)
	if [ $nextLME -eq 1 ]; then
	    nextLME=99
	fi
	prevLME=$(echo "($ii -1)" | bc )
	nextName=`awk -v lmecode=${nextLME} -F ';' '{if ($2==lmecode) print $1}' $csv`
	if [ $prevLME -eq 0 ]; then
	    prevName=`awk -v lmecode=99 -F ';' '{if ($2==lmecode) print $1}' $csv`
	else
	    prevName=$(awk -v lmecode=${prevLME} -F ';' '{if ($2==lmecode) print $1}' $csv)
	fi
    else
	nextLME=1
	prevLME=66
	nextName=`awk -v lmecode=${nextLME} -F ';' '{if ($2==lmecode) print $1}' $csv`
	prevName=`awk -v lmecode=${prevLME} -F ';' '{if ($2==lmecode) print $1}' $csv`
    fi

    nodeNext=${nodeCodes[$nextLME]}
    nodePrev=${nodeCodes[$prevLME]}
    sed -i 's/LMECODETOREPLACE/'${ii}'/' ${outname} 
    sed -i "s/NODENEXT/${nodeCodes[$nextLME]}/" ${outname}
    #perl -i -pe 's/NEXTNAME/\Q'${nextName}'/' ${outname}
    sed -i 's/NODEPREV/'${nodePrev}'/' ${outname}
    #perl -i -pe 's/PREVNAME/'${prevName}'/' ${outname}

    # edit in place: Area
    values=(`awk -v lmecode=${ii} -F ';' '{if ($2==lmecode) print $3,$4,$5,$6,$7,$8}' $csv`)
    countries=`awk -v lmecode=${ii} -F ';' '{if ($2==lmecode) print $9}' $csv`

    echo ${values[@]}
    sed -i 's/AREATOREPLACE/'${values[0]}'/' ${outname}
    # replace countries
    if [ -z "${countries}" ]; then
	sed -i 's/COUNTRYTOREPLACE/No Country/' ${outname}
    else
	plural=0
	plural=$(echo ${countries} | grep ',' | wc -l)
	if [ $plural -eq 0 ]; then
	    countries="\<b\>Country:\<\/b\> "${countries}
	else
	    countries="\<b\>Countries:\<\/b\> "${countries}
	fi
	
	perl -i -pe "s/COUNTRYTOREPLACE/${countries}/" ${outname}
    fi
    # replace WIDTH and HEIGHT
    widthVal=600
    heightVal=300
    TORGX=-180
    TORGY=90
    TSIZEX=150
    TSIZEY=75
    RESOLUTIONLIST='1.2,0.6,0.3,0.15,0.075,0.0375,0.01875,0.009375,0.0046875'
    if [ ${projType[$ii]} -eq 3995 ]; then
	RESOLUTIONLIST='31333.333333333332,15666.666666666666,7833.333333333333,3916.6666666666665,1958.3333333333333,979.1666666666666,489.5833333333333'
    fi
    if [ ${projType[$ii]} -eq 3031 ]; then
	RESOLUTIONLIST='31333.333333333332,15666.666666666666,7833.333333333333,3916.6666666666665,1958.3333333333333,979.1666666666666,489.5833333333333'
    fi
    gridType=1
    #exceptions
    case $ii in
	29) widthVal=200; heightVal=380; RESOLUTIONLIST='0.9473684210526315,0.4736842105263158,0.2368421052631579,0.1184210526315789,0.0592105263157895,0.0296052631578947,0.0148026315789474'; TSIZEX=100; TSIZEY=190; gridType=2;;
	30) widthVal=600; heightVal=350; RESOLUTIONLIST='1.2,0.6,0.3,0.15,0.075,0.0375,0.01875'; TSIZEX=300; TSSIZEY=175; gridType=3;;
	35) widthVal=300; heightVal=600; RESOLUTIONLIST='0.6,0.3,0.15,0.075,0.0375,0.01875,0009375'; TSIZEX=150; TSIZEY=300; gridType=4;;
	61) widthVal=600; heightVal=400; RESOLUTIONLIST='1.2,0.6,0.3,0.15,0.075,0.0375,0.01875'; TSIZEX=300; TSIZEY=200; gridType=5;;
    esac
    # gridType for polarprojection
    #echo ${widthVal}  ${outname} $gridType
    sed -i 's/WIDTHTOREPLACE/'${widthVal}'/' ${outname}
    sed -i 's/HEIGHTTOREPLACE/'${heightVal}'/' ${outname}
    sed -i 's/TORGX/'${TORGX}'/' ${outname}
    sed -i 's/TORGY/'${TORGY}'/' ${outname}
    sed -i "s/RESOLUTIONLIST/${RESOLUTIONLIST}/" ${outname}
    sed -i 's/TSIZEX/'${TSIZEX}'/' ${outname}
    sed -i 's/TSIZEY/'${TSIZEY}'/' ${outname}
done

# PWPW
cp $pwpTemplate ${outiframe}/99_referencemap.html

# end of script