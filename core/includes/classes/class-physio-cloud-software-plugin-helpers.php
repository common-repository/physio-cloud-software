<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/**
 * Class Physio_Cloud_Software_Plugin_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		PHYSIOCLOU
 * @subpackage	Classes/Physio_Cloud_Software_Plugin_Helpers
 * @author		B.S.E. Business Solution Enterprises LTD
 * @since		1.0.0
 */
class Physio_Cloud_Software_Plugin_Helpers
{

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */
	function write_log($log)
	{
		if (is_array($log) || is_object($log)) {
			error_log(print_r($log, true));
		} else {
			error_log($log);
		}
	}
}
