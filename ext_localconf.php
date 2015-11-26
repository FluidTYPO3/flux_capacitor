<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\FluidTYPO3\FluxCapacitor\FormEngine\StructureProvider::class] = array(
	'before' => array(
		\TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsRemoveUnused::class
	)
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\FluidTYPO3\FluxCapacitor\FormEngine\FlexFormFieldRecordConfigurationProvider::class] = array(
	'before' => array(
		\TYPO3\CMS\Backend\Form\FormDataProvider\InlineOverrideChildTca::class
	)
);

\FluidTYPO3\FluxCapacitor\Implementation\ImplementationRegistry::registerImplementation(
	\FluidTYPO3\FluxCapacitor\Implementation\FlexFormImplementation::class
);
