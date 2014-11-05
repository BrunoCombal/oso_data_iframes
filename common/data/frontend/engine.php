<?php
if(user_is_logged_in()){
function cmp($a, $b) {
    return strcmp($a->title, $b->title);
}

$nodes = node_load_multiple(array(), array('type' => 'open_ocean_indicator_description'));
//ksort($nodes);
usort($nodes, "cmp");
$categoriesList = array();
$categoriesList_temp = array();
foreach ($nodes as $node) { 
	if ($node->status == 1) {
		$categories = $node->field_category_oo['und'][0]['value'];
		$nodeCats = explode(';',$categories);
		foreach($nodeCats as $cat){
			if(!in_array(trim(strtolower(str_replace(' ','',$cat))), $categoriesList_temp)){
				array_push($categoriesList_temp, trim(strtolower(str_replace(' ','',$cat))));
				array_push($categoriesList, $cat);
				//var_dump($node->title);
			}
		}
	}
}
//var_dump($categoriesList_temp);
//echo "<br/>";
//var_dump$categoriesList);
array_multisort($categoriesList, $categoriesList_temp);

$tempOrder = array();
foreach ($nodes as $node) {
	$nodeR = array();
	$nodeR['title'] = $node->title;
	$nodeR['nid'] = $node->nid;
	$nodeR['status'] = $node->status;
	$nodeR['categories'] = $node->field_category_oo['und'][0]['value'];
	if(isset($node->field_xml_metadata_path['und'])){
		$nodeR['xml'] = urlencode(utf8_encode($node->field_xml_metadata_path['und'][0]['value']));
	}
	if(isset($node->field_view['und'])){
		$nodeR['view'] = $node->field_view['und'][0]['value'];
	}
	if(isset($node->field_google_earth['und'])){
		$nodeR['google_earth'] = $node->field_google_earth['und'][0]['value'];
	}
	if(isset($node->field_package_path['und'])){
		$nodeR['package'] = $node->field_package_path['und'][0]['value'];
	}
	if(isset($node->field_definition_oo['und'])){
		$nodeR['summary'] = $node->field_definition_oo['und'][0]['value'];
	}
	$field = field_view_field('node', $node, 'field_category_oo', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['category'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_definition_oo', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['definition'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_relevance', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['relevance'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_methodology', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['methodology'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_data_sources_oo', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['data_source'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_partners', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['partners'] = render($field);
	}

	
	if(isset($node->field_data_group['und'])){
		$group = $node->field_data_group['und'][0]['value'];
		$nodeR['group'] = $group;
		array_push($tempOrder, array($group, $nodeR));
	} else {
		array_push($tempOrder, array($node->title, $nodeR));
	}
}
sort($tempOrder);

$arrayToSave = array($categoriesList, $categoriesList_temp, $tempOrder);
if($arrayToSave){
	$oo_file = '/data/iframes/common/data/frontend/oo.json';
	$oo_file_temp = '/data/iframes/common/data/frontend/oo.json'.time();
	if(file_exists($oo_file_temp)){
		unlink($oo_file_temp);
	}
	fopen($oo_file_temp, 'w');
	file_put_contents($oo_file_temp, json_encode($arrayToSave));
	rename($oo_file_temp, $oo_file);
}
?>


<?php 
$nodes = node_load_multiple(array(), array('type' => 'lmes_indicator_description'));
//ksort($nodes);
usort($nodes, "cmp");
$categoriesList = array();
$categoriesList_temp = array();
foreach ($nodes as $node) { 
	if ($node->status == 1) {
		if($node->field_category){
			$categories = $node->field_category['und'][0]['value'];
			$nodeCats = explode(';',$categories);
			foreach($nodeCats as $cat){
				if(!in_array(trim(strtolower(str_replace(' ','',$cat))), $categoriesList_temp)){
					array_push($categoriesList_temp, trim(strtolower(str_replace(' ','',$cat))));
					array_push($categoriesList, $cat);
					//var_dump($node->title);
				}
			}
		}
	}
}
array_multisort($categoriesList, $categoriesList_temp);
?>
<span id="lmesTotal_temp" style="display:none"><?php echo count($nodes); ?></span>
<?php
$tempOrder = array();
foreach ($nodes as $node) {
	$nodeR = array();
	$nodeR['title'] = $node->title;
	$nodeR['nid'] = $node->nid;
	$nodeR['status'] = $node->status;
	if(isset($node->field_category['und'])){
		$nodeR['categories'] = $node->field_category['und'][0]['value'];
	}
	if(isset($node->field_xml_metadata_path['und'])){
		$nodeR['xml'] = urlencode(utf8_encode($node->field_xml_metadata_path['und'][0]['value']));
	}
	if(isset($node->field_view['und'])){
		$nodeR['view'] = $node->field_view['und'][0]['value'];
	}
	if(isset($node->field_google_earth['und'])){
		$nodeR['google_earth'] = $node->field_google_earth['und'][0]['value'];
	}
	if(isset($node->field_package_path['und'])){
		$nodeR['package'] = $node->field_package_path['und'][0]['value'];
	}
	if(isset($node->field_definition['und'])){
		$nodeR['summary'] = $node->field_definition['und'][0]['value'];
	}
	$field = field_view_field('node', $node, 'field_category', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['module'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_category2', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['category'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_definition', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['definition'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_unit', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['unit'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_rationale_for_inclusion', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['rationale'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_interlinkages_with_other_t', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['interlink'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_measurement_methods_and_ca', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['measurements'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_scale', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['scale'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_data_sources', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['data_source'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_agencies_contacts', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['agencies'] = render($field);
	}
	$field = field_view_field('node', $node, 'field_references', array('label' => 'hidden')); if(!empty($field)){
		$nodeR['references'] = render($field);
	}
	
	
	
	if(isset($node->field_data_group['und'])){
		$group = $node->field_data_group['und'][0]['value'];
		$nodeR['group'] = $group;
		array_push($tempOrder, array($group, $nodeR));
	} else {
		array_push($tempOrder, array($node->title, $nodeR));
	}
}
sort($tempOrder);

$arrayToSave = array($categoriesList, $categoriesList_temp, $tempOrder);
if($arrayToSave){
	$lmes_file = '/data/iframes/common/data/frontend/lmes.json';
	$lmes_file_temp = '/data/iframes/common/data/frontend/lmes.json'.time();
	if(file_exists($lmes_file_temp)){
		unlink($lmes_file_temp);
	}
	fopen($lmes_file_temp, 'w');
	file_put_contents($lmes_file_temp, json_encode($arrayToSave));
	rename($lmes_file_temp, $lmes_file);
}
?>
<div id="messages">
<div class="messages status">
<h2 class="element-invisible">Status message</h2>
Cache for <em class="placeholder">Data</em> has been regenerated</div>
</div>
<?php
} else { //user NOT logged in
?>
<div id="messages">
<div class="messages error">
<h2 class="element-invisible">Error message</h2>
<em class="placeholder">Notice</em>: This script can only be run with administrator rights.</div>
</div>
<?php
}
?>