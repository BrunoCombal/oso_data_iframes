#!/bin/bash

#author: Bruno Combal, IOC-UNESCO
#date: 2013, October
mapfile="/data/iframes/lmes/factsheets/lme_code_names.csv"
lmeCode='/data/iframes/lmes/factsheets/lme_code_names.csv'
template='sst_template.html'
out="/data/iframes/lmes/factsheets/sst"
outdir='/data/iframes/lmes/factsheets/sst/iframes'
mkdir -p ${outdir}

for ii in $(seq 1 66) 99
do
    outname=${outdir}/${ii}_sst.php
    cp $out/'bin'/${template} ${outname}
    # edit in place
    name=$(awk -v code=${ii} '{if ($1==code) print $2}' ${lmeCode} | sed 's/[0-9]*\ //' | tr '[:upper:]' '[:lower:]')
    lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
    echo $ii'... ready!'
    perl -i -pe 's/LMENAMETOREPLACE/'"${lmeName}"'/' ${outname}
    sed -i 's/LMECODETOREPLACE/'${ii}'/' ${outname}
done

#Create de array for the search box
arrayLMEs='var availableTags=['
f=$outdir/temp
data=$(awk -F ' ' '{printf "%02d\n", $1;}' '/data/iframes/lmes/factsheets/sst/data/sst_data_trend.txt' | sort -n | uniq | grep -v 99)
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

for f in ${outdir}/*.php
do
    perl -i -pe 's/LISTOFAVAILABLELMES/ '"${arrayLMEs}"'/' ${f}
done


echo $code'SST ... DONE!'


# end of script
