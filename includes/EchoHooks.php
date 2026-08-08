<?php

namespace MediaWiki\Skins\Vector;

use MediaWiki\Extension\Notifications\Hooks\BeforeDisplayOrangeAlertHook;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

class EchoHooks implements BeforeDisplayOrangeAlertHook {
	/** @inheritDoc */
	public function onBeforeDisplayOrangeAlert( User $user, Title $title ): false {
		return false;
	}
}
