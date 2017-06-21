<?php

/*

  # place code below in /panel/index.php
  # place code before {try} function (line #55)

  $captcha_file = '../assets/captcha/cgi/check.php';

  if(file_exists($captcha_file)) {
    include_once($captcha_file);
  }

*/

//////////////////////////////////////////////////////////////////
// SETUP KIRBY AND GET THE CAPTCHA PREFERENCES
//////////////////////////////////////////////////////////////////

// load the kirby bootstrapper

/*
  define('DS', DIRECTORY_SEPARATOR);
  $index = dirname(__DIR__);
  require($index . DS . '../../kirby' . DS . 'bootstrap.php');
  $kirby = kirby();
*/

// load the preferences

  $config = '../site/config/config.php';
  include_once($config);

  if(!c::get('kirbyEncryptDecrypt')) {
    echo 'key #1 not found';
    die();
  } else {
    $kirbyEncryptDecrypt = c::get('kirbyEncryptDecrypt');
  }

  if(!c::get('kirbyCredentialsKey')) {
    echo 'key #2 not found';
    die();
  } else {
    $kirbyCredentialsKey = c::get('kirbyCredentialsKey');
  }

//////////////////////////////////////////////////////////////////
// SET AND GET SOME VARS - SEE IF CHECK IS NEEDED
//////////////////////////////////////////////////////////////////

  date_default_timezone_set('Europe/Amsterdam');

  if(date_default_timezone_get()) {
    date_default_timezone_set(@date_default_timezone_get());
  } else {
    date_default_timezone_set('Europe/Amsterdam');
  }

$timestamp    = date('#l#j#F#Y#').$_SERVER['HTTP_HOST'].'#'.gethostname().'#'.$_SERVER['REMOTE_ADDR'].'#';
$file_crypt   = '../assets/captcha/cgi/crypt.php';
$file_error   = '../assets/captcha/cgi/404.php';
$file_form    = '../assets/captcha/cgi/form.php';
$credsCheck   = false;

  if(!class_exists('SaferCrypto')){include_once($file_crypt);}

  if(isset($_COOKIE['captcha'])) {
    $decrypted = SaferCrypto::decrypt($_COOKIE['captcha'],$kirbyEncryptDecrypt,true);

    if($decrypted == $timestamp) {
      $credsCheck = true;
    }
  }

//////////////////////////////////////////////////////////////////
// A DOUBLE-CHECK IS NEEDED, COOKIE IS NOT SET - YET...
//////////////////////////////////////////////////////////////////

if(!$credsCheck) {

//////////////////////////////////////////////////////////////////
// 1#2 -> SEE IF CREDENTIALS IN PANEL-URL DO MATCH
//////////////////////////////////////////////////////////////////

  function show404() {
    global $file_error;
    if (file_exists($file_error)) { include_once($file_error);die(); }
    else { header('Location: /404');die(); }
  }

// string is not set - quit

  if(!isset($_GET[$kirbyCredentialsKey])) {
    show404();
  }

//////////////////////////////////////////////////////////////////
// 2#2 -> URL-CREDENTIALS ARE OKAY, SHOW CAPTCHA-FORM
//////////////////////////////////////////////////////////////////

  if(!function_exists('activeDIR')) {

    function activeDIR($url) {
      substr($url,-1) != '/'?$url.= '/':$url = $url;;
      $path = parse_url($url, PHP_URL_PATH);
      $pathTrimmed = trim($path, '/');
      $pathTokens = explode('/', $pathTrimmed);

        if (substr($path, -1) !== '/') {
          array_pop($pathTokens);
        }

      return end($pathTokens);
    }
  }

  if(activeDIR($_SERVER['REQUEST_URI']) == 'login') {
    header('Location: '.preg_replace('#/login#','',$_SERVER['REQUEST_URI']));
    exit();
  }

  include_once($file_form);
  exit();
}

?>