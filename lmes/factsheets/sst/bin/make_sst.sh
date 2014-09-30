#!/bin/bash

#author: Bruno Combal, IOC-UNESCO
#date: 2013, October

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

echo $code'SST ... DONE!'


# end of script
