<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_user_listing', 'bb_display_users' );
function bb_display_users() {
	//security check
	check_ajax_referer( 'user_listing', 'nonce' );
	$ordering    = [ 'ASC', 'DESC' ];
	$ordering_by = [ 'display_name', 'user_login' ];
	$args        = [
		'number' => 10,
	];
	if ( isset( $_POST['offset'] ) && is_numeric( $_POST['offset'] ) ) {
		//validate received offset parameter
		$args['offset'] = (int) sanitize_text_field( $_POST['offset'] ); //skip this number of users and give the next
	}
	if ( isset( $_POST['role_filer'] ) && ! empty( $_POST['role_filer'] ) ) {
		//validate received role filter parameter
		if ( array_key_exists( $_POST['role_filer'], bb_get_role_names() ) ) {
			$args['role'] = sanitize_text_field( $_POST['role_filer'] );// filter users by role
		}
	}

	if ( isset( $_POST['order_by'] ) && ! empty( $_POST['order_by'] ) ) {
		//validate received order by parameter
		if ( in_array( $_POST['order_by'], $ordering_by, true ) ) {
			$args['orderby'] = sanitize_text_field( $_POST['order_by'] );//set sorting method
		}
	}

	if ( isset( $_POST['order_method'] ) && ! empty( $_POST['order_method'] ) ) {
		//validate received order parameter
		if ( in_array( $_POST['order_method'], $ordering, true ) ) {
			$args['order'] = sanitize_text_field( $_POST['order_method'] );//set the sort order
		}
	}

	$users       = new WP_User_Query( $args );
	$all_users   = $users->get_results();
	$total_users = $users->get_total();//Get result users count For Pagination

	ob_start();
	bb_users_list_html( $all_users );
	$users_data = ob_get_contents();
	ob_end_clean();
	ob_start();
	if ( $total_users ) {
		//get pagination pages count
		$total_pages = ceil( $total_users / 10 );
		if ( $total_pages > 1 ) {
			?>
            <div class="tablenav-pages">
                <div class="pagination-links">
					<?php
					$o = 0;
					for ( $p = 1; $p < $total_pages + 1; $p ++ ) {
						if ( isset( $_POST['offset'] ) && is_numeric( $_POST['offset'] ) && $o == $_POST['offset'] ) {
							$active_attr = ' active-button';
						} else {
							$active_attr = '';
						}
						?>
                        <a class="next-page sort-apply<?php echo esc_attr( $active_attr ); ?>"
                           data-page-number="<?php echo esc_attr( $p ); ?>"
                           data-offset="<?php echo esc_attr( $o ); ?>" href="#">
                            <span aria-hidden="true"><?php echo esc_html( $p ); ?></span>
                        </a>
						<?php
						$o += 10;
					}
					?>
                </div>
            </div>
		<?php }
	}
	$pagination_data = ob_get_contents();
	ob_end_clean();
	$send_data = [
		'users_data'      => $users_data,
		'pagination_data' => $pagination_data,
	];
	wp_send_json( $send_data );
}