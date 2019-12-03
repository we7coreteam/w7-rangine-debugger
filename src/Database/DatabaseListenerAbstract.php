<?php

namespace W7\Debugger\Database;

use W7\Core\Listener\ListenerAbstract;

abstract class DatabaseListenerAbstract extends ListenerAbstract {
	protected function log($event) {
		$sql = $event->sql ?? '';
		$bindings = (array) (empty($event->bindings) ? [] : $event->bindings);
		foreach ($bindings as $key => $binding) {
			// This regex matches placeholders only, not the question marks,
			// nested in quotes, while we iterate through the bindings
			// and substitute placeholders by suitable values.
			$regex = is_numeric($key)
				? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
				: "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

			// Mimic bindValue and only quote non-integer and non-float data types
			if (!is_int($binding) && !is_float($binding)) {
				$binding = $event->connection->getReadPdo()->quote($binding);
			}

			$sql = preg_replace($regex, $binding, $sql, 1);
		}
		itrace('connection ' . $event->connectionName . ', ' . $sql);
	}
}