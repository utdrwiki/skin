<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorComponentNavbarItem component
 */
class VectorComponentNavbarItem implements VectorComponent {
	/** @var array */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$subItems = [];

		foreach ($this->data["items"] as $subItem) {
			$subItems[] = (new VectorComponentNavbarItem($subItem))->getTemplateData();
		}

		return [
			'class' => '',
			'label' => '',
			'html-tooltip' => '',
			'label-class' => '',
			'html-before-portal' => '',
			'html-items' => '',
			'html-after-portal' => '',
			'array-list-items' => null,
		];
	}
}
