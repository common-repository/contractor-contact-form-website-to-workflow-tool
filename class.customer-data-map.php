<?php
class Customer_Data_Map {

	protected $input = array();

	public function __construct($input = array()) {
		$this->input = $input;
	}
	/**
	 * map customer data for local database storage
	 * @return [array] [customer data]
	 */
	public function get_plugin_input() {
		$map = array('email', 'first_name', 'last_name', 'company_name', 'is_sync', 'referred_by_id', 'referred_by_note');
		$addressFields = array('address','address_line_1','city','state_id','country_id','zip');
		$address = array();
		$data = $this->map_inputs($map);
		$address['address'] = $this->mapFirstSubInputs($addressFields, 'address');

		if(ine($this->input, 'same_as_customer_address')){
			$address['same_as_customer_address'] = true;
			$address['billing'] = $address['address'];
		} else {
			$address['billing']	= $this->mapFirstSubInputs($addressFields, 'billing');
			$address['same_as_customer_address'] = false;
		}

		$data['address'] = json_encode($address, true);
		$data['is_commercial'] = false ;

		if(ine($this->input, 'jp_customer_type2')) {
			//in commercial case company name and last name should be null
			$data['is_commercial']  = true;
			$data['first_name']   = htmlentities($this->input['company_name_commercial']);
			$data['company_name']    = '';
			$data['last_name']     = '';
			$data['contact']  = json_encode($this->input['contact']);
		}
		
		$data['phones'] = json_encode($this->map_phone_inputs(), true);
		$data['additional_emails'] = json_encode($this->map_additional_mail_input(), true);
		$data['created_at'] = current_time('mysql');
		$job = $this->map_job_input();
		$data['job'] = json_encode($job, true);
		if($this->input['referred_by_id'] === 'other') {
			$data['referred_by_note'] = $this->input['referred_by_note'];
			$data['referred_by_type'] = 'other';
		
		} else if(!empty($this->input['referred_by_id'])
			&& $this->input['referred_by_id'] != 'other'
			&& $this->input['referred_by_id'] > 0) {
			$data['referred_by_type'] = 'referral';
			$data['referred_by_id'] = $this->input['referred_by_id'];
		}

		return $data;
	}
	
	/**
     *  Map  Model fields to inputs
     *  @return array of mapped array fields.
     */
    private function mapFirstSubInputs($map, $inputKey){
    	$ret = array();
    	foreach ($map as $key => $value) {
			if(is_numeric($key)){
				$ret[$value] = isset($this->input[$inputKey][$value]) ? htmlentities($this->input[$inputKey][$value]) : "";
			}else{
				$ret[$key] = isset($this->input[$inputKey][$value]) ? htmlentities($this->input[$inputKey][$value]) : "";
			}
		}

        return $ret;
    }

    /**
     * map phone inputs
     * @return [array] [phones input]
     */
    private function map_phone_inputs() {
    	$phones = $this->input['phones'];
    	$ret = array();
    	foreach ($phones as $key => $phone) {
    		$ret[$key]['label'] = isset($phone['label']) ? htmlentities($phone['label']) : '';
    		$ret[$key]['number'] = isset($phone['number']) ? htmlentities($phone['number']) : '';
    		$ret[$key]['ext'] = isset($phone['ext']) ? htmlentities($phone['ext']) : '';
    	}

    	return $ret;
    }

    /**
     * map_additional mail input
     * @return [arrat] [additional email input]
     */
    private function map_additional_mail_input() {
    	if(! ine($this->input, 'additional_emails')) return array();
    	$additional_emails = $this->input['additional_emails'];
    	return $this->map_numeric_array_inputs($additional_emails);
    }

    /**
     * map job input
     * @return [array] [job input]
     */
    private function map_job_input() {
    	$jobInput = $this->input['job'];
    	$job['trades'] = $this->map_numeric_array_inputs($jobInput['trades']);
    	$job['description'] = htmlentities($jobInput['description']);
    	$job['other_trade_type_description'] = null;
    	if(in_array(24, $jobInput['trades'])) {
    		$job['other_trade_type_description'] = htmlentities($jobInput['other_trade_type_description']);
    	}

    	return $job;
    }

    /**
	 * Map input
	 * @param  [array] $map [input map]
	 * @return [array]      [mapped input]
	 */
	private function map_inputs($map) {
		$ret = array();
    	// empty the set default.
    	if(empty($input)) {
    		$input = $this->input;
    	}

    	foreach ($map as $key => $value) {
			if(is_numeric($key)){
				$ret[$value] = isset($input[$value]) ? htmlentities($input[$value]) : "";
			}else{
				$ret[$key] = isset($input[$value]) ? htmlentities($input[$value]) : "";
			}
		}

        return $ret;
	}

	/**
	 * map numeric array input like []
	 * @param  [type] $array_input [description]
	 * @return [type]              [description]
	 */
	private function map_numeric_array_inputs($array_input) {
		$ret = array();
    	foreach ($array_input as $key => $value) {
    		$ret[] = htmlentities($value);
    	}
    	
    	return $ret;
	}
}