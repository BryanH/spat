<?php
/*
 * Plugin Name: Subpages as Tabs Shortcode
 * Plugin URI: http://hbjitney.com/subpages-as-tabs.html
 * Description: Add [spat] or [subpage_tabs] to any page to embed all subpages as tabs at that location.
 * Version: 0.97
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

if ( !class_exists('SubPageAsTabs' ) ) {
	/**
 	* Wrapper class to isolate us from the global space in order
 	* to prevent method collision
 	*/
	class SubPageAsTabs {
		var $plugin_name;

		/**
		 * Set up all actions, instantiate other
		 */
		function __construct() {
				//add_filter( 'the_content', array( $this, 'subpages_tabs_shortcode' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'spat_shortcode_enqueue' ), 10 );
				add_shortcode('spat', array( $this, 'render_tabs' ) );
				add_shortcode('subpage_tabs', array( $this, 'render_tabs' ) );
				add_shortcode('subpages_tabs', array( $this, 'render_tabs' ) );
				add_filter( 'the_posts', array( $this, 'conditionally_add_scripts_and_styles' ) ); // the_posts gets triggered before wp_head
		}

		function spat_shortcode_enqueue() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'jquery-ui-widget' );

				wp_register_style( 'subpage-tab-style', plugins_url( 'tab-style.php', __FILE__ ) );
				wp_enqueue_style( 'subpage-tab-style' );
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
								|| ( true == stripos( $post->post_content, '[subpage_tabs]' ) )
								|| ( true == stripos( $post->post_content, '[subpages_tabs]' ) )
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
				$children = get_pages( array(
						'child_of' => $post->ID
						, 'parent' => $post->ID
						, 'sort_column' => 'menu_order'
				) );

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
					// Render any shortcodes
					$new_content = do_shortcode( $child->post_content );
					$child_contents .= "<div id='ctab-$child->ID'>
$new_content
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
				$content = $child_tablinks . $child_contents;
				return $content;
		}

		/**
		 * Add our options to the settings menu
		 */
		function add_admin() {
				add_options_page(
						__( "Subpage Tabs" )
						, __( "Subpage Tabs" )
						, 'manage_options'
						, 'subpage_tabs_plugin'
						, array( $this, 'plugin_options_page' )
				);
		}


		/**
		 * Callback for options page - set up page title and instantiate fields
		 */
		function plugin_options_page() {
?>
		<div class="plugin-options">
		<h2><span><?php _e( "Subpage as Tabs Options" ); ?></span></h2>
		<p><?php _e( "Here you set the tab appearance (colors, borders, etc)." ); ?></p>
		 <form action="options.php" method="post">
<?php
		  settings_fields( 'subpage_tabs_options' );
		  do_settings_sections( 'subpage_tabs_plugin' );
?>

		  <input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		 </form>
		</div>
<?php
		}

		/*
		 * Define options section (only one) and fields (also only one!)
		 */
		function admin_init() {
			// Group = setings_fields, name of options, validation callback
			register_setting( 'subpage_tabs_options', 'subpage_tabs_options', array( $this, 'options_validate' ) );
			// Unique ID, section title displayed, section callback, page name = do_settings_section
			add_settings_section( 'flickr_show_section', '', array( $this, 'main_section' ), 'subpage_tabs_plugin' );
			// Unique ID, Title, function callback, page name = do_settings_section, section name
			add_settings_field( 'flickr_width', __( 'Width (in pixels)' ), array( $this, 'width_field'), 'subpage_tabs_plugin', 'flickr_show_section');
			add_settings_field( 'flickr_height', __('Height (in pixels)' ), array( $this, 'height_field'), 'subpage_tabs_plugin', 'flickr_show_section');
			add_settings_field( 'flickr_username', __( 'Username' ), array( $this, 'username_field'), 'subpage_tabs_plugin', 'flickr_show_section');
		}

		/*
		 * Static content for options section
		 */
		function main_section() {
				// GNDN
		}


	}
}


/*
 * Sanity - was there a problem setting up the class? If so, bail with error
 * Otherwise, class is now defined; create a new one it to get the ball rolling.
 */
if( class_exists( 'SubPageAsTabs' ) ) {
	new SubPageAsTabs();
} else {
	$message = "<h2 style='color:red'>Error in plugin</h2>
	<p>Sorry about that! Plugin <span style='color:blue;font-family:monospace'>subpages_as_tabs_shortcode</span> reports that it was unable to start.</p>
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
