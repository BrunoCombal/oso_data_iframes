#!/bin/bash

# author: Joao Pedro Bourbon
# date: June 2014
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inDir='/data/public_store/lmes_ohi'
inFile='lmes_ohi.csv'
template='ohi_template.html'
temp=''
out='/data/iframes/lmes/factsheets/ohi'
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}
cp $inDir/$inFile $outdata/.

echo "OHI"
echo "==== Starting process ===="
echo "Processing individual data files..."

#Creation of the data files
f=$outdata/$inFile
for i in $(seq 1 66);
do

    lme=$(awk -F ';' -v theCode=${i} 'BEGIN { OFS = ","; ORS = "\n\n" } {if ($1 == theCode){$1=$2=""; print $0}}' $f)
    lme=${lme:2}
    lmeNumber=$i
    name=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' $mapfile | tr '[:upper:]' '[:lower:]')
    lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")

    if [ "$lme" ];then
        echo $lmeNumber","$lmeName","$lme >> ${outdata}/data.csv
    fi

        # create a new html iframe
    iframe=${outiframe}/ohi_${name}.php
    cp $out/'bin'/${template} ${iframe}


        # update information in the html page
    perl -i -pe 's/CHARTTITLETOREPLACE/ ('"${lmeName}"')/' ${iframe}
    perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
    perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}


    echo $lmeNumber '... ready!'
done

#Create de array for the search box
arrayLMEs='var availableTags=['
f=$outdata/data.csv
arrayLMEs="$arrayLMEs $(awk -F ',' '{printf "\"%02d %s\",\n", $1, $2}' $f)"
arrayLMEs=$(echo $arrayLMEs | sed -e 's/\(.*\)./\1/')
arrayLMEs="$arrayLMEs ];"
#echo $arrayLMEs

for f in ${outiframe}/*.php
do
    perl -i -pe 's/LISTOFAVAILABLELMES/ '"${arrayLMEs}"'/' ${f}
done
cp $(find ${outiframe} -name $(ls ${outiframe}/ | head -1)) ${outiframe}/printAll.php

echo "OHI ... DONE!"

# end of script