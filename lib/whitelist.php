<?php

require_once 'cmd.php';

class WhiteList {
	protected $wl = [];
	protected $commands = [
		Cmd::SELECT, Cmd::INSERT, Cmd::UPDATE, Cmd::DELETE, Cmd::SCHEMA
	];

	static function init() {
		return new WhiteList();
	}

	function table( string $name, $fields = [], $commands = [] ) {
		$this->wl[ $name ] = [
			'fields'   => $fields,
			'commands' => $commands
		];

		return $this;
	}

	function check( string $tbl, string $cmd ) {
		if ( ! isset( $this->wl[ $tbl ] ) ) {
			throw new Exception( "$tbl is not in " . implode( ',', array_keys( $this->wl ) ) );
		}

		if ( ! empty( $this->wl[ $tbl ]['commands'] ) && ! in_array( $cmd, $this->wl[ $tbl ]['commands'] ) ) {
			throw new Exception( "$cmd is not in " . implode( ',', $this->wl[ $tbl ]['commands'] ) );
		}

		if ( ! in_array( $cmd, $this->commands ) ) {
			throw new Exception( "$cmd is not in " . implode( ',', $this->commands ) );
		}
	}
}