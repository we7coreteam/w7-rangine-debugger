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

class Debugger {
	protected $name = "\n";
	protected $tags = [];
	protected static $loggerResolver;
	protected $hasHandle = false;

	public static function registerLoggerResolver(\Closure $resolver) {
		self::$loggerResolver = $resolver;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function addTag($name, $value) {
		$this->tags[$name] = $value;
	}

	public function addChildTag($tag, $childTag, $value) {
		$tagInfo = $this->tags[$tag] ?? [];
		if (!empty($tagInfo[$childTag])) {
			$tagInfo[$childTag] = (array)$tagInfo[$childTag];
			$tagInfo[$childTag][] = $value;
		} else {
			$tagInfo[$childTag] = $value;
		}

		$this->addTag($tag, $tagInfo);
	}

	public function handle() {
		if ($this->hasHandle) {
			return;
		}

		$this->hasHandle = true;
		if (!self::$loggerResolver) {
			throw new \RuntimeException('logger resolver is null');
		}
		$loggerResolver = self::$loggerResolver;
		/**
		 * @var LoggerInterface $logger
		 */
		$logger = $loggerResolver();
		$logger->debug($this->name . $this->processItems($this->tags));
	}

	private function processItems($items, $level = 0) {
		$contents = '';
		foreach ($items as $key => $item) {
			if ($level === 0) {
				$key = '[' . strtoupper($key) . "]\n";
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

	public function __destruct() {
		$this->handle();
	}
}
