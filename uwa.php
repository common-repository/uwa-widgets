<?php
/*
Plugin Name: UWA Widgets
Plugin URI: http://h6e.net/wordpress/plugins/uwa
Description: Let users add UWA widgets on their Wordpress dashboard
Version: 0.1.2
Author: h6e.net
Author URI: http://h6e.net/
*/

/**
 * Copyright (c) 2009 h6e (http://h6e.net/).
 *
 * This file is part of UWA Wordpress Plugin (http://h6e.net/wordpress/plugins/uwa)
 *
 * UWA Wordpress Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * UWA Wordpress Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with UWA Wordpress Plugin. If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'uwa-functions.php';

add_action('wp_dashboard_setup', 'uwa_init_dashboard'); 

function uwa_add_manage_page()
{
	add_options_page('Manage Widgets', 'UWA Widgets', 9, dirname(__FILE__) . '/uwa-manage.php');
}

add_action('admin_menu', 'uwa_add_manage_page');

function uwa_js()
{
	$plugin_url = get_option( 'siteurl' ) . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) ;
	?>
<script type="text/javascript" src="<?php echo $plugin_url ?>/IFrameMessaging.js"></script>
<script type="text/javascript" src="<?php echo $plugin_url ?>/uwa-container.js"></script>
<script type="text/javascript">
NV_MODULES = 'nvmodules.netvibes.com';
WP_ADMIN_URL = '<?php echo admin_url() ?>';
UWA.MessageHandler = new UWA.iFrameMessaging;
UWA.MessageHandler.init({
	'eventHandler': uwa_msg_handler,
	'trustedOrigin': NV_MODULES
});
</script>
	<?php
}

add_action('admin_head', 'uwa_js');
