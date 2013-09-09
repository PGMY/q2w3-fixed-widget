<?php
/*
Plugin Name: Q2W3 Fixed Widget
Plugin URI: http://www.q2w3.ru/q2w3-fixed-widget-wordpress-plugin/
Description: Fixes positioning of the selected widgets, when the page is scrolled down.
Author: Max Bond
Version: 4.0.1
Author URI: http://www.q2w3.ru/
*/

// Hooks

if ( is_admin() ) { 
	
	add_action('in_widget_form', array( 'q2w3_fixed_widget', 'add_option' ), 10, 3);

	add_filter('widget_update_callback', array( 'q2w3_fixed_widget', 'update_option' ), 10, 3);
	
	add_action('admin_init', array( 'q2w3_fixed_widget', 'register_settings' ));
	
	add_action('admin_menu', array( 'q2w3_fixed_widget', 'admin_init' ));
	
} else { 
	
	add_action('wp_enqueue_scripts', array( 'q2w3_fixed_widget', 'init' )); 
	
	add_filter('register_sidebar', array( 'q2w3_fixed_widget', 'register_sidebar_filter' ));
		
	add_action('wp_footer', array( 'q2w3_fixed_widget', 'action' ), 1);
	
} 

if ( class_exists('q2w3_fixed_widget', false) ) return; // if class is allready loaded return control to the main script

// Plugin class

class q2w3_fixed_widget {
	
	const ID = 'q2w3_fixed_widget';
	
	const VERSION = '4.0.1';
	
	protected static $sidebars_widgets;
	
	protected static $fixed_widgets;

	
	
	public static function init() {
		
		$options = self::load_options();
		
		add_filter('widget_display_callback', array( 'q2w3_fixed_widget', 'check' ), $options['widget_display_callback_priority'], 3);
		
		wp_enqueue_script('q2w3-fixed-widget', plugin_dir_url( __FILE__ ) . 'js/q2w3-fixed-widget.js', array('jquery'), self::VERSION);
				
		self::check_custom_ids();
						
	}
		
	public static function check($instance, $widget, $args){
    	
		if ( isset($instance['q2w3_fixed_widget']) && $instance['q2w3_fixed_widget'] ) {

			self::$fixed_widgets[$args['id']][$widget->id] = "'". $widget->id ."'";
				
		}
		
		return $instance;

	}
	
	protected static function check_custom_ids() {
		
		$options = self::load_options();
		
		if ( isset($options['custom-ids']) && $options['custom-ids'] ) {
		
			$ids = explode(PHP_EOL, $options['custom-ids']);
		
			foreach ( $ids as $id ) {
				
				$id = trim($id);

				if ( $id ) self::$fixed_widgets[self::get_widget_sidebar($id)][$id] = "'". $id ."'";
				
			}
		
		}
		
	}
	
	public static function get_widget_sidebar($widget_id) {
		
		if ( !self::$sidebars_widgets ) {
		
			self::$sidebars_widgets = wp_get_sidebars_widgets();
			
			unset(self::$sidebars_widgets['wp_inactive_widgets']);
	
		}
		
		if ( is_array(self::$sidebars_widgets) ) {
		
			foreach ( self::$sidebars_widgets as $sidebar => $widgets ) {
		
				$key = array_search($widget_id, $widgets);
		
				if ( $key !== false ) return $sidebar;
	
			}
		
		}
		
		return 'q2w3-default-sidebar';
		
	}
		
	public static function action() { 
	
		$options = self::load_options();
		
		if ( is_array(self::$fixed_widgets) && !empty(self::$fixed_widgets) ) {
						
			echo '<script type="text/javascript">'.PHP_EOL;

			if ( isset($options['window-load-enabled']) && $options['window-load-enabled'] == 'yes' ) {
				
				echo 'jQuery(window).load(function(){'.PHP_EOL;
				
			} else {
			
				echo 'jQuery(document).ready(function(){'.PHP_EOL;
			
			}
			
			$i = 0;
			
			foreach ( self::$fixed_widgets as $sidebar => $widgets ) {
			
				$i++;
				
				$widgets_array = implode(',', $widgets);
				
				echo '  var q2w3_sidebar_'. $i .'_options = { "sidebar" : "'. $sidebar .'", "margin_top" : '. $options['margin-top'] .', "margin_bottom" : '. $options['margin-bottom'] .', "screen_max_width" : '. $options['screen-max-width'] .', "widgets" : ['. $widgets_array .'] };'.PHP_EOL;
				
				echo '  q2w3_sidebar(q2w3_sidebar_'. $i .'_options);'.PHP_EOL;
				
				if ( $options['refresh-interval'] > 0 ) {
	
					echo '  setInterval(function () { q2w3_sidebar(q2w3_sidebar_'. $i .'_options); }, '. $options['refresh-interval'] .');'.PHP_EOL;
								
				} 
				
			}
			
			echo '});'.PHP_EOL;
						
			echo '</script>'.PHP_EOL;
			
		} 
	
	}

	public static function add_option($widget, $return, $instance) {  
	
		echo '<p>'.PHP_EOL;
    	
		echo '<input type="checkbox" name="'. $widget->get_field_name('q2w3_fixed_widget') .'" value="1" '. checked( $instance['q2w3_fixed_widget'], 1, false ) .'/>'.PHP_EOL;
    	
		echo '<label for="'. $widget->get_field_id('q2w3_fixed_widget') .'">'. __('Fixed widget', 'q2w3_fixed_widget') .'</label>'.PHP_EOL;
	
		echo '</p>'.PHP_EOL;    

	}

	public static function update_option($instance, $new_instance, $old_instance){
    
    	if ( isset($new_instance['q2w3_fixed_widget']) && $new_instance['q2w3_fixed_widget'] ) {
			
    		$instance['q2w3_fixed_widget'] = 1;
    
    	} else {
    	
    		$instance['q2w3_fixed_widget'] = false;
    	
    	}
    
    	return $instance;

	}
	
	protected static function load_language() {
	
		$currentLocale = get_locale();
	
		if (!empty($currentLocale)) {
				
			$moFile = dirname(__FILE__).'/lang/'.$currentLocale.".mo";
		
			if (@file_exists($moFile) && is_readable($moFile)) load_textdomain(self::ID, $moFile);
			
		}
	
	}
	
	public static function admin_init() {
		
		self::load_language();
		
		add_submenu_page( 'themes.php', __('Fixed Widget Options', 'q2w3_fixed_widget'), __('Fixed Widget Options', 'q2w3_fixed_widget'), 'activate_plugins', 'q2w3_fixed_widget', array( __CLASS__, 'settings_page' ) );
			
	}
	
	protected static function defaults() {
		
		$d['margin-top'] = 10;
			
		$d['margin-bottom'] = 0;
		
		$d['refresh-interval'] = 1500;
		
		$d['screen-max-width'] = 0;
		
		$d['window-load-enabled'] = false;
		
		$d['widget_display_callback_priority'] = 30;
		
		$d['disable-phone'] = false;
		
		$d['disable-tablet'] = false;
		
		return $d;
		
	}
	
	protected static function load_options() {
		
		$options = get_option(self::ID);	
		
		return array_merge(self::defaults(), (array)$options);
		
	}
	
	public static function register_settings() {
		
		register_setting(self::ID, self::ID, array( __CLASS__, 'save_options_filter' ) );
		
	}
	
	public static function save_options_filter($input) { // Sanitize user input
		
		$input['margin-top'] = (int)$input['margin-top'];
			
		$input['margin-bottom'] = (int)$input['margin-bottom'];
		
		$input['refresh-interval'] = (int)$input['refresh-interval'];

		$input['screen-max-width'] = (int)$input['screen-max-width'];
		
		$input['custom-ids'] = trim(wp_strip_all_tags($input['custom-ids']));
		
		return $input;
		
	}
	
	public static function settings_page() {
		
		$options = self::load_options();
						
		echo '<div class="wrap"><div id="icon-themes" class="icon32"><br /></div><h2>'. __('Fixed Widget Options', 'q2w3_fixed_widget') .'</h2>'.PHP_EOL;
		
		if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) { 
		
			echo '<div id="message" class="updated"><p>'. __('Settings saved.') .'</p></div>'.PHP_EOL;
		
		}
		
		echo '<form method="post" action="options.php">'.PHP_EOL;
		
		settings_fields(self::ID);
				
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Margin Top:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[margin-top]" value="'. $options['margin-top'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Margin Bottom:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[margin-bottom]" value="'. $options['margin-bottom'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 150px;">'. __('Refresh interval:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[refresh-interval]" value="'. $options['refresh-interval'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('milliseconds', 'q2w3_fixed_widget') .' / '. __('Set 0 to disable.', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 150px;">'. __('Screen Max Width:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[screen-max-width]" value="'. $options['screen-max-width'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3_fixed_widget') .' / '. __('Plugin will be disabled when browser window width equals or less then specified value', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;
				
		echo '<p><span >'. __('Custom HTML IDs (each one on a new line):', 'q2w3_fixed_widget') .'</span><br/><textarea name="'. self::ID .'[custom-ids]" style="width: 320px; height: 120px;">'. $options['custom-ids'] .'</textarea>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 195px;">'. __('Use jQuery(window).load() hook:', 'q2w3_fixed_widget') .'</span><input type="checkbox" name="'. self::ID .'[window-load-enabled]" value="yes" '. checked('yes', $options['window-load-enabled'], false) .' /> '. __('Use this option only if you have problems with <a href="http://wordpress.org/support/topic/doesnt-work-with-infinte-scroll-for-widget-scripts" target="_blank">other scroll oriented javascript code</a>', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 195px;">'. __('widget_display_callback priority:', 'q2w3_fixed_widget') .'</span><select name="'. self::ID .'[widget_display_callback_priority]"><option value="1" '. selected('1', $options['widget_display_callback_priority'], false) .'>1</option><option value="10" '. selected('10', $options['widget_display_callback_priority'], false) .'>10</option><option value="20" '. selected('20', $options['widget_display_callback_priority'], false) .'>20</option><option value="30" '. selected('30', $options['widget_display_callback_priority'], false) .'>30</option><option value="50" '. selected('50', $options['widget_display_callback_priority'], false) .'>50</option><option value="100" '. selected('100', $options['widget_display_callback_priority'], false) .'>100</option></select></p>'.PHP_EOL;
		
		echo '<p class="submit"><input type="submit" class="button-primary" value="'. __('Save Changes') .'" /></p>'.PHP_EOL;

		echo '</form>'.PHP_EOL;
		
		echo '<br/>'.PHP_EOL;

		echo '<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="Q36H2MHNVVP7U"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></form></p>'.PHP_EOL;
				
		echo '</div><!-- .wrap -->'.PHP_EOL;
		
	}
	
	public static function register_sidebar_filter($sidebar) {
		
		global $wp_registered_sidebars;
		
		if ( strpos($sidebar['before_widget'], 'id="%1$s"') !== false || strpos($sidebar['before_widget'], 'id=\'%1$s\'') !== false ) return;
		
		if ( strpos($sidebar['before_widget'], 'id=') === false ) {
			
			$tag_end_pos = strpos($sidebar['before_widget'], '>');
			
			if ( $tag_end_pos !== false ) {
				
				$wp_registered_sidebars[$sidebar['id']]['before_widget'] = substr_replace($sidebar['before_widget'], ' id="%1$s"', $tag_end_pos, 0);
				
			} 
			
		} else {

			$str_array = explode(' ', $sidebar['before_widget']);
			
			if ( is_array($str_array) ) {
				
				foreach ( $str_array as $str_part_id => $str_part ) {
					
					if ( strpos($str_part, 'id=') !== false ) {
						
						$str_array[$str_part_id] = 'id="%1$s"';
						
					}
					
				}

				$wp_registered_sidebars[$sidebar['id']]['before_widget'] = implode(' ', $str_array);
				
			}
									
		}
				
	}
	
}
