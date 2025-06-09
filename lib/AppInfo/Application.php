<?php

declare(strict_types=1);

namespace OCA\Overleaf\AppInfo;

use Closure;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Util;

class Application extends App implements IBootstrap {
	public const APP_ID = 'integration_overleaf';

	public const DEFAULT_OVERLEAF_SERVER = 'https://www.overleaf.com';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
		$context->injectFn(Closure::fromCallable([$this, 'loadFilesPlugin']));
	}
	public function loadFilesPlugin(IEventDispatcher $eventDispatcher): void {
		$eventDispatcher->addListener(LoadAdditionalScriptsEvent::class, function () {
			Util::addInitScript(self::APP_ID, self::APP_ID . '-filesplugin');
		});
	}
}
