<?php 

/**
 * @Web Testing CLI
 * This tool should be used to make basic requests against a web page and get
 * and collect expected results to either pass or fail
 *
 * @see CLI Commands
 *
 *  php web-test urls - test url(s) for a specified output 
 *   input url: my.url.com
 *    input method: POST or GET
 *    input header 1: 
 *    input header 2:
 *    input header 3:
 *   input url:
 */

/**
 * Include Required Files
 */
include 'url/URLS.php';
include 'web/Web.php';
include 'system/System.php';

/**
 * Script Params
 */
$web_test = FALSE;
$url_test = FALSE;
$verbose = FALSE;
$system = new System;

// check if the system has the valid requirements
if (!$system->valid_system_requirements()) {
  echo "Please make sure you have PHP >= 5.2 and cURL installed \n";
  die("Exiting program. \n");
} else {
  echo "System check has passed \n";
}

// Parse out the arguments from the commandline
foreach ($argv as $key => $arg) {
  if ($key == 1) {
    echo "Flag " . $arg . " \n";  
    
    if ($arg == 'urls') {
      $url_test = TRUE;    	
    } else if ($arg == 'web') {
      $web_test = TRUE;
    } 
  }
}

/**
 * Execute the desired test
 */
// Web page test
if ($web_test) {

}

// Endpoint test
if ($url_test) {
  // Declare object
  $url = new URLS;
  // Output explanation
  $url->explanation();
  $url->gather_input();
  $url->execute();
  $url->display_results();
}

// Print output message
exit("\n\n\n********* Web Test Script Exiting **********\n");
?>