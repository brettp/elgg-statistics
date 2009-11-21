<?php

	extend_view("metatags","statistics/flot");
	
	$tmp = get_objects_quantity_by_group();
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