<?php

	extend_view("metatags","statistics/flot");

//	$users_count = get_entities('user','','','','','',true);
//	$users = get_entities('user','','','',$users_count);
//
//	$objects_per_user = array();
//
//	if ($users_count){
//
//		foreach($users as $user){
//			$objects_count = count_user_objects($user->guid);
//
//			if (!array_key_exists($objects_count,$objects_per_user)){
//				$objects_per_user[$objects_count] = array($user->username);
//			}
//			else{
//				$objects_per_user[$objects_count][] = $user->username;
//			}
//		}
//	}
//
//	//order by the quantity of objects
//	ksort($objects_per_user);
//
//	//we put the latest first
//	$objects_per_user = array_reverse($objects_per_user,true);
//
//
//	$tmp = array();
//
//	$limit = 10;
//	$count = 0;
//
//
//	//we show just first $limit
//	foreach($objects_per_user as $object_count => $users){
//		foreach($users as $user_guid){
//			if ($count == $limit){
//				break;
//			}
//			$count ++;
//			$tmp[$user_guid] = $object_count;
//		}
//		if ($count == $limit){
//			break;
//		}
//	}

	
	$tmp = function_mia();

	$data = array();
	foreach($tmp as $user_guid => $objects_quantity){
		
		$data[$user_guid] = array('values'=>$objects_quantity);
		
	}
	


// Valores por defecto
$statistics_title = 'statistics:title';
echo elgg_view("output/estadisticas",array("internalname"=>"test1","data"=>$data,'statistics_title'=>$statistics_title));
echo "<br>";
//
//// Cambiando el maximo
//echo elgg_view("output/estadisticas",array("internalname"=>"test2","data"=>$data,"max"=>12));
//echo "<br>";
//
//// Cuando el valor máximo de los datos es mayor que el máximo se usa otro límite
//$data["part6"]=array('TODAY'=>3,'ME'=>12,"FRIENDS"=>4);
//echo elgg_view("output/estadisticas",array("internalname"=>"test3","data"=>$data));
//echo "<br>";
//
//// Cambiando las opciones de visualización
//echo elgg_view("output/estadisticas",array("internalname"=>"test4","data"=>$data,"background"=>"#c0c0c0","width"=>300,"height"=>150));
//echo "<br>"

?>