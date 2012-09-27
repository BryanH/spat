<?php
/*
 * Plugin Name: Subpages as Tabs Shortcode
 * Plugin URI: http://hbjitney.com/subpages-as-tabs.html
 * Description: Add [spat] to any page to embed all subpages as tabs at that location.
 * Version: 1.00
 * Author: HBJitney, LLC
 * Author URI: http://hbjitney.com/
 * License: GPL3

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !class_exists('SupPageAsTabs' ) ) {
	/**
 	* Wrapper class to isolate us from the global space in order
 	* to prevent method collision
 	*/
	class SupPageAsTabs {
		var $plugin_name;

		/**
		 * Set up all actions, instantiate other
		 */
		function __construct() {
				add_filter( 'the_content', array( $this, 'subpages_tabs_shortcode' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'spat_shortcode_enqueue' ), 10 );
		}

		function spat_shortcode_enqueue() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-widget' );
		}

		/*
		 * Process the content for the shortcode
		 */
		function subpages_tabs_shortcode( $content ) {
				// If a page, then do split
			if( is_page() ) {
					//'[caspio id=(.+)\s*]',
					// Get children
					global $post;

					$content = preg_replace(
							'/\[spit\]/'
							, "<pre>" . print_r( $post, true ) . "</pre>"
							, $content
					);
			}
			return $content;
		}
	}
}

/*
 * Sanity - was there a problem setting up the class? If so, bail with error
 * Otherwise, class is now defined; create a new one it to get the ball rolling.
 */
if( class_exists( 'SupPageAsTabs' ) ) {
		new SupPageAsTabs();
} else {
	$message = "<h2 style='color:red'>Error in plugin</h2>
	<p>Sorry about that! Plugin <span style='color:blue;font-family:monospace'>caspio-shortcode</span> reports that it was unable to start.</p>
	<p><a href='mailto:support@hbjitney.com?subject=Subpages+as+Tabs+shortcode%20error&body=What version of Wordpress are you running? Please paste a list of your current active plugins here:'>Please report this error</a>.
	Meanwhile, here are some things you can try:</p>
	<ul><li>Uninstall (delete) this plugin, then reinstall it.</li>
	<li>Make sure you are running the latest version of the plugin; update the plugin if not.</li>
	<li>There might be a conflict with other plugins. You can try disabling every other plugin; if the problem goes away, there is a conflict.</li>
	<li>Try a different theme to see if there's a conflict between the theme and the plugin.</li>
	</ul>";
	wp_die( $message );
}
?>
