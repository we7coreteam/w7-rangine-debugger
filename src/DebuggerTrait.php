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

namespace W7\Debugger;

use W7\Core\Helper\Traiter\AppCommonTrait;

trait DebuggerTrait {
	use AppCommonTrait;

	public function getDebugger() {
		if (!$debugger = $this->getContext()->getContextDataByKey('debugger')) {
			$debugger = new Debugger();
			$this->getContext()->setContextDataByKey('debugger', $debugger);
			if (isCo()) {
				defer(function () use ($debugger) {
					$message = '';
					$request = $this->getContext()->getRequest();
					if ($request) {
						$message = ' url: ' . $request->getUri()->getPath() . ' method: ' . $request->getMethod() . "\n";
					}
					$debugger->handle($message);
				});
			}
		}

		return $debugger;
	}
}
