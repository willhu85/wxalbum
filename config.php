﻿<?php
/**
 * Created by PhpStorm.
 * User: p_jdkunyu
 * Date: 14-7-23
 * Time: 上午10:44
 */

//===============================================
// Debug
//===============================================

//ini_set('display_errors', true);
//error_reporting(E_ERROR);
// E_ERROR | E_WARNING | E_PARSE

//===============================================
// Base path
//===============================================

define('WEB_DOMAIN','http://10.24.75.50:8080/Dropbox/willhu/wxalbum/'); //with http:// and trailing slash pls

//===============================================
// Session
//===============================================

session_start();

//===============================================
// Login control
//===============================================

define('ADMIN_NAME','admin');
define('ADMIN_PASS','admin');

define('SUPER_ADMIN_NAME','admin');
define('SUPER_ADMIN_PASS','super');


?>