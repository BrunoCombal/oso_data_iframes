#!/bin/bash

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
fName='data.csv'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

for ((ii=1; ii<67; ii++)); do
	data2000=$(awk -F ',' -v code=$ii '{if ($1==code){printf "%02d,%s,%s",$1,$18,$19}}' ${inDir}/${inFile2000} | tr -cd '[[:alnum:]].,')
	data2030=$(awk -F ',' -v code=$ii '{if ($1==code){printf "%s,%s",$18,$19}}' ${inDir}/${inFile2030} | tr -cd '[[:alnum:]].,')
	data2050=$(awk -F ',' -v code=$ii '{if ($1==code){printf "%s,%s",$18,$19}}' ${inDir}/${inFile2050} | tr -cd '[[:alnum:]].,')
	if [ ! "${data2000}" = '' ]; then
	
		name=$(awk -F ' ' -v thisLME=${ii} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		echo ${lmeName}','${data2000}','${data2030}','${data2050} >> $outdata/data.csv		
	fi
done

#generate the template files
f=${outdata}/data.csv
awk -F ',' '{if (NR > 0){printf "%s\n", $2}}' $f | while read lmeNumber
do
	name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
	lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
	
	iframe=${outiframe}/'icep_'${name}.php
	cp $out/'bin'/${template} ${iframe}


	
	perl -i -pe 's/CHARTTITLETOREPLACE/'"(${lmeName})"'/' ${iframe}
	perl -i -pe 's/THISLMECODETOREPLACE/'${lmeNumber}'/' ${iframe}
	perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
	
	
	echo $lmeNumber' - '$lmeName' ... ready!'
done

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

echo "ICEP ... DONE!"


