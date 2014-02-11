#!/bin/bash

# \author Bruno Combal, IOC-UNESCO
# \date January, 2014

# average deviation data

infile=/data/iframes/oo/mesozooplankton/data/deviation_log.txt
regionList=/data/iframes/oo/mesozooplankton/data/regions.txt

echo "Region Year Abundance CCS"
cat ${regionList}  | while read region
do
    years=($(grep "$region" ${infile} | cut -d ',' -f 2 | sort | uniq))
    for iyears in ${years[@]}
    do
	# abundance
	abundance=0
	tmp=($(grep "$region" ${infile} | grep "${iyears}" | cut -d ',' -f 4))
	if [ ${#tmp[@]} -ne 0 ]; then
	    for ii in ${tmp[@]}
	    do
		abundance=$(echo "$ii + $abundance" | bc -l)
	    done
	    abundance=$(echo "scale=10; $abundance / ${#abundance[@]}" | bc -l)
	else
	    abundance=''
	fi

	# ccs
	ccs=0
	tmp=($(grep "$region" ${infile} | grep "${iyears}" | cut -d ',' -f 5))
	if [ ${#tmp[@]} -ne 0 ]; then
	    for ii in ${tmp[@]}
	    do
		ccs=$(echo "$ii + $ccs" | bc -l )
	    done
	    
	    ccs=$(echo "scale=10; $ccs / ${#ccs[@]}" | bc -l)
	else
	    ccs=''
	fi

	echo $region, $iyears, $abundance, $ccs
    done
done


# end of script