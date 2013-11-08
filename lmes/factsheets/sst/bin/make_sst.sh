#!/bin/bash

#author: Bruno Combal, IOC-UNESCO
#date: 2013, October

lmeCode='/data/iframes/lmes/factsheets/lme_code_names.csv'
template='sst_template.html'
outdir='/data/iframes/lmes/factsheets/sst/iframes'
mkdir -p ${outdir}

for ii in $(seq 1 66)
do
    outname=${outdir}/${ii}_sst.html
    cp ${template} ${outname}
    # edit in place
    name=$(awk -v code=${ii} '{if ($1==code) print $0}' ${lmeCode} | sed 's/[0-9]*\ //')
    echo $name
    perl -i -pe 's/LMENAMETOREPLACE/'"${name}"'/' ${outname}
    sed -i 's/LMECODETOREPLACE/'${ii}'/' ${outname}
done