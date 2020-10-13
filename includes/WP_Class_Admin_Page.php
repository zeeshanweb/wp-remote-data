<?php
defined( 'ABSPATH' ) || exit;
if ( !class_exists('WP_Class_Admin_Page') )
{
	class WP_Class_Admin_Page
	{
		public static $_instance = null;
		public function __construct()
		{
			add_action('admin_menu', array($this, 'admin_menu'));
		}
		/**
		 * Return the plugin instance.
		 *
		 * @return WP_Class_Shortcode_Rendor
		 */
		public static function get_instance()
		{
			if ( is_null( self::$_instance ) ) 
			{
				self::$_instance = new self();
			}
			return self::$_instance;
		}		
		public function admin_menu()
		{
			add_management_page(__('WP Remote Data', 'wp-remote-data'), __('WP Remote Data', 'wp-remote-data'), 'administrator', 'wp-remote-data', array($this, 'plugin_page'));
		}
		/**
	   * Display plugin's admin page
	   *
	   */
		public function plugin_page()
		{
			if(!current_user_can('administrator'))
			{
				wp_die(__('Sorry, you are not allowed to access this page.'));
			}
			$get_cached_data = get_transient( WP_Class_Retrieve_data::$transient_name );
			$get_cached_data_decode = json_decode($get_cached_data);
			echo '<div class="wrap">';
			echo '<header style="padding-bottom: 20px;">';
			echo '<div class="wrap-container" style="text-align:center;">';			
			echo '<h1>Use below shortcode to render data on frontend.</h1>';
			echo '<p><code>[rendor_api_data]</code></p>';
			echo '<form id="wp_remote_data_form" action="' . admin_url('tools.php?page=wp-remote-data') . '" method="post" autocomplete="off">';
			wp_nonce_field( 'wp_remote_action','wp_remote_field' );
			submit_button( __( 'Prune Cache', 'wp-remote-data' ), 'primary' );
			echo '</form>';
			echo '</div>';
			echo '</header>';		
			if( isset($get_cached_data_decode->data) && (is_array($get_cached_data_decode->data) || is_object($get_cached_data_decode->data)) )
			{
				echo '<table style="border-collapse:collapse;width:80%;margin:auto;">';
				echo '<tr>';
				echo '<th>ID</th><th>Employee Name</th><th>Employee Salary</th><th>Employee Age</th><th>Profile Image</th>';
				foreach( $get_cached_data_decode->data as $display_data )
				{
					echo '<tr>';
					echo '<td>'.esc_html($display_data->id).'</td>';
					echo '<td>'.esc_html($display_data->employee_name).'</td>';
					echo '<td>'.esc_html($display_data->employee_salary).'</td>';
					echo '<td>'.esc_html($display_data->employee_age).'</td>';
					echo '<td>'.esc_html($display_data->profile_image).'</td>';
					echo '</tr>';
				}
				echo '</tr>';
			    echo '</table>';
			}else if( isset(WP_Class_Retrieve_data::$api_error_mssg[$get_cached_data]) )
			{
				echo '<p>'.WP_Class_Retrieve_data::$api_error_mssg[$get_cached_data].'</p>';
			}else
			{
				echo '<p>There is some issue while fetching the records from remote URL.</p>';
			}
			echo "<style>
				td, th {
				  border: 1px solid #dddddd;
				  text-align: left;
				  padding: 8px;
				}				
				tr:nth-child(even) {
				  background-color: #dddddd;
				}
				p.submit{text-align: center;}
				</style>";		
			echo '</div>'; // wrap
		}
	}
	WP_Class_Admin_Page::get_instance();
}