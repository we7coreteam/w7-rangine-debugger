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

namespace W7\Debugger\Pool;

use W7\Core\Pool\Event\PushConnectionEvent;
use W7\Debugger\DebuggerTrait;

class PushConnectionListener extends PoolListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var PushConnectionEvent $event
		 */
		$event = $params[0];

		$debugger = $this->getDebugger();
		$debugger->addChildTag($event->type . '-'.  $event->name, 'push-connection-to-pool', 'connection ' . $event->name . ', idle count ' . $event->pool->getIdleCount() . '. busy count ' . $event->pool->getBusyCount());
	}
}
