#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files
mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir="/data/public_store/lmes_pp"
template='pp_template.html'
temp=''
out="/data/iframes/lmes/factsheets/pp"
outiframe=$out'/iframes'$temp
outddata='data'$temp
outdata=$out/$outddata
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

echo "Primary Production"
echo "==== Starting process ===="
echo "Processing individual data files..."
for i in $(seq 1 66) 99;
do
	#Create individual data files
	fY=$(find ${inDir} -maxdepth 1 -type f ! -size 0 -name "*-$i-PPY-Y.CSV") 
 	fL=${inDir}/lmes_chla_ppy_trends.csv
	fT=${inDir}/regression_lines.csv
	
	
	lmeNumber=`awk -F ',' '{if (NR == 2){printf "%02d", $2}}' $fY `
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	
	outfile=${outdata}/${lmeNumber}_data.csv
	
	awk -F ',' '{if (NR > 1){print "YA", $4, $5}}' $fY >> ${outfile}
	
	
	ltAvg=$(awk -F ',' -v thisLME=${lmeNumber} '{if ($2==thisLME) {printf "LTA %s", $3};}' $fL)
	echo ${ltAvg} >> ${outfile}
	
	g=$(awk -F ',' -v thisLME=${lmeNumber} '{if ($2==thisLME) {printf "G %s", $4};}' $fL)
	echo ${g} >> ${outfile}
	
	#Computation of trend
	reg=($(awk -F ',' -v thisLME=${lmeNumber} '{if ($1==thisLME) {print $3};}' $fT))
	if [ "${reg[0]}" != "nan" ];
	then
		echo "TREND "${reg[0]} ${reg[1]} >> ${outfile}
	fi
	#echo "TREND "$trend >> ${outfile}
			#End of calculation of trend
	
	# create a new html iframe
	iframe=${outiframe}/pp_${name}.php
	cp $out/'bin'/${template} ${iframe}
		
	# update information in the html page
	title=($(echo $name | sed 's/_/\ /g'))		
	perl -i -pe 's/CHARTTITLETOREPLACE/Primary Productivity ('"${lmeName}"')/' ${iframe}
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


echo "Primary Productivity ... DONE!"

# end of script