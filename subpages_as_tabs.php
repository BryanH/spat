<?php
/*
 * Plugin Name: Subpages as Tabs Shortcode
 * Plugin URI: http://hbjitney.com/subpages-as-tabs.html
 * Description: Add [spat] or [subpage_tabs] to any page to embed all subpages as tabs at that location.
 * Version: 1.01
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
				//add_filter( 'the_content', array( $this, 'subpages_tabs_shortcode' ) );
				//add_action( 'wp_enqueue_scripts', array( $this, 'spat_shortcode_enqueue' ), 10 );
				add_shortcode('spat', array( $this, 'render_tabs' ) );
				add_shortcode('subpage_tabs', array( $this, 'render_tabs' ) );
				add_filter( 'the_posts', array( $this, 'conditionally_add_scripts_and_styles' ) ); // the_posts gets triggered before wp_head
		}

		function spat_shortcode_enqueue() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'jquery-ui-widget' );

				wp_register_style( 'tab-style', plugins_url( 'style.php', __FILE__ ) );
				wp_enqueue_style( 'tab-style' );
		}

		/**
		* Enqueue scripts iff there are posts that have the shortcode
		* cycle through all posts and use stripos (faster than regex) to see if shortcode is in one of the displayed posts
		* http://beerpla.net/2010/01/13/wordpress-plugin-development-how-to-include-css-and-javascript-conditionally-and-only-when-needed-by-the-posts/
		*/
		function conditionally_add_scripts_and_styles( $posts ) {
				if( empty( $posts ) ) {
					return $posts;
				}

				$shortcode_found = false;
				foreach( $posts as $post ) {
						if(
								( true == stripos( $post->post_content, '[spat]' ) )
								|| ( true == stripos( $post->post_content, '[subpage-tabs]' ) )
						) {
								$shortcode_found = true;
								break;
						}
				}

				if( $shortcode_found ) {
						$this->spat_shortcode_enqueue();
				}

				return $posts;
		}


		/*
		 * Process the content for the shortcode
		 */
		function render_tabs( $attributes ) {
				global $post;
				// If a page, then do split
				// Get ids of children
				$wpq = new WP_Query();
				$all_wp_pages = $wpq->query(array('post_type' => 'page'));

				// Filter through all pages and find Portfolio's children
				$children = get_page_children( $post->ID, $all_wp_pages );
				$child_titles = array();
				$child_contents = "
";
				$child_tablinks = "
<div id='subpage-tabs'>
	<ul>
";
				foreach ( $children as $child ) {
					$child_tablinks .= "		<li><a href='#ctab-$child->ID'>$child->post_title</a></li>
";
					$child_contents .= "<div id='ctab-$child->ID'>
$child->post_content
</div>
";
				}
				$child_tablinks .= "	</ul>
";
				$child_contents .= "</div>
<script type='text/javascript'>
/*<![CDATA[*/
jQuery(
	function(){
		jQuery('#subpage-tabs').tabs();
    }
);
/*]]>*/
</script>
";

				// echo what we get back from WP to the browser
				$content = $child_tablinks . $child_contents;
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
