<?php
/**
 * Plugin Name:       WP Remote Data
 * Plugin URI:        https://khanzeeshan.in/
 * Description:       Fetching remote data.
 * Version:           1.0.0
 * Author:            Zeeshan Khan
 * Author URI:        https://khanzeeshan.in/
 * Text Domain:       wp-remote-data
 */
defined( 'ABSPATH' ) || exit;
// Define.
if ( ! defined( 'WPREMOTEDATA_PATH' ) ) 
{
	define( 'WPREMOTEDATA_PATH', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'WPREMOTEDATA_PLUGIN_PATH' ) ) 
{
	define( 'WPREMOTEDATA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WPREMOTEDATA_URL_PATH' ) ) 
{
	define( 'WPREMOTEDATA_URL_PATH', plugins_url( '', __FILE__ ) );
}
if ( !class_exists( 'WP_Class_Remotedata' ) ) 
{
	include_once dirname( __FILE__ ) . '/includes/WP_Class_Remotedata.php';
}

// Init the plugin and load the plugin instance.
add_action( 'plugins_loaded', array( 'WP_Class_Remotedata', 'get_instance' ) );