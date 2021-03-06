<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_GZDP_Admin_Generator {

	/**
	 * Single instance of WooCommerce Germanized Main Class
	 *
	 * @var object
	 */
	protected static $_instance = null;

	public $generator = array();

	public $settings_observe = array();

	public $pages = array();

	protected $api_version = 'v2';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-germanized-pro' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-germanized-pro' ), '1.0' );
	}
	
	public function __construct() {

		$this->generator = array( 'widerruf' => '', 'agbs' => '' );
		$this->pages     = array( 'widerruf' => 'revocation', 'agbs' => 'terms' );

		add_action( 'init', array( $this, 'set_generator_titles' ) );

		add_filter( 'woocommerce_gzd_settings_sections', array( $this, 'register_section' ), 5 );

		foreach ( $this->generator as $key => $generator ) {
			add_action( 'wc_germanized_settings_section_before_' . $key, array( $this, 'set_wrapper' ), 1 );
			add_filter( 'woocommerce_gzd_get_settings_' . $key, array( $this, 'get_settings' ) );
			add_action( 'wc_germanized_settings_section_after_' . $key, array( $this, 'close_wrapper' ) );
			add_filter( 'woocommerce_gzd_get_sidebar_' . $key, array( $this, 'set_sidebar' ) );
			add_action( 'woocommerce_gzd_before_save_section_' . $key, array( $this, 'before_save' ), 0, 1 );
			add_action( 'woocommerce_gzd_after_save_section_' . $key, array( $this, 'save' ), 0, 1 );
		}

		add_action( 'woocommerce_settings_saved', array( $this, 'settings_update_message' ) );

		add_filter( 'woocommerce_gzdp_generator_settings', array( $this, 'add_settings_array' ), 0, 2 );
		add_filter( 'woocommerce_gzdp_generator_before_settings_save', array( $this, 'remove_settings_array' ), 0, 2 );
	}

	public function set_generator_titles() {
		$this->generator['widerruf'] = __( 'Widerruf Generator', 'woocommerce-germanized-pro' );
		$this->generator['agbs']     = __( 'AGB Generator', 'woocommerce-germanized-pro' );
	}

	public function add_settings_array( $settings, $generator ) {

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {

			    if ( ! isset( $setting['type'] ) ) {
			        continue;
                }

				if ( in_array( $setting['type'], array( 'checkbox_multiple' ) ) && strpos( $setting['id'], '[]' ) !== true )
					$settings[ $key ]['id'] .= '[]';
			}
		}

		return $settings;

	}

	public function remove_settings_array( $settings, $generator ) {

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {
				if ( in_array( $setting[ 'type' ], array( 'checkbox_multiple' ) ) )
					$settings[ $key ][ 'id' ] = str_replace( '[]', '', $setting[ 'id' ] );
			}
		}

		return $settings;

	}

	public function settings_update_message() {
		foreach ( $this->generator as $key => $generator ) {
			$show_message = false;
			$current_settings = get_option( 'woocommerce_gzdp_generator_current_settings_' . $key );
			if ( ! empty( $current_settings ) ) {
				foreach ( $current_settings as $setting => $value ) {
					$old_val = get_option( 'woocommerce_' . $setting );
					if ( is_array( $value ) && is_array( $old_val ) ) {
						foreach ( $value as $key => $val ) {
							if ( isset( $old_val[ $key ] ) && $old_val[ $key ] != $val )
								$show_message = true;
						}
					} elseif ( $old_val != $value ) {
						$show_message = true;
					}
				}
			}
			if ( $show_message )
				WC_Admin_Settings::add_error( sprintf( __( 'It seems like as if you have changed on of the settings used by the %s. Please regenerate your %s to avoid inaccurate legal texts.', 'woocommerce-germanized-pro' ), $generator, $generator ) );
		}
	}

	public function populate_settings_observal( $generator ) {
		$settings = get_option( 'woocommerce_gzdp_generator_settings_' . $generator );
		$cur_settings = array();

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $setting )
				$cur_settings[ $setting ] = get_option( 'woocommerce_' . $setting );
		}

		update_option( 'woocommerce_gzdp_generator_current_settings_' . $generator, $cur_settings );
	}

	public function register_section( $sections ) {
		
		foreach ( $this->generator as $key => $generator )
			$sections[ $key ] = $generator;
		
		return $sections;
	}

	public function set_sidebar() {
		return '<div class="wc-gzd-admin-settings-sidebar wc-gzdp-generator-sidebar"><div class="notice wc-gzdp-info inline info"></div></div>';
	}

	public function get_settings() {
		
		$GLOBALS[ 'hide_save_button' ] = true;
		
		if ( ! vendidero_helper_activated() )
			return array();
		
		global $current_section;
		
		$generator = $current_section;
		remove_filter( 'wc_germanized_show_settings_' . $generator, array( $this, 'hide_settings' ), 0 );

		$settings = array();

		if ( ! get_transient( 'woocommerce_gzdp_generator_success_' . $current_section ) )
			$settings = $this->get_generator( $generator );
		else
			add_filter( 'woocommerce_gzd_settings_wrapper_' . $current_section, array( $this, 'set_editor_class' ) );

		$settings = apply_filters( 'woocommerce_gzdp_generator_settings', $settings, $generator );

		$custom_types = WC_GZDP_Admin::instance()->get_custom_setting_types();

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {

				if ( isset( $setting['type'] ) && in_array( $setting['type'], $custom_types ) ) {
					$settings[ $key ]['type'] = 'gzdp_' . $settings[ $key ]['type'];
				}

				$setting_name = str_replace( '[]', '', $setting['id'] );

				if ( isset( $setting['default_wc_option'] ) && ! empty( $setting['default_wc_option'] ) ) {
					if ( ! get_option( $setting_name ) ) {
						update_option( $setting_name, get_option( $setting['default_wc_option'] ) );
					}
				}

				// Remove default if option exists
				if ( get_option( $setting_name ) && isset( $setting['default'] ) ) {
				    unset( $settings[ $key ]['default'] );
                }

				if ( isset( $setting['mandatory'] ) && $setting['mandatory'] ) {
					$settings[ $key ]['custom_attributes']['data-mandatory'] = '<span class="wc-gzdp-mandatory">' . __( 'mandatory', 'woocommerce-germanized-pro' ) . '</span>';
				}
			}
		}

		return ( ( ! $settings || empty( $settings ) ) ? array() : $settings );
	}

	public function set_editor_class() {
		return 'wc-gzdp-editor-result';
	}

	public function get_version( $generator ) {
		return get_option( 'woocommerce_gzdp_generator_version_' . $generator, '1.0.0' );
	}

	public function get_generator( $generator ) {
		
		$product = WC_germanized_pro()->get_vd_product();
		
		if ( ! $product->is_registered() )
			return false;
		
		$version = $this->get_version( $generator );
		$remote  = VD()->api->generator_version_check( $product, $generator );

		if ( ! $remote )
			return false;

		$remote_version    = $remote->version;
		$settings_required = $remote->settings;

		// Update required settings
		if ( ! empty( $settings_required ) )
			update_option( 'woocommerce_gzdp_generator_settings_' . $generator, $settings_required );
		else
			delete_option( 'woocommerce_gzdp_generator_settings_' . $generator );

		$generator_data = get_option( 'woocommerce_gzdp_generator_' . $generator, array() );

		$settings = array( 'api_version' => $this->api_version );
		$settings = array_merge( $settings, $this->get_options( 'woocommerce_', $settings_required ) );

		// Update generator data if remote version is newer than local version
		if ( version_compare( $version, $remote_version, "<" ) || empty( $generator_data ) ) {

			$generator_data = VD()->api->to_array( VD()->api->generator_check( $product, $generator, $settings ) );

			if ( $generator_data ) {
				update_option( 'woocommerce_gzdp_generator_' . $generator, $generator_data );
				update_option( 'woocommerce_gzdp_generator_version_' . $generator, $remote_version );
			} else {
				include_once( 'views/html-generator-error.php' );
				remove_action( 'wc_germanized_settings_section_after_' . $generator, array( $this, 'close_wrapper' ) );
				remove_action( 'wc_germanized_settings_section_before_' . $generator, array( $this, 'set_wrapper' ) );
				$GLOBALS[ 'hide_save_button' ] = true;
				return false;
			}
		}

		return ( empty( $generator_data ) ? array() : $generator_data );
	}

	public function get_options( $like = 'woocommerce_', $keys = array() ) {
		$return = array();

		if ( ! empty( $keys ) ) {
		    foreach ( $keys as $key ) {
				$return[ trim( $key ) ] = get_option( $like . $key );
			}
		}

		if ( $like == 'woocommerce_' ) {

		    $gateways = WC()->payment_gateways->payment_gateways();
			$return[ 'payment_methods' ] = array();

			if ( ! empty( $gateways ) ) {
				foreach ( $gateways as $key => $gateway ) {
					if ( $gateway->enabled == 'yes' )
						$return[ 'payment_methods' ][ $key ] = $gateway->get_title();
				}
			}

			$return['shipping_countries_restrict'] = 'no';
			$return['shipping_countries']          = WC()->countries->get_shipping_countries();

			$ship_to_countries                     = get_option( 'woocommerce_ship_to_countries' );
			$allowed_countries                     = get_option( 'woocommerce_allowed_countries' );

			if ( empty( $ship_to_countries ) ) {
			    $return['shipping_countries_restrict'] = $allowed_countries === 'specific' || $allowed_countries === 'all_except' ? 'yes' : 'no';
            } elseif( 'specific' === $ship_to_countries ) {
				$return['shipping_countries_restrict'] = 'yes';
            }
		}

		$return['url']                        = site_url();
		$return['default_delivery_time_text'] = '';
		$return['revocation_page_url']        = wc_gzd_get_page_permalink( 'revocation' );

		if ( get_option( 'woocommerce_gzd_default_delivery_time' ) ) {
		    $delivery_time_term_slug = get_option( 'woocommerce_gzd_default_delivery_time' );

		    if ( $term = get_term_by( 'id', $delivery_time_term_slug, 'product_delivery_time' ) ) {
		        $return['default_delivery_time_text'] = $term->name;
		    }
        }

		return apply_filters( 'woocommerce_gzdp_generator_settings', $return, $like, $keys );
	}

	public function set_wrapper() {
		
		if ( ! vendidero_helper_activated() ) {
			include_once( 'views/html-generator-install.php' );
			return;
		}
		
		global $current_section;
		$product = WC_germanized_pro()->get_vd_product();
		
		if ( ! $product->is_registered() )
			include_once( 'views/html-generator-error.php' );

		if ( $html = get_transient( 'woocommerce_gzdp_generator_success_' . $current_section ) )  {
			add_filter( 'wc_germanized_show_settings_' . $current_section, array( $this, 'hide_settings' ) );
			include_once( 'views/html-generator-editor.php' );
		} else {
			echo '<div class="wc-gzdp-generator wc-gzdp-generator-loading">
				<h3>' . $this->generator[ $current_section ] . '</h3>';
		}
	}

	public function hide_settings() {
		return false;
	}

	public function close_wrapper() {

		if ( ! vendidero_helper_activated() ) 
			return;
		
		global $current_section;
		
		if ( get_transient( 'woocommerce_gzdp_generator_success_' . $current_section ) ) {
			delete_transient( 'woocommerce_gzdp_generator_success_' . $current_section );
			return;
		}
		?>
		<p class="submit">
			<input type="hidden" name="generator" value="<?php echo esc_attr( $current_section ); ?>" />
			<input name="save" class="button-primary wc-gzdp-generator-submit" type="submit" value="<?php echo sprintf( _x( 'Start %s', 'generator', 'woocommerce-germanized-pro' ), $this->generator[ $current_section ] ); ?>" />
		</p>
		<div class="version"><p>Version <?php echo $this->get_version( $current_section );?></p></div>
		</div>
		<?php
	}

	public function before_save() {

		$generator = sanitize_title( $_POST[ 'generator' ] );
		
		if ( isset( $_POST[ 'generator_page_id' ] ) && get_transient( 'woocommerce_gzdp_generator_' . $generator ) ) {
			$this->save_to_page();
			add_filter( 'wc_germanized_show_settings_' . $generator, array( $this, 'hide_settings' ), 0 );
			remove_action( 'woocommerce_gzd_after_save_section_' . $generator, array( $this, 'save' ), 0 );
		}

	}

	public function save( $settings ) {
		
		$generator = sanitize_title( $_POST[ 'generator' ] );
		$product = WC_germanized_pro()->get_vd_product();

		// Delete hidden options
		$options = $this->get_options( $generator . '_' );

		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $option ) {
				if ( ! isset( $_POST[ $generator . '_' . $key ] ) )
					delete_option( $generator . '_' . $key );
			}
		}
		
		if ( ! $product->is_registered() )
			WC_Admin_Settings::add_error( _x( 'Please register WooCommerce Germanized Pro to enable the Generator.', 'generator', 'woocommerce-germanized-pro' ) );
		
		$version = $this->get_version( $generator );
		$remote = VD()->api->generator_version_check( $product, $generator );
		
		if ( ! $remote )
			return;

		if ( version_compare( $version, $remote->version, "<" ) )
			WC_Admin_Settings::add_error( _x( 'Seems like the Generator Version is not up to date. Please refresh before generating.', 'generator', 'woocommerce-germanized-pro' ) );
		
		$settings = apply_filters( 'woocommerce_gzdp_generator_before_settings_save', $settings, $generator );

		// Get data
		$data = array();
		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {
			    $setting_name = str_replace( '[]', '', $setting['id'] );

			    if ( isset( $setting['gzdp_parse_type'] ) ) {
				    if ( 'price' === $setting['gzdp_parse_type'] ) {
					    add_filter( "woocommerce_gzdp_generator_setting_{$setting_name}", array( $this, 'parse_price' ), 10, 2 );
				    } elseif ( 'page_url' === $setting['gzdp_parse_type'] ) {
					    add_filter( "woocommerce_gzdp_generator_setting_{$setting_name}", array( $this, 'parse_page_url' ), 10, 2 );
				    }
			    }

				if ( isset( $_POST[ $setting_name ] ) ) {
					$data[ $setting_name ] = apply_filters( 'woocommerce_gzdp_generator_setting_' . $setting_name, ( ! is_array( $_POST[ $setting_name ] ) ? esc_attr( $_POST[ $setting_name ] ) : (array) $_POST[ $setting_name ] ), $setting );
				}
			}
		}

		$settings = array( 'api_version' => $this->api_version );
		$settings = array_merge( $settings, $this->get_options( 'woocommerce_', $remote->settings ) );

		$result = VD()->api->generator_result_check( $product, $generator, $data, $settings );

		if ( ! $result ) {
			WC_Admin_Settings::add_error( _x( 'There seems to be a problem while generating. Is your update flatrate still active?', 'generator', 'woocommerce-germanized-pro' ) );
		} else {

			$this->populate_settings_observal( $generator );

			set_transient( 'woocommerce_gzdp_generator_' . $generator, $result, 3 * HOUR_IN_SECONDS );
			set_transient( 'woocommerce_gzdp_generator_success_' . $generator, $result, 3 * HOUR_IN_SECONDS );
		}

		remove_action( 'woocommerce_gzd_after_save_section_' . $generator, array( $this, 'save' ), 0 );
	}

	public function parse_page_url( $value, $setting ) {
		if ( ! empty( $value ) )
			return get_permalink( absint( $value ) );

		return false;
	}

	public function parse_price( $value, $setting ) {
		if ( ! empty( $value ) )
			return wc_price( $value );

		return false;
	}

	public function save_to_page() {

		$append = false;
		if ( isset( $_POST[ 'generator_page_append' ] ) && ! empty( $_POST[ 'generator_page_append' ] ) )
			$append = true;
		
		$generator = sanitize_title( $_POST[ 'generator' ] );
		$post = get_post( absint( $_POST[ 'generator_page_id' ] ) );
		
		if ( $post ) {
			
			$content = $_POST[ 'wc_gzdp_generator_content' ];
			
			if ( $append )
				$content = $post->post_content . "\n" . $content;

			// Sanitization happens here
			wp_update_post( array(
				'ID' => absint( $_POST[ 'generator_page_id' ] ),
				'post_content' => $content,
			) );
			
			update_post_meta( $post->ID, 'woocommerce_gzdp_generator_version_' . $generator, get_option( 'woocommerce_gzdp_generator_version_' . $generator ) );
		}
		
		delete_transient( 'woocommerce_gzdp_generator_' . $generator );
	}

}

WC_GZDP_Admin_Generator::instance();