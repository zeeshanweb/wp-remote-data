<?php
defined( 'ABSPATH' ) || exit;
if ( !class_exists('WP_Class_Shortcode_Rendor') )
{
	class WP_Class_Shortcode_Rendor
	{
		public static $_instance = null;
		public function __construct()
		{
			add_shortcode( 'rendor_api_data', array( $this, 'rendor_api_data_func') );
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
		/**
		 * Return api data using ajax.
		 *
		 * 
		 */
		public function rendor_api_data_func()
		{
			ob_start();
			?>
			<script>
				jQuery.ajax({
					url: <?php echo wp_json_encode( esc_url_raw( rest_url( 'vendor/v1/route' ) ) ); ?>
				}).done(function( data ) {
					 var get_data = JSON.parse(data);					 
					 if( jQuery.isEmptyObject(get_data.mssg) )
					 {
						var tbl_row = "";
						 var tbl_body = '';
						 var tbl_header = "<tr><th>Id</th><th>Employee Name</th><th>Employee Salary</th><th>Employee Age</th><th>Profile Image</th></tr>";
						 jQuery.each(get_data.data, function(key,value) {
						  //console.log(value);					  
						  tbl_row += "<tr>";
						  tbl_row += "<td>"+value.id+"</td>";
						  tbl_row += "<td>"+value.employee_name+"</td>";
						  tbl_row += "<td>"+value.employee_salary+"</td>";
						  tbl_row += "<td>"+value.employee_age+"</td>";
						  tbl_row += "<td>"+value.profile_image+"</td>";
						  tbl_row += "</tr>";
						});
						tbl_body += "<table>"+tbl_header+tbl_row+"</table>"; 
					 }else
					 {
						 tbl_body = get_data.mssg;
					 }				 
					jQuery( '#rendor_api_data' ).html( tbl_body );
				});
			</script>
			<?php
			echo '<div id="rendor_api_data"></div>';
			return ob_get_clean();
		}
	}
	WP_Class_Shortcode_Rendor::get_instance();
}