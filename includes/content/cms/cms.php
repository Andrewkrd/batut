<?php
/*
  $Id: cms.php,v 1.1 2011/08/30 21:06:15 ujirafika.ujirafika Exp $
  
  author Dave Howarth
  copyright 2008
  web http://www.box25.net
  email sales@box25.net
 
  Filename cms.php
  Desc Basic CMS system for osCommerce V3.0A5
  Modify by Gergely T�th
  http://oscommerce-extra.hu
*/

  // include the cms class
  require('includes/classes/cms.php');

  class osC_Cms_Cms extends osC_Template {

/* Private variables */

    var $_module = 'cms',
        $_group = 'cms',
        $_page_title,
        $_page_contents = 'cms.php',
        $_page_image = 'table_background_account.gif';

    function osC_Cms_Cms() {
      global $osC_Language;

      $this->_page_title = $osC_Language->get('cms_heading');
    }
  }
?>
