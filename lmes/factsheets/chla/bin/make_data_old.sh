#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files
mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
out='/data/iframes/lmes/factsheets/chla'
inDir='/data/public_store/lmes_chla'
template='chla_template.html'
temp=''
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

echo "Chlorophyle-A dataset"
echo "==== Starting process ===="
echo "Processing individual data files..."
for i in $(seq 1 66) 99;
do
	#Create individual data files
	fM=$(find ${inDir} -maxdepth 1 -type f ! -size 0 -name "*-$i-CHL-M*") 
	fY=$(find ${inDir} -maxdepth 1 -type f ! -size 0 -name "*-$i-CHL-Y*") 
 	fL=${inDir}/LONG-TERM-MEAN-CHL.CSV
	
	lmeNumber=`awk -F ',' '{if (NR == 2){printf "%02d", $2}}' $fM `
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	
	outfile=${outdata}/${lmeNumber}_data.csv
	rm -f ${outfile}
	awk -F ',' '{if (NR > 1){print "YM", $4, $5, $6}}' $fM >> ${outfile}
	awk -F ',' '{if (NR > 1){print "YAVG", $4, $5}}' $fY >> ${outfile}
	
	ltAvg=$(awk -F ',' -v thisLME=${lmeNumber} '{if ($2==thisLME) {printf "LTA %s", $6};}' $fL)
	echo ${ltAvg} >> ${outfile}
	
	# create a new html iframe
	iframe=${outiframe}/chla_${name}.php
	cp $out/'bin'/${template} ${iframe}
		
	# update information in the html page
	title=($(echo $name | sed 's/_/\ /g'))		
	perl -i -pe 's/CHARTTITLETOREPLACE/Chlorophyll-A ('"${lmeName}"')/' ${iframe}
	perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
	perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
		
	
	echo $lmeNumber' - '$lmeName
	
done
#Create the array for the search box
arrayLMEs='var availableTags=['
f=$outdata/temp
data=$(ls $outdata | grep -v LME | grep -v 99 | sed 's/_.*//g')
for lmeNumber in $data
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


echo "Chlorophyll-A ... DONE!"

# end of script