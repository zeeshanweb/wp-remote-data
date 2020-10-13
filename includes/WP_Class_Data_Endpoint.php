<?php
 
class WP_Class_Data_Endpoint extends WP_REST_Controller 
{
 
  public function __construct()
  {	  
  }
  /**
   * Register the routes for the objects of the controller.
   */
  // Register our REST Server
  public function hook_rest_server()
  {
    add_action( 'rest_api_init', array( $this, 'register_routes' ) );
  }
  //http://localhost/development/wp-json/vendor/v1/route
  public function register_routes() 
  {
    $version = '1';
    $namespace = 'vendor/v' . $version;
    $base = 'route';
    register_rest_route( $namespace, '/' . $base, array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_items' ),
        'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => array(
 
        ),
      ),) );
  } 
  /**
   * Get a collection of items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) 
  {
	 $value_get = get_transient( WP_Class_Retrieve_data::$transient_name );
	 $get_cached_data_decode = json_decode($value_get);
	 if( isset($get_cached_data_decode->data) && (is_array($get_cached_data_decode->data) || is_object($get_cached_data_decode->data)) )
	 {
		 $value = $value_get;
	 }else if( isset(WP_Class_Retrieve_data::$api_error_mssg[$value_get]) )
	 {
		$data_array = array('mssg'=> WP_Class_Retrieve_data::$api_error_mssg[$value_get]);
		$value = json_encode($data_array );
	 }else
	 {
		$data_array = array('mssg'=> 'There is some issue while fetching the records from remote URL.');
		$value = json_encode($data_array );
	 }
	 return new WP_REST_Response( $value, 200 );
  }
  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) 
  {
    return true; //<--use to make readable by all
  }
}
$WP_Class_Data_Endpoint = new WP_Class_Data_Endpoint();
$WP_Class_Data_Endpoint->hook_rest_server();