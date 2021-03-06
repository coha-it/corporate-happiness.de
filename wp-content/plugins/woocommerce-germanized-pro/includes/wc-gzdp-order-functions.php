<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

function wc_gzdp_order_needs_confirmation( $order_id ) {
	$order = ( is_object( $order_id ) ? $order_id : wc_get_order( $order_id ) ); 
	return wc_gzd_get_crud_data( $order, 'order_needs_confirmation' ) ? true : false;
}