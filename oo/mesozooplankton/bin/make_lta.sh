#!/bin/bash

# \author Bruno Combal, IOC-UNESCO, Paris
# \date January 2014

# Compute monthly LTA

infile=/data/iframes/oo/mesozooplankton/data/clean_filled_ts_sahfos.csv

# get list of regions

cut -d ';' -f 3 ${infile} | grep -i -v region | sort | uniq | while read ii
do
	test=$(echo $ii | tr -d [:space:])
	[ -z "$test" ] && continue
	# echo "processing region $ii"
	for imonth in $(seq 1 12)
	do
		abundance=0
		nabundance=0
		ccs=0
		nccs=0
		# sum up for all years
		lstData=($(grep "$ii" ${infile} | awk -F ";" -v month=${imonth} '{ if ($2==month && length($4)!=0){print $4}}' ))
		for idata in ${lstData[@]}
		do
			#echo $abundance, $nabundance
			abundance=$(echo "${abundance} + ${idata}" | bc)
			nabundance=$((nabundance+1))
		done

		lstData=($(grep "${ii}" ${infile} | awk -F ";" -v month=${imonth} '{ if ($2==month && length($5)!=0){print $5}}' ))
		for idata in ${lstData[@]}
		do
			ccs=$(echo "${ccs} + ${idata}" | bc)
			nccs=$((nccs+1))
		done

		avgAbundance=''
		if [ ${nabundance} -ne 0 ]; then
			avgAbundance=$(echo "$abundance/${nabundance}" | bc -l)
		fi
		avgCCS=''
		if [ ${nccs} -ne 0 ]; then
			avgCCS=$( echo "$ccs/$nccs" | bc -l)
		fi
		
		echo $ii, $imonth, $avgAbundance, $nabundance, $avgCCS, $nccs
	done
done

# end of script