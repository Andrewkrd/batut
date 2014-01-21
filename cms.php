<?php
/*
  $Id: cms.php,v 1.1 2011/08/31 20:04:30 ujirafika.ujirafika Exp $
  
  author Dave Howarth
  copyright 2008
  web http://www.box25.net
  email sales@box25.net
 
  Filename cms.php
  Desc Basic CMS system for osCommerce V3.0A5
  Modify by Gergely T�th
  http://oscommerce-extra.hu
  
  RuBiC modify (http://www.rubicshop.ru)  
*/

  $_SERVER['SCRIPT_FILENAME'] = __FILE__;

  require('includes/application_top.php');

  $osC_Language->load('cms');

  if ($osC_Services->isStarted('breadcrumb')) {
    $osC_Breadcrumb->add($osC_Language->get('breadcrumb_cms'), osc_href_link("articles/"));
  }

  $osC_Template = osC_Template::setup('cms');

  require('templates/' . $osC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>
