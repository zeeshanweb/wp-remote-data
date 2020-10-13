<?php
defined( 'ABSPATH' ) || exit;
if ( !class_exists('WP_Class_Retrieve_data') )
{
	class WP_Class_Retrieve_data
	{
		public static $fetch_url = "https://dummy.restapiexample.com/public/api/v1/employees";
		private static $_instance = null;
		public static $transient_name = 'wp_retrieve_transient_key';
		public static $api_error_mssg = array(500=>'There is some issue while fetching the records from remote URL.',429=>'Too Many Requests.');
		private $erorr_display = false;
		public function __construct()
		{
			add_action( 'init', array( $this, 'init_retrieve_and_save_data' ) );
			add_action( 'admin_notices', array( $this, 'wp_remote_admin_notice__error') );
		}
		/**
		 * display error.
		 *
		 * @return null
		 */
		public function wp_remote_admin_notice__error() 
		{
			if( true === $this->erorr_display )
			{
				$class = 'notice notice-success is-dismissible';
				$message = __( 'Cache prune successfully.', 'wp-remote-data' );		 
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}			 
        }
		/**
		 * Return the plugin instance.
		 *
		 * @return WP_Class_Retrieve_data
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
		 * delete the cache data.
		 *
		 * @return null
		 */
		public function rfresh_remote_data()
		{
			if( isset($_POST['wp_remote_field']) && wp_verify_nonce($_POST['wp_remote_field'], 'wp_remote_action'))
			{
				$delete_transient = delete_transient( self::$transient_name );
				if( true === $delete_transient )
				{
					$this->erorr_display = true;
				}				
			}
		}
		/**
		 * cached data usin WP transient API.
		 *
		 * This will expire after 12 hours
		 */
		public function init_retrieve_and_save_data()
		{
			//purge cache on button submit
			$this->rfresh_remote_data();
			$value = get_transient( self::$transient_name );
			$get_cached_data_decode = json_decode($value);
			//save data in transinet api if not exist
			if ( false === $value || !isset($get_cached_data_decode->data) )
			{
				$response = wp_remote_get( esc_url_raw( self::$fetch_url ) );			
				if ( is_wp_error( $response ) ) 
				{
				   set_transient( self::$transient_name, 500, 12 * HOUR_IN_SECONDS );
				} else 
				{
				   $response_code = wp_remote_retrieve_response_code( $response );
				   if( 200 !== $response_code )
				   {
					   set_transient( self::$transient_name, $response_code, 12 * HOUR_IN_SECONDS );
				   }else
				   {
					  $body = wp_remote_retrieve_body( $response );
					  $check_correct_json_data = $this->isdataJson($body);
					  if( $check_correct_json_data == true )
					  {
						  $data = $body;
					  }else
					  {
						  $data = json_encode($body);
					  }
					  set_transient( self::$transient_name, $data, 12 * HOUR_IN_SECONDS );  
				   }			   
				}
			}			
		}
		/**
		 * Check if data is in correct json format
		 *
		 */
		public function isdataJson( $string )
		{
			json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
		}
	}
	WP_Class_Retrieve_data::get_instance();
}