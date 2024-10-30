<?php 
    class Form_Settings extends JobProgress {
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
        
        /**
         * 
         * @return [type] [description]
         */
        public function __construct() {
            parent::__construct();
            add_action( 'admin_menu',array($this, 'jp_admin_page') );
            add_action( 'admin_init', array( $this, 'form_register_settings' ) );
        }


        /**
         * jobprogress admin section menus show on left side
         * @return [type] [description]
         */
        public function jp_admin_page() {
            // show Settings label on menu list
            add_submenu_page( 
                'jp_admin_page', 
                'Form Settings', 
                'Settings', 
                'manage_options', 
                'jp_settings_page', 
                array($this, 'index')
            );
        }


        function form_register_settings(){
            register_setting( 'jp_form_settings', 'jp_customer_form_fields', 'sd_callback_function' );
            register_setting( 'jp_form_settings', 'jp_use_custom_theme', 'sd_callback_function' );
        }

        /**
         * form fields manager in admin panel
         * @return [type] [description]
         */
        public function index() {
            $settings 	= get_option( 'jp_customer_form_fields' );
            $options = get_option( 'jp_use_custom_theme' );

            // hide/show and sorting of form fields form
            return require_once(JP_PLUGIN_DIR. 'form-settings-index-page.php');
        }
    }