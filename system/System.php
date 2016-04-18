<?php

/**
 * Class to determine if the environment is configured to run the script
 * This class checks for:
 *  - php 5.2 >=
 *  - cURL
 *  - php-json
 */

class System {

  public $php_version = FALSE;
  public $curl = FALSE;

  public function __construct() {

  	// Sets the current php version
  	$this->php_version = phpversion();

  	// Sets the initial php cURL flag
  	$this->curl = function_exists('curl_version');
  }

  /**
   * Utility function that detects if the current system is capable of  
   * running the web-testing tool
   *
   * @return Bool
   *  Makes sure the php 5.2 >= is installed and cURL is installed
   */
  public function valid_system_requirements() {

    if ($this->php_version < 5.2) {
      return FALSE;
    }

    if (!$this->curl) {
      return FASLE;
    }
    return TRUE;
  }
}