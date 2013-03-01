<?php
/*
  $Id: orders.php,v 1.1 2011/08/30 21:05:53 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  require('includes/classes/order.php');

  class osC_Account_Orders extends osC_Template {

/* Private variables */

    var $_module = 'orders',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account_history.php',
        $_page_image = 'table_background_history.gif';

/* Class constructor */

    function osC_Account_Orders() {
      global $osC_Services, $osC_Language, $osC_Customer, $osC_Breadcrumb;

      $this->_page_title = $osC_Language->get('orders_heading');

      $osC_Language->load('order');

      if ($osC_Services->isStarted('breadcrumb')) {
        $osC_Breadcrumb->add($osC_Language->get('breadcrumb_my_orders'), osc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));

        if (is_numeric($_GET[$this->_module])) {
          $osC_Breadcrumb->add(sprintf($osC_Language->get('breadcrumb_order_information'), $_GET[$this->_module]), osc_href_link(FILENAME_ACCOUNT, $this->_module . '=' . $_GET[$this->_module], 'SSL'));
        }
      }

      if (is_numeric($_GET[$this->_module])) {
        if (osC_Order::getCustomerID($_GET[$this->_module]) !== $osC_Customer->getID()) {
          osc_redirect(osc_href_link(FILENAME_ACCOUNT, $this->_module, 'SSL'));
        }

        $this->_page_title = sprintf($osC_Language->get('order_information_heading'), $_GET[$this->_module]);
        $this->_page_contents = 'account_history_info.php';
      }
    }
  }
?>
