<?php

print $_GET['l'];


   	
	$lme=$_GET['l'];
	$field='';

	if (($lme>0) && ($lme<67)) {
print 'icic';
		$thisNode=node_load(58);
		$thisField=field_get_items('node', $thisNode, 'field_productivity_chla');
		$output=field_view_value('node',$thisNode,'field_productivity_chla', $thisField[0]);
			print render($output);
		}



?>