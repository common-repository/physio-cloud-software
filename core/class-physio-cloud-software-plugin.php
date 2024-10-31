<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;
if (!class_exists('Physio_Cloud_Software_Plugin')) :

	/**
	 * Main Physio_Cloud_Software_Plugin Class.
	 *
	 * @package		PHYSIOCLOU
	 * @subpackage	Classes/Physio_Cloud_Software_Plugin
	 * @since		1.0.0
	 * @author		B.S.E. Business Solution Enterprises LTD
	 */
	final class Physio_Cloud_Software_Plugin
	{

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Physio_Cloud_Software_Plugin
		 */
		private static $instance;

		/**
		 * PHYSIOCLOU helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Physio_Cloud_Software_Plugin_Helpers
		 */
		public $helpers;

		/**
		 * PHYSIOCLOU settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Physio_Cloud_Software_Plugin_Settings
		 */
		public $settings;

		public $shortcodes;
		public $ajax;
		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone()
		{
			_doing_it_wrong(__FUNCTION__, __('You are not allowed to clone this class.', 'physio-cloud-software-plugin'), '1.0.0');
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup()
		{
			_doing_it_wrong(__FUNCTION__, __('You are not allowed to unserialize this class.', 'physio-cloud-software-plugin'), '1.0.0');
		}

		/**
		 * Main Physio_Cloud_Software_Plugin Instance.
		 *
		 * Insures that only one instance of Physio_Cloud_Software_Plugin exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Physio_Cloud_Software_Plugin	The one true Physio_Cloud_Software_Plugin
		 */
		public static function instance()
		{
			if (!isset(self::$instance) && !(self::$instance instanceof Physio_Cloud_Software_Plugin)) {
				self::$instance					= new Physio_Cloud_Software_Plugin;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Physio_Cloud_Software_Plugin_Helpers();
				self::$instance->settings		= new Physio_Cloud_Software_Plugin_Settings();
				self::$instance->shortcodes = new PhysioCloudSoftware_Backend_Interface_Shortcodes();
				self::$instance->ajax = new PhysioCloudSoftware_Backend_Interface_Ajax();
				//Fire the plugin logic
				new Physio_Cloud_Software_Plugin_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action('PHYSIOCLOU/plugin_loaded');
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes()
		{
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-helpers.php';
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-settings.php';

			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-run.php';
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-interface-backend.php';
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-interface-backend-credentials.php';
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-shortcodes.php';
			require_once PHYSIOCLOU_PLUGIN_DIR . 'core/includes/classes/class-physio-cloud-software-plugin-ajax.php';
			

		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks()
		{
			add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
			add_action('admin_menu', array(self::$instance, 'add_menu'));

			

		}
		public function add_menu()
		{
			add_menu_page(
				'Physio Cloud Software',
				'Physio Cloud Software',
				'manage_options',
				'physiocloudsoftwarepage',
				array(PhysioCloudSoftware_Backend_Interface::instance(), 'load_menu'),
				'',
				66
			);
			
		}
		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain()
		{
			load_plugin_textdomain('physio-cloud-software-plugin', FALSE, dirname(plugin_basename(PHYSIOCLOU_PLUGIN_FILE)) . '/languages/');
		}
	}

endif; // End if class_exists check.