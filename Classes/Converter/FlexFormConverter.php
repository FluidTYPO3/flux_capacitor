<?php
namespace FluidTYPO3\FluxCapacitor\Converter;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Class FlexFormConverter
 */
class FlexFormConverter implements ConverterInterface {

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var string
	 */
	protected $field;

	/**
	 * @var array
	 */
	protected $record;

	/**
	 * @var array
	 */
	protected $original = array();

	/**
	 * Constructor to receive all required parameters.
	 *
	 * @param string $table
	 * @param string $field
	 * @param array $record
	 */
	public function __construct($table, $field, array $record) {
		$this->table = $table;
		$this->field = $field;
		$this->record = $record;
		$this->original = $GLOBALS['TCA'][$table]['columns'][$field];
	}

	/**
	 * Modify the input FormEngine structure, returning
	 * the modified array.
	 *
	 * @param array $structure
	 * @return array
	 */
	public function convertStructure(array $structure) {
		$source = $this->resolveDataSourceDefinition($structure);
		if ($source === NULL || empty($source['sheets'])) {
			return $structure;
		}
		$this->synchroniseConfigurationRecords($source);
		return $structure;
	}

	/**
	 * Converts traditional FlexForm array data to an
	 * ArrayAccess-capable wrapper that reads values from
	 * a collection of related records, merged with original
	 * values (to protect for example "settings" as added
	 * by Extbase ConfigurationManager).
	 *
	 * @param array $data
	 * @return array|\ArrayAccess
	 */
	public function convertData(array $data) {
		$sheets = $this->resolveConfigurationRecords();
		foreach ($sheets as $sheetData) {
			$values = json_decode($sheetData['json_data'], JSON_OBJECT_AS_ARRAY);
			foreach ($values as $name => $value) {
				$this->assignVariableByDottedPath($data, $name, $value);
			}
		}
		return $data;
	}

	/**
	 * Synchronises the IRRE-attached relation records for
	 * the record in question, if record has been saved and
	 * now has a UID value. Uses the form structure defined
	 * in $dataSource (and creates records with default
	 * values those were if specified in data source).
	 *
	 * @param array $dataSource
	 * @return void
	 */
	protected function synchroniseConfigurationRecords(array $datasource) {
		$currentConfigurationRecords = $this->resolveConfigurationRecords();
		foreach ($datasource['sheets'] as $sheetName => $sheetConfiguration) {
			$sheetData = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
				'uid, name',
				'tx_fluxcapacitor_domain_model_sheet',
				$this->createSqlCondition(array('name' => $sheetName))
			);
			if (empty($sheetData)) {
				$label = $sheetConfiguration['ROOT']['TCEforms']['sheetTitle'];
				$sheetData = array(
					'pid' => $this->record['pid'],
					'name' => $sheetName,
					'sheet_label' => empty($label) ? $sheetName : $label,
					'source_table' => $this->table,
					'source_field' => $this->field,
					'source_uid' => $this->record['uid']
				);
				$this->getDatabaseConnection()->exec_INSERTquery('tx_fluxcapacitor_domain_model_sheet', $sheetData);
				$sheetData['uid'] = $this->getDatabaseConnection()->sql_insert_id();
			}
			$currentSettings = array();
			foreach ($this->getDatabaseConnection()->exec_SELECTgetRows(
				'uid, field_name',
				'tx_fluxcapacitor_domain_model_field',
				sprintf('sheet = %d', $sheetData['uid'])
			) as $fieldRecord) {
				$currentSettings[$fieldRecord['field_name']] = $currentSettings['uid'];
			}
			foreach ($sheetConfiguration['ROOT']['el'] as $fieldName => $fieldConfiguration) {
				$fieldConfiguration = $fieldConfiguration['TCEforms'];
				$type = $fieldConfiguration['config']['type'];
				if ($type === 'select' && isset($fieldConfiguration['config']['renderType'])) {
					$type = $fieldConfiguration['config']['renderType'];
				}
				if (!array_key_exists($fieldName, $currentSettings)) {
					$fieldData = array(
						'pid' => $this->record['pid'],
						'sheet' => $sheetData['uid'],
						'field_name' => $fieldName,
						'field_label' => $fieldConfiguration['label'],
						'field_type' => $type,
						'field_value' => $fieldConfiguration['config']['default'],
						'field_options' => json_encode($fieldConfiguration['config'], JSON_HEX_AMP | JSON_HEX_TAG)
					);
					$this->getDatabaseConnection()->exec_INSERTquery('tx_fluxcapacitor_domain_model_field', $fieldData);
				} else {
					$fieldData = array(
						'pid' => $this->record['pid'],
						'field_type' => $type,
						'field_label' => $fieldConfiguration['label'],
						'field_options' => json_encode($fieldConfiguration['config'], JSON_HEX_AMP | JSON_HEX_TAG)
					);
					$this->getDatabaseConnection()->exec_UPDATEquery(
						'tx_fluxcapacitor_domain_model_field',
						sprintf('uid = %d', $currentSettings[$fieldName]),
						$fieldData
					);
				}
			}
		}
	}

	/**
	 * Loads all records currently storing form settings as
	 * related records in the database.
	 *
	 * @return array
	 */
	protected function resolveConfigurationRecords() {
		$query = 'SELECT * FROM tx_fluxcapacitor_domain_model_sheet WHERE ' . $this->createSqlCondition();
		$result = $GLOBALS['TYPO3_DB']->sql_query($query);
		if (!$result->num_rows) {
			return array();
		}
		return mysqli_fetch_assoc($result);
	}

	/**
	 * Resolves a data source definition (TCEforms array)
	 * based on the properties of this converter instance
	 * and the *original* TCA of the source record field.
	 *
	 * @param array $structure
	 * @return array|NULL
	 */
	protected function resolveDataSourceDefinition(array $structure) {
		$config = $structure['processedTca']['columns'][$this->field]['config'];
		return BackendUtility::getFlexFormDS($config, $this->record, $this->table, $this->field);
	}

	/**
	 * @param array $matchers
	 * @return string
	 */
	protected function createSqlCondition(array $matchers = array()) {
		$condition = sprintf(
			'source_table = \'%s\' AND source_uid = %d AND source_field = \'%s\'',
			$this->table,
			$this->record['uid'],
			$this->field
		);
		foreach ($matchers as $column => $requiredValue) {
			$condition .= sprintf(' AND %s = %s', $column, is_string($requiredValue) ? '\'' . $requiredValue . '\'' : $requiredValue);
		}
		return $condition;
	}

	/**
	 * @return DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @param array $data
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	protected function assignVariableByDottedPath(array &$data, $name, $value) {
		if (!strpos($name, '.')) {
			$data[$name] = $value;
		} else {
			$assignIn = &$data;
			$segments = explode('.', $name);
			$last = array_pop($segments);
			foreach ($segments as $segment) {
				if (!array_key_exists($segment, $assignIn)) {
					$assignIn[$segment] = array();
				}
				$assignIn = &$assignIn[$segment];
			}
			$assignIn[$last] = $value;
		}
	}

}
