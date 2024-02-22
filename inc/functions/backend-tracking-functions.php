<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    // Track PDP phone button clicks
    add_action('rest_api_init', function () {
        register_rest_route( 'theme/v1', 'be_track(?:/(?P<id>\d+))?', array(
                'methods'  => 'GET',
                'callback' => 'trackPdpClicks',
                'permission_callback' => '__return_true',
        ));
    });

    function addTrackedEventToDb($type = NULL, $device = NULL, $item_id = NULL, $location = NULL) {
        if (!empty($type)) {
            global $wpdb;
            $table_name = 'backend_tracking'; // HARDCODED

            $data = [
                'id' => NULL,
                'type' => $type,
                'device' => $device,
                'item_id' => $item_id,
                'location' => $location,
                'timestamp' => NULL
            ];

            $wpdb->insert($table_name, $data);
        }
    }

    function trackPdpClicks( WP_REST_Request $request ) {
        $params = $request->get_params();
        $location = false;

        if (!empty($params['type'])) $type = $params['type'];
        if (!empty($params['device'])) $device = $params['device'];
        if (!empty($params['item_id'])) $item_id = $params['item_id'];
        if (!empty($params['location'])) $location = $params['location'];

        if (isset($type) && !empty($type)
            && isset($device) && !empty($device)
            && isset($item_id) && !empty($item_id)) {
            addTrackedEventToDb($type, $device, $item_id, $location);

            return true;
        }
    }
