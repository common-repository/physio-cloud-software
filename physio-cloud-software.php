<?php
/**
 * Physio Cloud Software
 *
 * @package       PHYSIOCLOU
 * @author        B.S.E. Business Solution Enterprises LTD
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Physio Cloud Software
 * Plugin URI:    https://physiocloudsoftware.com
 * Description:   Integration with the Physio Cloud Software
 * Version:       1.0.0
 * Author:        B.S.E. Business Solution Enterprises LTD
 * Author URI:    https://bse.com.cy
 * Text Domain:   physio-cloud-software-plugin
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Physio Cloud Software Plugin. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'PHYSIOCLOU_NAME',			'Physio Cloud Software Plugin' );

// Plugin version
define( 'PHYSIOCLOU_VERSION',		'1.0.0' );

// Plugin Root File
define( 'PHYSIOCLOU_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'PHYSIOCLOU_PLUGIN_BASE',	plugin_basename( PHYSIOCLOU_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'PHYSIOCLOU_PLUGIN_DIR',	plugin_dir_path( PHYSIOCLOU_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'PHYSIOCLOU_PLUGIN_URL',	plugin_dir_url( PHYSIOCLOU_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once PHYSIOCLOU_PLUGIN_DIR . 'core/class-physio-cloud-software-plugin.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  B.S.E. Business Solution Enterprises LTD
 * @since   1.0.0
 * @return  object|Physio_Cloud_Software_Plugin
 */
function PHYSIOCLOU() {
	return Physio_Cloud_Software_Plugin::instance();
}

PHYSIOCLOU();
