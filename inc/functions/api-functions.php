<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    // Search Box Endpoint
    add_action('rest_api_init', function () {
        register_rest_route( 'theme/v1', 'generate-serp-url(?:/(?P<id>\d+))?', array(
                'methods'  => 'GET',
                'callback' => 'generateSearchSerpUrl',
                'permission_callback' => '__return_true',
        ));
    });

    // Enquiry Submit Endpoint
    add_action('rest_api_init', function () {
        register_rest_route( 'theme/v1', 'enquiry-submit(?:/(?P<id>\d+))?', array(
                'methods'  => 'GET',
                'callback' => 'enquirySubmit',
                'permission_callback' => '__return_true',
        ));
    });

    function enquirySubmit( WP_REST_Request $request ) {
        $params = $request->get_params();
        $enq_form_data = [];
        $response = [
            'status' => 'default',
            'message' => [
                'ss-error-general' => []
            ]
        ];

        if (isset($params['pickup_date']) && !empty($params['pickup_date'])) {
            $pickup_date_sanitized = sanitize_text_field($params['pickup_date']);
            if (isValidDate($pickup_date_sanitized, 'Y-m-d')) $pickup_date = strtotime($pickup_date_sanitized);
            $enq_form_data['pickup_date'] = getFormatedDateDE($pickup_date_sanitized);
        }

        if (isset($params['delivery_date']) && !empty($params['delivery_date'])) {
            $delivery_date_sanitized = sanitize_text_field($params['delivery_date']);
            if (isValidDate($delivery_date_sanitized, 'Y-m-d')) $delivery_date = strtotime($delivery_date_sanitized);
            $enq_form_data['delivery_date'] = getFormatedDateDE($delivery_date_sanitized);
        }

        if (isset($params['pickup_hour']) && !empty($params['pickup_hour'])) {
            $pickup_hour_sanitized = sanitize_text_field($params['pickup_hour']);
            if (isValidDate($pickup_hour_sanitized, 'H:i:s') || isValidDate($pickup_hour_sanitized, 'H:i')) {
                $pickup_hour = strtotime($pickup_hour_sanitized);
            }
            $pickup_hour_sanitized = date_format(date_create($pickup_hour_sanitized), 'H:i');
            $enq_form_data['pickup_hour'] = $pickup_hour_sanitized;
        }

        if (isset($params['delivery_hour']) && !empty($params['delivery_hour'])) {
            $delivery_hour_sanitized = sanitize_text_field($params['delivery_hour']);
            if (isValidDate($delivery_hour_sanitized, 'H:i:s') || isValidDate($delivery_hour_sanitized, 'H:i')) {
                $delivery_hour = strtotime($delivery_hour_sanitized);
            }
            $delivery_hour_sanitized = date_format(date_create($delivery_hour_sanitized), 'H:i');
            $enq_form_data['delivery_hour'] = $delivery_hour_sanitized;
        }

        // Check if locations from locations select element were received
        if (isset($params['locations']) && !empty($params['locations'])) {
            $locations = sanitize_text_field($params['locations']);
            $enq_form_data['location'] = $locations;
        }

        // If locations select was not present on PDP take default location from hidden field
        if (!array_key_exists('location', $enq_form_data)) {
            if (isset($params['location']) && !empty($params['location'])) {
                $location = sanitize_text_field($params['location']);
                $enq_form_data['location'] = $location;
            }
        }

        if (isset($params['first_lastname']) && !empty($params['first_lastname'])) {
            $first_lastname = sanitize_text_field($params['first_lastname']);
            $enq_form_data['first_lastname'] = $first_lastname;
        }

        if (isset($params['age']) && !empty($params['age'])) {
            $age = sanitize_text_field($params['age']);
            $enq_form_data['age'] = $age;
        }

        if (isset($params['email']) && !empty($params['email'])) {
            $email = sanitize_text_field($params['email']);
            $enq_form_data['customerEmail'] = $email;
        }

        if (isset($params['phone']) && !empty($params['phone'])) {
            $customerPhone = sanitize_text_field($params['phone']);
            $enq_form_data['customerPhone'] = $customerPhone;
        }

        if (isset($params['message']) && !empty($params['message'])) {
            $message = sanitize_text_field($params['message']);
            $enq_form_data['message'] = $message;
        }

        if (isset($params['seller_id']) && !empty($params['seller_id'])) {
            $seller_id = sanitize_text_field($params['seller_id']);
            $get_seller_email = getSellerEmail($seller_id);
            if ($get_seller_email) $enq_form_data['sellerEmail'] = $get_seller_email;
            else $enq_form_data['sellerEmail'] = false;
        }

        if (isset($params['item_name']) && !empty($params['item_name'])) {
            $enq_form_data['itemName'] = sanitize_text_field($params['item_name']);
        }

        if (isset($params['item_price']) && !empty($params['item_price'])) {
            $enq_form_data['itemPrice'] = sanitize_text_field($params['item_price']);
        }

        if (isset($params['item_url']) && !empty($params['item_url'])) {
            $enq_form_data['itemUrl'] = sanitize_text_field($params['item_url']);
        }

        if (isset($params['item_image']) && !empty($params['item_image'])) {
            $enq_form_data['itemImage'] = sanitize_text_field($params['item_image']);
        }

        if (isset($params['seller_phone']) && !empty($params['seller_phone'])) {
            $enq_form_data['sellerPhone'] = json_decode($params['seller_phone']);
        }


        ////////////////////////
        // VALIDATION
        ////////////////////////

        // 1. Validate Date
        if (isset($pickup_date) && isset($delivery_date)) {
            if ($pickup_date > $delivery_date) {
                $response['status'] = 'error';
                array_push($response['message']['ss-error-general'], _t('PICKUP_DATE_AFTER_DELIVERY_DATE_ERROR', true));
            }

            // 2. Validate Hour
            if (isset($pickup_hour) && isset($delivery_hour)) {
                if ($pickup_date == $delivery_date) {
                    if ($pickup_hour >= $delivery_hour) {
                        $response['status'] = 'error';
                        $response['message']['ss-error-general'] = _t('PICKUP_HOUR_AFTER_DELIVERY_HOUR_ERROR', true);
                    }
                }
            } else {
                $response['status'] = 'error';
                array_push($response['message']['ss-error-general'], _t('HOURS_MISSING', true));
            }
        } else {
            $response['status'] = 'error';
            array_push($response['message']['ss-error-general'], _t('DATES_MISSING', true));
        }

        // 3. First & last name
        if (isset($first_lastname)) {
            if ($first_lastname == '') {
                $response['status'] = 'error';
                $response['message']['ss-error-first_lastname'] = _t('IS_REQUIRED', true);
            }

            if (strlen($first_lastname) < 4) {
                $response['status'] = 'error';
                $response['message']['ss-error-first_lastname'] = _t('TOO_SHORT', true);
            }
        } else {
            $response['status'] = 'error';
            $response['message']['ss-error-first_lastname'] = _t('IS_REQUIRED', true);
        }

        // 4. Email
        if (isset($email)) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['status'] = 'error';
                $response['message']['ss-error-email'] = _t('EMAIL_INVALID', true);
            }
        } else {
            $response['status'] = 'error';
            $response['message']['ss-error-email'] = _t('IS_REQUIRED', true);
        }

        // 5. Phone
        if (isset($customerPhone)) {
            if ($customerPhone == '') {
                $response['status'] = 'error';
                $response['message']['ss-error-phone'] = _t('IS_REQUIRED', true);
            }

            if (strlen($customerPhone) < 4) {
                $response['status'] = 'error';
                $response['message']['ss-error-phone'] = _t('TOO_SHORT', true);
            }
        } else {
            $response['status'] = 'error';
            $response['message']['ss-error-phone'] = _t('IS_REQUIRED', true);
        }

        // 6. Bot Spam
        if (isset($params['second_email']) && !empty($params['second_email'])) {
            $response['status'] = 'error';
            array_push($response['message']['ss-error-general'], _t('BOT_SPAM_ERROR', true));
        }

        if (empty($response['message']['ss-error-general'])) unset($response['message']['ss-error-general']);

        if ($response['status'] == 'error') {
            return $response;
        } else {
            if (empty($enq_form_data['sellerEmail']) ||
                !array_key_exists('itemName', $enq_form_data) ||
                !array_key_exists('itemPrice', $enq_form_data) ||
                !array_key_exists('itemUrl', $enq_form_data)) {
                return $response = [
                    'status' => 'error',
                    'message' => [
                        'ss-error-general' => [_t('SELLER_EMAIL_ERROR', true)]
                    ]
                ];
            }

            $emailSent = sendEnqEmails($enq_form_data);

            if ($emailSent) {
                return 'success';
            } else {
                return $response = [
                    'status' => 'error',
                    'message' => [
                        'ss-error-general' => [_t('ENQUIRY_ERROR', true)]
                    ]
                ];
            }
        }
    }
