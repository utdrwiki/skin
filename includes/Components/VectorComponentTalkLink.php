<?php
namespace MediaWiki\Skins\Vector\Components;

use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MessageLocalizer;

/**
 * VectorComponentLink component
 */
class VectorComponentTalkLink implements VectorComponent {
	/** @var string */
	private $icon;
	/** @var string */
	private $href;
	/** @var string */
	private $text;
	/** @var string */
	private $id;

	/**
	 * @param string $href
	 * @param string $text
	 * @param string $id
	 * @param null|string $icon
	 */
	public function __construct( string $href, string $text, string $id, $icon = null ) {
		$this->href = $href;
		$this->text = $text;
		$this->id = $id;
		$this->icon = $icon;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'href' => $this->href,
			'text' => $this->text,
			'icon' => $this->icon,

			'array-attributes' => [
				[
					'key' => 'id',
					'value' => $this->id,
				],
				[
					'key' => 'href',
					'value' => $this->href,
				]
			]
		];
	}
}
