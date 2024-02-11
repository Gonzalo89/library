<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Gonzalo <fruta@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Library\Tests\Unit\Controller;

use OCA\Library\Controller\NoteApiController;

class NoteApiControllerTest extends NoteControllerTest {
	public function setUp(): void {
		parent::setUp();
		$this->controller = new NoteApiController($this->request, $this->service, $this->userId);
	}
}
