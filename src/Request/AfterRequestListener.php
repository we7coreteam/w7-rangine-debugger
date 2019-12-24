<?php

namespace W7\Debugger\Request;

use W7\Core\Listener\ListenerAbstract;

class AfterRequestListener extends ListenerAbstract {
	public function run(...$params) {
		$this->log();
	}

	public function getCookies() {
		$cookies = [];
		foreach ((array)icontext()->getResponse()->getCookies() as $name => $cookie) {
			$cookies[] = [
				'name' => $cookie->getName(),
				'value' => $cookie->getValue() ? : 1,
				'expire' => $cookie->getExpires(),
				'path' => $cookie->getPath(),
				'domain' => $cookie->getDomain(),
				'secure' => $cookie->isSecure(),
				'http_only' => $cookie->isHttpOnly()
			];
		}

		return $cookies;
	}

	protected function log() {
		$beginMemory = icontext()->getContextDataByKey('memory_usage');
		$memoryUsage = memory_get_usage() - $beginMemory;
		$time = round(microtime(true) - icontext()->getContextDataByKey('time'), 3);

		itrace('response-header', serialize(icontext()->getResponse()->getHeaders()));
		itrace('response-cookies', serialize($this->getCookies()));
		itrace('response-content', icontext()->getResponse()->getBody()->getContents());
		itrace('end-request', 'memory_usage: ' . round($memoryUsage/1024/1024, 2).'MB' . ', time: ' . $time . 's');
	}
}