<?php

namespace W7\Debugger;

use W7\Core\Cache\Event\MakeConnectionEvent;
use W7\Core\Database\Event\QueryExecutedEvent;
use W7\Core\Database\Event\TransactionBeginningEvent;
use W7\Core\Database\Event\TransactionCommittedEvent;
use W7\Core\Database\Event\TransactionRolledBackEvent;
use W7\Core\Dispatcher\EventDispatcher;
use W7\Core\Log\LogManager;
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

class ServiceProvider extends ProviderAbstract{
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
		/**
		 * @var LogManager $logManager
		 */
		$logManager = iloader()->get(LogManager::class);
		$logManager->addChannel('rangine-debugger', 'stream', [
			'path' => RUNTIME_PATH . '/logs/trace.log',
			'level' => 'debug'
		]);
		ilogger()->channel('rangine-debugger')->pushProcessor(new TraceProcessor());
	}

	private function registerListener() {
		/**
		 * @var EventDispatcher $eventDispatcher
		 */
		$eventDispatcher = iloader()->get(EventDispatcher::class);

		$eventDispatcher->listen(ServerEvent::ON_USER_BEFORE_REQUEST, BeforeRequestListener::class);
		$eventDispatcher->listen(RouteMatchedEvent::class, RouteMatchedListener::class);
		$eventDispatcher->listen(SessionStartEvent::class, SessionStartListener::class);
		$eventDispatcher->listen(MakeConnectionEvent::class, MakeConnectionListener::class);
		$eventDispatcher->listen(MakeDatabaseConnectionEvent::class, MakeDatabaseConnectionListener::class);
		$eventDispatcher->listen(QueryExecutedEvent::class, QueryExecutedListener::class);
		$eventDispatcher->listen(TransactionBeginningEvent::class, TransactionBeginningListener::class);
		$eventDispatcher->listen(TransactionCommittedEvent::class, TransactionCommittedListener::class);
		$eventDispatcher->listen(TransactionRolledBackEvent::class, TransactionRolledBackListener::class);
		$eventDispatcher->listen(PoolMakeConnectionEvent::class, PoolMakeConnectionListener::class);
		$eventDispatcher->listen(PopConnectionEvent::class, PopConnectionListener::class);
		$eventDispatcher->listen(PushConnectionEvent::class, PushConnectionListener::class);
		$eventDispatcher->listen(ResumeConnectionEvent::class, ResumeConnectionListener::class);
		$eventDispatcher->listen(SuspendConnectionEvent::class, SuspendConnectionListener::class);
		$eventDispatcher->listen(ServerEvent::ON_USER_AFTER_REQUEST, AfterRequestListener::class);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {

	}
}
