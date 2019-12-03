<?php
if (!function_exists('itrace')) {
	function itrace($message) {
		ilogger()->channel('rangine-debugger')->debug($message);
	}
}