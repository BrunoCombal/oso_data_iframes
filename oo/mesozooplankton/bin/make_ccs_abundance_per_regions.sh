#!/bin/bash

#\author Bruno Combal, IOC-UNESCO
#\date February 2014

# create illustrations for CCS and Abundance, per region

template='/data/iframes/oo/mesozooplankton/template/mesozooplankton_yearlyts.html'
outdir='/data/iframes/oo/mesozooplankton/iframes'

declare -A dataType
dataType=(['Abundance']='2' ['CCS']='3' )

regions=('north_atlantic' 'north_east_pacific' 'north_west_atlantic' 'north_west_pacific' 'southern_ocean' 'benguela' 'australian')

for dt in ${!dataType[@]}
do
	for iregion in ${regions[@]}
	do
		outfile=${outdir}/${dt}_${iregion}.html
		cp -f ${template} ${outfile}
		# edit the file
		sed -i 's/TEMPLATEDATATYPENAME/'${dt}'/' ${outfile}
		sed -i 's/TEMPLATEDATATYPECODE/'${dataType[${dt}]}'/' ${outfile}
		sed -i 's/TEMPLATESERIESSELECT/'${iregion}'/' ${outfile}
	done
	
done

# end of script