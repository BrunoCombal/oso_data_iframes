#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_nutrients'
inFile2000='/lmes_nutrients_loading_eutrophication_2000.csv'
inFile2030='/lmes_nutrients_loading_eutrophication_2030.csv'
inFile2050='/lmes_nutrients_loading_eutrophication_2050.csv'
template='icep_template.html'
temp=''
out="/data/iframes/lmes/factsheets/icep"
outiframe=$out'/iframes'$temp
outddata='data'$temp
outdata=$out/$outddata
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

#Generate a temporary file with the time series for all the LMEs
f=${inDir}/${inFile2000}
awk -F ',' '{if (NR > 1){printf "%02d %s %s\n", $1, $18, $19}}' $f | while read lmeNumber icep_cat merged
do
	echo "$lmeNumber 2000 $icep_cat $merged" >> $outdata/temp.csv
done
f=${inDir}/${inFile2030}
awk -F ',' '{if (NR > 1){printf "%02d %s %s\n", $1, $18, $19}}' $f | while read lmeNumber icep_cat merged
do
	echo "$lmeNumber 2030 $icep_cat $merged" >> $outdata/temp.csv
done
f=${inDir}/${inFile2050}
awk -F ',' '{if (NR > 1){printf "%02d %s %s\n", $1, $18, $19}}' $f | while read lmeNumber icep_cat merged
do
	echo "$lmeNumber 2050 $icep_cat $merged" >> $outdata/temp.csv
done

#divide the data in individual files per LME
f=${outdata}/temp.csv
awk -F ' ' '{if (NR > 0){printf "%02d %s %s %s\n", $1, $2, $3, $4}}' $f | while read lmeNumber year icep_cat merged
do
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")

	echo "$lmeNumber $year $icep_cat $merged"  >> $outdata/${lmeNumber}_data.csv
done
rm $outdata/temp.csv

#generate the template files
for f in ${outdata}/*.csv
do
	lmeNumber=$(awk -F ' ' '{if (NR == 1) {printf "%s", $1};}' $f)
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	
	iframe=${outiframe}/'icep_'${name}.php
	cp $out/'bin'/${template} ${iframe}


	
	perl -i -pe 's/CHARTTITLETOREPLACE/'"(${lmeName})"'/' ${iframe}
	perl -i -pe 's/THISLMECODETOREPLACE/'${lmeNumber}'/' ${iframe}
	perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
	
	
	echo $lmeNumber" ... ready!"
done





echo "Nutrients ... DONE!"

# end of script