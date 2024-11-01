<?php

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

if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
	$widget_options = array();

$id = $_POST['id'];

if (!empty($widget_options[$id])) {

	switch ($_POST['action']) {
		case 'uwa-set-value':
			$name = $_POST['name'];
			$value = $_POST['value'];
			$widget_options[$id][$name] = $value;
			$update = true;
			break;
	}

}

if (isset($update) && $update === true) {
	update_option( 'dashboard_widget_options', $widget_options );
}
