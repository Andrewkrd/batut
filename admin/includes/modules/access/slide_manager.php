<?php
/*
  $Id: slide_manager.php,v 1.1 2011/08/29 22:15:15 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Access_Slide_manager extends osC_Access {
    var $_module = 'slide_manager',
        $_group = 'tools',
        $_icon = 'slide_manager.png',
        $_title,
        $_sort_order = 55;

    function osC_Access_Slide_manager() {
      global $osC_Language;

      $this->_title = $osC_Language->get('access_slide_manager_title');
    }
  }
?>