#!/bin/bash

# author: Joao Pedro Bourbon
# date: June 2014
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_fisheries'
inFile='LME_catch_LV.csv'
template='catch_a_template.html'
temp=''
out='/data/iframes/lmes/factsheets/catch_a'
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}
cp $inDir/$inFile $outdata/.

echo "Fisheries -> Annual Catch dataset"
echo "==== Starting process ===="
echo "Processing individual data files..."

#Creation of the data files
f=$outdata/$inFile
awk -F ';' '{if (NR > 1){printf "%02d_data.csv %s %s %s %s\n", $2, $1, $2, $3, $4}}' $f | sort -n | while read fName year lmeN catch usd
do
	echo $lmeN','$year','$catch','$usd >> $outdata/$fName
done

#gater data for the templates
for f in ${outdata}/*.csv
do
	if [[ $f != *LME* ]]; then
	
		lmeNumber=$(awk -F ',' '{if(NR == 1){printf "%s", $1};}' $f)
		name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
		lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		
		# create a new html iframe
		iframe=${outiframe}/catch_a_${name}.php
		cp $out/'bin'/${template} ${iframe}
	
		
		# update information in the html page
		perl -i -pe 's/CHARTTITLETOREPLACE/ ('"${lmeName}"')/' ${iframe}
		perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
		perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
		
		
		echo $lmeNumber '... ready!'
	fi
done
echo "Annual Catch .... DONE!"

# end of script