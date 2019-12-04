<?php

namespace W7\Debugger\Request;

use W7\Core\Listener\ListenerAbstract;

class AfterRequestListener extends ListenerAbstract {
	public function run(...$params) {
		$this->log();
	}

	protected function log() {
		$beginMemory = icontext()->getContextDataByKey('memory_usage');
		$memoryUsage = memory_get_usage() - $beginMemory;
		$time = round(microtime(true) - icontext()->getContextDataByKey('time'), 3);
		itrace('end-request', 'memory_usage: ' . round($memoryUsage/1024/1024, 2).'MB' . ', time: ' . $time . 's');
	}
}