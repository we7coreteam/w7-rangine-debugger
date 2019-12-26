<?php

namespace W7\Debugger\Request;

use W7\Core\Listener\ListenerAbstract;
use W7\Http\Message\Server\Request;

class BeforeRequestListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var Request $request
		 */
		$request = $params[0];
		icontext()->setRequest($request);
		icontext()->setContextDataByKey('trace_group', '[ url: ' . $request->getUri()->getPath() . ' ]');
		icontext()->setContextDataByKey('memory_usage', memory_get_usage());
		icontext()->setContextDataByKey('time', microtime(true));

		$this->log($request);
	}

	protected function log(Request $request) {
		itrace('begin-request', 'method: ' . $request->getMethod() . ', url: ' . $request->getUri()->getPath() . ', ip: ' . serialize(getClientIp()) . ', time: ' . date('Y-m-d H:i:s'));
		itrace('request-header', serialize($request->getHeaders()));
		itrace('request-data', 'post: ' . serialize($request->getParsedBody()) . ', query: ' . serialize($request->getQueryParams()));
	}
}