<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MessageLocalizer;

/**
 * VectorComponentAppearance component
 */
class VectorComponentAppearance implements VectorComponent {

	/** @var MessageLocalizer */
	private $localizer;

	/** @var bool */
	private $isPinned;

	/** @var string */
	public const ID = 'vector-appearance';
	public $id;

	public function __construct(
		MessageLocalizer $localizer,
		FeatureManager $featureManager,
		string $id = self::ID,
	) {
		$this->localizer = $localizer;
		// FIXME: isPinned is no longer accurate because the appearance menu uses client preferences
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_APPEARANCE_PINNED );
		$this->id = $id;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$pinnedContainer = new VectorComponentPinnableContainer( $this->id, $this->isPinned );
		$pinnableElement = new VectorComponentPinnableElement( $this->id );
		/* UTW change: we don't have 'pinning'
		$pinnableHeader = new VectorComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			// Name
			self::ID,
			// Feature name
			'appearance-pinned'
		);
		*/

		$data = $pinnableElement->getTemplateData() +
			$pinnedContainer->getTemplateData();

		return $data + [
			/* UTW change: we don't have 'pinning'
			'data-pinnable-header' => $pinnableHeader->getTemplateData()
			*/
		];
	}
}
