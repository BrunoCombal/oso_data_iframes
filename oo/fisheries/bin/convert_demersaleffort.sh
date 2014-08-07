#!/bin/bash
# author: Bruno Combal
# date: August 2014

# convert csv file to a series of json for highcharts

infile='/data/public_store/oo_fisheries/annual_demersal_fishing_effort_1950_2006.csv'

# list of FAO regions
fao=($(cut -d ',' -f 2 ${infile} | tail -n +2 | sort | uniq))

for ifao in ${fao[@]}
do
    echo "{name:'FFA "${ifao}"', marker:{enabled:false}, lineWidth:1, data:["
    awk -F ',' -v fao=${ifao} '{if ($2==fao){printf "[%d, %f],", $1, $3}}' ${infile}
    echo "]},"
done

# end of file