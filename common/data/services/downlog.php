<?php
if(isset($_GET['id'])){
	$retTotal = 0;
	$file = "/data/iframes/common/data/services/downcount.json";
	if(file_exists($file)){
		$array = json_decode(file_get_contents($file),true);
		$exists = false;
		for($i=0;$i<count($array);$i++){
			//var_dump($item['counter']);
			if($array[$i]['id'] == $_GET['id']){
				$array[$i]['counter'] = (int) $array[$i]['counter']+1;
				$array[$i]['counter'] = (string) $array[$i]['counter'];
				$resTotal = $array[$i]['counter'];
				$exists = true;
			}
		}
		if(!$exists){
			$obj = new StdClass();
			$obj->id = $_GET['id'];
			$obj->counter = "1";
			$array[] = $obj;
			$resTotal = "1";
		}
		file_put_contents($file, json_encode($array));
		
	} else {
		$obj = new StdClass();
		$obj->id = $_GET['id'];
		$obj->counter = "1";
		$array[] = $obj;
		fopen($file, 'w');
		$resTotal = "1";
		file_put_contents($file, json_encode($array));
	}
echo $resTotal;
	}
?>