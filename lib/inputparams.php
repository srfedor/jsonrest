<?php

class InputParams {
	public $tbl;

	public $id;
	public $cmd;
	public $flt;
	public $set;
	public $get;

	public $ord;
	public $page;

	function __construct($input) {
		$this->tbl = trim( $input['table'] );

		$this->id  = $input['id'] ?? false;
		$this->cmd = $input['cmd'] ?? 'select';
		$this->flt = $input['flt'] ?? [];
		$this->set = $input['set'] ?? [];
		$this->get = $input['get'] ?? '*';

		$this->ord  = $input['order'] ?? false;
		$this->page = $input['page'] ?? false;
	}

}