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

use W7\App;
use W7\Core\Database\Event\QueryExecutedEvent;
use W7\Core\Database\Event\TransactionBeginningEvent;
use W7\Core\Database\Event\TransactionCommittedEvent;
use W7\Core\Database\Event\TransactionRolledBackEvent;
use W7\Core\Log\Processor\SwooleProcessor;
use W7\Core\Pool\Event\PopConnectionEvent;
use W7\Core\Pool\Event\PushConnectionEvent;
use W7\Core\Pool\Event\ResumeConnectionEvent;
use W7\Core\Pool\Event\SuspendConnectionEvent;
use W7\Core\Provider\ProviderAbstract;
use W7\Core\Server\ServerEvent;
use W7\Debugger\Cache\AfterMakeConnectionListener as AfterMakeCacheConnectionListener;
use W7\Debugger\Database\QueryExecutedListener;
use W7\Debugger\Database\TransactionBeginningListener;
use W7\Debugger\Database\TransactionCommittedListener;
use W7\Debugger\Database\TransactionRolledBackListener;
use W7\Debugger\Pool\PopConnectionListener;
use W7\Debugger\Pool\PushConnectionListener;
use W7\Debugger\Pool\ResumeConnectionListener;
use W7\Debugger\Pool\SuspendConnectionListener;
use W7\Debugger\Database\AfterMakeConnectionListener as AfterMakeDatabaseConnectionListener;
use W7\Core\Database\Event\AfterMakeConnectionEvent as MakeDatabaseConnectionEvent;
use W7\Core\Cache\Event\AfterMakeConnectionEvent as MakeCacheConnectionEvent;
use W7\Core\Pool\Event\MakeConnectionEvent as PoolMakeConnectionEvent;
use W7\Debugger\Pool\MakeConnectionListener as PoolMakeConnectionListener;
use W7\Debugger\Request\AfterRequestListener;
use W7\Debugger\Request\BeforeRequestListener;

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
			$this->registerLogger('rangine-debugger', [
				'driver' => $this->config->get('handler.log.daily'),
				'path' => App::getApp()->getRuntimePath() . '/logs/trace.log',
				'level' => 'debug',
				'days' => 1,
				'processor' => [SwooleProcessor::class]
			]);
		}

		Debugger::registerLoggerResolver(function () {
			return $this->logger->channel('rangine-debugger');
		});
	}

	private function registerListener() {
		$this->getEventDispatcher()->listen(ServerEvent::ON_USER_BEFORE_REQUEST, BeforeRequestListener::class);
		$this->getEventDispatcher()->listen(MakeCacheConnectionEvent::class, AfterMakeCacheConnectionListener::class);
		$this->getEventDispatcher()->listen(MakeDatabaseConnectionEvent::class, AfterMakeDatabaseConnectionListener::class);
		$this->getEventDispatcher()->listen(QueryExecutedEvent::class, QueryExecutedListener::class);
		$this->getEventDispatcher()->listen(TransactionBeginningEvent::class, TransactionBeginningListener::class);
		$this->getEventDispatcher()->listen(TransactionCommittedEvent::class, TransactionCommittedListener::class);
		$this->getEventDispatcher()->listen(TransactionRolledBackEvent::class, TransactionRolledBackListener::class);
		$this->getEventDispatcher()->listen(PoolMakeConnectionEvent::class, PoolMakeConnectionListener::class);
		$this->getEventDispatcher()->listen(PopConnectionEvent::class, PopConnectionListener::class);
		$this->getEventDispatcher()->listen(PushConnectionEvent::class, PushConnectionListener::class);
		$this->getEventDispatcher()->listen(ResumeConnectionEvent::class, ResumeConnectionListener::class);
		$this->getEventDispatcher()->listen(SuspendConnectionEvent::class, SuspendConnectionListener::class);
		$this->getEventDispatcher()->listen(ServerEvent::ON_USER_AFTER_REQUEST, AfterRequestListener::class);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
	}
}
