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

namespace W7\Debugger;

use Psr\Log\LoggerInterface;
use W7\Core\Helper\Traiter\InstanceTraiter;

class Debugger {
	use InstanceTraiter;

	protected $tags = [];
	protected static $loggerResolver;

	public static function registerLoggerResolver(\Closure $resolver) {
		self::$loggerResolver = $resolver;
	}

	public function addTag($name, $value) {
		$this->tags[$name] = $value;
	}

	public function addChildTag($tag, $childTag, $value) {
		$tagInfo = $this->tags[$tag] ?? [];
		$tagInfo[$childTag] = $value;
		$this->addTag($tag, $tagInfo);
	}

	public function handle($message = '') {
		if (!self::$loggerResolver) {
			throw new \RuntimeException('logger resolver is null');
		}
		$loggerResolver = self::$loggerResolver;
		/**
		 * @var LoggerInterface $logger
		 */
		$logger = $loggerResolver();
		$logger->debug($message . $this->processItems($this->tags));
	}

	private function processItems($items, $level = 0) {
		$contents = '';
		foreach ($items as $key => $item) {
			if ($level === 0) {
				$key = '[\'' . $key . "']\n";
			} else {
				$key = '\'' . $key . "': ";
			}

			$pad = \str_pad('', $level, '	');
			$contents .= $pad . $key;

			if (is_array($item)) {
				$contents .= "{\n" . $this->processItems($item, $level + 1) . $pad . "}\n";
			} else {
				$contents .= '\'' . $item . '\'' . ",\n";
			}
		}

		return $contents;
	}
}
