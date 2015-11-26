<?php
namespace FluidTYPO3\FluxCapacitor\FormEngine;

use FluidTYPO3\FluxCapacitor\Implementation\ImplementationInterface;
use FluidTYPO3\FluxCapacitor\Implementation\ImplementationRegistry;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Class StructureProvider
 */
class StructureProvider implements FormDataProviderInterface {

	/**
	 * Add form data to result array
	 *
	 * @param array $result Initialized result array
	 * @return array Result filled with more data
	 */
	public function addData(array $result) {
		$implementations = $this->resolveImplementationsForTable($result['tableName'], $result['databaseRow']);
		foreach ($result['processedTca']['columns'] as $fieldName => $_) {
			foreach ($implementations as $implementation) {
				if ($implementation->appliesToTableField($result['tableName'], $fieldName)) {
					$result = $implementation->getConverterForTableFieldAndRecord(
						$result['tableName'],
						$fieldName,
						$result['databaseRow']
					)->convertStructure($result);
				}
			}
		}
		return $result;
	}

	/**
	 * @param string $table
	 * @param array $record
	 * @return ImplementationInterface[]
	 */
	protected function resolveImplementationsForTable($table, array $record) {
		return ImplementationRegistry::resolveImplementations($table, NULL, $record);
	}

}
