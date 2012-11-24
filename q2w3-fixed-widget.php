<?php
/*
Plugin Name: Q2W3 Fixed Widget
Plugin URI: http://www.q2w3.ru/q2w3-fixed-widget-wordpress-plugin/
Description: Fixes positioning of the selected widgets, when the page is scrolled down.
Author: Max Bond
Version: 1.0.2
Author URI: http://www.q2w3.ru/
*/

// Hooks

if ( is_admin() ) {
	
	add_action('in_widget_form', array( 'q2w3_fixed_widget', 'add_option' ), 10, 3);

	add_filter('widget_update_callback', array( 'q2w3_fixed_widget', 'update_option' ), 10, 3);
	
} else {
	
	add_action('template_redirect', array( 'q2w3_fixed_widget', 'init' ));
	
	add_filter('widget_display_callback', array( 'q2w3_fixed_widget', 'check' ), 10, 3);
		
	add_action('wp_footer', array( 'q2w3_fixed_widget', 'action' ), 1);
	
}


// if class allready loaded return control to the main script

if ( class_exists('q2w3_fixed_widget', false) ) return; 


// Plugin class

class q2w3_fixed_widget {
	
	protected static $fixed_widgets;
	
	
	
	public static function init() {
		
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('q2w3-fixed-widget', plugin_dir_url( __FILE__ ) . 'js/functions.js', array('jquery'), '1.2', true);
	 		
	}
		
	public static function check($instance, $widget, $args){
    	
		if ( $instance['q2w3_fixed_widget'] ) self::$fixed_widgets[$widget->id] = "'". $widget->id ."'";
	
		return $instance;

	}
		
	public static function action() { 
	
		if ( is_array(self::$fixed_widgets) && !empty(self::$fixed_widgets) ) {
			
			$array = implode(',', self::$fixed_widgets);
			
			echo '<script type="text/javascript">q2w3_fixed_widgets = new Array('. $array .');</script>'.PHP_EOL;
				
		} else {
			
			echo '<script type="text/javascript">q2w3_fixed_widgets = new Array();</script>'.PHP_EOL;
				
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
	
}
