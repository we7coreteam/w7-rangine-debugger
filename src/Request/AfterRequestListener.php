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

class AfterRequestListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		$debugger = $this->getDebugger();
		$debugger->addTag('response-header', $this->getContext()->getResponse()->getHeaders());
		$debugger->addTag('response-cookie', $this->getCookies());
		$debugger->addTag('response-content', $this->getContext()->getResponse()->getBody()->getContents());
		$debugger->handle(' url: ' . $this->getContext()->getRequest()->getUri()->getPath() . ' method: ' . $this->getContext()->getRequest()->getMethod() . "\n");
	}

	public function getCookies() {
		$cookies = [];
		foreach ((array)$this->getContext()->getResponse()->getCookies() as $name => $cookie) {
			$cookies[] = [
				'name' => $cookie->getName(),
				'value' => $cookie->getValue() ? : 1,
				'expire' => $cookie->getExpiresTime(),
				'path' => $cookie->getPath(),
				'domain' => $cookie->getDomain(),
				'secure' => $cookie->isSecure(),
				'http_only' => $cookie->isHttpOnly()
			];
		}

		return $cookies;
	}
}
