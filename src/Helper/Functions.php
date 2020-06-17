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

if (!function_exists('itrace')) {
	function itrace($group, $message, $context = []) {
		$context['trace_group'] = $group;
		\W7\Core\Facades\Logger::channel('rangine-debugger')->debug($message, $context);
	}
}
