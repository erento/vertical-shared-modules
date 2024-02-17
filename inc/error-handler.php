<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    
    //calling custom error handler
    set_error_handler("handleError");

    if (function_exists('add_action')) {
        add_action('qm/collect/new_php_error', 'handleError', 99999, 5 );

        // Enable bootom line for Ajax debugging
        add_action('init', 'handleErrorAjax', 99999, 5 );
    } 
    
    function handleError($code, $description, $file = null, $line = null, $context = null) {
        list($error, $log) = mapErrorCode($code);
        $data = array(
            'error' => $error,
            'file' => $file,
            'line' => $line,
            // 'debug' => print_r(debug_backtrace(2), true), ENABLE THIS FOR EXTENSIVE BACKTRACE DEBUG
            'debug' => '',
            'filter' => current_filter(),
            'message' => $error . ' (' . $code . '): ' . $description
        );

        // filter out wfm3_scan and litespeed_buffer_finalize error logs
        if (
            $data['filter'] === 'wfm3_scan' ||
            $data['filter'] === 'litespeed_buffer_finalize'
        ) {
            return;
        }

        if(isset($_SERVER['HTTP_HOST'])) {
            $data['url'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        } else {
            $data['cli'] = true;
        }

        error_log(print_r($data,true));
    }

    function handleErrorAjax() {
        if ( defined( 'DOING_AJAX' ) ) {
            $type = 'GET';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $type = 'POST';
            }

            // filter out async_litespeed error logs
            if (is_array($_REQUEST)) {
                if (array_key_exists('action', $_REQUEST) && $_REQUEST['action'] === 'async_litespeed') {
                    return;
                }
            }

            error_log("Ajax, " . $type . ": " . print_r(array($_REQUEST),true));
        }
    }
    
    // Map an error code into an Error word, and log location.
    function mapErrorCode($code) {
        $error = $log = null;
        switch ($code) {
            case E_PARSE:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                $log = LOG_ERR;
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_RECOVERABLE_ERROR:
                $error = 'Warning';
                $log = LOG_WARNING;
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                $log = LOG_NOTICE;
                break;
            case E_STRICT:
                $error = 'Strict';
                $log = LOG_NOTICE;
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $error = 'Deprecated';
                $log = LOG_NOTICE;
                break;
            default :
                break;
        }
        return array($error, $log);
    }
    