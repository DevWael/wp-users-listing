<?php
/*
Plugin Name: Users Listing
Plugin URI: https://github.com/DevWael/wp-users-listing
Description: List All WordPress Users With Ajax Filters
Version: 0.2
Author: Ahmad Wael
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
	wp_enqueue_style( 'bb-user-listing-css', BB_USER_LISTING_ASSETS . 'css/style.css', false, BB_USER_LISTING_VERSION );

	wp_enqueue_script( 'bb-user-listing-js', BB_USER_LISTING_ASSETS . 'js/requests.js', array( 'jquery' ), BB_USER_LISTING_VERSION, true );
	wp_localize_script(
		'bb-user-listing-js',
		'bb_ul_obj',
		[
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'user_listing' ),
		]
	);
}

/**
 * Get all roles plus an empty role for the filter select form field
 */
function bb_get_role_names() {

	global $wp_roles;

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}
	$all_roles = [
		'' => esc_html__( 'Select Role', 'bbioon' ),
	];

	return $all_roles + $wp_roles->get_names();
}

/**
 * List all users given from the wp user query result
 *
 * @param $all_users object from wp user query class
 */
function bb_users_list_html( $all_users ) {
	if ( ! empty( $all_users ) ) {
		$i = 1;
		foreach ( $all_users as $user ) {
			$user_info = get_userdata( $user->ID );
			?>
            <tr class="user-data <?php if ( $i % 2 == 0 ) {
				echo 'alternate';
			} ?>">
                <th class="check-column" style="text-align: center"
                    scope="row"><?php echo esc_html( $i ); ?></th>
                <th class="check-column" style="text-align: center"
                ><?php echo esc_html( $user_info->ID ); ?></th>
                <td class="column-columnname"><?php echo esc_html( $user_info->display_name ); ?></td>
                <td class="column-columnname"><?php echo esc_html( $user_info->user_login ); ?></td>
                <td class="column-cap"><?php echo esc_html( $user_info->roles ? $user_info->roles[0] : false ); ?></td>
            </tr>
			<?php
			$i ++;
		}
	} else {
		?>
        <tr class="user-data">
            <td colspan="5"
                style="text-align: center"><?php esc_html_e( 'No Users With These Filters...', 'bbioon' ); ?></td>
        </tr>
		<?php
	}
}

include plugin_dir_path( __FILE__ ) . 'admin.php'; //Load admin page
include plugin_dir_path( __FILE__ ) . 'users-request.php'; //Process ajax requests