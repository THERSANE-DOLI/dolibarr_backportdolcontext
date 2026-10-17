<?php
/**
 * EXTRACTED FROM DOLIBARR V19 core/class/commonhookactions.class.php (missing in V18)
 */
if (!class_exists('CommonHookActions')) {
	/**
	 * Parent class of all other hook actions classes
	 */
	abstract class CommonHookActions
	{
		/**
		 * @var string	String of results.
		 */
		public $resprints;

		/**
		 * @var array 	Array of results.
		 */
		public $results = array();
	}
}
