<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class PhysioCloudSoftware_Backend_Interface_Credentials
{
    function __construct()
    {
        $this->helpers = Physio_Cloud_Software_Plugin::instance()->helpers;
    }
    function LoadMenu($postdata, $method, $tab)
    {
        $usr = '';
        $key = '';
        if (
            $method == "POST" &&
            isset($postdata['username']) &&
            !empty($postdata['username']) &&
            isset($postdata['apikey']) &&
            !empty($postdata['apikey'])
        ) {

            $usr = sanitize_email($postdata['username']);
            $key = sanitize_text_field($postdata['apikey']);

            $res = wp_remote_get('https://dev.physio.bse.com.cy/publicapi/Appointments/TestConnection', array(
                'headers' => array(
                    'Username' => $usr,
                    'APIKey' => $key
                )
            ));
            
            $body = json_decode(wp_remote_retrieve_body($res));
            if ($body == 'success') {
                update_option('physiocloudsoftware_username',$usr, false);
                update_option('physiocloudsoftware_apikey',$key, false);
?>
                <h2>Credentials ok</h2>
            <?php
            } else {
            ?>
                <h2>Invalid Credentials</h2>
        <?php
            }
        }
        else {
            $usr = get_option('physiocloudsoftware_username');
            $key = get_option('physiocloudsoftware_apikey');
        }

        ?>
        <form action="" method="post" name="myForm" style="padding-top:20px;">
            <h3>Enter your credentials</h3>
            Username <input id="username" type="text" name="username" style="display:block" value="<?php echo wp_kses_post($usr); ?>" />
            API Key <input id="apikey" type="password" name="apikey" style="display:block" value="<?php echo wp_kses_post($key); ?>" />
            <input type="submit" name="submit" value="Submit" style="display:block" />
        </form>

<?php


    }
    function LoadMenuShortCode() {
        ?>
        <h4>Use the shortcode [physiocloudsoftware_booking] to add booking on your website</h4>
        <?php
    }
}
