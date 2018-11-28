<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Userlisting_Settings_Page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

	}

	public function wph_create_settings() {
		$page_title = 'WP User Listing';
		$menu_title = 'User Listing';
		$capability = 'manage_options';
		$slug       = 'userlisting';
		$callback   = array( $this, 'wph_settings_content' );
		$icon       = 'dashicons-admin-settings';
		$position   = 80;
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
	}

	public function wph_settings_content() { ?>
        <div class="wrap">
            <h1>WP User Listing</h1>
			<?php settings_errors(); ?>
            <div class="setting-controls">
				<?php
				settings_fields( 'userlisting' );
				do_settings_sections( 'userlisting' );
				//print_r(get_role_names());
				//submit_button();
				?>
                <button class="sort-apply button button-primary" type="button">Apply</button>
            </div>
            <div class="table-template">


                <table class="widefat" cellspacing="0">
                    <thead>
                    <tr>
                        <th id="id-column" class="manage-column column-columnname num" scope="col">ID</th>
                        <th id="name-columnname" class="manage-column column-columnname" scope="col">Name</th>
                        <th id="user-name-columnname" class="manage-column column-columnname" scope="col">User Name</th>
                        <th id="user-name-columnname" class="manage-column column-columnname" scope="col">Capability
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="alternate">
                        <th class="check-column" scope="row"></th>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                    </tr>
                    <tr>
                        <th class="check-column" scope="row"></th>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                    </tr>
                    <tr class="alternate">
                        <th class="check-column" scope="row"></th>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                    </tr>
                    <tr class="">
                        <th class="check-column" scope="row"></th>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                    </tr>
                    <tr class="alternate">
                        <th class="check-column" scope="row"></th>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                        <td class="column-columnname"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="tablenav-pages"><span class="displaying-num">89 items</span>
                    <span class="pagination-links">
                        <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                        <span class="paging-input">
                            <label for="current-page-selector" class="screen-reader-text">
                                Current Page
                            </label>
                            <input class="current-page" id="current-page-selector" type="text" name="paged" value="1"
                                   size="1"
                                   aria-describedby="table-paging">
                            <span class="tablenav-paging-text">
                                of
                                <span class="total-pages">5</span>
                            </span>
                        </span>
                        <a class="next-page" href="http://localhost/app/wp-admin/users.php?paged=2">
                            <span class="screen-reader-text">Next page</span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a class="last-page" href="http://localhost/app/wp-admin/users.php?paged=5">
                            <span class="screen-reader-text">Last page</span>
                            <span aria-hidden="true">»</span>
                        </a>
                    </span>
                </div>
            </div>
        </div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'userlisting_section', 'Display WordPress Users With Some Filters', array(), 'userlisting' );
	}

	public function wph_setup_fields() {
		$fields = array(
//			array(
//				'label'       => 'text field',
//				'id'          => 'text_field_id',
//				'type'        => 'text',
//				'section'     => 'userlisting_section',
//				'desc'        => 'description',
//				'placeholder' => 'placeholder',
//			),
//			array(
//				'label'       => 'text area',
//				'id'          => 'textarea_id',
//				'type'        => 'textarea',
//				'section'     => 'userlisting_section',
//				'desc'        => 'dsc',
//				'placeholder' => 'place',
//			),
//			array(
//				'label'       => 'wyswig',
//				'id'          => 'wyswig_id',
//				'type'        => 'wysiwyg',
//				'section'     => 'userlisting_section',
//				'desc'        => 'desc',
//				'placeholder' => 'holder',
//			),
//			array(
//				'label'       => 'checkbox',
//				'id'          => 'checkboxid',
//				'type'        => 'checkbox',
//				'section'     => 'userlisting_section',
//				'options'     => array(
//					'op1' => 'op1',
//					'op2' => 'op2',
//					'op3' => 'op3',
//				),
//				'desc'        => 'desc',
//				'placeholder' => 'holder',
//			),
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
					'name'     => esc_html__( 'Name', 'bbioon' ),
					'username' => esc_html__( 'User Name', 'bbioon' ),
				),
				'desc'    => '',
			),
			array(
				'id'      => 'bb_sorting_method',
				'default' => 'ASC',
				'label'   => esc_html__( 'Order', 'bbioon' ),
				'type'    => 'select',
				'section' => 'userlisting_section',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'bbioon' ),
					'DESC' => esc_html__( 'descending', 'bbioon' ),
				),
				'desc'    => '',
			),
//			array(
//				'label'       => 'select',
//				'id'          => 'idoptt',
//				'type'        => 'select',
//				'section'     => 'userlisting_section',
//				'options'     => array(
//					'degamils' => 'degamils',
//					'khales'   => 'khales',
//				),
//				'desc'        => 'desc',
//				'placeholder' => 'holder',
//			),

//			array(
//				'label'       => 'mmsk',
//				'id'          => 'frevcd',
//				'type'        => 'multiselect',
//				'section'     => 'userlisting_section',
//				'options'     => array(
//					'mmdd'    => 'mmdd',
//					'kfmknv'  => 'kfmknv',
//					' fkvnjf' => ' fkvnjf',
//					'fj vjf'  => 'fj vjf',
//					''        => '',
//				),
//				'desc'        => 'desc',
//				'placeholder' => 'fdvd',
//			),
//			array(
//				'label'       => 'medis',
//				'id'          => 'medissd',
//				'type'        => 'media',
//				'section'     => 'userlisting_section',
//				'desc'        => 'desccc',
//				'placeholder' => 'holder',
//			),
//			array(
//				'label'       => 'email',
//				'id'          => 'frdcd',
//				'type'        => 'email',
//				'section'     => 'userlisting_section',
//				'desc'        => 'dfvdf',
//				'placeholder' => 'fdvsd',
//			),
//			array(
//				'label'       => 'urlls',
//				'id'          => 'optt',
//				'type'        => 'url',
//				'section'     => 'userlisting_section',
//				'desc'        => 'dfsvfd',
//				'placeholder' => 'dcfs',
//			),
//			array(
//				'label'       => 'pass',
//				'id'          => 'ododod',
//				'type'        => 'password',
//				'section'     => 'userlisting_section',
//				'desc'        => 'sdcd',
//				'placeholder' => 'hlodee',
//			),
//			array(
//				'label'       => 'sdf',
//				'id'          => 'sdf',
//				'type'        => 'number',
//				'section'     => 'userlisting_section',
//				'desc'        => 'dsf',
//				'placeholder' => 'ssdfds',
//			),
//			array(
//				'label'       => 'rfwer',
//				'id'          => 'sdfg',
//				'type'        => 'tel',
//				'section'     => 'userlisting_section',
//				'desc'        => 'esfsd',
//				'placeholder' => 'sdfg',
//			),
//			array(
//				'label'       => 'sdfvsdf',
//				'id'          => 'sdfv',
//				'type'        => 'date',
//				'section'     => 'userlisting_section',
//				'desc'        => 'sdbvfds',
//				'placeholder' => 'sdfvs',
//			),
//			array(
//				'label'       => 'dfvfsdf',
//				'id'          => 'werfgew',
//				'type'        => 'color',
//				'section'     => 'userlisting_section',
//				'desc'        => 'werfwer',
//				'placeholder' => 'fsdvsdfv',
//			),
		);
		foreach ( $fields as $field ) {
			add_settings_field( $field['id'], $field['label'], array(
				$this,
				'wph_field_callback'
			), 'userlisting', $field['section'], $field );
			register_setting( 'userlisting', $field['id'] );
		}
	}

	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		if ( ! isset( $field['default'] ) ) {
			$field['default'] = '';
		}
		switch ( $field['type'] ) {
			case 'media':
				printf(
					'<input style="width: 40%%" id="%s" name="%s" type="text" value="%s"> <input style="width: 19%%" class="button userlisting-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
					$field['id'],
					$field['id'],
					$value,
					$field['id'],
					$field['id']
				);
				break;
			case 'radio':
			case 'checkbox':
				if ( empty( $value ) ) {
					$value = array( $field['default'] );
				}
				if ( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
					$options_markup = '';
					$iterator       = 0;
					foreach ( $field['options'] as $key => $label ) {
						$iterator ++;
						$options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
							$field['id'],
							$field['type'],
							$key,
							checked( $value[ array_search( $key, $value, true ) ], $key, false ),
							$label,
							$iterator
						);
					}
					printf( '<fieldset>%s</fieldset>',
						$options_markup
					);
				}
				break;
			case 'select':
			case 'multiselect':
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
			case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
					$field['id'],
					$field['placeholder'],
					$value
				);
				break;
			case 'wysiwyg':
				wp_editor( $value, $field['id'] );
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

	public function media_fields() {
		?>
        <script>
            jQuery(document).ready(function ($) {
                if (typeof wp.media !== 'undefined') {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('.userlisting-media').click(function (e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                $('input#' + id).val(attachment.url);
                            } else {
                                return _orig_send_attachment.apply(this, [props, attachment]);
                            }
                            ;
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                    $('.add_media').on('click', function () {
                        _custom_media = false;
                    });
                }
            });
        </script><?php
	}

}

new Userlisting_Settings_Page();