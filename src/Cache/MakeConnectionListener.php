<?php

/**
 * WeEngine Api System
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\Debugger\Cache;

use W7\Core\Cache\Event\MakeConnectionEvent;
use W7\Core\Listener\ListenerAbstract;
use W7\Debugger\DebuggerTrait;

class MakeConnectionListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var MakeConnectionEvent $event
		 */
		$event = $params[0];

		$debugger = $this->getDebugger();
		$debugger->addChildTag('cache', 'make_connection', 'create ' . $event->name . ' connection');
	}
}
