<?php
/*
  $Id: sitemap.php,v 1.1 2011/08/30 21:05:59 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  require('includes/classes/info.php');
  class osC_Info_Sitemap extends osC_Template {

/* Private variables */

    var $_module = 'sitemap',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_sitemap.php',
        $_page_image = 'table_background_specials.gif';

/* Class constructor */

    function osC_Info_Sitemap() {
      global $osC_Services, $osC_Language, $osC_Breadcrumb;

      $this->_page_title = $osC_Language->get('info_sitemap_heading');

      if ($osC_Services->isStarted('breadcrumb')) {
        $osC_Breadcrumb->add($osC_Language->get('breadcrumb_sitemap'), osc_href_link(FILENAME_INFO, "/" . $this->_module));
      }
    }
  }
?>
