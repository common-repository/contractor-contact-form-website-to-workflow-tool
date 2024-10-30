<?php
class JP_Request {

	/**
	 * Get Request
	 * @param  [string] $url [url of api]
	 * @param  array  $body  [body data]
	 * @return [array]       [response of api]
	 */
	public function get($url, $body = array()) {
		$args = array(
		    'headers'     => $this->get_header(),
		    'body'        => $body,
		);
		$response = wp_remote_get($url, $args);	
		$response_body = wp_remote_retrieve_body($response);
		$body_array = json_decode($response_body, true);
		
		return ine($body_array,'data') ? $body_array['data'] : null;
	}

	/**
	 * Post Request
	 * @param  [string] $url [url of api]
	 * @param  array  $body  [body data]
	 * @return [array]       [response of api]
	 */
	public function post($url, $body) {
		$args = array(
		    'timeout'     => 60,
		    'headers'     => $this->get_header(),
		    'body'        => $body,
		); 

		$response =  wp_remote_post($url, $args);
		$response_body = wp_remote_retrieve_body($response);
		$body_array = json_decode($response_body, true);
		return $body_array;
	}

	/**
	 * any type of request like GET,POST,DELETE,PUT
	 * @param  [type] $url    [url of api]
	 * @param  [type] $body   [body data]
	 * @param  string $method [GET, DELETE, POST, PUT]
	 * @return [type]         [response of api]
	 */
	public function request($url, $body, $method = 'GET') {
		$arg = array(
			'timeout' => 60,
			'headers' => $this->get_header(),
			'body'	  => $body,
			'method'  => $method
		);
		$response = wp_remote_request($url, $arg);
		$response_body = wp_remote_retrieve_body($response);
		$body_array = json_decode($response_body, true);
		
		return $body_array;
	}

	/**
	 * Authorization Header
	 * @return [array] [header]
	 */
	public function get_header() {
		$jp_token_option = get_option('jp_token_options');
		$bearer_token =  'Bearer '.$jp_token_option['access_token'];
		return array('Authorization' => $bearer_token);
	}
}