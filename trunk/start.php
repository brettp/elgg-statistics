<?php
/**
 *
 */

/**
 * Start plugin
 *
 * @return null
 */
function elgg_statistics_init(){
	register_page_handler("statistics","elgg_statistics_pagehandler");
}

/**
 * Add tools menu for admins
 *
 * @return null
 */
function elgg_statistics_pagesetup(){
	global $CONFIG;
	if(get_context()== "admin" && isadminloggedin()){
		add_submenu_item(elgg_echo("elgg_statistics:statistics"),"pg/statistics/");
	}

}

/**
 * Handle pages for stats
 *
 * @param $page
 * @return str
 */
function elgg_statistics_pagehandler($page){

	switch($page[0]){
		default:
			elgg_statistics_admin_page();
	}
}

/**
 * Serve up the admin page.
 * @return str
 */
function elgg_statistics_admin_page(){

}

register_elgg_event_handler('init', 'system', 'elgg_statistics_init');
register_elgg_event_handler('pagesetup', 'system', 'elgg_statistics_pagesetup');