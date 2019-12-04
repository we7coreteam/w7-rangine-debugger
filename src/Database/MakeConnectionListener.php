<?php

namespace W7\Debugger\Database;

use W7\Core\Database\Event\MakeConnectionEvent;

class MakeConnectionListener extends DatabaseListenerAbstract {
	public function run(...$params) {
		/**
		 * @var MakeConnectionEvent $event
		 */
		$event = $params[0];
		$this->log($event);
	}

	protected function log($event) {
		itrace('database', $event->name . ' create connection without pool');
	}
}