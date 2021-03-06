#!/bin/bash

# author: Bruno COMBAL, IOC-UNESCO
# date: January 2014

# reads a csv file, output: highcharts formatted time series

# format is : region, year, month, mean Mesozooplankton abundance, mean CCS

declare -A linkedTo
linkedTo['NE Oceanic Pacific']='NE Oceanic Pacific'
linkedTo['BC shelf']='NE Oceanic Pacific'
linkedTo['Cook Inlet']='NE Oceanic Pacific'
linkedTo['Alaskan Shelf']='NE Oceanic Pacific'
linkedTo['Aleutian Shelf']='NE Oceanic Pacific'
linkedTo['Aleutian Shelf']='NE Oceanic Pacific'
linkedTo['Western Gulf of Alaska']='NE Oceanic Pacific'
linkedTo['Northern Gulf of Alaska']='NE Oceanic Pacific'
linkedTo['Bering Sea']='NE Oceanic Pacific'

datacsv='/data/public_store/mesozooplankton/mesozooplankton_timeseries.csv'

# get first the list of regions
listRegion=($(cat ${datacsv} | grep -v 'Year' | cut -f 1 -d ';' | sed 's/ /_/g' | sort | uniq | sort))

echo '$(function(){'
echo '$('"'"'#mesozoograph'"'"').highcharts({'
echo 'credits: {enabled:true, href:'"'"'http://onesharedocean.org/data/public_store/mesozooplanton/data.zip'"'"', text:'"'"'Get data'"'"'},'

# chart definition
echo 'chart: { type:'"'"'scatter'"'"', renderTo:'"'"'mesozoograph'"'"'',
echo 'marginRight:180, marginBottom: 40,'
echo 'zoomType:'"'"'x'"'"', '
echo 'resetZoomButton:{relativeTo:'"'"'chart'"'"', position:{align:'"'"'right'"'"', verticalAlign:'"'"'bottom'"'"', x:-10, y:-50}}'
echo '},' # close chart: {
echo 'title:{text:'"'"'Mesozooplankton abundance'"'"'}, subtitle:{},'
echo 'xAxis:{title:{text:'"'"'Date'"'"'}, type:'"'"'datetime'"'"', allowDecimals:false}, '
echo 'yAxis:{title:{text:'"'"'number.m<sup>-3</sup>'"'"', useHTML:true}},'
#echo 'tooltip:{valueSuffix: '"'"'count/m<sup>-3</sup>'"'"', useHTML:true, valueDecimals:2},' 
echo 'legend:{layout:'"'"'vertical'"'"', align:'"'"'right'"'"', verticalAlign:'"'"'top'"'"', x:-10, y:73, borderWidth:0},'
echo 'plotOptions:{scatter:{marker:{radius:3}}},'
# series
echo -n "series: ["
for ii in ${listRegion[@]:0:25}
do
    echo "{name:'${ii}', data: ["
    awk -F ';' -v region=$ii '{ if ($1==region) {if (length($4)> 0 ) {print "[Date.UTC(",$2,",",$3," , 15), ",$4,"],"}; }}' $datacsv
    echo "]},"
done
echo "]" # end of series: [
echo '});' # end marker of $('#mesozoograph').highcharts({
echo '});' # end marker of $(function(){


# end of file