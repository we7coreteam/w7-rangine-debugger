<?php
if (!function_exists('itrace')) {
	function itrace($group, $message, $context = []) {
		$context['trace_group'] = $group;
		ilogger()->channel('rangine-debugger')->debug($message, $context);
	}
}