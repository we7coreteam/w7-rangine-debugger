<?php

namespace W7\Debugger\Log;

class TraceProcessor {
	public function __invoke(array $record) {
		if (!empty($record['context']['trace_group'])) {
			$record['channel'] = $record['context']['trace_group'];
		}
		if (isset($record['context']['trace_group'])) {
			unset($record['context']['trace_group']);
		}
		if (icontext()->getContextDataByKey('trace_group')) {
			$record['channel'] = icontext()->getContextDataByKey('trace_group') . ' ' . $record['channel'];
		}

		return $record;
	}
}