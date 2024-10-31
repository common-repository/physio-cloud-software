<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class PhysioCloudSoftware_Backend_Interface
{
    private static $instance;
    public $settings;
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof PhysioCloudSoftware_Backend_Interface)) {
            self::$instance                    = new PhysioCloudSoftware_Backend_Interface;
            self::$instance->settings = new Physio_Cloud_Software_Plugin_Settings;
            self::$instance->credentials = new PhysioCloudSoftware_Backend_Interface_Credentials;
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->helpers = Physio_Cloud_Software_Plugin::instance()->helpers;
    }

    public function load_menu()
    {
        $default_tab = null;
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
        if ($tab != '' || $tab != 'shortcodes') {
            $tab = '';
        }
        $method = 'GET';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $method = 'POST';
        }

?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <nav class="nav-tab-wrapper">
                <a href="?page=physiocloudsoftwarepage" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>">Credentials</a>
                <a href="?page=physiocloudsoftwarepage&tab=shortcodes" class="nav-tab <?php if ($tab === 'shortcodes') : ?>nav-tab-active<?php endif; ?>">Shortcodes</a>
            </nav>
            <div class="tab-content">
                <?php switch ($tab):
                    case 'shortcodes':
                        $this->credentials->LoadMenuShortCode($_POST, $method, $tab);
                        break;
                    default:
                        $this->credentials->LoadMenu();
                        break;
                endswitch; ?>
            </div>
        </div>
<?php
    }
}
