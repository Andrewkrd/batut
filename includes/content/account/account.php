<?php
/*
  $Id: account.php,v 1.1 2011/08/30 21:05:53 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  require('includes/classes/order.php');

  class osC_Account_Account extends osC_Template {

/* Private variables */

    var $_module = 'account',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account.php',
        $_page_image = 'table_background_account.gif';

    function osC_Account_Account() {
      global $osC_Language;

      $this->_page_title = $osC_Language->get('account_heading');
    }
  }
?>
