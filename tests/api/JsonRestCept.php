<?php

require_once __DIR__.'/../../conf.php';
require_once __DIR__.'/../../lib/cmd.php';
require_once __DIR__.'/../../lib/inputparams.php';
require_once __DIR__.'/../../lib/query/mysql.php';

$db = MySQLQuery::init( Conf::$dbname, Conf::$user, Conf::$password );
$script = file_get_contents(__DIR__.'/../../example.sql');
$db->execScript($script);

$I = new ApiTester($scenario);
$I->wantTo('perform actions and see result');

$tests = [
	['{"error":"sdjfhjsdhf is not in booking,tour"}', ['table' => 'sdjfhjsdhf']],
	['{"numrows":0}', ['table' => 'booking']],
	['{"numrows":1}', ['table' => 'tour', 'cmd' => 'insert', 'set' => ['description' => 'tour 1', 'qty' => 20, 'active'=> 1]]],
	['{"numrows":1}', ['table' => 'booking', 'cmd' => 'insert', 'set' => ['tour_id' => 1, 'qty' => 20]]],
	['{"numrows":1,"rows":[{"id":"1","tour_id":"1","qty":"20","date_from":null,"date_to":null}]}', ['table' => 'booking']],
];

foreach ($tests as $t) {
	$json = json_encode( $t[1] );

	$I->sendPOST( '/index.php', [ 'json' => $json ] );
	$I->seeResponseCodeIs( \Codeception\Util\HttpCode::OK ); // 200
	$I->seeResponseIsJson();
	$I->seeResponseContains( $t[0] );
}