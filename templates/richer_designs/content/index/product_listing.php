<?php
/*
  $Id: product_listing.php,v 1.12 2012/09/20 18:40:34 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>
<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true && substr(strrev($osC_Template->getPageImage()), 0, 1) !== "/") {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>



<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<div class="well product_filter" >


<?php
// optional Product List Filter
  if (PRODUCT_LIST_FILTER > 0) {
  	$filterlist_sql = "";
    if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
    	if(is_numeric($_GET['manufacturers'])){
      		$filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_TEMPLATES_BOXES . " tb, " . TABLE_PRODUCT_ATTRIBUTES . " pa where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$osC_Language->getID() . "' and tb.code = 'manufacturers' and tb.id = pa.id and pa.products_id = p.products_id and pa.value = '" . (int)$_GET['manufacturers'] . "' order by cd.categories_name";
    		$man_id = $_GET['manufacturers'];
    	}
      	else 
      	{   
      		$Qmanufacturer_pl = $osC_Database->query('select manufacturers_id from :table_manufacturers where manufacturers_sef = :manufacturers_sef');
		    $Qmanufacturer_pl->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
		    if(strpos( $_GET['manufacturers'], "/") !== false)
		    	$man_sef = substr($_GET['manufacturers'], 0, strpos( $_GET['manufacturers'], "/") );
		    else
		    	$man_sef = $_GET['manufacturers'];
		    $Qmanufacturer_pl->bindValue(':manufacturers_sef', $man_sef );
		    $Qmanufacturer_pl->execute();
            if ($Qmanufacturer_pl->numberOfRows() === 1) {  
				$man_id = $Qmanufacturer_pl->valueInt('manufacturers_id');           		
				$filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_TEMPLATES_BOXES . " tb, " . TABLE_PRODUCT_ATTRIBUTES . " pa where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$osC_Language->getID() . "' and tb.code = 'manufacturers' and tb.id = pa.id and pa.products_id = p.products_id and pa.value = '" . $man_id . "' order by cd.categories_name";	
            }
      	}      		
     } else {
      $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id > 0 and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
    }
	if($filterlist_sql !== "") {
    $Qfilterlist = $osC_Database->query($filterlist_sql);
    $Qfilterlist->execute();

    if ($Qfilterlist->numberOfRows() > 1) {
      //echo '<p><form name="filter" action="' . osc_href_link(FILENAME_DEFAULT) . '" method="get">' . $osC_Language->get('filter_show') . '&nbsp;';
      if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
        //echo osc_draw_hidden_field('manufacturers', $_GET['manufacturers']);
        $options = array(array('id' => '0', 'text' => $osC_Language->get('filter_all_categories')));
      } else {
        //echo osc_draw_hidden_field('cPath', $cPath);
        $options = array(array('id' => '0', 'text' => $osC_Language->get('filter_all_manufacturers')));
      }

      /*if (isset($_GET['sort'])) {
        echo osc_draw_hidden_field('sort', $_GET['sort']);
      }*/

      while ($Qfilterlist->next()) {
        $options[] = array('id' => $Qfilterlist->valueInt('id'), 'text' => $Qfilterlist->value('name'));
      }
      echo '<div class="pull-left">' . osc_draw_button_dropdown_menu('filter', $options, (isset($_GET['filter']) ? $_GET['filter'] : 0), "filter") . '</div>';
      //echo osc_draw_hidden_session_id_field() . '</form></p>' . "\n";
    }
  }
  }
  
  if (isset($_GET['manufacturers']) && !empty($_GET['manufacturers'])) {
    $osC_Products->setManufacturer($man_id);
  }

  $Qlisting = $osC_Products->execute();
  require('includes/modules/product_listing.php');
?>
