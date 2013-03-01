<?php
/*
  $Id: whos_online.php,v 1.1 2011/08/29 22:15:14 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Access_Whos_online extends osC_Access {
    var $_module = 'whos_online',
        $_group = 'tools',
        $_icon = 'user_earth.png',
        $_title,
        $_sort_order = 600;

    function osC_Access_Whos_online() {
      global $osC_Language;

      $this->_title = $osC_Language->get('access_whos_online_title');
    }
  }
?>
