<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Physio_Cloud_Software_Plugin_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		PHYSIOCLOU
 * @subpackage	Classes/Physio_Cloud_Software_Plugin_Settings
 * @author		B.S.E. Business Solution Enterprises LTD
 * @since		1.0.0
 */
class Physio_Cloud_Software_Plugin_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Physio_Cloud_Software_Plugin_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = PHYSIOCLOU_NAME;
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'PHYSIOCLOU/settings/get_plugin_name', $this->plugin_name );
	}
}
