<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\FluidTYPO3\FluxCapacitor\Implementation\FlexFormImplementation::registerForTableAndField('tt_content', 'pi_flexform');

$GLOBALS['TCA']['tx_fluxcapacitor_domain_model_sheet'] = array(
	'label' => 'FluxCapacitor sheet',
	'ctrl' => array(
		'title' => 'FluxCapacitor configuration',
		'label'     => 'sheet_label',
		'prependAtCopy' => '',
		'hideAtCopy' => FALSE,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => FALSE,
		'origUid' => 't3_origuid',
		'dividers2tabs' => TRUE,
		'useColumnsForDefaultValues' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'sortby' => '',
		'enablecolumns' => array(),
		'iconfile'          => 'EXT:flux_capacitor/ext_icon.png',
	),
	'types' => array(
		'0' => array(
			'showitem' => 'form_fields'
		),
	),
	'columns' => array(
		'name' => array(
			'label' => '',
			'config' => array(
				'type' => 'input'
			)
		),
		'form_fields' => array(
			'label' => '',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_fluxcapacitor_domain_model_field',
				'foreign_field' => 'sheet',
				'foreign_label' => 'field_label',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 999,
				'appearance' => array(
					'collapseAll' => FALSE,
					'expandSingle' => FALSE,
					'levelLinksPosition' => 'none',
					'useSortable' => FALSE,
					'showPossibleLocalizationRecords' => FALSE,
					'showRemovedLocalizationRecords' => FALSE,
					'showAllLocalizationLink' => FALSE,
					'showSynchronizationLink' => FALSE,
					'enabledControls' => array(
						'info' => FALSE,
						'new' => FALSE,
						'create' => FALSE,
						'add' => FALSE,
						'localize' => FALSE,
						'delete' => FALSE,
					)
				)
			)
		)
	)
);

$GLOBALS['TCA']['tx_fluxcapacitor_domain_model_field'] = array(
	'label' => 'FluxCapacitor settings',
	'ctrl' => array(
		'title' => 'FluxCapacitor configuration',
		'label'     => '',
		'prependAtCopy' => '',
		'hideAtCopy' => FALSE,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => FALSE,
		'origUid' => 't3_origuid',
		'dividers2tabs' => TRUE,
		'useColumnsForDefaultValues' => 'type',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'sortby' => '',
		'enablecolumns' => array(),
		'iconfile'          => 'EXT:flux_capacitor/ext_icon.png',
	),
	'types' => array(
		'0' => array(
			'showitem' => 'field_value'
		),
	),
	'columns' => array(
		'field_name' => array(
			'label' => 'Field name',
			'config' => array(
				'type' => 'input'
			)
		),
		'field_label' => array(
			'label' => 'Field label',
			'config' => array(
				'type' => 'input'
			)
		),
		'field_value' => array(
			'label' => 'Field value',
			'config' => array(
				'type' => 'input'
			)
		),
	)
);
