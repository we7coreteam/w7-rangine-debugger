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

namespace W7\Debugger\Request;

use W7\Core\Listener\ListenerAbstract;
use W7\Debugger\DebuggerTrait;
use W7\Http\Message\Base\Cookie;

class AfterRequestListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		$headers = $this->getContext()->getResponse()->getHeaders();
		foreach ($headers as &$header) {
			$header = implode(';', $header);
		}

		$debugger = $this->getDebugger();
		$debugger->addTag('response-header', $headers);
		$debugger->addTag('response-cookie', $this->getCookies());
		$debugger->addTag('response-content', $this->getContext()->getResponse()->getBody()->getContents());

		if (!isCo()) {
			$message = ' url: ' . $this->getContext()->getRequest()->getUri()->getPath() . ' method: ' . $this->getContext()->getRequest()->getMethod() . "\n";
			$debugger->handle($message);
		}
	}

	public function getCookies() {
		$cookies = [];
		/**
		 * @var Cookie $cookie
		 */
		foreach ((array)$this->getContext()->getResponse()->getCookies() as $name => $cookie) {
			$cookies[] = (string)$cookie;
		}

		return $cookies;
	}
}
