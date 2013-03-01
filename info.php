<?php
/*
  $Id: info.php,v 1.1 2011/08/31 20:04:30 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  $_SERVER['SCRIPT_FILENAME'] = __FILE__;

  require('includes/application_top.php');

  $osC_Language->load('info');

  if ($osC_Services->isStarted('breadcrumb')) {
    $osC_Breadcrumb->add($osC_Language->get('breadcrumb_information'), osc_href_link("info/"));
  }

  $osC_Template = osC_Template::setup('info');

  require('templates/' . $osC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>
