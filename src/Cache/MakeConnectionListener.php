<?php

namespace W7\Debugger\Cache;

use W7\Core\Cache\Event\MakeConnectionEvent;
use W7\Core\Listener\ListenerAbstract;

class MakeConnectionListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var MakeConnectionEvent $event
		 */
		$event = $params[0];
		$this->log($event);
	}

	protected function log(MakeConnectionEvent $event) {
		itrace($event->name . ' create connection without pool');
	}
}