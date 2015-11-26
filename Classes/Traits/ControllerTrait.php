<?php
namespace FluidTYPO3\FluxCapacitor\Traits;

use FluidTYPO3\FluxCapacitor\Implementation\ImplementationRegistry;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class ControllerTrait
 */
trait ControllerTrait {

	/**
	 * @var ConfigurationManagerInterface;
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @param ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$contentObject = $this->configurationManager->getContentObject();
		$table = $contentObject->getCurrentTable();
		$field = isset($this->tableName) ? $this->tableName : 'pi_flexform';
		$record = $contentObject->data;
		$implementations = ImplementationRegistry::resolveImplementations($table, $field, $record);
		foreach ($implementations as $implementation) {
			$this->settings = $implementation->getConverterForTableFieldAndRecord($table, $field, $record)
				->convertData($this->settings);
		}
	}

}
