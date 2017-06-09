<?php

abstract class Query {
	abstract static function init( $db, $user, $password, $host = 'localhost', $port = '3306' );
	abstract function build( InputParams $in );
	abstract function execute();
	abstract function fetch(): Generator;
	abstract function numRows(): int;
	abstract function close();
	abstract function sql();
	abstract function execScript($sql);

}