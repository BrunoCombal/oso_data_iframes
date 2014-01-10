#!/bin/bash

# author: Bruno Combal, IOC-UNESCO
# date: January 2014

# put together components for mesozooplankton abundance graphs

bindir='/data/iframes/oo/mesozooplankton/bin'
templatedir='/data/iframes/oo/mesozooplankton/template/'
outfile='/data/iframes/oo/mesozooplankton/iframes/mesozooplankton_abundance.html'

cat ${templatedir}/header.html > ${outfile}
${bindir}/make_timeseries.sh >> ${outfile}
cat ${templatedir}/footer.html >> ${outfile}

