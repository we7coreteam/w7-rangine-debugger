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

namespace W7\Debugger\Database;

use W7\Core\Database\Event\AfterMakeConnectionEvent;

class AfterMakeConnectionListener extends DatabaseListenerAbstract {
	public function run(...$params) {
		/**
		 * @var AfterMakeConnectionEvent $event
		 */
		$event = $params[0];

		$debugger = $this->getDebugger();
		$debugger->addChildTag('database-' . $event->name, 'make-connection', 'create ' . $event->name . ' connection');
	}
}
