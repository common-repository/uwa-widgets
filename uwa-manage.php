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

?>

<div class="wrap">

<h2>Manage Widgets</h2>

<form method="post" action="">

<?php

if ( !$widget_options = get_option( 'dashboard_widget_options' ) )
	$widget_options = array();

if (!empty($_GET) && isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($widget_options[$id]);
	update_option( 'dashboard_widget_options', $widget_options );
}

if (!empty($_POST) && !empty($_POST['moduleUrl'])) {

	$id = 'uwa-' . md5( rand(1, 999) . mktime() );

	$widget_options[$id] = array();
	$widget_options[$id]['moduleUrl'] = $uwaUrl = $_POST['moduleUrl'];
	$widget_options[$id]['type'] = 'uwa';

	uwa_load_exposition();

	$parser = Parser_Factory::getParser('uwa', $uwaUrl);
	$widget = $parser->buildWidget();

	if ($widget) {
		$widget_options[$id]['title'] = $widget->getTitle();
		update_option( 'dashboard_widget_options', $widget_options );
	}

}

?>

<h3>Widget list</h3>

<table class="form-table">
<?php
foreach($widget_options as $id => $widget) {
	if (isset($widget['moduleUrl'])) {
		?>
		<tr>
			<td><strong><?php echo $widget['title'] ?></strong></td>
			<td><?php echo $widget['moduleUrl'] ?></td>
			<td><a href="<?php echo $_SERVER["REQUEST_URI"] . '&remove=' . $id ?>">Remove</a></td>
		</tr>
		<?php
	}
}
?>
</table>

<h3>Add a widget</h3>

<table class="form-table">
<tr>
<td><label>UWA Url</label></td>
<td><input class="regular-text" type="text" name="moduleUrl" value="" /></td>
</tr>
</table>

<p class="submit">
<input class="button-primary" type="submit" value="Add widget" />
</p>

</form>

</div>