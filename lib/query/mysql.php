<?php

require_once 'query.php';

class MySQLQuery extends Query {

	/**
	 * @var mysqli
	 */
	protected $db;

	protected $dbname;
	protected $user;
	protected $password;
	protected $host;
	protected $port;

	protected $qry;
	protected $cmd;

	/**
	 * @var mysqli_result
	 */
	protected $result;

	function __construct( $dbname, $user, $password, $host = 'localhost', $port = '3306' ) {
		$this->dbname   = $dbname;
		$this->user     = $user;
		$this->password = $password;
		$this->host     = $host;
		$this->port     = $port;

		$this->db = new mysqli( $this->host, $this->user, $this->password, $this->dbname, $this->port );
		if ( mysqli_connect_errno() ) {
			throw new Exception( sprintf( "Connect failed: %s\n", mysqli_connect_error() ) );
		}
		$this->db->set_charset( 'utf8' );
	}

	function __destruct() {
		$this->close();
	}

	static function init( $dbname, $user, $password, $host = 'localhost', $port = '3306' ) {
		return new MySQLQuery( $dbname, $user, $password, $host, $port );
	}

	function build( InputParams $in ) {
		if ( $in->id && ! is_int( $in->id ) ) {
			throw new Exception( 'ID is not Int' );
		}

		////////// query builder
		$this->cmd = $in->cmd;

//		$qb = null;
		switch ( $in->cmd ) {
			case Cmd::SELECT: $qb = new QueryBuilderSelect(); break;
			case Cmd::INSERT: $qb = new QueryBuilderInsert(); break;
			case Cmd::UPDATE: $qb = new QueryBuilderUpdate(); break;
			case Cmd::DELETE: $qb = new QueryBuilderDelete(); break;
//			case Cmd::SCHEMA:
//				break;
			default: throw new Exception( 'Unknown command: '.$in->cmd );
		}

		$this->qry = $qb->build($this->db, $in);
	}

	function execute() {
		if ( $this->qry == '' ) {
			throw new Exception( "Query is empty" );
		}

		$this->result = $this->db->query($this->qry);
		if ( ! $this->result ) {
			throw new Exception( sprintf( "SQL failed: %s\n", $this->db->error ) );
		}
	}

	function fetch() : Generator {
		while ( $row = $this->result->fetch_assoc() ) {
			yield $row;
		}
	}

	function numRows():int {
		if ( $this->cmd == Cmd::SELECT ) {
			return $this->result->num_rows;
		} else {
			return $this->db->affected_rows;
		}
	}

	function close() {
		if ( $this->db ) mysqli_close( $this->db );
	}

	function sql() {
		return $this->qry;
	}

	function execScript($sql) {
		$this->db->multi_query($sql);
	}
}