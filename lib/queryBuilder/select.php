<?php

class QueryBuilderSelect extends QueryBuilder {
	function build($db, InputParams $in) {
		$q = "SELECT {$in->get} FROM {$in->tbl}";

		$q .= $this->where($db, $in);

		if ( trim( $in->ord ) != '' ) {
			$q .= ' ORDER BY ' . $in->ord;
		}

		if ( trim( $in->page ) != '' ) {
			$q .= ' LIMIT ' . $in->page;
		}

		return $q;
	}
}
