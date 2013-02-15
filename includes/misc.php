<?php

/**
 * Makes from an array of arrays a flat array.
 *
 * @param array $array the arra to flatten
 * @return array flattenned array
 */
function gp_array_flatten( $array ) {
    $res = array();
    foreach( $array as $value ) {
        $res = array_merge( $res, is_array( $value )? gp_array_flatten( $value ) : array( $value ) );
    }
    return $res;
}

/**
 * Passes the message set through the next redirect.
 *
 * Works best for edit requests, which want to pass error message or notice back to the listing page.
 *
 * @param string $message The message to be passed
 * @param string $key Optional. Key for the message. You can pass several messages under different keys.
 * A key has one message. The default is 'notice'.
 */
function gp_notice_set( $message, $key = 'notice' ) {
	GlotPress::$redirect_notices[ $key ] = $message;
}

/**
 * Retrieves a notice message, set by {@link gp_notice()}
 *
 * @param string $key Optional. Message key. The default is 'notice'
 */
function gp_notice( $key = 'notice' ) {
	return isset( GlotPress::$redirect_notices[ $key ] )? GlotPress::$redirect_notices[ $key ] : '';
}

if ( !function_exists( 'gp_generate_password' ) ) :
/**
 * Generates a random password drawn from the defined set of characters
 * @return string the password
 */
function gp_generate_password( $length = 12, $special_chars = true ) {
	return WP_Pass::generate_password( $length, $special_chars );
}
endif;

/**
 * Returns an array of arrays, where the i-th array contains the i-th element from
 * each of the argument arrays. The returned array is truncated in length to the length
 * of the shortest argument array.
 *
 * The function works only with numerical arrays.
 */
function gp_array_zip() {
	$args = func_get_args();
	if ( !is_array( $args ) ) {
		return false;
	}
	if ( empty( $args ) ) {
		return array();
	}
	$res = array();
	foreach ( $args as &$array ) {
		if ( !is_array( $array) ) {
			return false;
		}
		reset( $array );
	}
	$all_have_more = true;
	while (true) {
		$this_round = array();
		foreach ( $args as &$array ) {
			$all_have_more = ( list( $key, $value ) = each( $array ) );
			if ( !$all_have_more ) {
				break;
			}
			$this_round[] = $value;
		}
		if ( $all_have_more ) {
			$res[] = $this_round;
		} else {
			break;
		}
	}
	return $res;
}

function gp_array_any( $callback, $array ) {
	foreach( $array as $item ) {
		if ( $callback( $item ) ) {
			return true;
		}
	}
	return false;
}

function gp_array_all( $callback, $array ) {
	foreach( $array as $item ) {
		if ( !$callback( $item ) ) {
			return false;
		}
	}
	return true;
}

function gp_error_log_dump( $value ) {
	if ( is_array( $value ) || is_object( $value ) ) {
		$value = print_r( $value, true );
	}
	error_log( $value );
}

function gp_object_has_var( $object, $var_name ) {
	return in_array( $var_name, array_keys( get_object_vars( $object ) ) );
}