<?php

abstract class QueryBuilder {
	abstract function build($db, InputParams $in);

	function where($db, InputParams $in) {
		$q = '';
		if ( ! empty( $in->flt ) ) {
			$q   .= ' WHERE ';
		}
		return $q;
	}

}