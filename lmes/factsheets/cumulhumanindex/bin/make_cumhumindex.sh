#!/bin/bash

# author: Bruno Combal, IOC-UNESCO
# date: October 2013

lmesNames='/data/iframes/lmes/factsheets/lme_code_names.csv'
template='cumhumanindex_template.html'
outdir='/data/iframes/lmes/factsheets/cumulhumanindex/iframes'
mkdir -p ${outdir}

cat $lmesNames | while read line
do
    code=$(echo ${line} | sed 's/\ .*//' | tr -d '[:space:]')
    name=$(echo ${line} | sed 's/[0-9]*\ //')

    outname=${outdir}/${code}_cumhumindex.html

    # cp template
    cp ${template} ${outname}
    
done

