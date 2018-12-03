<?php
/**
 * code based on wp-hasty.com
 * https://www.wp-hasty.com/tools/wordpress-settings-options-page-generator/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Listing_Settings_Page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'create_settings' ) );
		add_action( 'admin_init', array( $this, 'setup_sections' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );
	}

	public function create_settings() {
		$page_title = 'WP User Listing';
		$menu_title = 'User Listing';
		$capability = 'manage_options';
		$slug       = 'userlisting';
		$callback   = array( $this, 'settings_content' );
		$icon       = 'dashicons-admin-settings';
		$position   = 80;
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
	}

	public function settings_content() { ?>
        <div class="wrap user-listing">
            <h1>WP User Listing</h1>
			<?php settings_errors(); ?>
            <div class="setting-controls">
				<?php
				settings_fields( 'userlisting' );
				do_settings_sections( 'userlisting' );
				//print_r(get_role_names());
				//submit_button();
				$args        = [
					'number' => 10 //max number of users 10
				];
				$users       = new WP_User_Query( $args );
				$all_users   = $users->get_results();
				$total_users = $users->get_total();//Get result users count For Pagination
				?>
                <button class="sort-apply blue-button button button-primary" data-offset="0" type="button">Apply</button>
            </div>
            <br>
            <br>
            <div class="table-template">
				<?php if ( $total_users ) {
					$total_pages = ceil( $total_users / 10 ); //count pages based on 10 users of the given result per page
					?>
                    <div class="tablenav users-pagination">
						<?php if ( $total_pages > 1 ) { ?>
                            <div class="tablenav-pages">
                                <div class="pagination-links">
									<?php
									$o = 0;
									for ( $p = 1; $p < $total_pages + 1; $p ++ ) {
										?>
                                        <a class="next-page sort-apply<?php if ( ! $o ) {
											echo ' active-button';
										} ?>" data-page-number="<?php echo esc_attr( $p ); ?>"
                                           data-offset="<?php echo esc_attr( $o ); ?>" href="#">
                                            <span aria-hidden="true"><?php echo esc_html( $p ); ?></span>
                                        </a>
										<?php
										$o += 10;
									}
									?>
                                </div>
                            </div>
						<?php } ?>
                    </div>
				<?php } ?>

                <table class="widefat" cellspacing="0">
                    <thead>
                    <tr>
                        <th id="hash-column" class="manage-column column-columnname num" scope="col">#</th>
                        <th id="id-column" class="manage-column column-columnname num" scope="col">ID</th>
                        <th id="name-columnname" class="manage-column column-columnname" scope="col">Name</th>
                        <th id="user-name-columnname" class="manage-column column-columnname" scope="col">User Name</th>
                        <th id="user-name-columnname" class="manage-column column-columnname" scope="col">Role
                        </th>
                    </tr>
                    </thead>
                    <tbody>
					<?php bb_users_list_html( $all_users ); ?>
                    </tbody>
                </table>
            </div>
        </div>
		<?php
	}

	public function setup_sections() {
		add_settings_section( 'userlisting_section', 'Display WordPress Users With Some Filters', array(), 'userlisting' );
	}

	public function setup_fields() {
		$fields = array(
			array(
				'id'      => 'bb_role_names_filter',
				'label'   => esc_html__( 'Roles Filter', 'bbioon' ),
				'type'    => 'select',
				'section' => 'userlisting_section',
				'options' => bb_get_role_names(),
				'desc'    => '',
			),
			array(
				'id'      => 'bb_sorting_by',
				'default' => 'name',
				'label'   => esc_html__( 'Order By', 'bbioon' ),
				'type'    => 'select',
				'section' => 'userlisting_section',
				'options' => array(
					'display_name' => esc_html__( 'Name', 'bbioon' ),
					'user_login'   => esc_html__( 'User Name', 'bbioon' )
				),
				'desc'    => ''
			),
			array(
				'id'      => 'bb_sorting_method',
				'default' => 'ASC',
				'label'   => esc_html__( 'Order', 'bbioon' ),
				'type'    => 'select',
				'section' => 'userlisting_section',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'bbioon' ),
					'DESC' => esc_html__( 'descending', 'bbioon' )
				),
				'desc'    => ''
			)
		);
		foreach ( $fields as $field ) {
			add_settings_field( $field['id'], $field['label'], array(
				$this,
				'field_callback'
			), 'userlisting', $field['section'], $field );
			register_setting( 'userlisting', $field['id'] );
		}
	}

	public function field_callback( $field ) {
		$value = get_option( $field['id'] );
		if ( ! isset( $field['default'] ) ) {
			$field['default'] = '';
		}
		switch ( $field['type'] ) {
			case 'select':
				if ( empty( $value ) ) {
					$value = array( $field['default'] );
				}
				if ( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
					$attr    = '';
					$options = '';
					foreach ( $field['options'] as $key => $label ) {
						$options .= sprintf( '<option value="%s" %s>%s</option>',
							$key,
							selected( $value[ array_search( $key, $value, true ) ], $key, false ),
							$label
						);
					}
					if ( $field['type'] === 'multiselect' ) {
						$attr = ' multiple="multiple" ';
					}
					printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>',
						$field['id'],
						$attr,
						$options
					);
				}
				break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$field['placeholder'],
					$value
				);
		}
		if ( $desc = $field['desc'] ) {
			printf( '<p class="description">%s </p>', $desc );
		}
	}
}

new User_Listing_Settings_Page();