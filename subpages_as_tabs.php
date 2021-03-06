<?php
/*
 * Plugin Name: Subpages as Tabs Shortcode
 * Plugin URI: http://hbjitney.com/subpages-as-tabs.html
 * Description: Add [spat] or [subpage_tabs] to any page to embed all subpages as tabs at that location.
 * Version: 1.07
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
				add_action( 'wp_enqueue_scripts', array( $this, 'spat_shortcode_enqueue' ), 11 );
				add_action( 'admin_enqueue_scripts', array( $this, 'spat_admin_shortcode_enqueue' ) );
				add_action( 'add_meta_boxes', array( $this, 'add_some_meta_box' ) );
		add_filter('screen_layout_columns', array(&$this, 'on_screen_layout_columns'), 10, 2);

				add_action('admin_menu', array( $this, 'add_admin' ) );
				add_action( 'admin_init', array( $this, 'admin_init' ) );

				add_shortcode('spat', array( $this, 'render_tabs' ) );
				add_shortcode('subpage_tabs', array( $this, 'render_tabs' ) );
				add_shortcode('subpages_tabs', array( $this, 'render_tabs' ) );

				add_filter( 'the_posts', array( $this, 'conditionally_add_scripts_and_styles' ) ); // the_posts gets triggered before wp_head
		}

		function on_screen_layout_columns($columns, $screen) {
			if ($screen == $this->pagehook) {
				$columns[$this->pagehook] = 2;
			}
			return $columns;
		}

		function spat_shortcode_enqueue() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-widget' );

				wp_register_style( 'jquery-ui-tab-style', plugins_url( 'tab-style.php', __FILE__ ) );
				wp_register_style( 'subpage-tab-style', plugins_url( 'tab-style.php', __FILE__ ) );
				wp_register_style( 'subpage-tab-style', plugins_url( 'tab-style.php', __FILE__ ) );
				wp_enqueue_style( 'subpage-tab-style' );
		}

		/** Admin resources
		*/
		function spat_admin_shortcode_enqueue( $hook ) {
			// Only target our page
			wp_enqueue_script('spectrum_js', plugins_url( 'spectrum.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), '33.3' );
			wp_register_style( 'spectrum', plugins_url( 'spectrum.css', __FILE__ ) );
			wp_enqueue_style( 'spectrum' );

			wp_register_style( 'subpage-tab-style', plugins_url( 'tab-style.php', __FILE__ ) );
			wp_enqueue_style( 'subpage-tab-style' );

			wp_enqueue_script('spat', plugins_url( 'spat.js', __FILE__ ), array( 'spectrum_js' ), 0.9 );
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
// TODO: start jquery 'loading' action here.

				$child_tablinks = "
<div id='subpage-tabs' class='ui-tabs'>
	<ul>
";
				foreach ( $children as $child ) {
					$child_tablinks .= "		<li><a href='#ctab-$child->ID'>$child->post_title</a></li>
";
					// Render any shortcodes
					$new_content = do_shortcode( $child->post_content );
					$child_contents .= "<div id='ctab-$child->ID' class='ui-tabs-hide'>
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
// TODO: destroy jquery 'loading' action here.

				$content = $child_tablinks . $child_contents;
				return $content;
		}

		/**
		 * Add our options to the settings menu
		 */
		function add_admin() {
			$this->pagehook = add_options_page(
					__( "Subpages as Tabs" )
					, __( "Subpages as Tabs" )
					, 'manage_options'
					, 'subpage_tabs_plugin'
					, array( $this, 'plugin_options_page' )
				);

			//register callback to gets call prior your options_page rendering
			add_action( 'load-' . $this->pagehook, array( &$this, 'add_the_meta_boxes' ) );
		}

		/**
	     * Adds the meta box container
	     */
	    public function add_the_meta_boxes() {
	        add_meta_box(
	            'spat_options_metabox'					// ID
	            , __( 'Subpages As Tabs Options' ) 		// Title
	            , array( $this, 'plugin_options_form' ) // Render Code function
	            , $this->pagehook							// Page hook
	            , 'normal'								// Context
	            , 'core'								// ??
	        );

	        add_meta_box(
	             'spat_demo_metabox'					// ID
	            , __( 'Preview' )							// Title
	            , array( $this, 'plugin_demo_page' ) 	// Render Code Function
	            , $this->pagehook						// Page hook
	            , 'side'								// Context
	            , 'core'								// ??
	        );
	    }

		/**
		 * Callback for options page - set up page title and instantiate fields
		 */
		function plugin_options_page() {
			global $screen_layout_columns;
?>
		<div class="wrap">
<?php
			screen_icon('options-general');
?>
			<h2><?php _e( "Subpage as Tabs Options" ); ?></h2>
			<p><?php _e( "Here you set the tab appearance (colors, borders, etc)." ); ?></p>
			<form action="options.php" method="post">
				<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
				<div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
					<div id="side-info-column" class="inner-sidebar">
<?php
			do_meta_boxes($this->pagehook, 'side', null);
?>
					</div>

					<div id="post-body" class="has-sidebar">
						<div id="post-body-content" class="has-sidebar-content">
<?php
			do_meta_boxes($this->pagehook, 'normal', null);
?>
						</div>
					</div>
				</div>
				<script type="text/javascript">
				/*<![CDATA[*/
					jQuery(document).ready( function($) {
						// close postboxes that should be closed
						$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
						// postboxes setup
						postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
					});
				/*]]>*/
				</script>
			</form>
		</div>
<?php
		}

		/*
		 * Content for normal meta box
		 */
		function plugin_options_form() {
?>
			<input type="hidden" name="action" value="save_metaboxes_general" />
<?php
		  settings_fields( 'subpages_as_tabs_options' );
		  do_settings_sections( 'subpage_tabs_plugin' );
?>

		  <input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
<?php
		}

		/**
		 * Content for side meta box
		 */
		function plugin_demo_page() {
?>
<div id="subpage-tabs">
	<ul>
		<li>
			<a href="#tab1">Tab 1</a>
		</li>

		<li>
			<a href="#tab2">Tab 2</a>
		</li>

		<li>
			<a href="#tab3">Tab 3</a>
		</li>
	</ul>

	<div id="tab1">
		<h2>Example 1</h2><p>Maecenas sed diam eget risus varius blandit sit
		amet non magna. Morbi leo risus, porta ac consectetur ac,
		vestibulum at eros. Fusce dapibus, tellus ac cursus commodo,
		tortor mauris condimentum nibh, ut fermentum massa justo sit amet
		risus. Etiam porta sem malesuada magna mollis euismod. Aenean eu
		leo quam. Pellentesque ornare sem lacinia quam venenatis
		vestibulum. Maecenas faucibus mollis interdum.</p>
	</div>

	<div id="tab2">
		<h2>Example 2</h2><p>Donec sed odio dui. Donec id elit non mi porta gravida at eget
		metus. Aenean eu leo quam. Pellentesque ornare sem lacinia quam
		venenatis vestibulum. Nullam id dolor id nibh ultricies vehicula
		ut id elit. Praesent commodo cursus magna, vel scelerisque nisl
		consectetur et. Etiam porta sem malesuada magna mollis
		euismod.</p>
	</div>

	<div id="tab3">
		<h2>Example 3</h2><p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas faucibus mollis interdum. Donec
		ullamcorper nulla non metus auctor fringilla. Fusce dapibus,
		tellus ac cursus commodo, tortor mauris condimentum nibh, ut
		fermentum massa justo sit amet risus. Aenean eu leo quam.
		Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus
		sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
		Curabitur blandit tempus porttitor.</p>
	</div>
</div>
<?php
		}

		/*
		 * Define options section (only one) and fields (also only one!)
		 */
		function admin_init() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			// Group = setings_fields, name of options, validation callback
			register_setting( 'subpages_as_tabs_options', 'subpages_as_tabs_options', array( $this, 'options_validate' ) );
			// Unique ID, section title displayed, section callback, page name = do_settings_section
			add_settings_section( 'subpages_tabs_section', '', array( $this, 'main_section' ), 'subpage_tabs_plugin' );
			// Unique ID, Title, function callback, page name = do_settings_section, section name
			add_settings_field( 'spat_active_tab_background', __( 'Active Tab Background' ), array( $this, 'active_tab_background'), 'subpage_tabs_plugin', 'subpages_tabs_section');
			/**/
			add_settings_field( 'spat_active_tab_foreground', __('Active Tab Text' ), array( $this, 'active_tab_foreground'), 'subpage_tabs_plugin', 'subpages_tabs_section');
			add_settings_field( 'spat_inactive_tab_background', __( 'Inactive Tab Background' ), array( $this, 'inactive_tab_background'), 'subpage_tabs_plugin', 'subpages_tabs_section');
			add_settings_field( 'spat_inactive_tab_foreground', __('Inactive Tab Text' ), array( $this, 'inactive_tab_foreground'), 'subpage_tabs_plugin', 'subpages_tabs_section');

			add_settings_field( 'border', __('Border' ), array( $this, 'border'), 'subpage_tabs_plugin', 'subpages_tabs_section');
			//*/
		}

		/*
		 * Static content for options section
		 */
		function main_section() {
				// GNDN
		}

		/*
		 * Code for fields
		 */
		function active_tab_background() {
			// Matches field # of register_setting
			$options = get_option( 'subpages_as_tabs_options' );
?>
			<input id="spat_active_tab_background" name="subpages_as_tabs_options[active_tab_background]" class="color_pick" type="color" size="7" value="<?php _e( $options['active_tab_background'] );?>" />
<?php
		}

		/*
		 * Code for fields
		 */
		function active_tab_foreground() {
			// Matches field # of register_setting
			$options = get_option( 'subpages_as_tabs_options' );
?>
			<input id="spat_active_tab_foreground" name="subpages_as_tabs_options[active_tab_foreground]" class="color_pick" type="color" size="7" value="<?php _e( $options['active_tab_foreground'] );?>" />
<?php
		}

		/*
		 * Code for fields
		 */
		function inactive_tab_background() {
			// Matches field # of register_setting
			$options = get_option( 'subpages_as_tabs_options' );
?>
			<input id="spat_inactive_tab_background" name="subpages_as_tabs_options[inactive_tab_background]" class="color_pick" type="color" size="7" value="<?php _e( $options['inactive_tab_background'] );?>" />
<?php
		}


		/*
		 * Code for fields
		 */
		function inactive_tab_foreground() {
			// Matches field # of register_setting
			$options = get_option( 'subpages_as_tabs_options' );
?>
			<input id="spat_inactive_tab_foreground" name="subpages_as_tabs_options[inactive_tab_foreground]" class="color_pick" type="color" size="7" value="<?php _e( $options['inactive_tab_foreground'] );?>" />
<?php
		}

		/*
		 * Code for fields
		 */
		function border() {
			// Matches field # of register_setting
			$options = get_option( 'subpages_as_tabs_options' );
?>
			<input id="spat_border" name="subpages_as_tabs_options[border]" class="color_pick" type="color" size="7" value="<?php _e( $options['border'] );?>" />
<?php
		}


		/*
		 * Validate presense of parameters
		 * Verify height, width are numbers
		 */
		function options_validate( $input ) {
				$active_tab_background = trim( $input['active_tab_background'] );
				if( empty( $active_tab_background ) ) {
						add_settings_error( "spat_active_tab_background", '', __( "Active Tab Background is required." ) );
				}
				$newinput['active_tab_background'] = $active_tab_background;

				$active_tab_foreground = trim( $input['active_tab_foreground'] );
				if( empty( $active_tab_foreground ) ) {
						add_settings_error( "spat_active_tab_foreground", '', __( "Active Tab Text is required." ) );
				}
				$newinput['active_tab_foreground'] = $active_tab_foreground;

				$inactive_tab_background = trim( $input['inactive_tab_background'] );
				if( empty( $inactive_tab_background ) ) {
						add_settings_error( "spat_inactive_tab_background", '', __( "Inactive Tab Background is required." ) );
				}
				$newinput['inactive_tab_background'] = $inactive_tab_background;

				$inactive_tab_foreground = trim( $input['inactive_tab_foreground'] );
				if( empty( $inactive_tab_foreground ) ) {
						add_settings_error( "spat_inactive_tab_foreground", '', __( "Inactive Tab Text is required." ) );
				}
				$newinput['inactive_tab_foreground'] = $inactive_tab_foreground;

				$border = trim( $input['border'] );
				if( empty( $border ) ) {
						add_settings_error( "spat_border", '', __( "Border is required." ) );
				}
				$newinput['border'] = $border;

				return $newinput;
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
