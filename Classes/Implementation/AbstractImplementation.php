<?php
namespace FluidTYPO3\FluxCapacitor\Implementation;

/**
 * Class AbstractImplementation
 */
abstract class AbstractImplementation {

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Default implementation of constructor that's
	 * valid according to the expected interface.
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings = array()) {
		$this->settings = $settings;
	}

	/**
	 * Default implementation applies to any record
	 * that's not empty, given that the other to
	 * appliesTo() methods have both returned TRUE.
	 *
	 * @param array $record
	 * @return boolean
	 */
	public function appliesToRecord(array $record) {
		return !empty($record);
	}

}
