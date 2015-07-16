#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inFile="/data/public_store/lmes_socioeco/lme_area_pop_nldi_hdi.csv"
template='hdi_template.html'
temp=''
out="/data/iframes/lmes/factsheets/hdi"
outiframe=$out'/iframes'$temp
outddata='data'$temp
outdata=$out/$outddata
fName='data.csv'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

echo "Human Development Index"
echo "==== Starting process ===="
echo "Processing individual data files..."


tempFile=${outdata}/cutted.txt
cut -d ',' -f 1,3-13 ${inFile} > ${tempFile}
cat ${mapfile} | while read code name;
do
	toappend=$(awk -F ',' -v code=$code '{if ($1==code) {print $0};}' $tempFile);
	
	lmeNumber=$code
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	lmeN=$(printf "%02d" $lmeNumber)
	echo $lmeName,$lmeN,$toappend >> ${outdata}/data.csv
		
	# create a new html iframe
	iframe=${outiframe}/hdi_${name}.php
	cp $out/'bin'/${template} ${iframe}
		
	# update information in the html page
	perl -i -pe 's/CHARTTITLETOREPLACE/'"${lmeName}"'/' ${iframe}
	perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeN}'"/' ${iframe}
	perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
	
	echo $lmeN' - '$lmeName' ... Done!'
done
rm $tempFile
arrayLMEs='var availableTags=['
f=$outdata/$fName
arrayLMEs="$arrayLMEs $(awk -F ',' '{printf "\"%02d %s\",\n", $2, $1}' $f)"
arrayLMEs=$(echo $arrayLMEs | sed -e 's/\(.*\)./\1/')
arrayLMEs="$arrayLMEs ];"
#echo $arrayLMEs

for f in ${outiframe}/*.php
do
	perl -i -pe 's/LISTOFAVAILABLELMES/ '"${arrayLMEs}"'/' ${f}
done
cp $(find ${outiframe} -name $(ls ${outiframe}/ | head -1)) ${outiframe}/printAll.php

echo "Human Development Index ... DONE!"

# end of script