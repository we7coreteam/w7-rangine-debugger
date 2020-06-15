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

use W7\Core\Cache\Event\MakeConnectionEvent;
use W7\Core\Database\Event\QueryExecutedEvent;
use W7\Core\Database\Event\TransactionBeginningEvent;
use W7\Core\Database\Event\TransactionCommittedEvent;
use W7\Core\Database\Event\TransactionRolledBackEvent;
use W7\Core\Pool\Event\PopConnectionEvent;
use W7\Core\Pool\Event\PushConnectionEvent;
use W7\Core\Pool\Event\ResumeConnectionEvent;
use W7\Core\Pool\Event\SuspendConnectionEvent;
use W7\Core\Provider\ProviderAbstract;
use W7\Core\Route\Event\RouteMatchedEvent;
use W7\Core\Server\ServerEvent;
use W7\Core\Session\Event\SessionStartEvent;
use W7\Debugger\Cache\MakeConnectionListener;
use W7\Debugger\Database\QueryExecutedListener;
use W7\Debugger\Database\TransactionBeginningListener;
use W7\Debugger\Database\TransactionCommittedListener;
use W7\Debugger\Database\TransactionRolledBackListener;
use W7\Debugger\Log\TraceProcessor;
use W7\Debugger\Pool\PopConnectionListener;
use W7\Debugger\Pool\PushConnectionListener;
use W7\Debugger\Pool\ResumeConnectionListener;
use W7\Debugger\Pool\SuspendConnectionListener;
use W7\Debugger\Database\MakeConnectionListener as MakeDatabaseConnectionListener;
use W7\Core\Database\Event\MakeConnectionEvent as MakeDatabaseConnectionEvent;
use W7\Core\Pool\Event\MakeConnectionEvent as PoolMakeConnectionEvent;
use W7\Debugger\Pool\MakeConnectionListener as PoolMakeConnectionListener;
use W7\Debugger\Request\AfterRequestListener;
use W7\Debugger\Request\BeforeRequestListener;
use W7\Debugger\Route\RouteMatchedListener;
use W7\Debugger\Session\SessionStartListener;

class ServiceProvider extends ProviderAbstract {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		if ((ENV & DEBUG) != DEBUG) {
			return false;
		}

		$this->registerLog();
		$this->registerListener();
	}

	private function registerLog() {
		if (empty($this->config->get('log.channel.rangine-debugger'))) {
			$this->config->set('log.channel.rangine-debugger', [
				'driver' => 'stream',
				'path' => RUNTIME_PATH . '/logs/trace.log',
				'level' => 'debug'
			]);
		}

		$this->logger->channel('rangine-debugger')->pushProcessor(new TraceProcessor());
	}

	private function registerListener() {
		$this->registerEvent(ServerEvent::ON_USER_BEFORE_REQUEST, BeforeRequestListener::class);
		$this->registerEvent(RouteMatchedEvent::class, RouteMatchedListener::class);
		$this->registerEvent(SessionStartEvent::class, SessionStartListener::class);
		$this->registerEvent(MakeConnectionEvent::class, MakeConnectionListener::class);
		$this->registerEvent(MakeDatabaseConnectionEvent::class, MakeDatabaseConnectionListener::class);
		$this->registerEvent(QueryExecutedEvent::class, QueryExecutedListener::class);
		$this->registerEvent(TransactionBeginningEvent::class, TransactionBeginningListener::class);
		$this->registerEvent(TransactionCommittedEvent::class, TransactionCommittedListener::class);
		$this->registerEvent(TransactionRolledBackEvent::class, TransactionRolledBackListener::class);
		$this->registerEvent(PoolMakeConnectionEvent::class, PoolMakeConnectionListener::class);
		$this->registerEvent(PopConnectionEvent::class, PopConnectionListener::class);
		$this->registerEvent(PushConnectionEvent::class, PushConnectionListener::class);
		$this->registerEvent(ResumeConnectionEvent::class, ResumeConnectionListener::class);
		$this->registerEvent(SuspendConnectionEvent::class, SuspendConnectionListener::class);
		$this->registerEvent(ServerEvent::ON_USER_AFTER_REQUEST, AfterRequestListener::class);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
	}
}
