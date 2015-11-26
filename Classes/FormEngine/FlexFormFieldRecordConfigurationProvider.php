<?php
namespace FluidTYPO3\FluxCapacitor\FormEngine;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Class FlexFormFieldRecordConfigurationProvider
 */
class FlexFormFieldRecordConfigurationProvider implements FormDataProviderInterface {

	/**
	 * Add form data to result array
	 *
	 * @param array $result Initialized result array
	 * @return array Result filled with more data
	 */
	public function addData(array $result) {
		if ($result['tableName'] === 'tx_fluxcapacitor_domain_model_field') {
			if (!empty($result['databaseRow']['field_options'])) {
				$result['processedTca']['columns']['field_value']['config'] = json_decode($result['databaseRow']['field_options'], JSON_OBJECT_AS_ARRAY);
			}
			$result['processedTca']['columns']['field_value']['label'] = $result['databaseRow']['field_name'];
		}
		return $result;
	}

}
