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
use W7\Http\Message\Server\Request;

class BeforeRequestListener extends ListenerAbstract {
	use DebuggerTrait;

	public function run(...$params) {
		/**
		 * @var Request $request
		 */
		$request = $params[0];
		$headers = $request->getHeaders();
		foreach ($headers as &$header) {
			$header = $header[0];
		}

		$debugger = $this->getDebugger();
		$message = ' url: ' . $this->getContext()->getRequest()->getUri()->getPath() . ' method: ' . $this->getContext()->getRequest()->getMethod() . "\n";
		$debugger->setName($message);
		$debugger->addTag('request-header', $headers);
		$debugger->addTag('request-data', [
			'post' => $request->getParsedBody(),
			'query' => $request->getQueryParams(),
			'input' => $request->raw()
		]);
	}
}
