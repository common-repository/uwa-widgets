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

function uwa_set_value(id, name, value)
{
	jQuery.ajax({
		type:'post', success:null, error:null,
		url: WP_ADMIN_URL + 'options-general.php?page=uwa/uwa-ajax.php',
		data:{
			action:'uwa-set-value', id:id, name:name, value:value,
		}
	});
}

function uwa_msg_handler(message)
{
	var id = message.id;
	switch (message.action) {
		case 'resizeHeight':
			var el = jQuery('#' + id + ' iframe');
			if (el) {
				el.attr('height', message.value);
				jQuery(window).trigger('resize');
			}
			// uwa_set_value(id, 'height', message.value);
			break;
		case 'setTitle':
			var el = jQuery('#' + id + ' span.title');
			if (el) {
				el.html(message.value);
			}
			break;
		case 'setValue':
			uwa_set_value(id, message.name, message.value);
			break;
		default:
			console.log(message);
			break;
	}
};
