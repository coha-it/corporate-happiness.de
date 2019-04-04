<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide woocommerce-gzdp-register-vat-id-form-row">
	<label for="reg_vat_id"><?php esc_html_e( 'VAT ID', 'woocommerce-germanized-pro' ); ?>&nbsp;<?php echo $required ? '<span class="required">*</span>' : ''; ?></label>
	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="vat_id" id="reg_vat_id" value="<?php echo ( ! empty( $_POST['vat_id'] ) ) ? esc_attr( wp_unslash( $_POST['vat_id'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
</p>