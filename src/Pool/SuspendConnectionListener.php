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

use W7\Core\Pool\Event\SuspendConnectionEvent;
use W7\Debugger\DebuggerTrait;

class SuspendConnectionListener extends PoolListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var SuspendConnectionEvent $event
		 */
		$event = $params[0];

		$debugger = $this->getDebugger();
		$debugger->addChildTag($event->type . '-'.  $event->name, 'suspend-connection-from-pool', 'connection ' . $event->name . ', idle count ' . $event->pool->getIdleCount() . '. wait count ' . $event->pool->getWaitCount());
	}
}
