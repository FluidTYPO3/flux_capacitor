<?php
namespace FluidTYPO3\FluxCapacitor\Implementation;

use FluidTYPO3\FluxCapacitor\Converter\FlexFormConverter;

/**
 * Class FlexFormImplementation
 */
class FlexFormImplementation extends AbstractImplementation implements ImplementationInterface {

	/**
	 * @var array
	 */
	protected static $registrations = array();

	/**
	 * @param string $table
	 * @param string $field
	 * @param \Closure|NULL $additionalConditionChecker
	 * @return void
	 */
	public static function registerForTableAndField($table, $field, \Closure $additionalConditionChecker = NULL) {
		if (!isset(static::$registrations[$table])) {
			static::$registrations[$table] = array();
		}
		if ($field === NULL) {
			$registrations = &static::$registrations[$table];
		} else {
			if (!isset(static::$registrations[$table][$field])) {
				static::$registrations[$table][$field] = array();
			}
			$registrations = &static::$registrations[$table][$field];
		}
		$registrations[] = array(
			static::class,
			$additionalConditionChecker
		);
		$GLOBALS['TCA'][$table]['columns'][$field] = array(
			'label' => 'Configuration',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_fluxcapacitor_domain_model_sheet',
				'foreign_field' => 'source_uid',
				'foreign_table_field' => 'source_table',
				'foreign_label' => 'sheet_label',
				'minitems' => 0,
				'maxitems' => 99999,
				'appearance' => array(
					'collapseAll' => 0,
					'expandSingle' => 0,
					'levelLinksPosition' => 'none',
					'useSortable' => 0,
					'showPossibleLocalizationRecords' => 0,
					'showRemovedLocalizationRecords' => 0,
					'showAllLocalizationLink' => 0,
					'showSynchronizationLink' => 0,
					'enabledControls' => array(
						'info' => FALSE,
						'new' => FALSE,
						'hide' => FALSE,
						'localize' => FALSE,
						'delete' => FALSE,
					)
				),
				'behaviour' => array(
					'enableCascadingDelete' => TRUE,
					'disableMovingChildrenWithParent' => FALSE,
					'localizeChildrenAtParentLocalization' => TRUE
				)
			)
		);
	}

	/**
	 * Must return TRUE only if this implementation applies
	 * to the table and field provided. Each implementation
	 * can then allow configuring whether or not it should
	 * apply to a given table/field in any way desired.
	 *
	 * @param string $table
	 * @param string $field
	 * @return boolean
	 */
	public function appliesToTableField($table, $field) {
		if ($field === NULL) {
			return static::appliesToTable($table);
		}
		return isset(static::$registrations[$table][$field]);
	}

	/**
	 * Must return TRUE only if this implementation applies
	 * to the table provided. Each implementation can then
	 * allow configuring whether or not it should apply to
	 * a given table in any way desired.
	 *
	 * @param string $table
	 * @param string $field
	 * @return boolean
	 */
	public function appliesToTable($table) {
		return isset(static::$registrations[$table]);
	}

	/**
	 * Returns a Converter that does the actual processing
	 * required by this Implementation. Requires the table,
	 * field and record as input parameters, allowing an
	 * Implementation to return any number of different
	 * Converters based on these identifying values.
	 *
	 * @param string $table
	 * @param string $field
	 * @param array $record
	 * @return ConverterInterface
	 */
	public function getConverterForTableFieldAndRecord($table, $field, array $record) {
		return new FlexFormConverter($table, $field, $record);
	}

}
