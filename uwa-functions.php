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

function uwa_load_exposition()
{
	define('EXPOSITION', dirname(__FILE__) . '/Exposition/');
	set_include_path(get_include_path() . PATH_SEPARATOR . EXPOSITION);

	include 'Exposition.php';
	include 'Parser/Factory.php';

	Exposition::load(false);
	
	return true;
}

function uwa_init_dashboard()
{
	if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
		return;

	foreach ($widget_options as $id => $options) {
		if ( !empty($options) && !empty($options['moduleUrl']) ) {
			wp_add_dashboard_widget($id, $options['title'], 'uwa_widget', 'uwa_widget_configure');
		}
	}
}

function uwa_widget($widget_id = '', $array)
{
	$widget_id = $array['id'];

	if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
		$widget_options = array();
	if ( !isset($widget_options[$widget_id]) )
		$widget_options[$widget_id] = array();

	$uwaUrl = $widget_options[$widget_id]['moduleUrl'];

	$ifproxyUrl = get_option( 'siteurl' ) .
		'/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/ifproxy.html';

	$iframeUrl = 'http://nvmodules.netvibes.com/widget/frame?uwaUrl=' . urlencode($uwaUrl);
	$iframeUrl .= '&id=' . $widget_id . '&status=1';
	$iframeUrl .= '&ifproxyUrl=' . urlencode($ifproxyUrl);

	foreach ($widget_options[$widget_id] as $key => $value) {
		$iframeUrl .= '&' . $key . '=' . urlencode($value);
	}

	echo '<iframe width="100%" height="250" border="0" scrolling="no" src="' . $iframeUrl . '"></iframe>';
}

function uwa_widget_configure($widgetId = '', $array)
{
	$widget_id = $array['id'];

	if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
		$widget_options = array();
	if ( !isset($widget_options[$widget_id]) )
		$widget_options[$widget_id] = array();

	if ('POST' == $_SERVER['REQUEST_METHOD']) {
		foreach ($_POST as $key => $value) {
			if ($value == 'on') {
				$value = 'true';
			}
			$widget_options[$widget_id][$key] = $value;
		}
		update_option( 'dashboard_widget_options', $widget_options );
		return;
	}

	$uwaUrl = $widget_options[$widget_id]['moduleUrl'];

	uwa_load_exposition();
	$parser = Parser_Factory::getParser('uwa', $uwaUrl);
	$widget = $parser->buildWidget();

	echo '<table style="width:100%;border:0">';
	foreach ($widget->getPreferences() as $preference) {
		$name = $preference->getName();
		$value = !empty($widget_options[$widget_id][$name]) ? $widget_options[$widget_id][$name] : '';
		if (empty($value)) {
			$defaultValue = $preference->getDefaultValue();
			if ($defaultValue) {
				$value = $defaultValue;
			}
		}
		echo '<tr><td><label>' . $preference->getLabel() . '</label></td><td>';
		switch ($preference->getType()) {
			case 'text':
				echo '<input type="text" name="' . $name . '" value="' . $value . '" />';
				break;			
			case 'boolean':
				$checked = $value == 'true' ? ' checked="checked"' : '';
				echo '<input type="checkbox" name="' . $name . '"' . $checked . ' />';
				break;
			case 'password':
				echo '<input type="password" name="' . $name . '" value="' . $value . '" />';
				break;
			case 'range':
				$opt = $preference->getRangeOptions();
				echo '<select name="' . $name . '">';
				for ($i = (int)$opt['min']; $i <= (int)$opt['max']; $i += $opt['step']) {
					$selected = (int)$value == $i ? ' selected="selected"' : '';
					echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				echo '</select>';
				break;
			case 'list':
				$options = $preference->getListOptions();
				echo '<select name="' . $name . '">';
				foreach ($options as $label => $key) {
					$selected = (string)$value == (string)$key ? ' selected="selected"' : '';
					echo '<option value="' . $key . '"' . $selected . '>' . $label . '</option>';
				}
				echo '</select>';
				break;
			case 'hidden':
				break;
			default:
				echo 'Unknown type: ' . $preference->getType() . '<br>';
		}
		echo '</tr>';
	}
	echo '</table>';
}
