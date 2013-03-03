<?php
/*
  $Id: products_expected.php,v 1.1 2011/08/29 22:15:14 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Access_Products_expected extends osC_Access {
    var $_module = 'products_expected',
        $_group = 'content',
        $_icon = 'date.png',
        $_title,
        $_sort_order = 700;

    function osC_Access_Products_expected() {
      global $osC_Language;

      $this->_title = $osC_Language->get('access_products_expected_title');
    }
  }
?>
