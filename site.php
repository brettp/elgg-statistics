<?php
	/**
	 * Elgg site statistics.
	 * 
	 * @package Elgg Site Statistics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		admin_gatekeeper();
		
		$message_user_statictics = statistics_get_entity_users_messages();
		
		$count_user = count($message_user_statictics);
		
		$old_context = get_context();
		
		set_context('site_message_user_list');
		
		$area2 = elgg_view_entity_list($message_user_statictics, $count_user, 0, $count_user, false, false, true);
		
		set_context($old_context);
		
		
	// Display them in the page
        $body = elgg_view_layout("two_column_left_sidebar", '', $area2, '');
		
	// Display page
		page_draw('elgg_statistics:site',$body);
			
