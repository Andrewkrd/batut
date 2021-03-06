<?php
/*
  $Id: search.php,v 1.1 2011/08/30 21:13:25 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Boxes_search extends osC_Modules {
    var $_title,
        $_code = 'search',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_search() {
      global $osC_Language;

      $this->_title = $osC_Language->get('box_search_heading');
    }

    function initialize() {
      global $osC_Language, $osC_Template;

      $this->_title_link = osc_href_link(FILENAME_SEARCH);

      $this->_content = '<form name="search" action="' . osc_href_link(FILENAME_SEARCH, null, 'NONSSL', false) . '" method="get">' .
                        osc_draw_input_field('keywords', null, 'style="width: 80%;" maxlength="30"') . '&nbsp;' . osc_draw_hidden_session_id_field() . $osC_Template->osc_draw_images_button('header-nav-search-go.png', $osC_Language->get('box_search_heading')) . '<br />' . sprintf($osC_Language->get('box_search_text'), osc_href_link(FILENAME_SEARCH)) .
                        '</form>';
    }
  }
?>
