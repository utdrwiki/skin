<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Output\OutputPage;
use MessageLocalizer;

/**
 * VectorComponentCategories component
 */
class VectorComponentCategories implements VectorComponent {
	/** @var MessageLocalizer */
	private $localizer;
	/** @var OutputPage */
	private $out;

	/**
	 * @param MessageLocalizer $localizer for generation of tooltip and access keys
	 * @param OutputPage $out for retrieving the categories
	 */
	public function __construct( MessageLocalizer $localizer, OutputPage $out ) {
		$this->localizer = $localizer;
		$this->out = $out;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$categoryLinks = $this->out->getCategoryLinks();
		$normalCategoryLinks = $categoryLinks['normal'] ?? null;
		if ( empty( $normalCategoryLinks ) ) {
			return [];
		}
		return [
			'msg-in' => $this->localizer->msg( 'utw-header-categories-in' )->text(),
			'category-links' => $normalCategoryLinks,
		];
	}
}
