<?php
namespace FluidTYPO3\FluxCapacitor\Implementation;

/**
 * Class ImplementationRegistry
 */
class ImplementationRegistry {

	/**
	 * @var array
	 */
	protected static $implementations = array();

	/**
	 * Register an instance of an implementation, adding it
	 * to the registry (to be thawed once requested). Can be
	 * provided with a settings array that is passed once to
	 * the implementation during instantiation.
	 *
	 * @param string $implementationClassName
	 * @param array $settings
	 * @return void
	 */
	public static function registerImplementation($implementationClassName, array $settings = array()) {
		foreach (static::$implementations as $implementationData) {
			list ($registeredClassName, $registeredSettings) = $implementationData;
			if ($registeredClassName === $implementationClassName && $registeredSettings == $settings) {
				return;
			}
		}
		static::$implementations[] = array($implementationClassName, $settings);
	}

	/**
	 * Resolves a set of Implementation instances which apply
	 * to the table, field and record provided in arguments.
	 *
	 * @param string $table
	 * @param string $field
	 * @param array $record
	 * @return ImplementationInterface[]
	 */
	public static function resolveImplementations($table, $field, array $record) {
		$implementations = array();
		foreach (static::$implementations as $index => $implementationData) {
			/** @var ImplementationInterface $instance */
			if (count($implementationData) === 3) {
				list ($registeredClassName, $registeredSettings, $instance) = $implementationData;
			} else {
				list ($registeredClassName, $registeredSettings) = $implementationData;
				$instance = new $registeredClassName($registeredSettings);
			}
			if ($field === NULL && $instance->appliesToTable($table)) {
				$implementations[] = $instance;
			} elseif ($instance->appliesToTableField($table, $field) && $instance->appliesToRecord($record)) {
				$implementations[] = $instance;
			}
		}
		return $implementations;
	}

}
