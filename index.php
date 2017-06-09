<?php

error_reporting(E_ALL); ini_set('display_errors', 1);

require_once 'lib/jsonrest.php';

require_once 'conf.php';

JsonRest::init(
	MySQLQuery::init( Conf::$dbname, Conf::$user, Conf::$password ),
	WhiteList::init()
	         ->table( 'booking' )
	         ->table( 'tour', [ 'start_from' ], [ Cmd::SELECT, Cmd::INSERT ] )
)->run();





