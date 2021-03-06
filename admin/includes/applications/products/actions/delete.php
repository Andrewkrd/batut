<?php
/*
  $Id: delete.php,v 1.1 2011/08/29 22:09:18 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Application_Products_Actions_delete extends osC_Application_Products {
    public function __construct() {
      global $osC_Language, $osC_MessageStack;

      parent::__construct();

      $this->_page_contents = 'delete.php';

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        if ( osC_Products_Admin::delete($_GET[$this->_module], (isset($_POST['product_categories']) && is_array($_POST['product_categories'])) ? $_POST['product_categories'] : null) ) {
          $osC_MessageStack->add($this->_module, $osC_Language->get('ms_success_action_performed'), 'success');
        } else {
          $osC_MessageStack->add($this->_module, $osC_Language->get('ms_error_action_not_performed'), 'error');
        }

        osc_redirect_admin(osc_href_link_admin(FILENAME_DEFAULT, $this->_module . '&cID=' . $_GET['cID']));
      }
    }
  }
?>
