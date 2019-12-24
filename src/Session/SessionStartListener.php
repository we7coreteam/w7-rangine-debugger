<?php

namespace W7\Debugger\Session;

use W7\Core\Listener\ListenerAbstract;
use W7\Core\Session\Event\SessionStartEvent;
use W7\Core\Session\Session;

class SessionStartListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var SessionStartEvent $sessionEvent
		 */
		$sessionEvent = $params[0];
		$this->log($sessionEvent->session);
	}

	private function log(Session $session) {
		itrace('session-start', 'session_id: ' . $session->getId() . ', session_data: ' . serialize($session->all()));
	}
}