<?php

class QueryBuilderDelete extends QueryBuilder {
	function build($db, InputParams $in) {
		$q = "DELETE FROM {$in->tbl}";
		$q .= $this->where($db, $in);
		return $q;
	}
}