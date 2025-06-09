<?php

namespace OCA\Overleaf\Listener;

use OCA\Overleaf\AppInfo\Application;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\Event;
use OCP\Util;

class LoadAdditionalScriptsListener {
	public function handle(Event $event): void {
		if (!($event instanceof LoadAdditionalScriptsEvent)) {
			return;
		}
		Util::addInitScript(Application::APP_ID, Application::APP_ID . '-filesplugin');
	}
}
