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