<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class WC_GZDP_Admin_Legal_Checkboxes {

	/**
	 * Single instance of WooCommerce Germanized Main Class
	 *
	 * @var object
	 */
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_filter( 'woocommerce_gzd_admin_legal_checkbox', array( $this, 'maybe_add_checkbox' ), 10, 2 );
		add_action( 'woocommerce_gzd_before_save_legal_checkbox', array( $this, 'adjust_new_saving' ), 10, 1 );
		add_filter( 'woocommerce_gzd_admin_new_legal_checkbox_link', array( $this, 'adjust_new_link' ), 10 );
		add_filter( 'woocommerce_gzd_legal_checkbox_fields_before_titles', array( $this, 'additional_fields' ), 10, 2 );
	}

	protected function to_assoc( $arr ) {
		foreach( $arr as $key => $value ) {
			unset( $arr[ $key ] );
			$arr[ $value ] = $value;
		}

		return $arr;
	}

	public function additional_fields( $fields, $checkbox ) {

		$classes = $checkbox->get_option( 'html_classes', array() );
		$wrapper_classes = $checkbox->get_option( 'html_wrapper_classes', array() );

		$additional_fields = array(
			array(
				'title'             => __( 'Template', 'woocommerce-germanized-pro' ),
				'type'              => 'text',
				'id'                => $checkbox->get_form_field_id( 'template_name' ),
				'desc'              => sprintf( __( 'Override the template within your (child) theme: %s', 'woocommerce-germanized-pro' ), '<br/><code>child-theme/woocommerce-germanized/' . $checkbox->get_template_name() . '</code>' ),
				'desc_tip'          => __( 'Adjust the PHP template being loaded for this checkbox.', 'woocommerce-germanized-pro' ),
				'default'           => $checkbox->get_template_name(),
			),

			array(
				'title'             => __( 'HTML Id', 'woocommerce-germanized-pro' ),
				'type'              => 'text',
				'id'                => $checkbox->get_form_field_id( 'html_id' ),
				'desc_tip'          => __( 'Adjust the PHP template being loaded for this checkbox.', 'woocommerce-germanized-pro' ),
				'default'           => $checkbox->get_html_id(),
			),

			array(
				'title'             => __( 'HTML Name', 'woocommerce-germanized-pro' ),
				'type'              => 'text',
				'id'                => $checkbox->get_form_field_id( 'html_name' ),
				'desc_tip'          => __( 'Adjust the HTML name attribute.', 'woocommerce-germanized-pro' ),
				'default'           => $checkbox->get_html_name(),
			),

			array(
				'title'             => __( 'HTML Classes', 'woocommerce-germanized-pro' ),
				'type'              => 'multiselect',
				'class'             => 'wc-gzd-enhanced-tags',
				'id'                => $checkbox->get_form_field_id( 'html_classes' ),
				'desc_tip'          => __( 'Add or edit classes for the input checkbox. Add classes by typing in a term and then select that term from the dropdown list.', 'woocommerce-germanized-pro' ),
				'default'           => $this->to_assoc( $classes ),
				'options'           => $this->to_assoc( $classes ),
			),

			array(
				'title'             => __( 'HTML Wrapper Classes', 'woocommerce-germanized-pro' ),
				'type'              => 'multiselect',
				'class'             => 'wc-gzd-enhanced-tags',
				'id'                => $checkbox->get_form_field_id( 'html_wrapper_classes' ),
				'desc_tip'          => __( 'Add or edit classes for the wrapper p-tag. Add classes by typing in a term and then select that term from the dropdown list.', 'woocommerce-germanized-pro' ),
				'default'           => $this->to_assoc( $wrapper_classes ),
				'options'           => $this->to_assoc( $wrapper_classes ),
			),
		);

		return array_merge( $fields, $additional_fields );
	}

	public function adjust_new_link() {
		return admin_url( 'admin.php?page=wc-settings&tab=germanized&section=checkboxes&checkbox_id=new' );
	}

	public function adjust_new_saving( $checkbox ) {
		if ( $checkbox->is_new() ) {
			// Parse admin name
			$name = ( isset( $_POST['woocommerce_gzd_legal_checkboxes_settings_new_admin_name'] ) ? wc_clean( $_POST['woocommerce_gzd_legal_checkboxes_settings_new_admin_name'] ) : 'new' );
			$id = $this->generate_id_by_name( $name );
			$checkbox->set_id( $id );

			// Replace $_POST keys with new id
			foreach( $_POST as $key => $value ) {
				if ( strpos( $key, 'woocommerce_gzd_legal_checkboxes_settings_new' ) !== false ) {
					$new_key = str_replace( 'woocommerce_gzd_legal_checkboxes_settings_new', 'woocommerce_gzd_legal_checkboxes_settings_' . $id, $key );
					$_POST[ $new_key ] = $value;
					unset( $_POST[ $key ] );
				}
			}

			add_action( 'woocommerce_gzd_after_save_legal_checkbox', array( $this, 'redirect_new' ), 10, 1 );
		}
	}

	public function redirect_new( $checkbox ) {
		wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=germanized&section=checkboxes&checkbox_id=' . $checkbox->get_id() ) );
	}

	public function maybe_add_checkbox( $checkbox, $checkbox_id ) {

		if ( ! $checkbox ) {
			$checkbox = new WC_GZD_Legal_Checkbox( 'new', array(
				'admin_name' => __( 'New', 'woocommerce-germanized-pro' ),
			) );

			$locations = array_keys( WC_GZD_Legal_Checkbox_Manager::instance()->get_locations() );
			$checkbox->set_supporting_locations( $locations );
		}

		return $checkbox;
	}

	protected function generate_id_by_name( $name = '', $postfix = '' ) {
		$id = str_replace( '-', '_', sanitize_title( ( $name === '' ? 'new' : $name ) ) );

		if ( ! empty( $postfix ) ) {
			$postfix = absint( $postfix );
			$id = $id . '_' . $postfix;
		}

		if ( $exists = WC_GZD_Legal_Checkbox_Manager::instance()->get_checkbox( $id ) || 'new' === $id ) {
			if ( empty( $postfix ) ) {
				$postfix = 0;
			}

			$id = $this->generate_id_by_name( $id, ++$postfix );
		}

		return $id;
	}
}

WC_GZDP_Admin_Legal_Checkboxes::instance();