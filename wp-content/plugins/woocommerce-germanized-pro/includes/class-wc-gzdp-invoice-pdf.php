<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_GZDP_Invoice_PDF extends WC_GZDP_PDF {

	public function set_type() {
		$this->type = 'invoice';
	}

	public function set_css() {
		$this->css = apply_filters( 'woocommerce_gzdp_pdf_css', '
    		<style>
    			* {
    				color: ' . $this->object->get_color_option( 'font_color' ) . ';
    			}
    			body {
    				font-size: ' . $this->object->get_option( 'font_size' ) . 'pt;
    			}
 				p {
 					line-height: 1.5;
 				}
				.static {
					line-height: 1.5;
				}
				.address-header {
					font-size: ' . ( $this->object->get_option( 'font_size' ) - 1 ) . 'pt;
				}
				.number {
					font-size: 12pt;
				}
				.number-smaller {
					font-size: 10pt;
					color: #AAAAAA;
				}
				.number .invoice-desc {
					font-size: 16pt;
					font-weight: bold;
				}
				table.main {
					cellpadding: 4px;
					line-height: 2;
				}
				.right {
					float: right;
				}
				table.main tr.header th {
					border: 0.5pt solid ' . $this->object->get_color_option( 'table_border_color' ) . ';
					background-color: ' . $this->object->get_color_option( 'table_header_bg' ) . ';
					color: ' . $this->object->get_color_option( 'table_header_font_color' ) . ';
					text-align: center;
				}
				table.main tr.data td {
					border: 0.5pt solid ' . $this->object->get_color_option( 'table_border_color' ) . ';
					text-align: center;
					vertical-align: middle;
				}
				table.main tr .first {
					text-align: left;
				}
				table.main tr .last {
					text-align: right;
				}
				table.main tr.footer th {
					text-align: right;
				}
				.small, small {
					font-size: ' . $this->object->get_option( 'font_size' ) . 'pt;
				}
				table.main tr.footer td {
					text-align: right;
				}
				table.main tr.footer-total th {
					font-weight: bold;
				}
				table.main tr.footer-total td {
					font-weight: bold;
					text-decoration: underline;
				}
				.footer-page-numbers {
					font-size: ' . ( $this->object->get_option( 'font_size' ) - 1 ) . 'pt;
					text-align: right;
				}
				.footer-custom {
					border-top: 1px solid ' . $this->object->get_color_option( 'table_border_color' ) . ';
					font-size: ' . ( $this->object->get_option( 'font_size' ) - 1 ) . 'pt;
				}
				.tax_label {
					display: none;
				}
				.footer-custom table {

				}
				table.footer {
					font-size: ' . $this->object->get_option( 'font_size' ) . 'pt;
				}
				' . $this->object->get_option( 'custom_css' ) . '
			</style>
    	' );
	}

}