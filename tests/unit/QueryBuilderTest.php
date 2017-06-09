<?php

require_once __DIR__.'/../../conf.php';
require_once __DIR__.'/../../lib/cmd.php';
require_once __DIR__.'/../../lib/inputparams.php';
require_once __DIR__.'/../../lib/query/mysql.php';
require_once __DIR__.'/../../lib/queryBuilder/queryBuilder.php';
require_once __DIR__.'/../../lib/queryBuilder/select.php';
require_once __DIR__.'/../../lib/queryBuilder/insert.php';
require_once __DIR__.'/../../lib/queryBuilder/update.php';
require_once __DIR__.'/../../lib/queryBuilder/delete.php';

class QueryBuilderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 * @var MySQLQuery
	 */
    protected $db;

    protected function _before()
    {
	    $this->db = MySQLQuery::init( Conf::$dbname, Conf::$user, Conf::$password );
    }

    protected function _after()
    {
    }

    public function tests()
    {
    	$tests = [
    	    ['SELECT * FROM booking', ['table' => 'booking']],
    	    ['SELECT * FROM booking ORDER BY id DESC', ['table' => 'booking', 'order' => 'id DESC']],
    	    ['SELECT * FROM booking LIMIT 10,20', ['table' => 'booking', 'page' => '10,20']],
		    ['SELECT * FROM booking ORDER BY id DESC LIMIT 10,20', ['table' => 'booking', 'order' => 'id DESC', 'page' => '10,20']],
		    ['DELETE FROM booking', ['table' => 'booking', 'cmd' => 'delete']],
		    ['INSERT INTO booking (`tour_id`) VALUES ( 10)', ['table' => 'booking', 'cmd' => 'insert', 'set' => ['tour_id' => 10]]],
		    ['UPDATE booking SET `tour_id`=10', ['table' => 'booking', 'cmd' => 'update', 'set' => ['tour_id' => 10]]],
		    ['INSERT INTO booking (`tour_id`,`qty`) VALUES ( 10, 20)', ['table' => 'booking', 'cmd' => 'insert', 'set' => ['tour_id' => 10, 'qty' => 20]]],
		    ['UPDATE booking SET `tour_id`=10, `qty`=20', ['table' => 'booking', 'cmd' => 'update', 'set' => ['tour_id' => 10, 'qty' => 20]]],
	    ];

    	foreach ($tests as $t) {
		    $inp = new InputParams( $t[1] );
		    $this->db->build( $inp );
		    $this->assertEquals( $t[0], $this->db->sql() );
	    }

    }
}