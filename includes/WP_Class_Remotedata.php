<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WP_Class_Remotedata' ) )
{
	class WP_Class_Remotedata
	{
		private static $_instance = null;		
		public function __construct()
		{
			$this->includes();
		}
		/**
		 * Return the plugin instance.
		 *
		 * @return WP_Class_Remotedata
		 */
		public static function get_instance()
		{
			if ( is_null( self::$_instance ) ) 
			{
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		/**
		 * Includes all the necessary files.
		 *
		 */
		public function includes()
		{
			include_once WPREMOTEDATA_PLUGIN_PATH . 'includes/WP_Class_Retrieve_data.php';
			include_once WPREMOTEDATA_PLUGIN_PATH . 'includes/WP_Class_Data_Endpoint.php';
			include_once WPREMOTEDATA_PLUGIN_PATH . 'includes/WP_Class_Shortcode_Rendor.php';
			include_once WPREMOTEDATA_PLUGIN_PATH . 'includes/WP_Class_Admin_Page.php';
		}
	}
}