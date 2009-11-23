<?php
/**
 * 
 * Get the users created on the timeline separated by...
 * @return array
 */
function users_time_site_data() {
	return get_entities_created_on_the_time('user', '', 'day');
}

/**
 * 
 * This function get the users that send most messages order desc.
 * @return array
 */
function users_messages_site_data() {
	return statistics_get_entity_users_messages();
}

function users_objects_quantity_site_data() {
	return get_objects_quantity_by_entity('user');
}

function groups_objects_quantity_site_data() {
	return get_objects_quantity_by_entity('group');
}

/**
 * Get Entities created on the time
 * @return Array
 */
function get_entities_created_on_the_time($type, $subtype, $separared_by='day', $timelower = null, $timelower = null){
    $count_entities = get_entities($type, $subtype, 0, 'time_created', 0, 0, true, null, null, $timelower, $timeupper);
    $data = array();
    $old_date = "";
    for ($i=0; $i<$count_entities; $i+=100) {
        $entities = get_entities($type, $subtype, 0,'time_created', 100, $i, false, null, null, $timelower, $timeupper);
        if (!empty($entities)) { 
            foreach($entities as $entity){
            	switch($separared_by) {
            		case 'year':
            			$format_time = 'y';
            			break;
            		case 'month':
            			$format_time = 'ym';
            			break;
            		case 'week':
            			$format_time = 'yW';
            			break;	
            		case 'day':
            			$format_time = 'ymd';
            			break;
            		case 'hour':
            			$format_time = 'ymdH';
            			break;
            		case 'minute':
            			$format_time = 'ymdHi';
            			break;	
            		default:
            			$format_time = 'ymd';
				}
				$time = date($format_time, $entity->time_created);
            	if ($time != $old_date) {
                    $data[$time] = 1;
                    $old_date = $time;
                } else {
                	$data[$time]++;
                }
            }
        }
    }
    return $data;
}

function users_language_site_data(){
    global $CONFIG;

    $users_by_lang = get_number_users_by_lang();
    /*
     * FIXME Ocurre que a veces trae todas las traducciones y otras veces trae solo la de ingles. 
     * Habria que ver si ocurre lo mismo en la 1.6
    */
     /* foreach ($CONFIG->translations as $name => $v) {
		$title = elgg_echo($name, $name);
		elgg_dump($title);
        $value = 0;
		if(array_key_exists($name,$users_by_lang)){
          $value=$users_by_lang[$name];
        }
		$data[$title] = $value;
	}*/

    return $users_by_lang;
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
	
	if (!empty($data)){
	    foreach ($data as $lang) {
	        $key = $lang->language;
	        if(trim($key)==""){
	            $key="en";
	        }
	        $result[$key] += $lang->total;
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
			
			if ($entity instanceof ElggUser) {
				$title = $entity->name;
			} else {
				$title = $entity->title;
			}
			
			$data = new StdClass();
			$data->guid = $entity->guid;
			$data->name = $title; 
			
			if (!array_key_exists($objects_count, $objects_per_entity)) {
				$objects_per_entity[$objects_count] = array($data);
			}
			else {
				$objects_per_entity[$objects_count][] = $data;
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
	foreach($objects_per_entity as $object_count => $entities){
		foreach($entities as $entity){
			if ($count == $limit){
				break;
			}
			$count ++;
			$key = $entity->guid;
			$entity->amount = $object_count;
			$tmp[] = $entity;
			$object_count;
		}
		if ($count == $limit){
			break;
		}
	}
	return $tmp;	
}

/**
 * 
 * Esta funcion obtiene los usuarios que mas mensajes enviaron ordenados descendentemente.
 * This function get the users that send most messages order desc.
 * 
 * @param $limit int
 * @return ElggUsers entity
 * @author. Carlos Tealdi & Diego Gallardo.
 */
function statistics_get_entity_users_messages($limit = null) {
	global $CONFIG;

	$limit = get_entities('user', '', '', '', 0, 0, true);
	$users = get_entities('user', '', '', '', $limit);
	
	$user_message_array = array();
	
	//Now we just acumulate the users ans count the messages they sent.
	foreach($users as $user) {
		$message_count = get_entities_from_metadata('fromId',$user->getGUID(),'object','messages', $user->getGUID(), 0, 0, '', 0, true);
		
		if ($message_count) {
			$user_message_array[$user->getGUID()] = $message_count;
		}
	}
	
	//Now we sort the users by they count messages.
	arsort($user_message_array);
	
	//Slice the array if we have a limit.
	if (!is_null($limit) && count($user_message_array) > $limit) {
		$user_message_array = array_slice($user_message_array, 0, $limit, true);
	}
	
	//Get the entities to return it.
	$data = array();
	foreach ($user_message_array as $user_guid => $amount) {
		$entity = get_entity($user_guid);
		$data_item = new StdClass();
		$data_item->guid = $user_guid;
		$data_item->name = $entity->name;
		$data_item->amount = $amount;
		$data[] = $data_item;
	} 
			
	return $data;
}