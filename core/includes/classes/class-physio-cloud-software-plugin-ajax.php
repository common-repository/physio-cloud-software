<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class PhysioCloudSoftware_Backend_Interface_Ajax
{
    private $helpers;
    function __construct()
    {
        $this->helpers = Physio_Cloud_Software_Plugin::instance()->helpers;
    }

    function physio_book_now_action_function()
    {
        ob_clean();
        if (
            !isset($_POST['nonce'])
            || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'physio_book_now_nonce')
        ) {
            $this->JsonResponse("Failed to verify nonce. Please refresh the page.");
        }
        $date = isset($_POST['date']) && !empty($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        $firstname = isset($_POST['firstname']) && !empty($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';
        $surname = isset($_POST['surname']) && !empty($_POST['surname']) ? sanitize_text_field($_POST['surname']) : '';
        $dob = isset($_POST['dob']) && !empty($_POST['dob']) ? sanitize_text_field($_POST['dob']) : '';
        $gender = isset($_POST['gender']) && !empty($_POST['gender']) ? sanitize_text_field((int)$_POST['gender']) : '';
        $phone = isset($_POST['phone']) && !empty($_POST['phone']) ? sanitize_text_field((int)$_POST['phone']) : '';
        if ($date == '' || date_parse($date) === false) {
            $this->JsonResponse('You must select an appointment date and time');
        }
        if ($firstname == '') {
            $this->JsonResponse('Firstname is required');
        }
        if ($surname == '') {
            $this->JsonResponse('Lastname is required');
        }
        if ($dob == '') {
            $this->JsonResponse('DOB is required');
        }
        if ($gender == '') {
            $this->JsonResponse('Gender is required');
        }
        if ($gender != 1 && $gender != 2) {
            $this->JsonResponse('Incorrect Gender selected.');
        }
        if ($phone == '') {
            $this->JsonResponse('Phone is required');
        }

        $msg = file_get_contents(PHYSIOCLOU_PLUGIN_DIR . 'core/includes/assets/templates/appointment.html');
        $date_array = date_parse($date);
        $dp = date('d-m-Y H:i', mktime($date_array['hour'], $date_array['minute'], $date_array['second'], $date_array['month'], $date_array['day'], $date_array['year']));
        $msg = str_ireplace('%dateselected%', $dp, $msg);
        $msg = str_ireplace('%firstname%', $firstname, $msg);
        $msg = str_ireplace('%surname%', $surname, $msg);
        $msg = str_ireplace('%dob%', $dob, $msg);
        $msg = str_ireplace('%gender%', (($gender == 1) ? 'Male' : 'Female'), $msg);
        $msg = str_ireplace('%tel%', $phone, $msg);
        $usr = get_option('physiocloudsoftware_username');
        $res = wp_mail(array(
            get_bloginfo('admin_email'),
            '',
        ), 'New Appointment Requred', $msg, array('Content-Type: text/html; charset=UTF-8'));
        if (!$res) {
            $this->JsonResponse('Failed to send the appointment request. Please refresh the page abd try again in a few minutes.');
        }
        $this->JsonResponse('OK');
    }
    function JsonResponse($msg)
    {
        echo wp_send_json(array(
            'msg' => $msg
        ));
    }
}
