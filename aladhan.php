<?php

/**
 * Plugin Name: AlAdhan Prayer Times Plugin
 * Plugin URI: http://aladhan.com/clients-api
 * Description: This plugin allows you to compute and display prayer times and Hijri Dates on a WP Post or via a widget
 * Version: 1.0.0
 * Author: ميزان الدين عبد ذي الجلال و الاكرام
 * Author URI: http://aladhan.com
 * License: GPL2
 
 * https://premium.wpmudev.org/blog/wordpress-plugin-development-guide/, https://codex.wordpress.org/Roles_and_Capabilities
 */

// When debugging, uncomment the below lines.
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once(realpath(__DIR__) . '/vendor/autoload.php');
require_once(realpath(__DIR__) . '/classes/Render.php');

use AlAdhanApi\TimesByAddress;

class AlAdhanPlugin {

    public $viewPath;

    public function __construct()
    {
        $this->viewPath = realpath(__DIR__) . '/views/';
        
        add_action( 'wp_enqueue_scripts', [$this, 'loadCss'] );
        add_action( 'admin_enqueue_scripts', [$this, 'loadCss'] );
        add_action('admin_menu', [$this, 'loadMenu']);
        add_action('admin_init', [$this, 'setup']);
        add_action( 'admin_enqueue_scripts', [$this, 'loadColourPicker'] );
        add_action( 'widgets_init', [$this, 'loadWidget']);
    }
    
    public function loadWidget()
    {
        register_widget( 'AlAdhanPrayerTimesWidget' ); 
    }   
    
    public function loadCss()
    {
        wp_register_style( 'aladhan-style', plugins_url( '/css/style.css', __FILE__ ), array(), time(), 'all' );
        wp_enqueue_style( 'aladhan-style' );
    }
    
    public function loadColourPicker()
    {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker-script', plugins_url('/js/script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
    
    public function loadMenu()
    {
        add_menu_page('AlAdhan Plugin Settings', 'AlAdhan.com', 'manage_options', 'aladhan-settings', [$this, 'aladhan_settings_page'], 'dashicons-admin-generic');
    }
    
    public function aladhan_settings_page()
    {
        $this->render();
    }

    public function setup()
    {
        register_setting('aladhan-settings-group', 'prayer_times_method');
        register_setting('aladhan-settings-group', 'prayer_times_address');
        register_setting('aladhan-settings-group', 'prayer_times_school');
        register_setting('aladhan-settings-group', 'prayer_times_latitude_adjustment_method');
        register_setting('aladhan-settings-group', 'prayer_times_display_format');
        register_setting('aladhan-settings-group', 'prayer_times_display_header_bg');
        register_setting('aladhan-settings-group', 'prayer_times_display_header_text');
        register_setting('aladhan-settings-group', 'prayer_times_display_row_bg');
        register_setting('aladhan-settings-group', 'prayer_times_display_row_text');
        register_setting('aladhan-settings-group', 'prayer_times_display_heading');
        register_setting('aladhan-settings-group', 'prayer_times_display_heading_bg');
        register_setting('aladhan-settings-group', 'prayer_times_display_heading_text');
		register_setting('aladhan-settings-group', 'prayer_times_override_fajr');
		register_setting('aladhan-settings-group', 'prayer_times_override_dhuhr');
		register_setting('aladhan-settings-group', 'prayer_times_override_asr');
		register_setting('aladhan-settings-group', 'prayer_times_override_maghrib');
		register_setting('aladhan-settings-group', 'prayer_times_override_isha');
    }

    
    public function render()
    {
        return $this->loadView('settings');         
    }

    // GENERIC HELPER FUNCTIONS IN CLASS

    public function loadView($name)
    {
        include($this->viewPath . $name . '.php');
    }
}
    
new AlAdhanPlugin();

/*** Plugin Ends here ***/

/*** Widget Begins ***/


/**
 * Adds AlAdhanApiWidget widget.
 */
class AlAdhanPrayerTimesWidget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'AlAdhanPrayerTimesWidget', // Base ID
			__('Al Adhan Prayer Times Widget', 'text_domain'), // Name
			array( 'description' => __( 'Prayer Times Widget', 'text_domain' ), ) // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
     	echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
        $address = esc_attr( get_option('prayer_times_address') ) == '' ? 'Makkah, Saudi Arabia' : esc_attr( get_option('prayer_times_address') );
        $displayFormat = esc_attr( get_option('prayer_times_display_format') ) == '' ? 'horizontal' : esc_attr( get_option('prayer_times_display_format') ); 
        $method =  esc_attr( get_option('prayer_times_method') ) == '' ? '4' : esc_attr( get_option('prayer_times_method') );
        $school = esc_attr( get_option('prayer_times_school') ) == '' ? '0' : esc_attr( get_option('prayer_times_school') );
        $latitudeMethod = esc_attr( get_option('prayer_times_latitude_adjustment_method') ) == '' ? '3' : esc_attr( get_option('prayer_times_latitude_adjustment_method') );
        $displayHeading = esc_attr( get_option('prayer_times_display_heading') ) == '' ? 'Prayer Times Today' :  esc_attr( get_option('prayer_times_display_heading') );
        $displayHeadingBgColour = esc_attr( get_option('prayer_times_display_heading_bg') );
        $displayHeadingColour = esc_attr( get_option('prayer_times_display_heading_text') ); 
        $prayerTimings = (new TimesByAddress($address, null, $method, $latitudeMethod, $school))->get();
		Render::Timings($displayFormat,
                        $prayerTimings,
                        $displayHeadingBgColour,
                        $displayHeadingColour,
                        $displayHeading,
                        esc_attr( get_option('prayer_times_display_header_bg') ),
                        esc_attr( get_option('prayer_times_display_row_bg') ),
                        esc_attr( get_option('prayer_times_display_header_text') ),
                        esc_attr( get_option('prayer_times_display_row_text') ),
						esc_attr( get_option('prayer_times_override_fajr') ),
                        esc_attr( get_option('prayer_times_override_dhuhr') ),
                        esc_attr( get_option('prayer_times_override_asr') ),
                        esc_attr( get_option('prayer_times_override_maghrib') ),
                        esc_attr( get_option('prayer_times_override_isha') )
                       );
        
        echo $args['after_widget'];
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( '', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}
    
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
