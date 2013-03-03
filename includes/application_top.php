<?php
/*
  $Id: application_top.php,v 1.17 2012/10/14 17:01:33 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting to E_ALL except E_NOTICE
  error_reporting(E_ALL ^ E_NOTICE);

// set the local configuration parameters - mainly for developers
  if ( file_exists('includes/local/configure.php') ) {
    include('includes/local/configure.php');
  }

// include server parameters
  require('includes/configure.php');

// set the level of error reporting
  error_reporting(E_ALL);

//  ini_set('log_errors', true);
//  ini_set('error_log', DIR_FS_WORK . 'oscommerce_errors.log');

// redirect to the installation module if DB_SERVER is empty
  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osCommerce Online Merchant v3.0a5');

// set the type of request (secure or not)
  $request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'SSL' : 'NONSSL';

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// compatibility work-around logic for PHP4
  require('includes/functions/compatibility.php');

// include the list of project filenames
  require('includes/filenames.php');

// include the list of project database tables
  require('includes/database_tables.php');

// initialize the message stack for output messages
  require('includes/classes/message_stack.php');
  $osC_MessageStack = new osC_MessageStack();

// initialize the cache class
  require('includes/classes/cache.php');
  $osC_Cache = new osC_Cache();

// include the database class
  require('includes/classes/database.php');

// make a connection to the database... now
  $osC_Database = osC_Database::connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
  $osC_Database->selectDatabase(DB_DATABASE);

// set the application parameters
  $Qcfg = $osC_Database->query('select configuration_key as cfgKey, configuration_value as cfgValue from :table_configuration');
  $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
  $Qcfg->setCache('configuration');
  $Qcfg->execute();

  while ($Qcfg->next()) {
    define($Qcfg->value('cfgKey'), $Qcfg->value('cfgValue'));
  }

  $Qcfg->freeResult();
  
// 301 Redirect if need
  $Qred = $osC_Database->query('select redirect_id, redirect_src, redirect_dest from :table_redirect');
  $Qred->bindTable(':table_redirect', TABLE_REDIRECT);
  $Qred->setCache('redirect_cat');
  $Qred->execute();

  while ($Qred->next()) {
  	if($_SERVER["REQUEST_URI"] == $Qred->value('redirect_src')) {
  		$Qred_upd = $osC_Database->query('UPDATE :table_redirect SET redirect_cnt=redirect_cnt+1 WHERE redirect_id=:redirect_id');
  		$Qred_upd->bindTable(':table_redirect', TABLE_REDIRECT);
  		$Qred_upd->bindTable(':redirect_id', $Qred->value('redirect_id'));
  		$Qred_upd->execute();
  		
  		if(!apc_fetch($Qred->value('redirect_src') . " > " . $Qred->value('redirect_dest')))
        		apc_add($Qred->value('redirect_src') . " > " . $Qred->value('redirect_dest'), $_SERVER, 259200);
  		
		header ('HTTP/1.1 301 Moved Permanently');  				
		header('Location: ' . $Qred->value('redirect_dest'));
  	}
  }
  $Qred->freeResult();
 

// include functions
  require('includes/functions/general.php');
  require('includes/functions/html_output.php');

// include and start the services
  require('includes/classes/services.php');
  $osC_Services = new osC_Services();
  $osC_Services->startServices();
?>
