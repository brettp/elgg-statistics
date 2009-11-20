<?php

function elgg_statistics_init(){

    register_page_handler("statistics","elgg_statistics_pagehandler");
}

function elgg_statistics_pagesetup(){
    global $CONFIG;
    if(get_context()== "admin" && isadminloggedin()){
        add_submenu_item(elgg_echo("elgg_statistics:statistics"),"pg/statistics/");
    }

}

function elgg_statistics_pagehandler($page){

    switch($page[0]){
        default:
            elgg_statistics_wrapper();
    }

}

function elgg_statistics_wrapper($page){
    require dirname(__FILE__)."/index.php";

}
register_elgg_event_handler('init','system','elgg_statistics_init');
register_elgg_event_handler('pagesetup','system','elgg_statistics_pagesetup');
