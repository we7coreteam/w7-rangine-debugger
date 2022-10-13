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

namespace W7\Debugger\Request;

use W7\Core\Listener\ListenerAbstract;
use W7\Debugger\DebuggerTrait;
use W7\Http\Message\Base\Cookie;
use W7\Http\Message\Server\Response;

class AfterRequestListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var Response $response
		 */
		$response = $params[1];
		$headers = $response->getHeaders();
		foreach ($headers as &$header) {
			$header = implode(';', $header);
		}
		$cookies = [];
		/**
		 * @var Cookie $cookie
		 */
		foreach ((array)$response->getCookies() as $name => $cookie) {
			$cookies[] = (string)$cookie;
		}

		$debugger = $this->getDebugger();
		$debugger->addTag('response-header', $headers);
		$debugger->addTag('response-cookie', $cookies);
		$debugger->addTag('response-content', $response->getBody()->getContents());
	}
}
