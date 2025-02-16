<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MessageLocalizer;

/**
 * VectorComponentPageTools component
 */
class VectorComponentPageTools implements VectorComponent {

	/** @var array */
	private $menus;

	/** @var MessageLocalizer */
	private $localizer;

	/** @var bool */
	private $isPinned;

	/** @var VectorComponentPinnableHeader */
	private $pinnableHeader;

	/** @var string */
	public const ID = 'vector-page-tools';

	/** @var string */
	public const TOOLBOX_ID = 'p-tb';

	/** @var string */
	private const ACTIONS_ID = 'p-cactions';

	/**
	 * @param array $menus
	 * @param MessageLocalizer $localizer
	 * @param FeatureManager $featureManager
	 */
	public function __construct(
		array $menus,
		MessageLocalizer $localizer,
		FeatureManager $featureManager
	) {
		$this->menus = $menus;
		$this->localizer = $localizer;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_PAGE_TOOLS_PINNED );
		$this->pinnableHeader = new VectorComponentPinnableHeader(
			$localizer,
			$this->isPinned,
			// Name
			self::ID,
			// Feature name
			'page-tools-pinned'
		);
	}

	private function getItemIcon( ?string $itemId ): ?string {
		switch ( $itemId ) {
			case 't-whatlinkshere': return 'link';
			case 't-newpage': return 'add';
			case 't-upload': return 'upload';
			case 't-specialpages': return 'specialPages';
			case 't-info': return 'info';
			case 't-contributions': return 'userContributions';
			case 't-log': return 'listBullet';
			case 't-blockip': return 'block';
			case 't-userrights': return 'userGroup';
			default: return null;
		}
	}

	/**
	 * Revises the labels of the p-tb and p-cactions menus.
	 */
	private function getMenus(): array {
		return array_map( function ( $menu ) {
			switch ( $menu['id'] ?? '' ) {
				case self::TOOLBOX_ID:
					/* UTW change: 'general' is the only label, so let's change it to 'tools'
					$menu['label'] = $this->localizer->msg( 'vector-page-tools-general-label' )->text();
					*/
					$menu['label'] = $this->localizer->msg( 'vector-page-tools-label' )->text();
					$menu['html-items'] = null;
					$menu['array-list-items'] = array_map( function ( $item ) {
						$item['array-links'][0]['icon'] = "{$this->getItemIcon( $item['id'] )}-progressive";
						return $item;
					}, $menu['array-items'] );
					break;
				case self::ACTIONS_ID:
					$menu['label'] = $this->localizer->msg( 'vector-page-tools-actions-label' )->text();
					break;
			}

			return $menu;
		}, $this->menus );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$pinnedContainer = new VectorComponentPinnableContainer( self::ID, $this->isPinned );
		$pinnableElement = new VectorComponentPinnableElement( self::ID );

		$data = $pinnableElement->getTemplateData() +
			$pinnedContainer->getTemplateData();

		return $data + [
			/* UTW change: we don't have 'pinning'
			'data-pinnable-header' => $this->pinnableHeader->getTemplateData(),
			*/
			'data-menus' => $this->getMenus()
		];
	}
}
