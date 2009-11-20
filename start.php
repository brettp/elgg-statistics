<?php
/**
 * Advanced Elgg Statistics
 *
 * @package ElggStatistics
 * @author ElggCampBA 2009
 * @link http://elgg.com/
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
	if (get_context() == 'admin' && isadminloggedin()){
		add_submenu_item(elgg_echo("elgg_statistics:statistics"), $CONFIG->site->url . "pg/statistics/");
	}
}

/**
 * Handle pages for stats
 *
 * @param $page
 * @return str
 */
function elgg_statistics_pagehandler($page){
	admin_gatekeeper();

	$old_context = get_context();
	set_context('admin');

	switch($page[0]){
		case 'user':
			$title = elgg_echo('elgg_statistics:user');
			$content = elgg_statistics_user_page();
			break;

		case 'group':
			$title = elgg_echo('elgg_statistics:group');
			$content = elgg_statistics_group_page();
			break;

		case 'site':
		default:
			$title = elgg_echo('elgg_statistics:site');
			$content = elgg_statistics_site_page();
	}

	set_context($old_context);

	$content_title = elgg_view_title($title);
	$body = elgg_view_layout('two_column_left_sidebar', '', '' . $content_title . $content);
	page_draw($title, $body);
}

/**
 * Serve up the admin page.
 * @return str
 */
function elgg_statistics_site_page(){
	echo elgg_view("statistics/site");
}

/**
 * Serve up the user page
 * @return str
 */
function elgg_statistics_user_page(){
	echo elgg_view("statistics/user");
}

/**
 * Serve up the group page
 * @return str
 */
function elgg_statisitics_group_page(){
	echo elgg_view("statistics/group");
}

register_elgg_event_handler('init', 'system', 'elgg_statistics_init');
register_elgg_event_handler('pagesetup', 'system', 'elgg_statistics_pagesetup');
