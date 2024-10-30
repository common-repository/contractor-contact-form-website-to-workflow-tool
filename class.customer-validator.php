<?php
class Customer_Validator {

	/**
	 * [$validation_error validation of customer form
	 * @var [array]
	 */
	public $wp_error = null;

	/**
	 * check customer form input value is valid
	 * @return boolean [description]
	 */
	public function is_valid() {
		$is_commercial = false;
		$has_error = false;
		$error = new WP_Error;

		if(ine($_POST, 'jp_customer_type2')) {
			$is_commercial = true;
		}

		if(!isset($_POST['jp_customer_type1']) && !isset($_POST['jp_customer_type2'])) {
			$error->add('customer_type', 'This field is required.');
			$has_error = true;
		}

		if( ($is_commercial) && ( sanitize_text_field($_POST['company_name_commercial']) === '') ) {
			$error->add('company_name_commercial', 'Please enter the company name.');
			$has_error = true;
		} 

		if (! $is_commercial && (sanitize_text_field($_POST['first_name']) === '')) {
			$error->add('first_name', 'Please enter the first name.');
			$has_error = true;
		}

		if (! $is_commercial && (sanitize_text_field($_POST['last_name']) === '')) {
			$error->add('last_name', 'Please enter the last name.');
			$has_error = true;
		} 

		// if(!ine($_POST, 'email')) {
		// 	$error->add('email', 'Please enter the email');
		// 	$has_error = true;
		// }

		if(ine($_POST, 'email') && ! filter_var(sanitize_text_field($_POST['email']), FILTER_VALIDATE_EMAIL)){
			$error->add('email', 'The email must be a valid email address.');
			$has_error = true;
		}
		
		if(isset($_POST['additional_emails']) 
			&& !empty($_POST['additional_emails'])) {
			$additionalEmails = array_filter($_POST['additional_emails']);
			foreach ($additionalEmails as $key => $additional_email) {
				if(! filter_var($additional_email, FILTER_VALIDATE_EMAIL))	{
					unset($_POST['additional_emails'][$key]);
				}
			}
		}

		// if(ine($_POST, 'address')) {
		// 	$address = $_POST['address'];
		// 	if(!ine($address, 'address')) {
		// 		$error->add('address', 'Please enter the address.');
		// 		$has_error = true;
		// 	}
		// 	if(!ine($address, 'city')) {
		// 		$error->add('city', 'Please enter the city.');
		// 		$has_error = true;
		// 	}
		// 	if(!ine($address, 'zip')) {
		// 		$error->add('zip', 'Please enter the zip code.');	
		// 		$has_error = true;
		// 	}
		// 	if(!ine($address, 'country_id')) {
		// 		$error->add('country_id', 'Please select the country.');
		// 		$has_error = true;
		// 	}
		// 	if(!ine($address, 'state_id')) {
		// 		$error->add('state_id', 'Please select the state.');
		// 		$has_error = true;
		// 	}
		// } else {
		// 	$error->add('address', 'Please enter the address.');
		// 	$error->add('city', 'Please enter the city.');
		// 	$error->add('zip', 'Please enter the zip code.');	
		// 	$error->add('country_id', 'Please select the country.');
		// 	$error->add('state_id', 'Please select the state.');
		// 	$has_error = true;
		// }

		if(ine($_POST, 'job')) {
			if(! ine($_POST['job'], 'trades')) {
				$error->add("job_trades", 'Please select the trades.');
				$has_error = true;
			}

			if(! ine($_POST['job'], 'description')) {
				$error->add("job_description", 'Please enter the description.');
				$has_error = true;
			}

			if(ine($_POST['job'], 'trades') 
				&& in_array(24, $_POST['job']['trades'])
				&& !ine($_POST['job'], 'other_trade_type_description')) {
				$error->add("other_trade_type_description", 'Please enter the note.');
				$has_error = true;
			}

		}

		// if(!ine($_POST, 'referred_by_id')) {
		// 	$error->add('referred_by_id', 'Please select the referred by.');
		// 	$has_error = true;
		// }

		if(ine($_POST, 'referred_by_id') 
			&& $_POST['referred_by_id'] === 'other' 
			&& !ine($_POST, 'referred_by_note')) {
			$error->add('referred_by_note', 'Note field field is required');
			$has_error = true;
		}
		
		if(count($_POST['phones'])) {
			$phones = array_filter($_POST['phones']);
			foreach ($phones as $key => $value) {
				if(! ine($value, 'label')) {
					$error->add("phones.$key.label", 'Please choose the phone label.');
					$has_error = true;
				}
				if(! ine($value, 'number')) {
					$error->add("phones.$key.number", 'This field is required.');
					$has_error = true;
					continue;
				}
				$number = str_replace(array( '(', ')',' ','-' ), '', $value['number']);
				if(!is_numeric($number)) {
					$error->add("phones.$key.number", 'The phone must be a number.');
					$has_error = true;
					continue;
				}

				if(strlen($number) > 10) {
					$error->add("phones.$key.number", 'The phone number may not be greater than 10 digit.');
					$has_error = true;
				}

				if(strlen($number) < 10) {
					$error->add("phones.$key.number", 'The phone number may not be less than than 10 
					digit.');
					$has_error = true;
				}
			}
		}
		$this->wp_error = $error;
		if($has_error) {

			return false;
		}
		
		return true;
	}

	/**
	 * get validation error
	 * @return [array] [validation errors]
	 */
	public function get_wp_error() {

		return $this->wp_error;
	}
}