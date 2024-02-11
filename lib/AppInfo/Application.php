<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Gonzalo <fruta@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Library\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'library';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}


	public function register(IRegistrationContext $context): void {
		/*
		 * For further information about the app bootstrapping, please refer to our documentation:
		 * https://docs.nextcloud.com/server/latest/developer_manual/app_development/bootstrap.html
		 */
		// Register your services, event listeners, etc.
	}

	public function boot(IBootContext $context): void {
		/*
		 * For further information about the app bootstrapping, please refer to our documentation:
		 * https://docs.nextcloud.com/server/latest/developer_manual/app_development/bootstrap.html
		 */
		// Prepare your app.
	}
}
