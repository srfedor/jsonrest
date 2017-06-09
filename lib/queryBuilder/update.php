<?php

class QueryBuilderUpdate extends QueryBuilder {
	function build($db, InputParams $in) {
		if ( empty( $in->set ) ) {
			throw new Exception( "`set` cannot be empty if update" );
		}
		$q   = "UPDATE {$in->tbl} SET";
		$sep = '';
		foreach ( $in->set as $field => $val ) {
			if ( is_int( $val ) || is_float( $val ) ) {
				$q .= "$sep `$field`=" . $val;
			} else {
				$q .= "$sep `$field`='" . mysqli_real_escape_string( $db, $val ) . "'";
			}
			$sep = ',';
		}
		return $q;
	}
}
