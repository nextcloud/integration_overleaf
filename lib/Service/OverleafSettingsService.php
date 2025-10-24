<?php


/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Overleaf\Service;

use OCA\Overleaf\AppInfo\Application;
use OCP\IAppConfig;

class OverleafSettingsService {
	public function __construct(
		private IAppConfig $appConfig,
	) {
	}

	public function getOverleafServer(): string {
		return $this->appConfig->getValueString(Application::APP_ID, 'overleaf_server', lazy: true) ?: Application::DEFAULT_OVERLEAF_SERVER;
	}
	public function setOverleafServer(string $server): void {
		$this->appConfig->setValueString(Application::APP_ID, 'overleaf_server', $server, lazy: true);
	}
}
