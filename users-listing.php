<?php
/*
Plugin Name: Users Listing
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: apple
Author URI: https://github.com/DevWael
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//Plugin Activation indicator
if ( ! defined( 'BB_USER_LISTING' ) ) {
	define( 'BB_USER_LISTING', true );
}

//plugin version
if ( ! defined( 'BB_USER_LISTING_VERSION' ) ) {
	define( 'BB_USER_LISTING_VERSION', 1.0 );
}

//plugin assets url
define( 'BB_USER_LISTING_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );

/**
 * Add Plugin Styles & Scripts
 */
add_action( 'admin_enqueue_scripts', 'bb_user_listing_assets' );
function bb_user_listing_assets() {
	wp_enqueue_style( 'razz-custom-widgets', BB_USER_LISTING_ASSETS . 'css/style.css' );

	wp_enqueue_script( 'bb-user-listing-js', BB_USER_LISTING_ASSETS . 'js/requests.js', array( 'jquery' ), BB_USER_LISTING_VERSION, true );
	wp_localize_script(
		'bb-user-listing-js',
		'bb_ul_obj',
		array(
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'online_visitors' ),
		)
	);
}

function bb_get_role_names() {

	global $wp_roles;

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}
	$all_roles = array(
		'' => esc_html__( 'Select Role', 'bbioon' )
	);

	return $all_roles + $wp_roles->get_names();
}

include plugin_dir_path( __FILE__ ) . 'admin.php';
include plugin_dir_path( __FILE__ ) . 'users-template.php';