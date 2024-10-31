<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

/**
 * Class Physio_Cloud_Software_Plugin_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		PHYSIOCLOU
 * @subpackage	Classes/Physio_Cloud_Software_Plugin_Run
 * @author		B.S.E. Business Solution Enterprises LTD
 * @since		1.0.0
 */
class Physio_Cloud_Software_Plugin_Run
{

	public $shortcodes;
	public $ajax;

	/**
	 * Our Physio_Cloud_Software_Plugin_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		$this->shortcodes = new PhysioCloudSoftware_Backend_Interface_Shortcodes();
		$this->ajax = new PhysioCloudSoftware_Backend_Interface_Ajax();
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks()
	{

		add_action('plugin_action_links_' . PHYSIOCLOU_PLUGIN_BASE, array($this, 'add_plugin_action_link'), 20);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_scripts_and_styles'), 20);
		add_shortcode('physiocloudsoftware_booking', array($this->shortcodes, 'render'));
		add_action('wp_ajax_physio_book_now_action', array($this->ajax, 'physio_book_now_action_function'));
	}



	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Adds action links to the plugin list table
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$links An array of plugin action links.
	 *
	 * @return	array	An array of plugin action links.
	 */
	public function add_plugin_action_link($links)
	{

		$links['our_shop'] = sprintf('<a href="%s" target="_blank" title="Website" style="font-weight:700;">%s</a>', 'https://physiocloudsoftware.com', __('Website', 'physio-cloud-software-plugin'));

		return $links;
	}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles()
	{
		wp_enqueue_style('physioclou-backend-styles', PHYSIOCLOU_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), PHYSIOCLOU_VERSION, 'all');
		wp_enqueue_script('physioclou-backend-scripts', PHYSIOCLOU_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), PHYSIOCLOU_VERSION, false);
		wp_localize_script('physioclou-backend-scripts', 'physioclou', array(
			'plugin_name'   	=> __(PHYSIOCLOU_NAME, 'physio-cloud-software-plugin'),
		));
	}
}
