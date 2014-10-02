#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_icep/data'
inFile='LME_NEWSRH2000_Nutrients_and_ICEP.csv'
template='icep_template.html'
temp=''
out='/data/iframes/lmes/factsheets/icep'
outiframe=$out'/iframes'$temp
outddata='data'$temp
outdata=$out/$outddata
#rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
#mkdir -p ${outdata}

#parse the source file creating a clean source
f=${inDir}/${inFile}
awk -F ';' '{if (NR > 2){printf "%02d %s %s %s\n", $1, $12, $13, $14}}' $f | while read lmeNumber icep icepn icepp
do
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")

	# create a new html iframe
	iframe=${outiframe}/'icep_'${name}.html
	cp $out/'bin'/${template} ${iframe}
	
	perl -i -pe 's/CHARTTITLETOREPLACE/'"(${lmeName})"'/' ${iframe}
	perl -i -pe 's/THISLMECODETOREPLACE/'${lmeNumber}'/' ${iframe}
	
	echo $lmeNumber" ... ready!"
done





echo "ICEP ... DONE!"

# end of script