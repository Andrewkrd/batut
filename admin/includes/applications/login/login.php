<?php
/*
  $Id: login.php,v 1.1 2011/08/29 22:07:56 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Application_Login extends osC_Template_Admin {

/* Protected variables */

    protected $_module = 'login',
              $_page_title,
              $_page_contents = 'main.php';

/* Class constructor */

    public function __construct() {
      global $osC_Language;

      $this->_page_title = $osC_Language->get('heading_title');
    }
  }
?>