<?php
try{
	if(isset($_GET['uuid'])){
		$uuid = '/data'.urldecode(utf8_decode($_GET['uuid']));
		$xmlfile = file_get_contents($uuid);
		//echo $uuid;
		$xml = new SimpleXmlElement($xmlfile);
	} else {
		//$xmlfile = "/data/public_store/oo_warmpool/rsrc/new_ipwp_xml_iso19139.xml";
		$xml = simplexml_load_file($xmlfile);
	}
} catch (Exception $err){
	?>
	<div id="messages">
<div class="messages error">
<h2 class="element-invisible">Error message</h2>
<em class="placeholder">Notice</em>: <?php echo $err->getMessage(); ?></div>
</div>
	<?php
}


$ns = $xml->getNamespaces(true);
$gmd = $xml->children($ns['gmd']);
$md = '/gmd:MD_Metadata';

$fileIdentifier = array();
$t = $gmd->xpath($md.'/gmd:fileIdentifier/gco:CharacterString');
if($t[0] != ''){
	$fileIdentifier = array((string) $t[0]);
}

$characterSet = array();
$t = $gmd->xpath($md.'/gmd:characterSet/gmd:MD_CharacterSetCode');
if($t != ''){
	$t = $t[0]->attributes();
	$characterSet = array((string) $t);
}




$contacts = array();
$t = $gmd->xpath($md.'/gmd:contact');
foreach ($t as $k => $v) {
	$temp = array();
	$individualName = $v->xpath('gmd:CI_ResponsibleParty/gmd:individualName/gco:CharacterString');
	if($individualName[0] != '') {
		array_push($temp, array('Name', array((string) $individualName[0])));
	}
	$organizationName = $v->xpath('gmd:CI_ResponsibleParty/gmd:organisationName/gco:CharacterString');
	if($organizationName[0] != '') {
		array_push($temp, array('Organization', array((string) $organizationName[0])));
	}
	$positionName = $v->xpath('gmd:CI_ResponsibleParty/gmd:positionName/gco:CharacterString');
	if($positionName[0] != '') {
		array_push($temp, array('Position', array((string) $positionName[0])));
	}
	
	$contactInfo = $v->xpath('gmd:CI_ResponsibleParty/gmd:contactInfo/gmd:CI_Contact');
	$contactInfo_temp = array();
	foreach ($contactInfo as $k1 => $v1) {
		$temp1 = array();
		$phone = $v1->xpath('gmd:phone/gmd:CI_Telephone');
		if(count($phone) > 0) {
			$voice = $phone[0]->xpath('gmd:voice/gco:CharacterString');
			if($voice[0] != '') {
				array_push($temp1, array('Voice', array((string) $voice[0])));
			}
			$fax = $phone[0]->xpath('gmd:facsimile/gco:CharacterString');
			if($fax[0] > '') {
				array_push($temp1, array('Fax', array((string) $fax[0])));
			}
			if(count($temp1) > 0) {
				array_push($contactInfo_temp, array('Phone', $temp1));
			}
		}
		$temp1 = array();
		$address = $v1->xpath('gmd:address/gmd:CI_Address');
		if(count($address) > 0) {
			$deliveryPoint = $address[0]->xpath('gmd:deliveryPoint/gco:CharacterString');
			if ($deliveryPoint[0] != '') {
				array_push($temp1, array('Delivery Point', array((string) $deliveryPoint[0])));
			}
			$city = $address[0]->xpath('gmd:city/gco:CharacterString');
			if ($city[0] != '') {
			var_dump($city);
				array_push($temp1, array('City', array((string) $city[0])));
			}
			$administrativeArea = $address[0]->xpath('gmd:administrativeArea/gco:CharacterString');
			if ($administrativeArea[0] != '') {
				array_push($temp1, array('Email Address', (string) $administrativeArea[0]));
			}
			$postalCode = $address[0]->xpath('gmd:postalCode/gco:CharacterString');
			if ($postalCode[0] != '') {
				array_push($temp1, array('Email Address', array((string) $postalCode[0])));
			}
			$country = $address[0]->xpath('gmd:country/gco:CharacterString');
			if ($country[0] != '') {
				array_push($temp1, array('Email Address', array((string) $countryy[0])));
			}
			$emailAddress = $address[0]->xpath('gmd:electronicMailAddress/gco:CharacterString');
			if ($emailAddress[0] != '') {
				array_push($temp1, array('Email Address', array((string) $emailAddress[0])));
			}
			if(count($temp1)>0){
				array_push($contactInfo_temp, array('Address', $temp1));
			}
		}
		$temp1 = array();
	}
	$role = $v->xpath('gmd:CI_ResponsibleParty/gmd:role/gmd:CI_RoleCode');
	if(count($role) > 0) {
		$role = (string) $role[0]->attributes()->codeListValue;
		array_push($temp, array('Role', array(getRoles($role))));
	}
	
	array_push($temp, array('Contact Info', $contactInfo_temp));
	
	//var_dump($contactInfo_temp);
	
	
array_push($contacts, $temp);		//var_dump($contacts);
}
$temp = '';

$dateStamp = array();
$t = $gmd->xpath($md.'/gmd:dateStamp/gco:DateTime');
if($t[0] != ''){
	array_push($dateStamp, (string) $t[0]);
}
//var_dump($dateStamp);
$temp = '';

$referenceSystemInfo = array();
$t = $gmd->xpath($md.'/gmd:referenceSystemInfo/gmd:MD_ReferenceSystem/gmd:referenceSystemIdentifier/gmd:RS_Identifier/gmd:code/gco:CharacterString');
if($t[0] != ''){
	array_push($referenceSystemInfo, (string) $t[0]);
}
//var_dump($referenceSystem);
$temp = '';

$identificationInfo = array();
$idInf = $md.'/gmd:identificationInfo/gmd:MD_DataIdentification';
$citation = array();
$t = '/gmd:citation/gmd:CI_Citation';
$title =$gmd->xpath($idInf.$t.'/gmd:title/gco:CharacterString');
if($title[0] != ''){
	array_push($citation, array('Title', array((string) $title[0])));
}
$date =$gmd->xpath($idInf.$t.'/gmd:date/gmd:CI_Date/gmd:date/gco:DateTime');
if($title[0] != ''){
	array_push($citation, array('Publication Date', array((string) $date[0])));
}
$presentationForm = $gmd->xpath($idInf.$t.'/gmd:presentationForm/gmd:CI_PresentationFormCode');
if(count($presentationForm) > 0){
	$presentationForm = $presentationForm[0]->attributes()->codeListValue;
	array_push($citation, array('Presentation Form', array(getPresentationForm((string) $presentationForm))));
}
array_push($identificationInfo, array('Citation', $citation));

$abstract = array();
$t = '/gmd:abstract/gco:CharacterString';
$t = $gmd->xpath($idInf.$t);
if($t[0] != ''){
	$abstract = array('Abstract', array((string) $t[0]));
}
array_push($identificationInfo, $abstract);

$status = array();
$t = '/gmd:status/gmd:MD_ProgressCode';
$status_temp = $gmd->xpath($idInf.$t);
if($status_temp != ''){
	$status_temp = $status_temp[0]->attributes()->codeListValue;
	$status = array('Status', array(getStatus((string) $status_temp)));
}
array_push($identificationInfo, $status);

$contacts2 = array();
$t = $gmd->xpath($idInf.'/gmd:pointOfContact');
foreach ($t as $k => $v) {
	$temp = array();
	$individualName = $v->xpath('gmd:CI_ResponsibleParty/gmd:individualName/gco:CharacterString');
	if($individualName[0] != '') {
		array_push($temp, array('Name', array((string) $individualName[0])));
	}
	$positionName = $v->xpath('gmd:CI_ResponsibleParty/gmd:positionName/gco:CharacterString');
	if($positionName[0] != '') {
		array_push($temp, array('Position', array((string) $positionName[0])));
	}
	$role = $v->xpath('gmd:CI_ResponsibleParty/gmd:role/gmd:CI_RoleCode');
	if(count($role) > 0) {
		$role = (string) $role[0]->attributes()->codeListValue;
		array_push($temp, array('Role', array(getRoles($role))));
	}
	$organizationName = $v->xpath('gmd:CI_ResponsibleParty/gmd:organisationName/gco:CharacterString');
	if($organizationName[0] != '') {
		array_push($temp, array('Organization', array((string) $organizationName[0])));
	}
	
	$contactInfo = $v->xpath('gmd:CI_ResponsibleParty/gmd:contactInfo/gmd:CI_Contact');
	$contactInfo_temp = array();
	foreach ($contactInfo as $k1 => $v1) {
		$temp1 = array();
		$phone = $v1->xpath('gmd:phone/gmd:CI_Telephone');
		if(count($phone) > 0) {
			$voice = $phone[0]->xpath('gmd:voice/gco:CharacterString');
			if($voice[0] != '') {
				array_push($temp1, array('Voice', array((string) $voice[0])));
			}
			$fax = $phone[0]->xpath('gmd:facsimile/gco:CharacterString');
			if($fax[0] > '') {
				array_push($temp1, array('Fax', array((string) $fax[0])));
			}
			if(count($temp1)>0){
				array_push($contactInfo_temp, array('Phone', $temp1));
			}
		}
		$temp1 = array();
		$address = $v1->xpath('gmd:address/gmd:CI_Address');
		if(count($address) > 0) {
			$deliveryPoint = $address[0]->xpath('gmd:deliveryPoint/gco:CharacterString');
			if ($deliveryPoint[0] != '') {
				array_push($temp1, array('Delivery Point', array((string) $deliveryPoint[0])));
			}
			$city = $address[0]->xpath('gmd:city/gco:CharacterString');
			if ($city[0] != '') {
			var_dump($city);
				array_push($temp1, array('City', array((string) $city[0])));
			}
			$administrativeArea = $address[0]->xpath('gmd:administrativeArea/gco:CharacterString');
			if ($administrativeArea[0] != '') {
				array_push($temp1, array('Email Address', array((string) $administrativeArea[0])));
			}
			$postalCode = $address[0]->xpath('gmd:postalCode/gco:CharacterString');
			if ($postalCode[0] != '') {
				array_push($temp1, array('Email Address', array((string) $postalCode[0])));
			}
			$country = $address[0]->xpath('gmd:country/gco:CharacterString');
			if ($country[0] != '') {
				array_push($temp1, array('Email Address', array((string) $countryy[0])));
			}
			$emailAddress = $address[0]->xpath('gmd:electronicMailAddress/gco:CharacterString');
			if ($emailAddress[0] != '') {
				if(count($emailAddress)>1){
					$o = "";
					foreach($emailAddress as $v){
						$o .= $v.";";
					}
					$o = rtrim($o, ";");
					array_push($temp1, array('Email Address', array((string) $o)));
				}else{
					array_push($temp1, array('Email Address', array((string) $emailAddress[0])));
				}
			}
			if(count($temp1)>0){
				array_push($contactInfo_temp, array('Address', $temp1));
			}
		
		}
		$temp1 = array();
	}
	
	
	array_push($temp, array('Contact Info', $contactInfo_temp));
	//var_dump($contactInfo_temp);
	
	array_push($contacts2, $temp);
	//var_dump($contacts);
}
array_push($identificationInfo, array('Points of Contact', $contacts2));
$temp = '';

$resMaintenance = array();
$t = '/gmd:resourceMaintenance/gmd:MD_MaintenanceInformation/gmd:maintenanceAndUpdateFrequency/gmd:MD_MaintenanceFrequencyCode';
$resMaintenance_temp = $gmd->xpath($idInf.$t);
if($resMaintenance_temp != ''){
	$resMaintenance_temp = $resMaintenance_temp[0]->attributes()->codeListValue;
	$resMaintenance = array('Resource Maintenance', array(getResMaintenance((string) $resMaintenance_temp)));
}
array_push($identificationInfo, $resMaintenance);


$keywords = array();
$t = '/gmd:descriptiveKeywords/gmd:MD_Keywords/gmd:keyword';
$keywords_temp = $gmd->xpath($idInf.$t);
$t1 = array();
if($keywords_temp[0] != ''){
	foreach ($keywords_temp as $k => $v){
		array_push($keywords,(string) $v->children($ns['gco']));
	}
}
array_push($identificationInfo, array('Keywords', $keywords));

$resConstraints = array();
$t = '/gmd:resourceConstraints';
$lConstraints = $gmd->xpath($idInf.$t.'/gmd:MD_LegalConstraints/gmd:accessConstraints/gmd:MD_RestrictionCode');
if($lConstraints[0] != NULL){
	$lConstraints = $lConstraints[0]->attributes()->codeListValue;
	array_push($resConstraints, array('Access Constraints', array(getAccConstraints((string) $lConstraints))));
}
$oConstraints = $gmd->xpath($idInf.$t.'/gmd:MD_LegalConstraints/gmd:otherConstraints/gco:CharacterString');
if($oConstraints[0] != NULL){
	$oConstraints = $oConstraints[0];
	array_push($resConstraints, array('Other Constraints', array(getOthConstraints((string) $oConstraints))));
}
array_push($identificationInfo, array('Resource Constraints', $resConstraints));

$spatialRepresentation = array();
$t = '/gmd:spatialRepresentationType/gmd:MD_SpatialRepresentationTypeCode';
$sp_temp = $gmd->xpath($idInf.$t);
if($sp_temp[0] != NULL){	
	$t1 = array();
	foreach($sp_temp as $k => $v){
		$v = $v[0]->attributes()->codeListValue;
		array_push($t1, array(getSpRepresentation((string) $v))); 
	}
	$spatialRepresentation = array('Spatial Representation', $t1);
}
array_push($identificationInfo, $spatialRepresentation);

$spatialResolution = array();
$t = $gmd->xpath($idInf.'/gmd:spatialResolution/gmd:MD_Resolution/gmd:equivalentScale/gmd:MD_RepresentativeFraction/gmd:denominator/gco:Integer');
if($t[0] != NULL){
	$spatialResolution = array('Spatial Resolution', array((string) $t[0]));
}
array_push($identificationInfo, $spatialResolution);

$topicCategory = array();
$t = $gmd->xpath($idInf.'/gmd:topicCategory/gmd:MD_TopicCategoryCode');
if($t[0] != NULL){
	$topicCategory = array('Topic Category', array(getTopicCategory((string) $t[0])));
}
array_push($identificationInfo, $topicCategory);

$temporalElement = array();
$timePeriod = array();
$t = '/gmd:extent/gmd:EX_Extent/gmd:temporalElement/gmd:EX_TemporalExtent/gmd:extent/gml:TimePeriod';
if($t[0] != NULL){
	$t1 = $gmd->xpath($idInf.$t.'/gml:beginPosition');
	array_push($timePeriod, array('Begin', (string) $t1[0]));
	$t1 = $gmd->xpath($idInf.$t.'/gml:endPosition');
	array_push($timePeriod, array('Ebd', (string) $t1[0]));
}
$temporalElement = array('Temporal Element', $timePeriod);
array_push($identificationInfo, $temporalElement);

$boundingBox = array();
$bb = array();
$t = '/gmd:extent/gmd:EX_Extent/gmd:geographicElement/gmd:EX_GeographicBoundingBox';
$tw = '/gmd:westBoundLongitude/gco:Decimal';
$t1 = $gmd->xpath($idInf.$t.$tw);
array_push($bb, array('West',array((string) $t1[0])));
$te = '/gmd:eastBoundLongitude/gco:Decimal';
$t1 = $gmd->xpath($idInf.$t.$te);
array_push($bb, array('East',array((string) $t1[0])));
$ts = '/gmd:southBoundLatitude/gco:Decimal';
$t1 = $gmd->xpath($idInf.$t.$ts);
array_push($bb, array('South',array((string) $t1[0])));
$tn = '/gmd:northBoundLatitude/gco:Decimal';
$t1 = $gmd->xpath($idInf.$t.$tn);
array_push($bb, array('North',array((string) $t1[0])));

$boundingBox = array('Geographical Bounds', $bb);
array_push($identificationInfo, $boundingBox);


$supplementalInfo = array();
$t = '/gmd:supplementalInformation/gco:CharacterString';
$t = $gmd->xpath($idInf.$t);
if((string) $t[0] != ''){
	array_push($supplementalInfo,  (string) $t[0]);
}
array_push($identificationInfo, array('Supplemental Information', $supplementalInfo));
$temp = '';
$t = '';

$contentInfo = array();
$ct = $md.'/gmd:contentInfo/gmd:MD_CoverageDescription';
$t = $gmd->xpath($ct.'/gmd:attributeDescription/gco:RecordType');
$temp = array();
if($t[0] != ''){
	array_push($contentInfo, array('attribute', array((string) $t[0])));
}
$t = $gmd->xpath($ct.'/gmd:contentType/gmd:MD_CoverageContentTypeCode');
if($t != NULL){
	$t = $t[0]->attributes()->codeListValue;
	array_push($contentInfo, array('Content Type', array(getContentType((string) $t))));
}
$temp = '';

$distributionInfo = array();
$di = $md.'/gmd:distributionInfo/gmd:MD_Distribution';
$distributionFormat = array();
$t = $gmd->xpath($di.'/gmd:distributionFormat/gmd:MD_Format/gmd:name/gco:CharacterString');
if($t[0] != ''){
	array_push($distributionFormat, array('Data type', array((string) $t[0])));
}
$t = $gmd->xpath($di.'/gmd:distributionFormat/gmd:MD_Format/gmd:version/gco:CharacterString');
if($t[0] != ''){
	array_push($distributionFormat, array('Version', array((string) $t[0])));
}
array_push($distributionInfo, array('Distribution Format', $distributionFormat));

$to = $di.'/gmd:transferOptions/gmd:MD_DigitalTransferOptions/gmd:onLine/gmd:CI_OnlineResource';
$to_temp = array();
$t = $gmd->xpath($to);
foreach($t as $k => $v){
	$transfer = array();
	
	$t1 = $v->xpath('gmd:name/gco:CharacterString');
	if($t1[0] != ''){
		array_push($transfer, array('Name', array((string) $t1[0])));
	} else {
		$t1 = $v->xpath('gmd:name');
		if((string) $t1[0]->children($ns['gmx']) != '') {
			$t1 = $t1[0]->children($ns['gmx']);
			array_push($transfer, array('Name', array((string) $t1)));
		}
	}
	$t1 = $v->xpath('gmd:description/gco:CharacterString');
	if($t1[0] != ''){
		array_push($transfer, array('Description', array((string) $t1[0])));
	}
	$t1 = $v->xpath('gmd:linkage/gmd:URL');
	if($t1[0] != ''){
		array_push($transfer, array('URL', array((string) $t1[0])));
	}
	$t1 = $v->xpath('gmd:protocol/gco:CharacterString');
	if($t1[0] != ''){
		array_push($transfer, array('Protocol', array((string) $t1[0])));
	}
	$t1 = $v->xpath('gmd:name/gmx:MimeType');
	if($t1[0] != ''){
		array_push($transfer, array('MIME Type', array((string) $t1[0])));
	}
	
	array_push($to_temp, $transfer);
}
array_push($distributionInfo, array("Transfer Options", $to_temp));
$temp = '';
$t = '';

$dataQualityInfo = array();
$t = $gmd->xpath($md.'/gmd:dataQualityInfo/gmd:DQ_DataQuality/gmd:scope/gmd:DQ_Scope');
if($t[0] != ''){
	array_push($dataQualityInfo, array('Data Quality Info', array((string) $t[0])));
}





function getRoles($str) {
	$arr = array(
		'author' => 'Author', 
		'custodian' => 'Custodian', 
		'distributor' => 'Distributor',
		'originator' => 'Originator',
		'owner' => 'Owner',
		'pointOfContact' => 'Point of contact',
		'principalInvestigator' => 'Principal investigator',
		'processor' => 'Processor',
		'publisher' => 'Publisher',
		'resourceProvider' => 'Resource provider',
		'user' => 'User'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getPresentationForm($str) {
	$arr = array(
		'mapDigital' => 'Digital Map',
		'imageDigital' => 'Digital Image',
		'tableDigital' => 'Digital Table'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getStatus($str) {
$arr = array(
		'completed' => 'Completed'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getResMaintenance($str){
	$arr = array(
		'asNeeded' => 'as needed'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getAccConstraints($str){
	$arr = array(
	'copyright' => 'Copyright'
	);
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getOthConstraints($str){
	$arr = array(
		'geossDataCore' => 'GEOSS Data Core'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getSpRepresentation($str){
	$arr = array(
		'grid' => 'Grid',
		'stereoModel' => 'Stereo model',
		'tin' => 'TIN',
		'textTable' => 'Text, table',
		'vector' => 'Vector',
		'video' => 'Video'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getLanguage($str){
	$arr = array(
		'eng' => 'English'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getTopicCategory($str){
	$arr = array(
		'environment' => 'Environment',
		'climate' => 'Climate',
		'boundaries' => 'Boundaries'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}
function getContentType
($str){
	$arr = array(
		'environment' => 'Environment',
		'physicalMeasurement' => 'Physical Measurement',
		'imagem' => 'Image'
	 );
	 $res = $arr[$str];
	 if($res == null) {$res = $str;}
	 return $res;
}

$metadata = array("Metadata",array());
array_push($metadata[1], array('File Identifier', $fileIdentifier));
array_push($metadata[1], array('Character Set', $characterSet));
array_push($metadata[1], array('Contacts', $contacts));
array_push($metadata[1], array('Date Stamp', $dateStamp));
array_push($metadata[1], array('Reference System', $referenceSystemInfo));
array_push($metadata[1], array('Identification Info', $identificationInfo));
array_push($metadata[1], array('Content Info', $contentInfo));
array_push($metadata[1], array('Distribution Info', $distributionInfo));
array_push($metadata[1], array('Data Quality Info', $dataQualityInfo));

function treeView($arr){
	foreach($arr as $k => $v) {
		if(gettype($v) == 'string'){
			echo $v.'<br/>';
		} else {
			foreach($v as $k1 => $v1) {
				if(gettype($v1) == 'string'){
					echo '|-- '.$v1.'<br/>';
				} else {
					foreach($v1 as $k2 => $v2) {
						if(gettype($v2) == 'string'){
							echo '|---- '.$v2.'<br/>';
						} else {
							foreach($v2 as $k3 => $v3) {
								if(gettype($v3) == 'string'){
									echo '|------ '.$v3.'<br/>';
								} else {
									foreach($v3 as $k4 => $v4) {
										if(gettype($v4) == 'string'){
											echo '|-------- '.$v4.'<br/>';
										} else {
											foreach($v4 as $k5 => $v5) {
												if(gettype($v5) == 'string'){
													echo '|---------- '.$v5.'<br/>';
												} else {
													foreach($v5 as $k6 => $v6) {
														if(gettype($v6) == 'string'){
															echo '|------------ '.$v6.'<br/>';
														} else {
															foreach($v6 as $k7 => $v7) {
																if(gettype($v7) == 'string'){
																	echo '|-------------- '.$v7.'<br/>';
																} else {
																	foreach($v7 as $k8 => $v8) {
																		if(gettype($v8) == 'string'){
																			echo '|---------------- '.$v8.'<br/>';
																		} else {
																			foreach($v8 as $k9 => $v9) {
																				if(gettype($v9) == 'string'){
																					echo '|------------------ '.$v9.'<br/>';
																				} else {
																					foreach($v9 as $k10 => $v10) {
																						if(gettype($v10) == 'string'){
																							echo '|-------------------- '.$v10.'<br/>';
																						} else {
																							foreach($v10 as $k11 => $v11) {
																								if(gettype($v11) == 'string'){
																									echo '|---------------------- '.$v11.'<br/>';
																								} else {
																									foreach($v11 as $k12 => $v12) {
																										if(gettype($v12) == 'string'){
																											echo '|------------------------ '.$v12.'<br/>';
																										} else {
																											
																										}
																									}
																								}
																							}
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}	
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

		}
	}
}
//treeView($metadata);
?>
<script tupe="text/javascript">
var metadata = <?php echo json_encode($metadata); ?>;
//console.log(metadata);
</script>


<!-- TEMPLATE -->
<ul class='xml'>
	<li><div class='fset'><span class='lgend'>Identification Info</span>
		<ul>
			<li class='divValue'><span class='section'>Dataset</span>
				<ul>
					<?php foreach($metadata[1][5][1][0][1] as $value){ ?>
					<?php if($value[0] != 'Publication Date'){ ?>
					<li>
						<span class='title'><?php echo $value[0]; ?></span>
						<span class='value'><?php echo $value[1][0]; ?></span>
					</li>
					<?php } ?>
					<?php } ?>
					<?php if(count($metadata[1][5][1][1][1])>0) { //Abstract ?>
						
					<li>
						<span class='title'><?php echo $metadata[1][5][1][1][0]; ?></span>
						<span class='value'><?php echo $metadata[1][5][1][1][1][0]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][10][1])>0) { //Temporal Info ?>
						
					<li>
						<span class='title'>Time Period</span>
						<span class='value'><?php echo $metadata[1][5][1][10][1][0][1]; ?> - <?php echo $metadata[1][5][1][10][1][1][1]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][11][1])>0){ //Geographical Bounds ?>
					<li>
						<span class='title'><?php echo $metadata[1][5][1][11][0]; ?></span>
						<span class='value'><?php echo $metadata[1][5][1][11][1][0][0].':'.$metadata[1][5][1][11][1][0][1][0].' '.$metadata[1][5][1][11][1][1][0].':'.$metadata[1][5][1][11][1][1][1][0].' '.$metadata[1][5][1][11][1][2][0].':'.$metadata[1][5][1][11][1][2][1][0].' '.$metadata[1][5][1][11][1][3][0].':'.$metadata[1][5][1][11][1][3][1][0]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][12][1])>0) { //Supplemental Info ?>
						
					<li>
						<span class='title'><?php echo $metadata[1][5][1][12][0]; ?></span>
						<span class='value'><?php echo $metadata[1][5][1][12][1][0]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][4][1])>0) { //Reference System ?>
					<li>
						<span class='title'><?php echo $metadata[1][4][0]; ?></span>
						<span class='value'><?php echo $metadata[1][4][1][0]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][5][1])>0) { //Keywords ?>
					<li>
						<span class='title'><?php echo $metadata[1][5][1][5][0]; ?></span>
						<span class='value'><?php foreach($metadata[1][5][1][5][1] as $v){echo $v.'; ';} ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][2][1])>0) { //Status ?>
					<li>
						<span class='title'><?php echo $metadata[1][5][1][2][0]; ?></span>
						<span class='value'><?php echo $metadata[1][5][1][2][1][0]; ?></span>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][3][1])>0) { //Points of Contact ?>
						<li><span class='section'><?php echo $metadata[1][5][1][3][0]; ?></span>
					
						<ul>
							<?php foreach($metadata[1][5][1][3][1] as $contact){ ?>
							<li>
								<span class='title subsection'><?php echo $contact[0][0]; ?></span>
								<span class='subValue'><?php echo $contact[0][1][0]; ?></span>
							</li>
							<?php for($i=1;$i<count($contact);$i++){ ?>
								<?php if($contact[$i][0] != 'Contact Info') { ?>
									<li>
										<span class='title subsectionContact'><?php echo $contact[$i][0]; ?></span>
										<span class='subValue'><?php echo $contact[$i][1][0]; ?></span>
									</li>
									<?php } else { ?>
									<?php foreach($contact[$i][1] as $contactInfo){ ?>
										<?php foreach($contactInfo[1] as $contactInfoMean){ ?>
											<li>
												<span class='title subsectionContact'><?php echo $contactInfoMean[0]; ?></span>
												<?php if($contactInfoMean[0] != 'Email Address') { ?>
												<span class='subValue'><?php echo $contactInfoMean[1][0]; ?></span>
												<?php } else { ?>
												<span class='subValue email'>
												<?php if(strstr($contactInfoMean[1][0], ";") != ""){
													$emails = explode(";", $contactInfoMean[1][0]);
													for($u=0;$u<count($emails);$u++){
														$temp = "";
														if($u < count($emails)-1){$temp = "; ";}
														echo utf8_encode(str_rot13('<a href="mailto://'.$emails[$u].'">'.$emails[$u].'</a>'.$temp));
													}
												} else {
													echo utf8_encode(str_rot13('<a href="mailto://'.$contactInfoMean[1][0].'">'.$contactInfoMean[1][0].'</a>'));
												} ?>
												
												</span>
												<?php } ?>
											</li>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
					<?php if(count($metadata[1][5][1][6][1])>0) { //Resource Constraints ?>
					<li>
						<span class='section'><?php echo $metadata[1][5][1][6][0]; ?></span>
						<ul>
							<?php foreach($metadata[1][5][1][6][1] as $resConstraints){ ?>
							<li>
								<span class='title'><?php echo $resConstraints[0]; ?></span>
								<span class='value'><?php echo $resConstraints[1][0]; ?></span>
							</li>
							<?php } ?>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</li>
	<li><div class='fset'><span class='lgend'>Distribution Info</span>
		<ul>
			<li>
				<span class='title'><?php echo $metadata[1][7][1][0][1][0][0]; ?></span>
				<span class='value'><?php echo $metadata[1][7][1][0][1][0][1][0]; ?></span>
			</li>
			<li>
				<span class='section'><?php echo $metadata[1][7][1][1][0]; ?></span>
				<ul>
					<?php foreach($metadata[1][7][1][1][1] as $files){ ?>
					<li>
						<ul>
						<?php foreach($files as $details){ ?>
						<?php
							$cl1 = "title";
							$cl2 = "value";
							if (trim($details[0]) != "Name"){
								$cl1 = "title subsectionContact";
								$cl2 = "subValue";
							}
						?>
							<li>
								<span class='<?php echo $cl1; ?>'><?php echo $details[0]; ?></span>
								<?php if (trim(strtolower($details[0])) == trim(strtolower('URL'))){ ?>
									<span class='subValue'><a href="<?php echo $details[1][0]; ?>"><?php echo $details[1][0]; ?></a></span>
								<?php } else { ?>
									<span class='<?php echo $cl2; ?>'><?php echo $details[1][0]; ?></span>
								<? } ?>
							</li>
						<?php } ?>
							<li><div style="height:10px">&nbsp;</div></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</li>
</ul>