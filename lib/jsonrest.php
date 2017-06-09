<?php

require_once 'queryBuilder/queryBuilder.php';
require_once 'queryBuilder/select.php';
require_once 'queryBuilder/insert.php';
require_once 'queryBuilder/delete.php';
require_once 'queryBuilder/update.php';

require_once 'query/mysql.php';
require_once 'whitelist.php';
require_once 'inputparams.php';

/**
 * Class JsonRest
 */
class JsonRest {
	/**
	 * @var Query
	 */
	protected $qry;
	/**
	 * @var WhiteList
	 */
	protected $wl;
	/**
	 * @var InputParams
	 */
	protected $input;

	static function init( Query $qry, WhiteList $wl ) {
		return new JsonRest( $qry, $wl );
	}

	function __construct( Query $qry, WhiteList $wl ) {
		$this->qry = $qry;
		$this->wl  = $wl;
	}

	function parseQuery() {
		if ( ! isset( $_REQUEST['json'] ) || trim( $_REQUEST['json'] ) == '' ) {
			throw new Exception( "`json` GET or POST parameter is empty" );
		}

		$json = json_decode( urldecode($_REQUEST['json']), true );
		if ( ! $json ) {
			//convert to error string
			switch ( json_last_error() ) {
				case JSON_ERROR_DEPTH:
					$err = 'Maximum depth exceeded!';
					break;
				case JSON_ERROR_STATE_MISMATCH:
					$err = 'Underflow or the modes mismatch!';
					break;
				case JSON_ERROR_CTRL_CHAR:
					$err = 'Unexpected control character found';
					break;
				case JSON_ERROR_SYNTAX:
					$err = 'Malformed JSON';
					break;
				case JSON_ERROR_UTF8:
					$err = 'Malformed UTF-8 characters found!';
					break;
				default:
					$err = 'Unknown error!';
					break;
			}
			throw new Exception( $err );
		}

		return new InputParams($json);
	}

	function run() {
		$out = [];
		try {
			$input = $this->parseQuery();

			$this->wl->check( $input->tbl, $input->cmd );

			$this->qry->build( $input );

			$this->qry->execute();

			$out['numrows'] = $this->qry->numRows();

			if ( $input->cmd == Cmd::SELECT ) {
				foreach ( $this->qry->fetch() as $row ) {
					$out['rows'][] = $row;
				}
			} else if ( $input->cmd == Cmd::SCHEMA ) {

			}


		} catch ( Exception $e ) {
			$out['error'] = $e->getMessage();
		}


		header("Content-type:application/json");
		echo json_encode($out);
	}

}
