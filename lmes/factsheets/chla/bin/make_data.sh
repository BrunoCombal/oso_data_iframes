#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

out='/data/iframes/lmes/factsheets/chla'
inDir=$out'/CSVS'
template='chla_template.html'
temp=''
outddata='data'$temp
outiframe=$out'/iframes'$temp
outdata=$out/$outddata'/'
rm -fr $outdata
rm -fr $outiframe
mkdir -p ${outiframe}
mkdir -p ${outdata}

echo "Chlorophyle-A dataset"
echo "==== Starting process ===="
echo "Processing individual data files..."
for f in ${inDir}/*.CSV
do
	#Create individual data files
	if [[ $f != *67* ]] && [[ $f != *PPD* ]]; then
		lme=`awk -F ',' '{if (NR == 2){printf "%02d_%s.csv", $2, $1}}' $f | tr '[:upper:]' '[:lower:]' `
		name=`awk -F ',' '{if (NR == 2){printf "%s", $1}}' $f | tr '[:upper:]' '[:lower:]' `
		lmeName=$(echo ${name} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g" | sed -s "s/ Us / U.S. /g")
		lmeNumber=`awk -F ',' '{if (NR == 2){printf "%02d", $2}}' $f `
		#if [[ $lmeN != 99 ]]; then
			outfile=${outdata}/${lmeNumber}_data.csv
			rm -f ${outfile}
			awk -F ',' '{print "YM", $3, $4, $5}' $f >> ${outfile}
			
			
			#Append year averages for this LME on the outfile
			for ((iyear=1998; iyear<2014; iyear++)); do
				data=($(awk -F ',' -v thisyear=${iyear} '{if ($3==thisyear) {print $5} }' ${f}  | dos2unix ))
				sum=0
				for ix in ${data[@]}; do
					sum=`echo "scale=12; ${sum} + ${ix}" | bc`
				done
				avg=`echo "scale=12; ${sum} / ${#data[@]}" | bc`
				echo "YAVG" ${iyear} ${avg} >> ${outfile}
			done
			
			#Append Longtime average to outfile
			ltAvg=$(awk -F ',' -v thisLME=${lmeNumber} '{if ($2==thisLME) {printf "LTA %s", $8};}' ${inDir}/'67 REGIONS-CHL-LONG-TERM-MEAN.CSV')
			echo ${ltAvg} >> ${outfile}
		#fi
		
		# create a new html iframe
		iframe=${outiframe}/chla_${name}.php
		cp $out/'bin'/${template} ${iframe}
		
		# update information in the html page
		title=($(echo $name | sed 's/_/\ /g'))
		
		perl -i -pe 's/CHARTTITLETOREPLACE/Chlorophyll-a ('"${lmeName}"')/' ${iframe}
		perl -i -pe 's/THISLMECODETOREPLACE/"'${lmeNumber}'"/' ${iframe}
		perl -i -pe 's/THISLMEOUTDATA/"'${outddata}'"/' ${iframe}
		
		
		echo $lmeNumber" ready!"
	fi
	
	
done
echo "Chlorophyll-A ... DONE!"

# end of script