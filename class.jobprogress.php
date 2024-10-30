<?php
class JobProgress extends JP_Request {

	/**
	 * [$wpdb description]
	 * @var [object]
	 */
	protected $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->init_hooks();
	}

	/**
	 * Initializes WordPress hooks
	 */
	private function init_hooks() {
		add_action('admin_enqueue_scripts', array($this, 'admin_script'));
		add_action('wp_footer', array($this, 'scripts'));
		add_action('admin_menu',array($this, 'jp_admin_page') );
	}

	/**
	 * jobprogress admin section menus show on left side
	 * @return [type] [description]
	 */
	public function jp_admin_page() {
		add_menu_page( 
			'JP Options', 
			'JobProgress', 
			'manage_options', 
			'jp_admin_page', 
			array($this, 'authorization'),
			JP_MENU_ICON,
			6
		);
	}
	
	/**
	 * 
	 * @return [type] [description]
	 */
	public function authorization() {
		//get domain
		$domain = get_domain();
		$redirect_url = $this->get_redirect_url();

		if(	ine($_GET, 'access_token')
			&& ine($_GET, 'refresh_token')
			&& ine($_GET, 'expires_in')
			&& ine($_GET, 'token_type')
			&& ine($_GET, 'user_id')
			&& wp_verify_nonce($_GET['_wpnonce'], 'jp_connect_form')
			&& ! $this->is_connected()
		) {
			$jp_token_data = array(
				'access_token'  => $_GET['access_token'],
				'refresh_token' => $_GET['refresh_token'],
				'expires_in'    => $_GET['expires_in'],
				'token_type'    => $_GET['token_type']
			);

			// Check if the token is valid
			if(!$this->isValidToken($jp_token_data['access_token'])){
				return require_once(JP_PLUGIN_DIR. 'connect-form.php');
			}

			update_option('jp_token_options', $jp_token_data);

			$body = array(
				'includes[]' => 'company_details'
			);

			// get user detail from jobprogress
			$user = $this->get(JP_USER_URL.$_GET['user_id'] .'?'. http_build_query($body));
			if(ine($user, 'id')) {
				update_option('jp_connected_user', $user);	
			}
			
			if(($trades = get_transient("jp_trades")) === false 
				|| $trades === '' ) {
				$trades = $this->get(JP_TRADE_URL);
				set_transient("jp_trades", $trades, 86400);
			}
			
			if(($states = get_transient("jp_states")) === false 
				|| $states === '') {
				$states = $this->get(JP_STATE_URL);
				set_transient("jp_states", $states, 86400);
			}
			
			if(($countries = get_transient("jp_countries")) === false 
				|| $countries === '') {
				$user = $this->get_connected_user();
				$countries = $this->get(JP_COUNTRY_URL.'?company_id='.$user['company_id']);
				set_transient("jp_countries", $countries, 86400);
			}

			if(($referrals = get_transient("jp_referrals")) === false 
				|| $referrals === '') {
				$referrals = $this->get(JP_REFERRALS_URL);
				set_transient("jp_referrals", $referrals, 86400);
			}

			// customer form settings
			$settings = get_form_default_settings();
			update_option( 'jp_customer_form_fields', $settings );
		}

		if(ine($_POST, 'disconnect')) {
			$this->disconnect();
			echo "<script>window.location = '$redirect_url';</script>";
		}
		$jp_user = $this->get_connected_user();
		if($this->is_connected()) {

			return require_once(JP_PLUGIN_DIR. 'disconnect-form.php');	
		}

		return require_once(JP_PLUGIN_DIR. 'connect-form.php');
	}

	/**
	 * Add  jQuery Validation script on customers
	 */
	public  function scripts() {
		wp_register_script(
			'jquery-validate',
			plugin_dir_url( __FILE__ ) . 'js/jquery.validate.js',
			array('jquery'),
			'1.15.0',
			true
		);

		wp_enqueue_script(
			'custom',
			plugin_dir_url( __FILE__ ) . 'js/custom.js',
			array('jquery-validate')
		);

		wp_enqueue_script(
			'custom',
			plugin_dir_url( __FILE__ ) . 'js/customer-validation.js',
			array('jquery-validate')
		);

		wp_enqueue_script(
			'select2',
			plugin_dir_url( __FILE__ ) . 'js/select2.min.js',
			array('jquery-validate')
		);

		wp_enqueue_script(
			'mask',
			plugin_dir_url( __FILE__ ) . 'js/jquery.mask.min.js',
			array('jquery-validate')
		);

		// Include Underscore Js
		wp_enqueue_script( 'wp-util' );

		wp_enqueue_style(
			'select2',
			plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
			array(),
			'1.0'
		);
		wp_enqueue_style(
			'custom-style',
			plugin_dir_url( __FILE__ ) . 'css/custom.css',
			array(),
			'1.0'
		);
		// wp_enqueue_style(
		// 	'style',
		// 	plugin_dir_url( __FILE__ ) . 'css/style.css',
		// 	array(),
		// 	'1.0'
		// );
	}

	/**
	 * add admin script on connect and disconnect page and customer page
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function admin_script($hook){

		if((string)$hook === 'toplevel_page_jp_admin_page') {

			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_script('custom-admin-side', plugin_dir_url( __FILE__ ) .'js/custom-admin-side.js');
		}
		if((string)$hook === 'toplevel_page_jp_admin_page'
		|| (string)$hook === 'jobprogress_page_jp_customer_page' ) {
			wp_enqueue_style('custom', plugin_dir_url( __FILE__ ) . 'css/admin-style.css');
		}
		if((string)$hook === 'jobprogress_page_jp_settings_page' ) {
			wp_enqueue_style('custom-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
			wp_enqueue_style('form-settings', plugin_dir_url( __FILE__ ) . 'css/form-settings.css');
			wp_enqueue_script('form-settings', plugin_dir_url( __FILE__ ) .'js/form-settings.js');
			wp_enqueue_script('jquery-sort', plugin_dir_url( __FILE__ ) .'js/jquery-ui.min.js');
		}
	}

	/**
	 * customer table create on plugin activation
	 * @return [type] [description]
	 */
	public  function plugin_activation() {
		$customer_query = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."customers(
		  id int(10) unsigned NOT NULL AUTO_INCREMENT,
		  first_name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  last_name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  company_name varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  additional_emails varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  address text NOT NULL,
		  phones text,
		  is_sync tinyint(1) NOT NULL DEFAULT '0',
		  is_commercial tinyint(1) NOT NULL DEFAULT '0',
		  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  job text NULL,
		  customer_id int(10) DEFAULT NULL,
		  PRIMARY KEY (id)
		)";
        $sql = "ALTER TABLE ".$this->wpdb->prefix."customers ADD referred_by_id int(12) NULL, ADD referred_by_type varchar(255) DEFAULT NULL, ADD referred_by_note text DEFAULT NULL;";

        $addContactField = "ALTER TABLE ". $this->wpdb->prefix."customers ADD contact text DEFAULT NULL;";
		$this->wpdb->show_errors = false;
		$this->wpdb->query($customer_query);
		$this->wpdb->query($sql);
		$this->wpdb->query($addContactField);
	}

	/**
	 * jobprogress detail like accesss token and jobprgress user and cache data clear
	 * @return [type] [description]
	 */
	public  function plugin_deactivation() {
		
		if($this->is_connected()) {
			return $this->disconnect();
		}
	}


	/**
	 * get access token
	 * @return [array] [access token]
	 */
	public function get_access_token() {

		return get_option('jp_token_options');
	}

	/**
	 * check user is connected
	 * @return boolean [true or false]
	 */
	protected function is_connected() {

		return (get_option('jp_token_options' )) ? true : false;
	}

	
	/**
	 * update access token
	 * @param  [array] $token [description]
	 * @return [type]        [description]
	 */
	protected function update_access_token($token) {
		update_option('jp_token_options', $token);
	}

	/**
	 * plugin task removed
	 * @return [type] [description]
	 */
	private function disconnect() {
		$data = array(
			'domain' =>	get_domain()
		);
		$response = $this->request(JP_DISCONNECT_URL, $data, JP_DELETE_REQUEST);
		if(ine($response, 'status') && (int)$response['status'] != 200) {

			return false;
		}
		delete_transient('jp_trades');
		delete_transient('jp_states');
		delete_transient('jp_countries');
		delete_transient('jp_referrals');
		delete_option('jp_token_options');
		delete_option('jp_customer_form_fields');
		delete_option('jp_connected_user');
		wp_clear_scheduled_hook('jp_token_refresh_hook');
		wp_clear_scheduled_hook('jp_customer_sync_hook');
	}

	/**
	 * get redirect url
	 * @return [url] [description]
	 */
	private function get_redirect_url() {
		$url = get_domain().$_SERVER['REQUEST_URI'];
		$url_parts = parse_url($url);
    	$url = $url_parts['scheme'] 
    		. '://' . $url_parts['host'] 
    		. (isset($url_parts['path'])
    		?$url_parts['path']
    		:'');
    		
    	return $url . '?page=jp_admin_page';
	}

	public function get_connected_user() {

		return get_option('jp_connected_user');
		return get_option('jp_customer_form_fields');
	}

	/**
	 * Checks if a token is valid.
	 *
	 * @param mixed $token The token to be checked.
	 * @return bool Returns true if the token is valid, false otherwise.
	 */
	public function isValidToken($token) {
		$args = array(
			'headers'     => [
				'Authorization' => 'Bearer '.$token,
			],
		);
		$url = JP_TRADE_URL;
		$response = wp_remote_get($url, $args);	
		$response_body = wp_remote_retrieve_body($response);
		$body_array = json_decode($response_body, true);
		if(ine($body_array,'status') && (int)$body_array['status'] == 200) {
			return true;

		}
		return false;
	}
}