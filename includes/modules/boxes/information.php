<?php
/*
  $Id: information.php,v 1.1 2011/08/30 21:13:25 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Boxes_information extends osC_Modules {
    var $_title,
        $_code = 'information',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_information() {
      global $osC_Language;
      
      $this->_title = $osC_Language->get('box_information_heading');
    }

    function initialize() {
      global $osC_Database, $osC_Language;

      $this->_title_link = osc_href_link(FILENAME_INFO);
      
      $QinfoBuild = $osC_Database->query('select info_url as info_url, info_name as text from :table_info where language_id = :language_id and active = 1 order by sort_order ASC');
      $QinfoBuild->bindTable(':table_info', TABLE_INFO);
      $QinfoBuild->bindInt(':language_id', $osC_Language->getID());
      $QinfoBuild->execute();

      $this->_content = '';
      while ($QinfoBuild->next()) {
        
        $this->_content .= '<li>' . osc_link_object(osc_href_link("info/" . $QinfoBuild->value("info_url")), $QinfoBuild->value("text")) . '</li>';
      }
            $QinfoBuild->freeResult();
      $this->_content .= '  <li>' . osc_link_object(osc_href_link("info/" . "contact"), $osC_Language->get('box_information_contact')) . '</li>' .
                        '  <li>' . osc_link_object(osc_href_link("info/" . "sitemap"), $osC_Language->get('box_information_sitemap')) . '</li>';
    }
  }
?>