<?php
/*
Plugin Name: Q2W3 Fixed Widget
Plugin URI: http://www.q2w3.ru/q2w3-fixed-widget-wordpress-plugin/
Description: Fixes positioning of the selected widgets, when the page is scrolled down.
Author: Max Bond
Version: 2.2.1
Author URI: http://www.q2w3.ru/
*/

// Hooks

if ( is_admin() ) {
	
	add_action('in_widget_form', array( 'q2w3_fixed_widget', 'add_option' ), 10, 3);

	add_filter('widget_update_callback', array( 'q2w3_fixed_widget', 'update_option' ), 10, 3);
	
	add_action('admin_init', array( 'q2w3_fixed_widget', 'register_settings' ));
	
	add_action('admin_menu', array( 'q2w3_fixed_widget', 'admin_init' ));
	
} else { 
	
	add_action('template_redirect', array( 'q2w3_fixed_widget', 'init' ));
	
	add_filter('widget_display_callback', array( 'q2w3_fixed_widget', 'check' ), 10, 3);
		
	add_action('wp_footer', array( 'q2w3_fixed_widget', 'action' ), 1);
	
}

// if class allready loaded return control to the main script

if ( class_exists('q2w3_fixed_widget', false) ) return; 

// Plugin class

class q2w3_fixed_widget {
	
	const ID = 'q2w3_fixed_widget';
	
	protected static $fixed_widgets;

	
	
	public static function init() {
		
		wp_enqueue_script('q2w3-fixed-widget', plugin_dir_url( __FILE__ ) . 'js/q2w3-fixed-widget.js', array('jquery'), '2.2.1');
		
		self::check_custom_ids();
		
	}
		
	public static function check($instance, $widget, $args){
    	
		if ( $instance['q2w3_fixed_widget'] ) self::$fixed_widgets[$widget->id] = "'". $widget->id ."'";
	
		return $instance;

	}
	
	protected static function check_custom_ids() {
		
		$options = self::load_options();
		
		if ( !$options['custom-ids'] ) return;
		
		$ids = explode(PHP_EOL, $options['custom-ids']);
		
		foreach ( $ids as $id ) self::$fixed_widgets[$id] = "'". $id ."'";
		
	}
		
	public static function action() { 
	
		$options = self::load_options();
		
		if ( $options['disable-phone'] == 'yes' || $options['disable-tablet'] == 'yes' ) {
		
			require 'q2w3-mobile-detect.php';
			
			$detect = new Q2W3_Mobile_Detect();
			
			$device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
			
			if ( $device_type == 'phone' && $options['disable-phone'] == 'yes' ) {
				
				self::$fixed_widgets = array();
				
			} 
			
			if ( $device_type == 'tablet' && $options['disable-tablet'] == 'yes' ) {
				
				self::$fixed_widgets = array();
				
			}
		
		}
					
		if ( is_array(self::$fixed_widgets) && !empty(self::$fixed_widgets) ) {
						
			$array = implode(',', self::$fixed_widgets);
						
			echo '<script type="text/javascript">'.PHP_EOL;
			
			echo 'jQuery(document).ready(function(){'.PHP_EOL;
			
			echo '  var q2w3_sidebar_options = { "sidebar" : "q2w3_default", "margin_top" : '. $options['margin-top'] .', "margin_bottom" : '. $options['margin-bottom'] .', "widgets" : ['. $array .'] }'.PHP_EOL;
			
			if ( $options['refresh-interval'] > 0 ) {

				echo '  setInterval(function () { q2w3_sidebar(q2w3_sidebar_options); }, '. $options['refresh-interval'] .');'.PHP_EOL;
							
			} else {
				
				echo '  q2w3_sidebar(q2w3_sidebar_options);'.PHP_EOL;
				
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
		
		$d['refresh-interval'] = 1000;
		
		return $d;
		
	}
	
	protected static function load_options() {
		
		$options = get_option(self::ID);	
		
		return array_merge(self::defaults(), (array)$options);
		
	}
	
	public static function register_settings() {
		
		register_setting(self::ID, self::ID, array( __CLASS__, 'save_options_filter' ) );
		
	}
	
	public static function save_options_filter($input) {
		
		// Sanitize user input
		
		$input['margin-top'] = (int)$input['margin-top'];
			
		$input['margin-bottom'] = (int)$input['margin-bottom'];
			
		return $input;
		
	}
	
	public static function settings_page() {
		
		$options = self::load_options();
						
		echo '<div class="wrap"><h2>'. __('Fixed Widget Options', 'q2w3_fixed_widget') .'</h2>'.PHP_EOL;
		
		if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) { 
		
			echo '<div id="message" class="updated"><p>'. __('Settings saved.') .'</p></div>'.PHP_EOL;
		
		}
		
		echo '<form method="post" action="options.php">'.PHP_EOL;
		
		echo wp_nonce_field('update-options', '_wpnonce', true, false).PHP_EOL;
		
		echo '<input type="hidden" name="action" value="update" />'.PHP_EOL;
		
		echo '<input type="hidden" name="page_options" value="'. self::ID .'" />'.PHP_EOL;
				
		echo '<p><span style="display: inline-block; width: 100px;">'. __('Margin Top:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[margin-top]" value="'. $options['margin-top'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 100px;">'. __('Margin Bottom:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[margin-bottom]" value="'. $options['margin-bottom'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 100px;">'. __('Refresh interval:', 'q2w3_fixed_widget') .'</span><input type="text" name="'. self::ID .'[refresh-interval]" value="'. $options['refresh-interval'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('milliseconds', 'q2w3_fixed_widget') .' / '. __('Set 0 to disable.', 'q2w3_fixed_widget') .'</p>'.PHP_EOL;
				
		echo '<p><span >'. __('Custom HTML IDs (each one on a new line):', 'q2w3_fixed_widget') .'</span><br/><textarea name="'. self::ID .'[custom-ids]" style="width: 320px; height: 120px;">'. $options['custom-ids'] .'</textarea>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 195px;">'. __('Disable plugin on phone devices:', 'q2w3_fixed_widget') .'</span><input type="checkbox" name="'. self::ID .'[disable-phone]" value="yes" '. checked('yes', $options['disable-phone'], false) .' /></p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 195px;">'. __('Disable plugin on tablet devices:', 'q2w3_fixed_widget') .'</span><input type="checkbox" name="'. self::ID .'[disable-tablet]" value="yes" '. checked('yes', $options['disable-tablet'], false) .' /></p>'.PHP_EOL;
		
		echo '<p class="submit"><input type="submit" class="button-primary" value="'. __('Save Changes') .'" /></p>'.PHP_EOL;

		echo '</form>'.PHP_EOL;
		
		echo '<br/>'.PHP_EOL;

		echo '<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="Q36H2MHNVVP7U"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></form></p>'.PHP_EOL;
				
		echo '</div><!-- .wrap -->'.PHP_EOL;
		
	}
	
}
