<?php

if (isset($vars["data"])) {
    foreach ($vars["data"] as $module => $data) {
    	$body.="<h1>".elgg_echo("elgg_statistics:$module")."</h1>";
        $body.=print_r($data,true);
        if ($module == 'users_language') {
        	$body .= elgg_view('output/pie', array(
        		'data' => $data,
        	)); 
        }
    }
}

echo elgg_view("page_elements/contentwrapper",array('body'=>$body));
