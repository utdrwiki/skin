<?php
namespace MediaWiki\Skins\Vector\Components;

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
	private string|null $rel;

	/**
	 * @param string $href
	 * @param string $text
	 * @param string $id
	 * @param null|string $icon
	 */
	public function __construct( string $href, string $text, string $id, $icon = null, $rel = null ) {
		$this->href = $href;
		$this->text = $text;
		$this->id = $id;
		$this->rel = $rel;
		$this->icon = $icon;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$data = [
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
		if ( $this->rel !== null ) {
			$data['array-attributes'][] = [
				'key' => 'rel',
				'value' => $this->rel,
			];
		}
		return $data;
	}
}
