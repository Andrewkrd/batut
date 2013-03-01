<?php
/*
  $Id: product_listing.php,v 1.12 2012/10/19 19:43:19 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[$key] = "";
  }

  if ( ($Qlisting->numberOfRows() > MAX_DISPLAY_SEARCH_RESULTS) ||
       ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<div class="listingPageLinks">
  <span style="float: right;"><?php echo $Qlisting->getBatchPageLinks('page', osc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></span>

  <?php echo $Qlisting->getBatchTotalPages($osC_Language->get('result_set_number_of_products')); ?>
</div>

<?php
  }
?>


<div class="pull-right">	
	<?php
	 $options = array(array('id' => '0', 'text' => 'По алфавиту: возр.'),
	 				  array('id' => '1', 'text' => 'По алфавиту: убыв.'),
	 				  array('id' => '2', 'text' => 'По цене: возр.'),
	 				  array('id' => '3', 'text' => 'По цене: убыв.'));
	 echo osc_draw_button_dropdown_menu('filter', $options, (isset($_GET['sort']) ? $_GET['sort'] : 0), "sort");
	?>	

</div>
</div>		


<?php
  if ($Qlisting->numberOfRows() > 0) {

$Qcategories_seo = $osC_Database->query('select cd.categories_name, cd.categories_text, cd.categories_text_top from :table_categories_description cd where cd.categories_id = :categories_id');
$Qcategories_seo->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
$Qcategories_seo->bindInt(':categories_id', $current_category_id);
$Qcategories_seo->execute();
$cat_text_top = $Qcategories_seo->value('categories_text_top');


$chpos = false;
if(strpos($_SERVER["REQUEST_URI"], "manufacturers=") > 0)
	$chpos = strpos($_SERVER["REQUEST_URI"], "manufacturers=");
	
if(strpos($_SERVER["REQUEST_URI"], "manufacturers,") > 0)
	$chpos = strpos($_SERVER["REQUEST_URI"], "manufacturers,");
	
if($chpos) {
	$man_sef = substr($_SERVER["REQUEST_URI"], $chpos + 14);
	if(strpos($man_sef, "&"))
		$man_sef = substr($man_sef, 0, strpos($man_sef, "&"));
	if(strpos($man_sef, "/"))
		$man_sef = substr($man_sef, 0, strpos($man_sef, "/"));
	
	$Qmanufacturers = $osC_Database->query('select manufacturers_text, manufacturers_name from :table_manufacturers where manufacturers_sef = :manufacturers_sef');
	$Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
	$Qmanufacturers->bindValue(':manufacturers_sef', $man_sef);
	$Qmanufacturers->execute();
	$manufacturers_text = $Qmanufacturers->value('manufacturers_text');
	$manufacturers_name = $Qmanufacturers->value('manufacturers_name');
}


if ( !empty($cat_text_top) ) {	?>
<!--  <div class="moduleBox">
<div class="content"> --> 
  <?php //echo $Qcategories_seo->value('categories_text_top'); ?>
<!-- </div>
</div>
<br /> -->
<?php
  }
	

?>



<?php
  /*  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
      $lc_key = false;
      $lc_align = '';

      switch ($column_list[$col]) {
        case 'PRODUCT_LIST_MODEL':
          $lc_text = $osC_Language->get('listing_model_heading');
          $lc_key = 'model';
          break;
        case 'PRODUCT_LIST_NAME':
          $lc_text = $osC_Language->get('listing_products_heading');
          $lc_key = 'name';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $lc_text = $osC_Language->get('listing_manufacturer_heading');
          $lc_key = 'manufacturers';
          break;
        case 'PRODUCT_LIST_PRICE':
          $lc_text = $osC_Language->get('listing_price_heading');
          $lc_key = 'price';
          $lc_align = 'left';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $lc_text = $osC_Language->get('listing_quantity_heading');
          $lc_key = 'quantity';
          $lc_align = 'right';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $lc_text = $osC_Language->get('listing_weight_heading');
          $lc_key = 'weight';
          $lc_align = 'right';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $lc_text = $osC_Language->get('listing_image_heading');
          $lc_align = 'center';
          break;
        case 'PRODUCT_LIST_BUY_NOW':
          $lc_text = $osC_Language->get('listing_buy_now_heading');
          $lc_align = 'center';
          break;
      }

      if ($lc_key !== false) {
        $lc_text = osc_create_sort_heading($lc_key, $lc_text);
      }

      echo '      <td align="' . $lc_align . '" class="productListing-heading">&nbsp;' . $lc_text . '&nbsp;</td>' . "\n";
    }*/
?>


<?php
    $rows = 0;
    while ($Qlisting->next()) {
      $osC_Product = new osC_Product($Qlisting->valueInt('products_id'));
    $rows++;

      // Вариант товаров плитками (Default thumbnails)
		if($rows == 1 || fmod ($rows , 3) == 1)
			echo '<div class="row-fluid"><ul class="thumbnails product-list-inline-small">';

		echo '<li class="span4">';
		
	    if(array_key_exists('PRODUCT_LIST_IMAGE', $column_list)) {
			echo '<div class="thumbnail" style="text-align:center;">'. osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle())) . '</div>'; 
		}		
		if(array_key_exists('PRODUCT_LIST_NAME', $column_list)) {
			echo '<div class="caption"><h3>';
		    if (isset($_GET['manufacturers'])) {
              echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '/manufacturers,' . $_GET['manufacturers']), $osC_Product->getTitle());
            } else {
              echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Product->getTitle());
            }
            echo '</h3>';
            // Introduce Text
			echo '<p>' . $osC_Product->getSubTitle() . '</p>';
			
		}		
		if(array_key_exists('PRODUCT_LIST_PRICE', $column_list))
			$price = $osC_Product->getPriceFormatedList(true);
			
		if($osC_Product->getAvailable() == 0)
			echo '<p>' . $price . '<span class="label label-inverse pull-left">Отсутствует</span></p>';
		elseif($osC_Product->getAvailable() == 1)
			echo '<div>' . $price . '<span class="label label-success pull-left">В наличии</span></div>';
		elseif($osC_Product->getAvailable() == 2)
			echo '<p>' . $price . '<span class="label pull-left">Под заказ</span></p>';
		elseif($osC_Product->getAvailable() == 3)
			echo '<p>' . $price . '<span class="label pull-left">Под заказ 10-14 дней</span></p>';

		
		echo '<div class="clearfix"></div>';
		
   		if(array_key_exists('PRODUCT_LIST_BUY_NOW', $column_list) && $osC_Product->getAvailable() >= 1 ) {
			echo '<div class="button pull-left"><a href=' . osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '?action=cart_add') . '><i class="icon-shopping-cart icon-large"></i> <span style="color: white;">' . $osC_Language->get('button_buy_now') . '</span></a></div>';
		}
		//echo $price;
		
		echo '</div>';
		echo '</li>';
		
		if(fmod ($rows , 3) == 0 || $Qlisting->numberOfRows() == $rows)
			echo "</ul></div>";

      
      // Вариант товаров в строку
/*		echo '<div class="row">';
	    if(array_key_exists('PRODUCT_LIST_IMAGE', $column_list)) {
			//$lc_text = '<div class="span1">' . osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle())) . '</div>';
			echo '<div class="span1">'. osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Image->show("1330464533_batut-9082n.jpg", $osC_Product->getTitle())).'</div>'; // Картинка $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle()
		}		
		if(array_key_exists('PRODUCT_LIST_NAME', $column_list)) {
			echo '<div class="span6"><h5>';
			//echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Product->getTitle());
		    if (isset($_GET['manufacturers'])) {
              echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '/manufacturers,' . $_GET['manufacturers']), $osC_Product->getTitle());
            } else {
              echo '&nbsp;' . osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Product->getTitle()) . '&nbsp;';
            }			
			// Introduce Text
			echo '</h5>';
			echo '<p>Отличный батут за небольшие деньги. Отличный батут за небольшие деньги. Отличный батут за небольшие деньги. Отличный батут за небольшие деньги. Отличный батут за небольшие деньги.</p>';
			echo '</div>';
		}		
		if(array_key_exists('PRODUCT_LIST_PRICE', $column_list)) {
			echo '<div class="span1"><p>' . $osC_Product->getPriceFormated(true) . '</p></div>';
		}		
		if(array_key_exists('PRODUCT_LIST_BUY_NOW', $column_list)) {
			echo '<div class="span2"><p><a href=' . osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '&action=cart_add') . ' class="btn btn-primary">' . $osC_Language->get('button_buy_now') . '</a></p></div>';
		}
		echo '</div><hr>';*/
    }
?>



<?php
  } else {
    echo $osC_Language->get('no_products_in_category');
    header("HTTP/1.1 404 Not Found");
  }
?>



<?php
  if ( ($Qlisting->numberOfRows() > MAX_DISPLAY_SEARCH_RESULTS ) ||
       ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>


  <?php echo $Qlisting->getBatchPageLinks('page', osc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>

  <?php //echo $Qlisting->getBatchTotalPages($osC_Language->get('result_set_number_of_products')); ?>


<?php
  }
?>











<?php
// Seo Text
if ($Qlisting->numberOfRows() > 0) {
$cat_text = $Qcategories_seo->value('categories_text');
if ( !empty($cat_text) || !empty($manufacturers_name)) {
?>

<div class="moduleBox">
<div class="content">
  
  <?php
	if ( !empty($cat_text) )

		echo $cat_text; 
		
	elseif( !empty($manufacturers_text ))

		echo "Купить " . $manufacturers_text . " в Краснодаре. Продажа " . $manufacturers_name  . " в интернет магазине.";
	
	elseif( !empty($manufacturers_name) )
		
		echo "Купить " . $manufacturers_name . " в Краснодаре. Продажа " . $manufacturers_name  . " в интернет магазине.";

  ?>
  

</div>
<br />
<?php
  }	}
?>
