#!/bin/bash

# author: Joao Pedro Bourbon
# date: June 2014
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_fisheries/Stock_status_LME'
template='stocks_template.html'
temp=''
out='/data/iframes/lmes/factsheets/stocks'
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p $outiframe
mkdir -p $outdata


echo "Fisheries -> Stocks"
echo "==== Starting process ===="
echo "Processing individual data files..."

TEMPFILE=/tmp/$RANDOM.tmp
echo 1 > $TEMPFILE
awk -F ' ' '{printf "%02d %s\n", $1, $2}' $mapfile | sort -n | while read lmeN lme
do
	name=$(echo $lme | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo $name | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	lmeNumber=$lmeN
	
	temp=$(echo $lme | sed -s "s/_US_/_U_S__/g" | sed "s/LABRADOR_NEWFOUNDLAND/newfoundland-labrador_shelf/g" | sed "s/_HAWAIIAN/-HAWAIIAN/g" | sed "s/CENTRAL_AMERICAN/CENTRAL-AMERICAN/g" | sed "s/CANADIAN_EASTERN_ARCTIC_WEST_GREENLAND/west_greenland_shelf/g" | sed "s/_BISCAY/-biscay/g" | sed "s/_CELEBES/-celebes/g" | sed "s/NORTH_WEST/northeast/g" | sed "s/NORTH_WEST/northwest/g" | sed "s/SOUT_EAST/southeast/g" | sed "s/SOUTH_WEST/southwest/g" | sed "s/JAPAN/japan_east_sea/g" | sed "s/SEA_OF_OKHOTSK/OKHOTSK_SEA/g" | sed "s/NORTHERN_BERING_CHUKCHI_SEAS/CHUKCHI_SEA/g" | sed "s/ICELAND_SHELF_AND_SEA/ICELAND_SHELF_SEA/g" | sed "s/ANTARCTICA/ANTARCTIC/g" | sed "s/_COMPLEX//g" | sed "s/CENTRAL_ARCTIC/arctic_ocean/g" | sed "s/ALEUTIAN_ISLANDS/arctic_archipelago/g" | sed "s/CANADIAN_HIGH_ARCTIC_NORTH_GREENLAND/baffin_bay_davis_strait/g")
	#echo $a
	fN=$(echo 'LME__'$temp'_StockStatus.csv' | tr '[:upper:]' '[:lower:]')
	fN1=$(echo 'LME__'$temp'_CatchByStockStatus.csv' | tr '[:upper:]' '[:lower:]')
	#echo $fN
	for f in ${inDir}/*
	do
		#if [[ $f != *_CatchBy* ]]; then
			ofN=$(echo $f | tr '[:upper:]' '[:lower:]')
			if [[ $ofN == *$fN ]]; then
				#COUNTER=$[$(cat $TEMPFILE) + 1]
				cp $f ${outdata}${lmeN}'_data.csv'
			fi
			if [[ $ofN == *$fN1 ]]; then
				COUNTER=$[$(cat $TEMPFILE) + 1]
				cp $f ${outdata}${lmeN}'_data1.csv'
				
				
				# create a new html iframe
				iframe=${outiframe}/stocks_${name}.php
				cp $out/'bin'/${template} ${iframe}
				
				# update information in the html page
				perl -i -pe 's/CHARTTITLETOREPLACE/ '"${lmeName}"'/' ${iframe}
				perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
				perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
				
				
				echo $COUNTER' - '$lmeN' ('$lmeName') ...ready!'
				
				
				
				
			
				
			fi
			
		#fi
	done
	echo $COUNTER > $TEMPFILE
done
unlink $TEMPFILE



#Create de array for the search box
arrayLMEs='var availableTags=['
f=$outdata/temp
data=$(ls $outdata | grep -v LME | grep -e data.csv | sed 's/_.*//g')
for lmeNumber in ${data[@]}
do
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
		lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		echo $lmeNumber','$lmeName >> $f
done

arrayLMEs="$arrayLMEs $(awk -F ',' '{printf "\"%s %s\",\n", $1, $2}' $f)"
arrayLMEs=$(echo $arrayLMEs | sed -e 's/\(.*\)./\1/')
arrayLMEs="$arrayLMEs ];"
#echo $arrayLMEs
unlink $f

for f in ${outiframe}/*.php
do
	perl -i -pe 's/LISTOFAVAILABLELMES/ '"${arrayLMEs}"'/' ${f}
done



#echo "Done!"

# end of script