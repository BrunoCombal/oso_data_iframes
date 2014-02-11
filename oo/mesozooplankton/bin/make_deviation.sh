#!/bin/bash

# \author Bruno Combal, IOC-UNESCO
# \date January, 2014

# compute difference to LTA
ltaFile=/data/iframes/oo/mesozooplankton/data/lta.txt
regionList=/data/iframes/oo/mesozooplankton/data/regions.txt
infile=/data/iframes/oo/mesozooplankton/data/clean_filled_ts_sahfos.csv

# process per region
cat ${regionList} | while read region
do
    # for this region, store the 12 monthly LTA
	# caution: if a value is empty, nothing is append: enclose with ''
	declare -a abundanceLTA=('' '' '' '' '' '' '' '' '' '' '' '')
	declare -a ccsLTA=('' '' '' '' '' '' '' '' '' '' '' '')
	for imonth in $(seq 1 12)
	do
		ipos=$((imonth-1))
		abundanceLTA[$ipos]="$(grep "$region" ${ltaFile} | awk -F ',' -v month=${imonth} '{ if ($2==month) {print $3}}' )"
		ccsLTA[$ipos]="$(grep "$region" ${ltaFile} | awk -F ',' -v month=${imonth} '{if ($2==month){print $5}}' )"
	done
	
    grep "${region}" ${infile} | while read line
    do
		thisYear=$(echo $line | cut -d ';' -f 1)
		thisMonth=$(echo $line | cut -d ';' -f 2)
		[ -z "${thisMonth}" ] && continue #requires yearly computation
		thisAbundance=$(echo ${line} | cut -d ';' -f 4)
		thisCCS=$(echo ${line} |cut -d ';' -f 5)
		# echo $line
		if [ -n "${thisMonth}" ]; then
			ipos=$((thisMonth-1))
		else
			ipos=-1
		fi
		deviationAbundance=''
		# if there is a data, its LTA exists
		if [ -n "${thisAbundance}" ] ; then
			if [ "$thisAbundance" != 0 ] ; then
				deviationAbundance=$(echo "scale=8; l(${thisAbundance}/${abundanceLTA[$ipos]})/l(10)" | bc -l)
			fi
		fi
		deviationCCS=''
		# if there is a data, its LTA exists
		if [ -n "${thisCCS}" ]; then
			if [ "${thisCCS}" != 0 ]; then
				deviationCCS=$(echo "scale=8; l(${thisCCS}/${ccsLTA[$ipos]})/l(10)" | bc -l)
			fi
		fi
		echo $region, $thisYear, $thisMonth, $deviationAbundance, $deviationCCS
		#, $ipos, $thisAbundance, ${abundanceLTA[$ipos]}, $thisCCS, ${ccsLTA[$ipos]}
    done
done

# end of script