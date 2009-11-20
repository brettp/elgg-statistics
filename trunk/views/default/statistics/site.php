<?php

if(isset($vars["data"])){
    foreach($vars["data"] as $module=>$data){
        $body.="<h1>".elgg_echo("elgg_statistics:$module")."</h1>";
        $body.=print_r($data,true);
    }
}

echo elgg_view("page_elements/contentwrapper",array('body'=>$body));
