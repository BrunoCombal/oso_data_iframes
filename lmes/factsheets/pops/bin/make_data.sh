#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inFile=" /data/public_store/lmes_pops/lmes_pops.csv"
template='pops_template.html'
temp=''
out="/data/iframes/lmes/factsheets/pops"
outiframe=$out'/iframes'$temp
outddata='data'$temp
outdata=$out/$outddata
fName='data.csv'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

echo "POPs"
echo "==== Starting process ===="
echo "Processing individual data files..."


f=$inFile
awk -F ',' '{if (NR > 1){printf "%02d %s %s %s %s %s %s %s\n", $1, $3, $4, $5, $6, $7, $8, $9}}' $f | sort -n | while read lmeN nlocs pcb pcbr ddt ddtr hch hchr
do
		
		lmeNumber=$lmeN
		name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
		lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		echo $lmeN','$lmeName','$nlocs','$pcb','$pcbr','$ddt','$ddtr','$hch','$hchr >> $outdata/$fName
		echo $lmeNumber $lmeName
		
		# create a new html iframe
		iframe=${outiframe}/pops_${name}.php
		cp $out/'bin'/${template} ${iframe}
		
		# update information in the html page
		perl -i -pe 's/CHARTTITLETOREPLACE/'"${lmeName}"'/' ${iframe}
		perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
		perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
	
done
arrayLMEs='var availableTags=['
f=$outdata/$fName
arrayLMEs="$arrayLMEs $(awk -F ',' '{printf "\"%s %s\",\n", $1, $2}' $f)"
arrayLMEs=$(echo $arrayLMEs | sed -e 's/\(.*\)./\1/')
arrayLMEs="$arrayLMEs ];"
#echo $arrayLMEs

for f in ${outiframe}/*.php
do
	perl -i -pe 's/LISTOFAVAILABLELMES/ '"${arrayLMEs}"'/' ${f}
done

echo "POPs ... DONE!"

# end of script