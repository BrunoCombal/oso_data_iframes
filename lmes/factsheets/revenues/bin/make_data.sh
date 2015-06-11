#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
inFile="/data/public_store/lmes_socioeco/rsrc/wrk_files/lmes_revenues_nldi_extract.csv"
template='revenues_template.html'

outDir="/data/iframes/lmes/factsheets/revenues"
iframeDir=${outDir}/iframes
dataDir=${outDir}/data
outData=${dataDir}/data.csv

mkdir -p ${iframeDir}
mkdir -p ${dataDir}

echo "Revenues"
echo "==== Starting process ===="
echo "Processing individual data files..."

# generate a working file for the php scripts
# keep only lines starting with an lme number (allows generating printAll.php)
awk -F";" '$1~ "^[0-9].*$" {print $0}' ${inFile} > ${outData}
# change ; into ,
sed -i 's/;/,/g' ${outData}

cat ${mapfile} | while read code name;
do
    #toappend=$(awk -F ';' -v code=$code '{if ($1==code) {print $0};}' ${inFile});

    lmeNumber=$code
    outName=$(awk -F ' ' -v thisLME=${lmeNumber} '{if ($1==thisLME) {printf "%s", $2};}' ${mapfile} | tr '[:upper:]' '[:lower:]')
    lmeName=$(echo ${outName} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
    lmeN=$(printf "%02d" $lmeNumber)

    # create a new html iframe
    namePhp=`echo ${name} | tr [:upper:] [:lower:]`
    iframe=${iframeDir}/revenues_${namePhp}.php
    cp -f ${outDir}/bin/${template} ${iframe}

    # update information in the html page
    perl -i -pe 's/CHARTTITLETOREPLACE/'"${lmeName}"'/' ${iframe}
    perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeN}'"/' ${iframe}
    perl -i -pe 's|THISLMEOUTDATA|"'${dataDir#/data}'"|' ${iframe}

    echo $lmeN' - '$lmeName' ... Done!'
done

# let's generate the printAll.php code
arrayLMEs='var availableTags=['

arrayLMEs="$arrayLMEs $(awk -F ';' -v g='"' 'NR<2{next}{ if ($1 > 0) {printf "%s%02d %s%s,\n", g,$1, $2,g}}' ${inFile})"
# "' #to help emacs parsing colors...

arrayLMEs=$(echo $arrayLMEs | sed -e 's/\(.*\)./\1/')
arrayLMEs="$arrayLMEs ];"
echo $arrayLMEs > tmpfile

for f in ${iframeDir}/*.php
do
    #perl -i -pe "s/LISTOFAVAILABLELMES/${arrayLMEs}/" ${f}
    sed -i '/LISTOFAVAILABLELMES/{
    r tmpfile
    d
    }' ${f} 
done

referenceFile=`find ${iframeDir} -name revenues_*.php | head -1`
cp $referenceFile ${iframeDir}/printAll.php

echo "Revenues... DONE!"

# end of script