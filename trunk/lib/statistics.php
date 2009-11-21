<?php

function users_time_site_data(){
    $count_users = get_entities('user','',0,'time_created',100,0,true);

    $data = array();
    $old_date = "";
    for($i=0;$i<$count_users;$i+=100){
        $entities = get_entities('user','',0,'time_created',100,$i);
        if(!empty($entities)){
            foreach($entities as $entity){
                if($entity->time_created != $old_date){
                    $data[$entity->time_created]=1;
                    $old_date = $entity->time_created;
                }
                $data[$entity->time_created]++;
            }
        }
    }
    return $data;
}

function users_language_site_data(){
    global $CONFIG;

    $users_by_lang = get_number_users_by_lang();
	foreach ($CONFIG->translations as $name => $v) {
		$title = elgg_echo($name, $name);
        $value = 0;
		if(array_key_exists($name,$users_by_lang)){
          $value=$users_by_lang[$name];
        }
		$data[$title] = $value;
	}

    return $data;
}

function get_number_users_by_lang($show_deactivated = false) {
	global $CONFIG;

	$access = "";

	if (!$show_deactivated) {
		$access = "and " . get_access_sql_suffix();
	}

	$query= "SELECT u.language,count(*) as total FROM {$CONFIG->dbprefix}entities e, {$CONFIG->dbprefix}users_entity u ";
	$query.="WHERE type='user' AND e.guid=u.guid ";
	$query.=$access;
	$query.=" GROUP BY language";

	$result = array();
	$data = get_data($query);
	if(!empty($data)){
	    foreach($data as $lang){
	        $key = $lang->language;
	        if(trim($key)==""){
	            $key="en";
	        }
	        $result[$key]+=$lang->total;
	    }
	}
	return $result;
}

function get_objects_quantity_by_entity($entity_type = 'user') {
	$entities_count = get_entities($entity_type, '', '', '', '', '', true);
	$entities = get_entities($entity_type, '', '', '', $entities_count);
	
	$objects_per_entity = array();
	if ($entities_count) {
		foreach($entities as $entity){
			$objects_count = count_user_objects($entity->guid);
			
			if (!array_key_exists($objects_count, $objects_per_entity)) {
				$objects_per_entity[$objects_count] = array($entity->name);
			}
			else{
				if ($entity instanceof ElggUser) {
					$title = $entity->name;
				} else {
					$title = $entity->title;
				}
				$objects_per_entity[$objects_count][] = $title;
			}
		}
	}
	
	//order by the quantity of objects
	ksort($objects_per_entity);
	
	//we put the latest first
	$objects_per_entity = array_reverse($objects_per_entity, true);
	
	$tmp = array();
	
	$limit = 10;
	$count = 0;
	
	//we show just first $limit
	foreach($objects_per_entity as $object_count => $entity){
		foreach($entity as $entity_guid){
			if ($count == $limit){
				break;
			}
			$count ++;
			$tmp[$entity_guid] = $object_count;
		}
		if ($count == $limit){
			break;
		}
	}	
	return $tmp;	
}

function get_objects_quantity_by_user() {
	return get_objects_quantity_by_entity('user');
}

function get_objects_quantity_by_group() {
	return get_objects_quantity_by_entity('group');
}