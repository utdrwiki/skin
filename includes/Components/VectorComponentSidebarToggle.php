<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MessageLocalizer;

/**
 * VectorComponentSidebarToggle component
 */
class VectorComponentSidebarToggle implements VectorComponent {
	/** @var MessageLocalizer message localizer  */
	private $localizer;

	/** @var bool if the sidebar is visible */
	private $isVisible;

	/**
	 * @param MessageLocalizer $localizer
	 * @param FeatureManager $featureManager
	 */
	public function __construct(
		MessageLocalizer $localizer,
		FeatureManager $featureManager
	) {
		$this->localizer = $localizer;
		$this->isVisible = $featureManager->isFeatureEnabled( Constants::FEATURE_PAGE_TOOLS_PINNED );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$msg = $this->isVisible ? 'utw-sidebar-hide' : 'utw-sidebar-show';
		$icon = $this->isVisible ? 'next' : 'previous';
		return [
			'msg' => $this->localizer->msg( $msg )->text(),
			'icon' => $icon,
		];
	}
}
