<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       raileo
 * @since      1.0.0
 *
 * @package    Raileo
 * @subpackage Raileo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Raileo
 * @subpackage Raileo/admin
 * @author     Raileo <raileo>
 */
class Raileo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Raileo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Raileo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/raileo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Raileo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Raileo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/raileo-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * adding menu to the sidebar
	 */
	public function add_raileo_to_sidebar() {
		$icon = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="0 0 24 24" width="24pt" height="24pt"><defs><clipPath id="_clipPath_Mk19M9ALR870ZYxWnI2pstuGfLu5RMHO"><rect width="24" height="24"/></clipPath></defs><g clip-path="url(#_clipPath_Mk19M9ALR870ZYxWnI2pstuGfLu5RMHO)"><path d=" M 3 3 L 3 20 C 3 20.553 3.447 21 4 21 L 21 21 L 21 19 L 5 19 L 5 3 L 3 3 Z " fill="rgb(158,163,168)"/><path d=" M 15.293 14.707 C 15.684 15.098 16.316 15.098 16.707 14.707 L 21.707 9.707 L 20.293 8.293 L 16 12.586 L 13.707 10.293 C 13.316 9.902 12.684 9.902 12.293 10.293 L 7.293 15.293 L 8.707 16.707 L 13 12.414 L 15.293 14.707 Z " fill="rgb(158,163,168)"/></g></svg>');
		add_menu_page('Raileo Settings', 'Raileo', 'manage_options', 'raileo-monitoring', 'raileo_page', $icon, 200);
	}

}
