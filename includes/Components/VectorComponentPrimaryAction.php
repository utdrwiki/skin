<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorComponentPrimaryAction component
 */
class VectorComponentPrimaryAction implements VectorComponent {
	/** @var null|array primary action  */
	private $primaryAction;

	/** @var array dropdown actions */
	private $dropdownActions;

	/**
	 * @param array $primaryAction
	 * @param array $dropdownActions
	 */
	public function __construct(
		array|null $primaryAction,
		array $dropdownActions
	) {
		$this->primaryAction = $primaryAction;
		$this->dropdownActions = $dropdownActions;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		if ( $this->primaryAction === null ) {
			return [];
		}
        $this->primaryAction['array-attributes'] = [
            [
                'key' => 'href',
                'value' => $this->primaryAction['href'],
            ],
        ];
        if ( isset( $this->primaryAction['single-id'] ) ) {
            $this->primaryAction['array-attributes'][] = [
                'key' => 'id',
                'value' => $this->primaryAction['single-id'],
            ];
        }
        $this->dropdownActions['label'] = '';
		return [
			'data-primary' => $this->primaryAction,
			'data-dropdown' => $this->dropdownActions,
		];
	}
}
