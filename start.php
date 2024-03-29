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

    // register unit tests for stats
    register_plugin_hook('unit_test', 'system', 'elgg_statistics_test');
}

/**
 * Runs unit tests for the stats object.
 */
function elgg_statistics_test($hook, $type, $value, $params) {
    global $CONFIG;
    $value[] = dirname(__FILE__) . '/tests.php';
    return $value;
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
            $option = (isset($page[1]))?$page[1]:"users";
            $content = elgg_statistics_site_page($option);
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
function elgg_statistics_site_page() {

	require dirname(__FILE__)."/lib/statistics.php";
	
    //FIXME Do this in a way that would be 'discoverable'
    $site_graphs = array(
    	"users_time",
    	"users_language", 
    	"users_messages",
    	"users_objects_quantity",
    	"groups_objects_quantity"
    );
    $data = array();
    foreach($site_graphs as $graph){
        $data_function = "{$graph}_site_data";
        if(function_exists($data_function)){
            $data[$graph]= $data_function();
        }
    }
    return elgg_view("statistics/site",array('option'=>$option,'data'=>$data));
}

/**
 * Serve up the user page
 * @return str
 */
function elgg_statistics_user_page(){
	require dirname(__FILE__)."/lib/statistics.php";
    return elgg_view("statistics/user");
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
