#!/bin/bash

# author: Joao Pedro Bourbon
# date: June 2014
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_fisheries'
inFile='LME_MTI_FiB_50_10.csv'
template='mti_template.html'
temp=''
out="/data/iframes/lmes/factsheets/mti"
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}
cp $inDir/$inFile $outdata/.

echo "Fisheries -> MTI"
echo "==== Starting process ===="
echo "Processing individual data files..."

#Creation of the data files
f=$outdata/$inFile
awk -F ';' '{if (NR > 1){printf "%0d_data.csv %s %s %s %s\n", $2, $2, $3, $4, $5}}' $f | sort -n | while read fName lmeN year mti fib
do
	echo $lmeN','$year','$mti','$fib >> $outdata/$fName
done

#gater data for the templates
for f in ${outdata}/*.csv
do
	if [[ $f != *LME* ]]; then
	
		lmeNumber=$(awk -F ',' '{if(NR == 1){printf "%s", $1};}' $f)
		name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
		lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		
		# create a new html iframe
		iframe=${outiframe}/mti_${name}.php
		cp $out/'bin'/${template} ${iframe}
	
		
		# update information in the html page
		perl -i -pe 's/CHARTTITLETOREPLACE/ ('"${lmeName}"')/' ${iframe}
		perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
		perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
		
		
		echo $lmeNumber '... ready!'
	fi
done
echo "NTI & FiB ... DONE!"

# end of script