#!/bin/bash

# author: Bruno Combal, IOC-UNESCO
# date: October 2013

lmesNames='/data/iframes/lmes/factsheets/lme_code_names.csv'
template='ecolfootprint_template.html'
outdir='/data/iframes/lmes/factsheets/ecologicalfootprint/iframes'
mkdir -p ${outdir}

cat $lmesNames | while read line
do
    code=$(echo ${line} | sed 's/\ .*//' | tr -d '[:space:]')
    name=$(echo ${line} | sed 's/[0-9]*\ //')

    outname=${outdir}/${code}_ecolfootprint.html

    # cp template
    cp ${template} ${outname}
    
done

