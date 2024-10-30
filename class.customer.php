<?php 
class Customer extends JobProgress {

	/**
	 * [$validation_error validation error
	 * @var null
	 */
	public $wp_error = null;

	/**
	 * [$input description]
	 * @var array
	 */
	public $input = array();
	
	protected $customer_form_saved = null;

	protected $customer_form_wpdb_error = null;

	/**
	 * 
	 * @return [type] [description]
	 */
	public function __construct() {
		parent::__construct();
		add_shortcode( 'jobprogress_customer_form_code', array($this, 'cf_short_code') );
		add_action( 'admin_menu',array($this, 'jp_admin_page') );
	}

	/**
	 * customer form show and save customer data
	 * @return [type] [description]
	 */
	public  function cf_short_code() {
		ob_start();
		if(!$this->is_connected()) return false;
		$this->save_customer();
		return ob_get_clean();
	}

	/**
	 * jobprogress admin section menus show on left side
	 * @return [type] [description]
	 */
	public function jp_admin_page() {
		// show customer label on menu list
		add_submenu_page( 
			'jp_admin_page', 
			'Customer Manager', 
			'Customers', 
			'manage_options', 
			'jp_customer_page', 
			array($this, 'index')
		);
	}

	/**
	 * listing of customer page on index page
	 * @return [type] [description]
	 */
	public function index() {
		$input = $_GET;
		$page_num = isset( $input['page_num'] ) ? absint( $input['page_num'] ) : 1;
		$order   = ine($input, 'order') ? $input['order'] : 'desc';
		$order_by = ine($input, 'order_by') ? $input['order_by'] : 'created_at';
		$limit = ine($input, 'limit') ? $input['limit'] : JP_CUSTOMER_LIMIT_PAGINATION; 
		$offset = ( $page_num - 1 ) * $limit;
		$total = $this->wpdb->get_var( "SELECT COUNT(id) FROM {$this->wpdb->prefix}customers" );
		$num_of_pages = ceil( $total / $limit );
		$sql = "SELECT * FROM {$this->wpdb->prefix}customers";
		
		if(ine($input, 'date')) {
			$sql .= " where DATE_FORMAT(created_at, '%Y-%m-%d') = '". sanitize_text_field($input['date']) . "'";
		}
		$sql .= " ORDER BY $order_by $order";
		$sql .= " LIMIT $offset, $limit";
		$customers = $this->wpdb->get_results( $sql );
		
		return require_once(JP_PLUGIN_DIR. 'customer-index-page.php');
	}

	/**
	 * Save jobprogress customer 
	 * @return [type] [description]
	 */
	public function save_customer() {
		if(isset($_POST) && !empty($_POST) && wp_verify_nonce( $_POST['_wpnonce'], 'submit_jp_customer_form' ) ) {

			require_once(JP_PLUGIN_DIR. 'class.customer-validator.php');
			$validator = new Customer_Validator;		
			if($validator->is_valid()) {
				$input = $_POST;
				$table_name = $this->wpdb->prefix . 'customers';
				require_once(JP_PLUGIN_DIR. 'class.customer-data-map.php');
				$customer =  new Customer_Data_Map($input);
				$plugin_input = $customer->get_plugin_input();
				$this->wpdb->insert($table_name, $plugin_input);
				if(! $this->wpdb->last_error) {
					$this->show_form(true);
				}else {
					$this->customer_form_wpdb_error = $this->wpdb->last_error;
				}
			}
			$this->wp_error = $validator->get_wp_error();
		}
		$this->show_form();
	}

	/**
	 * show add customer page
	 * @return [html] [show customer page]
	 */
	public function show_form($refresh = false, $queryString = array()) {

		if($refresh) {
			set_transient("jp_form_submitted", 1, 10);
			$url = get_domain() . $_SERVER['REQUEST_URI'];
			echo '<script type="text/javascript">
			window.location = "'.esc_url($url).'"
			</script>';
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
		$other_referr = array(
			'id'   => 'other',
			'name' => 'Other(Enter here)'
		);
		if($referrals) {
			array_unshift($referrals, $other_referr);
		} else{
			$referrals[] = $other_referr;
		}

		require_once(JP_PLUGIN_DIR. 'customer-template.php');
		return require_once(JP_PLUGIN_DIR. 'customer-form-page.php');
	}

		
	/**
	 * get wrapper of error message
	 * @param  string $code [error code]
	 * @return [html]       [error message ]
	 */
	protected function get_error_wrapper($code = '') {
		$error = null;
		if(! $this->wp_error) {
			return false;
		}
		if(! $this->wp_error->get_error_message($code)) {
			return false;
		}
		
		$id  = str_replace(array('.','_'), '-', $code). '-error';
		$for = str_replace('.', '_', $code).'_error';
	 	$html = '<label id='.$id.' class="error" for='.$for.'>';
	 	$html .= $this->wp_error->get_error_message($code);
	 	$html .= '</label>';

		return $html;
	}
}