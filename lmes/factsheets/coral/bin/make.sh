#!/bin/bash

#author: Bruno COMBAL, IOC-UNESCO
#date: March 2015

template=template.php
out=/data/iframes/lmes/factsheets/coral/iframes
mkdir -p ${out}

listLME=(4 5 6 10 11 12 16 17 30 31 32 33 34 35 36 37 38 39 40 41 44 45 47 49)
for ii in ${listLME[@]}
do
    outfile=${out}/coral_${ii}.php
    cp ${template} ${outfile}
    sed -i 's/LMECODETOREPLACE/'${ii}'/' ${outfile}
done

# end of script
