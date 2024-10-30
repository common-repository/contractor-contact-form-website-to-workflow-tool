<?php
/*
Plugin Name: Contractor Contact Form Website to Workflow Tool
Description: This useful plugin is a website to workflow tool that allows contractors to drive leads directly from their own website form inquiries directly into their Leap workcenters. Leap is  a Cloud based Business Management Platform for Home Improvement Contractors. With this useful plugin, you can drive customers directly from your website into your Leap workflow stages and begin to populate both customer and job related leads and prospects for immediate and mistake free follow-up.  This is an automated Customer Relationship Management tool which will save you time and minimize mistakes. For more information about Leap, please visit our website.
Plugin URI: http://example.com
Version: 4.3.0
Author: Leap
Author URI: http://www.jobprogress.com/
*/
// exit;
define('JP_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('JP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));
define('API_BASE_URL', 'https://jobprogress.com/api/public/api/v1/');
define('PUBLIC_JP_CLIENT_ID', '42766958');
define('PUBLIC_JP_CLIENT_SECRET', 'schs1EKRpLaS1auhTIc25JrlWSjkry1P');
define('JP_AUTHORIZATION_URL', API_BASE_URL.'login_form');
define('JP_DISCONNECT_URL', API_BASE_URL.'logout');
define('JP_REFRESH_TOKEN_URL', API_BASE_URL.'oauth2/renew_access_token');
define('JP_TRADE_URL', API_BASE_URL.'company/trades');
define('JP_STATE_URL', API_BASE_URL.'company/states');
define('JP_COUNTRY_URL', API_BASE_URL.'countries');
define('JP_ADD_CUSTOMER_URL', API_BASE_URL.'customers/save_customer_third_party');
define('JP_USER_URL', API_BASE_URL.'company/users/');
define('JP_REFERRALS_URL', API_BASE_URL.'referrals');
define('JP_REFRESH_TOKEN_GRANT_TYPE', 'refresh_token');
define('JP_CUSTOMER_LIMIT_PAGINATION', 10);
define('JP_DELETE_REQUEST', 'DELETE');
define('JP_MENU_ICON', 'https://jobprogress.com/app/favicon.ico');
define('JP_CUSTOMER_FORM_SAVED', 'Your request sent successfully.');
require_once(JP_PLUGIN_DIR. 'class.jp-request.php');
require_once(JP_PLUGIN_DIR. 'class.jobprogress.php');
require_once(JP_PLUGIN_DIR. 'class.customer.php');
require_once(JP_PLUGIN_DIR. 'class.form_settings.php');
require_once(JP_PLUGIN_DIR. 'class.scheduler.php');
$scheduler = New Scheduler;
$customer  = New Customer;
$settings = New Form_Settings;
register_activation_hook(__FILE__, array($customer, 'plugin_activation'));
register_deactivation_hook(__FILE__, array($customer, 'plugin_deactivation'));

// For form settings page
register_activation_hook(__FILE__, array($settings, 'plugin_activation'));
register_deactivation_hook(__FILE__, array($settings, 'plugin_deactivation'));

function ine($haystack,$needle){
	return (isset($haystack[$needle]) && !empty($haystack[$needle]));
}

function get_form_default_settings()
{
    // customer form settings
    $settings = array(
        'field1' => array(
            'name' => 'customer_type',
            'title' => 'Customer Type',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field2' => array(
            'name' => 'customer_name',
            'title' => 'Customer Name',
            'isHide' => '0',
            'isRequired' => '1',
            'isCommercial' => array(
                'name' => 'customer_name_commercial',
                'title' => 'Commercial',
                'isHide' => '0',
                'isRequired' => '1'
            )
        ),
        'field3' => array(
            'name' => 'company_name',
            'title' => 'Company Name',
            'isHide' => '0',
            'isRequired' => '1',
            'isCommercial' => array(
                'name' => 'company_name_commercial',
                'title' => 'Commercial',
                'isHide' => '0',
                'isRequired' => '1'
            )
        ),
        'field4' => array(
            'name' => 'customer_phone',
            'title' => 'Customer Phone',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field5' => array(
            'name' => 'customer_email',
            'title' => 'Customer Email',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field6' => array(
            'name' => 'customer_address',
            'title' => 'Customer address',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field7' => array(
            'name' => 'billing_address',
            'title' => 'Billing address',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field8' => array(
            'name' => 'referred_by',
            'title' => 'Referred By',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field9' => array(
            'name' => 'trades',
            'title' => 'Trades',
            'isHide' => '0',
            'isRequired' => '1'
        ),
        'field10' => array(
            'name' => 'description',
            'title' => 'Description',
            'isHide' => '0',
            'isRequired' => '1'
        )
    );

    return $settings;
}
if (! function_exists('dd')) {
    function dd($data = array()) {
    	echo "<pre>";
    	print_r($data);
    	exit;
    }
}

function format_number($number = false) {
	return "(".substr($number, 0, 3).") ".substr($number, 3, 3)."-".substr($number,6);
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( ! isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( ! isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

/**
 * get domain
 * @return [url] [site domain]
 */

 function get_domain() {
    $host = ((!empty($_SERVER['HTTPS']) 
            && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] === 443)
            ? 'https://'
            :'http://';

    return $host . $_SERVER['HTTP_HOST'];
}
