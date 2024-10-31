<?php


if (!defined('ABSPATH')) exit;

class PhysioCloudSoftware_Backend_Interface_Shortcodes
{
    private $helpers;
    function __construct()
    {
        $this->helpers = Physio_Cloud_Software_Plugin::instance()->helpers;
    }

    public function render($args)
    {
        $atts = array_change_key_case((array) $args, CASE_LOWER);
        ob_start();
        $usr = get_option('physiocloudsoftware_username');
        $key = get_option('physiocloudsoftware_apikey');
        $res = wp_remote_get('https://dev.physio.bse.com.cy/publicapi/Appointments/GetAvailableDates', array(
            'headers' => array(
                'Username' => $usr,
                'APIKey' => $key
            )
        ));
        $body = json_decode(wp_remote_retrieve_body($res));
        if (!is_wp_error($res) && !empty($body)) {
            $dates = $body;
        }


?>
        <div style="box-shadow:1px 1px 6px 0px;width:100%;max-width:700px;margin: 0 auto">
            <div style="background-color:white">
                <div style="padding:15px;padding-bottom:5px ">
                    <h3>Book Appointment</h3>
                </div>
            </div>
            <div style="background-color:lightgray;border-top:1px solid lightblue;border-bottom:1px solid lightblue">
                <div style="padding:20px">
                    <div class="book_mobile_elements" style="display:inline-block;width:48%;vertical-align:top">
                        <div>
                            <span style="display:block;width:228px;margin:0 auto">First Name *</span>
                            <input name="book_firstname" type="text" style="display:block;background-color:white;margin:0 auto" required />
                        </div>
                    </div>
                    <div class="book_mobile_elements" style="display:inline-block;width:48%;vertical-align:top">
                        <div>
                            <span style="display:block;width:228px;margin:0 auto">Last Name *</span>
                            <input name="book_surname" type="text" style="display:block;background-color:white;margin:0 auto" required />
                        </div>
                    </div>
                    <div class="book_mobile_elements" style="display:inline-block;width:48%;vertical-align:top">
                        <div style="padding-top:15px">
                            <span style="display:block;width:228px;margin:0 auto">DOB *</span>
                            <input name="book_dob" type="date" style="height: 47.5px;width: 228px;display:block;background-color:white;margin:0 auto" required />
                        </div>
                    </div>
                    <div class="book_mobile_elements" style="display:inline-block;width:48%;vertical-align:top">
                        <div style="padding-top:15px">
                            <span style="display:block;width:228px;margin:0 auto">Gender *</span>
                            <select name="book_gender" style="height: 47.5px;width: 228px;display:block;background-color:white;margin:0 auto" required>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="book_mobile_elements" style="display:inline-block;width:48%;vertical-align:top">
                        <div style="padding-top:15px">
                            <span style="display:block;width:228px;margin:0 auto">Phone *</span>
                            <input name="book_phone" type="number" style="height: 47.5px;width: 228px;display:block;background-color:white;margin:0 auto" required />
                        </div>
                    </div>
                    <div class="nohighlight" style="margin-top:20px;width:100%;background-color: white;box-shadow:1px 1px 3px 0px;padding:15px;overflow:auto;">
                        <div class="booking_dates_header" style="padding:5px;display:inline-flex">
                            <?php
                            $donedates = array();
                            foreach ($dates as $d) : ?>
                                <?php
                                $show = true;
                                $date = date("d-m", strtotime($d));
                                $day = date('D', strtotime($d));
                                if (!array_key_exists($date, $donedates)) {
                                    $donedates[$date] = [];
                                } else {
                                    $show = false;
                                }
                                array_push($donedates[$date], strtotime($d));
                                ?>
                                <?php if ($show) : ?>
                                    <div style="margin-right:5px;width:80px">
                                        <span style="display:block;text-align: center;"><?php echo wp_kses_post($day); ?></span>
                                        <span style="display:block;text-align: center;"><?php echo wp_kses_post($date); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php
                        echo '<div style="display:flex;height:380px">';
                        $lastdate = '';
                        foreach ($donedates as $key => $value) {
                            echo '<div style="">';
                            foreach ($value as $d) {
                                echo '<div onclick="markItem(this)" data-d="' . wp_kses_post(date(DATE_ATOM, $d)) . '" class="booking_dates_items" style="padding:5px;display:inline-flex">';
                                echo '<div style="width:75px;border:1px solid lightgray;margin-bottom:5px">';
                                echo '<span style="display:block;text-align: center;padding-top: 5px;padding-bottom: 5px;">' . wp_kses_post(date('H:i', $d)) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        echo '</div>';
                        ?>

                    </div>
                </div>
            </div>
            <div style="background-color:white;">
                <div style="padding:15px;padding-bottom:15px">
                    <div style="margin: 0 auto;text-align:center">
                        <span style="display:block;width:100%;background-color: trasparent" id="book_now_error"></span>
                        <button class="hover_cancel_now" style="transition: all 0.2s linear;border:1px solid lightgray;">Cancel</button>
                        <button class="hover_book_now" onclick="bookNow()" style="transition: all 0.2s linear;border:1px solid lightgray;">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function bookNow() {
                var dateselected = '';
                var items = document.getElementsByClassName('booking_dates_items_selected');
                if (items.length == 0) {
                    alert('You must select an appointment date and time');
                    return;
                }
                dateselected = items[0].dataset.d;

                var firstname = document.getElementsByName('book_firstname')[0];
                if (firstname.value == '' || firstname.value == null || firstname.value === undefined) {
                    alert('Firstname is required');
                    firstname.classList.add('required_bold_red');
                    return;
                }
                firstname.classList.remove('required_bold_red');

                var surname = document.getElementsByName('book_surname')[0];
                if (surname.value == '' || surname.value == null || surname.value === undefined) {
                    alert('Lastname is required');
                    surname.classList.add('required_bold_red');
                    return;
                }
                surname.classList.remove('required_bold_red');

                var dob = document.getElementsByName('book_dob')[0];
                if (dob.value == '' || dob.value == null || dob.value === undefined) {
                    alert('DOB is required');
                    dob.classList.add('required_bold_red');
                    return;
                }
                dob.classList.remove('required_bold_red');

                var gender = document.getElementsByName('book_gender')[0];
                if (gender.value == '' || gender.value == null || gender.value === undefined) {
                    alert('Gender is required');
                    gender.classList.add('required_bold_red');
                    return;
                }
                gender.classList.remove('required_bold_red');

                var phone = document.getElementsByName('book_phone')[0];
                if (phone.value == '' || phone.value == null || phone.value === undefined) {
                    alert('Phonee is required');
                    phone.classList.add('required_bold_red');
                    return;
                }
                phone.classList.remove('required_bold_red');

                var nonce = '<?php echo wp_create_nonce('physio_book_now_nonce'); ?>';
                apiPostXhr('<?php echo admin_url('admin-ajax.php'); ?>', 'physio_book_now_action', 'date=' + dateselected + '&firstname=' + firstname.value + "&surname=" + surname.value + "&dob=" + dob.value + "&gender=" + gender.value + "&phone=" + phone.value + "&nonce=" + nonce);
            }

            function apiPostXhr(url, action, data) {
                var elem = document.getElementById('book_now_error');
                let request = new XMLHttpRequest();

                request.open('POST', url, true);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                request.onload = function() {
                    var res = JSON.parse(this.responseText);
                    
                    if (res.msg != "OK" || res.msg === undefined) {
                        elem.innerHTML = res.msg;
                        elem.classList.add('required_bold_red');
                    }
                    else{
                        elem.innerHTML = "Your appointment has been sent and will inform you by email."
                        elem.classList.remove('required_bold_red');
                    }
                };
                request.onerror = function() {
                    // Connection error
                };
                request.send('action=' + action + "&" + data);
            }

            function markItem(item) {
                if (item.dataset.d == undefined) {
                    return;
                }
                var d = item.dataset.d;
                document.querySelectorAll('.booking_dates_items').forEach((e) => {
                    if (e.dataset.d !== d) {
                        e.classList.remove('booking_dates_items_selected');
                    }
                });
                item.classList.add('booking_dates_items_selected');
            }
        </script>
        <style>
            @media screen and (max-width:768px) {
                .book_mobile_elements {
                    display: block !important;
                    width: 100% !important;
                }

                .book_mobile_elements>div {
                    padding-top: 0px !important;
                }
            }

            .nohighlight {
                user-select: none;
                -moz-user-select: none;
                -webkit-user-select: none;
                -webkit-touch-callout: none;
                -ms-user-select: none;
            }

            .required_bold_red {
                border: 3px solid red !important;
            }

            .booking_dates_items_selected>div>span {
                background-color: #ddad14 !important;
            }

            .booking_dates_items {
                transition: all 0.2s linear;
                cursor: pointer;
            }

            .booking_dates_items>div>span {
                background-color: aqua;
            }

            .booking_dates_items:hover {
                transform: scale(1.1);
            }

            .booking_dates_items:hover>div>span {
                background-color: #ddad14;
            }

            .hover_cancel_now {
                background-color: white;
                color: gray;
            }

            .hover_cancel_now:hover {
                background-color: red;
                color: white;
                transform: scale(1.1);
            }

            .hover_book_now {
                background-color: #ddad14;
            }

            .hover_book_now:hover {
                background-color: green;
                transform: scale(1.1);
            }
        </style>
<?php
        return ob_get_clean();
    }
}
