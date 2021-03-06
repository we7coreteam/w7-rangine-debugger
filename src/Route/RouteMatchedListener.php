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

namespace W7\Debugger\Route;

use W7\Core\Listener\ListenerAbstract;
use W7\Core\Route\Event\RouteMatchedEvent;
use W7\Core\Route\Route;
use W7\Debugger\DebuggerTrait;

class RouteMatchedListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var RouteMatchedEvent $event
		 */
		$event = $params[0];

		if ($event->route instanceof Route) {
			$routeMiddleware = $event->route->getMiddleware();
			$routeHandler = $event->route->handler instanceof \Closure ? 'closure' : implode('@', $event->route->handler);
			$routeArgs = $event->route->getArgs();
		} else {
			$routeMiddleware = $event->route['middleware'];
			$routeHandler = $event->route['controller'] instanceof \Closure ? 'closure' : $event->route['controller'] . '@' . $event->route['method'];
			$routeArgs = $event->route['args'];
		}

		$middleWares = [];
		foreach ($routeMiddleware as $item) {
			$middleWares[] = $item['class'] . ':arg[' . implode(',', $item['arg']) . ']';
		}

		$debugger = $this->getDebugger();
		$debugger->addTag('route', [
			'handler' => $routeHandler,
			'args' => $routeArgs,
			'middleware' => implode(',', $middleWares)
		]);
	}
}
