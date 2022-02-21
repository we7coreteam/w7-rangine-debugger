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

namespace W7\Debugger\Redis;

use W7\Core\Listener\ListenerAbstract;
use W7\Core\Redis\Event\AfterMakeConnectionEvent;
use W7\Debugger\DebuggerTrait;

class AfterMakeConnectionListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var AfterMakeConnectionEvent $event
		 */
		$event = $params[0];

		$debugger = $this->getDebugger();
		$debugger->addChildTag('redis-' . $event->name, 'make-connection', 'create ' . $event->name . ' connection');
	}
}
