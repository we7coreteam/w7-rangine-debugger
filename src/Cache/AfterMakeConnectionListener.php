<?php

/**
 * Rangine debugger
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\Debugger\Cache;

use W7\Core\Cache\Event\AfterMakeConnectionEvent;
use W7\Core\Listener\ListenerAbstract;

class AfterMakeConnectionListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var AfterMakeConnectionEvent $event
		 */
		$event = $params[0];
		$this->log($event);
	}

	protected function log(AfterMakeConnectionEvent $event) {
		itrace('cache', 'create ' . $event->name . ' connection');
	}
}
