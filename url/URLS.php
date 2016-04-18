<?php

/**
 * Class to test URL endpoints
 * 
 */

class URLS {

  protected $input_urls;
  protected $input_urls_method;
  protected $input_urls_headers;
  protected $input_urls_run_count;
  protected $url_test_summary;
  protected $start_time;
  protected $end_time;
  protected $total_time;

  public function __construct() {

    $this->input_urls = array();
    $this->input_urls_method = array();
    $this->input_urls_headers = array();
    $this->input_urls_run_count = array();
    $this->url_test_summary = array();
  }

  /**
   * Utility function to output how the URLS class gathers input
   *
   */
  public function explanation() {

  	echo "\n";
  	echo "******************************************************************** \n";
  	echo "\n";
  	echo "*** URL TEST ***\n";
  	echo "\n";
  	echo "Please input URL(s) for the endpoint(s) that you would like to test. \n";
  	echo "URL input is gathered using the following input options: \n";
  	echo "\n";
  	echo "* URL. (Required) A valid http URL to an endpoint. \n";
  	echo "* Method. (Required) GET or POST \n";
  	echo "* Header 1. (Optional) Key:Value \n";
  	echo "* Header 2. (Optional) Key:Value \n";
  	echo "* Header 3. (Optional) Key:Value \n";
  	echo "* Header N. (Optional) Key:Value \n";
  	echo "\n";
  	echo "******************************************************************** \n";
    echo "\n";
  }

  /**
   * Utility function used to gather URL input from the user
   * This input will be saved in local variables for cURL execution
   */
  public function gather_input() {

    // Enter URL
    // @TODO increase input validation (this assumes http:// or https://)
  	echo "Please enter a URL. Please use http(s): \n";
  	$url = trim(fgets(STDIN));
  	if (!empty($url) && strpos($url, 'http') !== FALSE) {
  	  $this->input_urls[] = $url;
  	} else {
  	  die("\n*** A Proper URL Was Not Entered. Script is exiting ***\n");
  	}

    // Enter method
  	echo "Please enter 1 for POST or 2 for GET: \n";
  	$method = (int)trim(fgets(STDIN));
  	if (!empty($method) && is_numeric($method) && $method === 1) {
  	  $this->input_urls_method[] = TRUE;
  	} else {
  	  $this->input_urls_method[] = FALSE;
  	}

  	// Run count
  	echo "How many times would you like to request this URL: \n";
    $count = (int)trim(fgets(STDIN));
    if (!empty($count) && is_numeric($count) && $count > 0) {
      $this->input_urls_run_count[] = $count;
    } else {
      $this->input_urls_run_count[] = 1;
    }
    
    // Headers
    echo "Do you wish to enter header? [y/n] \n";
    $header_flag = trim(fgets(STDIN));
    $header_flag = strtolower($header_flag);
    // Validate header input
  	if (!empty($header_flag) && $header_flag === 'y') {
	  $e = 0;
	  // Header array
	  $headers = array();
	  // Allow up to 10 headers to be entered
	  while ($e < 10) {
	    // Header Key
	    echo "Enter a header key: \n";
	    $header_key = trim(fgets(STDIN));
	    if (!empty($header_key)) {
	      $headers[$e]['key'] = $header_key;
	    } else {
	      echo "** Invalid header key ** \n";
	      break;
	    }
        // Header Value
	    echo "Enter a header value: \n";
	    $header_value = trim(fgets(STDIN));
	    if (!empty($header_value)) {
          $headers[$e]['value'] = $header_value;
	    } else {
	      echo "** Invalid header value ** \n";
	      break;
	    }

	    // Check if the user wants to continue inputting headers
	    echo "Do you have another header to input? [y/n] \n";
	    $header_input = trim(fgets(STDIN));
	    $header_input = strtolower($header_input);
	    if (!empty($header_input) && $header_input === 'n') {
          break;
	    }
	    $e++;
	  }
	  // Add the headers to the url
	  $this->input_urls_headers[] = $headers;
	} else {
	  echo "Cannot understand user input. Skipping. \n";
	}

    // Continue with input
    echo "Do you wish enter another URL? [y/n] \n";
    $continue = trim(fgets(STDIN));
    $continue = strtolower($continue);
  	if (!empty($continue) && $continue === 'y') {
  	  $this->gather_input();
  	} else {
  	  echo "\n";
  	  for ($i = 0; $i < count($this->input_urls); $i++) {
  	  	echo "URL: " . $this->input_urls[$i] . " \n";
  	  	if ($this->input_urls_method[$i]) {
  	  		echo "Method: POST \n";
  	  	} else {
            echo "Method: GET \n";
  	  	}
  	  	echo "Run Count: " . $this->input_urls_run_count[$i] . " \n";

  	  	if (isset($this->input_urls_headers[$i])) {
  	  	  echo "Headers: \n";
  	  	  foreach ($this->input_urls_headers[$i] as $key => $value) {
  	  	    echo "  Key: " . $value['key'] . " => " . $value['value'] . " \n";
  	  	  }	
  	  	}
  	  }
  	}
  }

  /**
   * Utility function that executes the cURL requests that were defined by the user
   *
   */
  public function execute() {

  	echo "******* Executing Requests *******\n";
  	echo "\n";
    for ($i = 0; $i < count($this->input_urls); $i++) {
    	echo "URL: " . $this->input_urls[$i] . " \n";

	    for ($e = 0; $e < $this->input_urls_run_count[$i]; $e++) {

	      // Set the start time
	      $this->set_start_time();
	      $curl = curl_init(); 

	      $headers = array();
	      if (isset($this->input_urls_headers[$i])) {
	        foreach ($this->input_urls_headers[$i] as $key => $value) {
	      	  $headers[] = $value['key'] . ": " . $value['value'];
	        }
	      }

	      curl_setopt($curl, CURLOPT_URL, $this->input_urls[$i]); 
	      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 
	      curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	      curl_setopt($curl, CURLOPT_MAXREDIRS, 4); 
	      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); 
	      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); 

	      if ($this->input_urls_method[$i]) {
	      	curl_setopt($curl, CURLOPT_POST, TRUE); 
	      }

	      if (count($headers) > 0) {
	      	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	      }

	      $curl_return = curl_exec($curl); 
	      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	      echo "Satus: " . $status . " \n"; 
	      $this->set_end_time();
	      echo "Total request time: " . $this->total_time . "\n";
	      curl_close($curl); 
	    }
	}
  }

  /**
   * Utility function to set a start time to calculate the total time of a request
   *
   */
  public function set_start_time() {

  	$m_time = microtime();
    $m_time = explode(' ', $m_time);
    $m_time = $m_time[1] + $m_time[0];
    $this->start_time = $m_time;
  }

  /**
   * Utility function to set an end time to calculate the total time of a request
   *
   */
  public function set_end_time() {

  	$m_time = microtime();
    $m_time = explode(" ", $m_time);
    $m_time = $m_time[1] + $m_time[0];
    $this->end_time = $m_time;
    $this->total_time = ($this->end_time - $this->start_time);
  }
}