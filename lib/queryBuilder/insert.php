<?php

class QueryBuilderInsert extends QueryBuilder {
	function build($db, InputParams $in) {
		if ( empty( $in->set ) ) {
			throw new Exception( "`set` cannot be empty if insert" );
		}

		$keys = array_map( function ( $k ) {
			return "`$k`";
		}, array_keys( $in->set ) );

		$q = "INSERT INTO {$in->tbl} (";
		$q .= implode( ',', $keys );
		$q .= ") VALUES (";
		$sep = '';
		foreach ( $in->set as $field => $val ) {
			if ( is_int( $val ) || is_float( $val ) ) {
				$q .= "$sep $val";
			} else {
				$q .= "$sep '" . mysqli_real_escape_string( $db, $val ) . "'";
			}
			$sep = ',';
		}

		$q .= ")";
		return $q;
	}
}