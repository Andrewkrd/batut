<?php
/*
  $Id: new.php,v 1.2 2012/09/28 19:05:23 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true) {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  $osC_Products = new osC_Products();
  $osC_Products->setSortBy('date_added', '-');

  $Qproducts = $osC_Products->execute();

  if ($Qproducts->numberOfRows() > 0) {
    while ($Qproducts->next()) {
      $osC_Product = new osC_Product($Qproducts->valueInt('products_id'));
?>

  <tr>
    <td width="<?php echo $osC_Image->getWidth('thumbnails') + 10; ?>" valign="top" align="center">

<?php
      if ( $osC_Product->hasImage() ) {
        echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle()));
      }
      if($osC_Product->getManufacturer()!=="none")
      	$manufacturer = '<br />' . $osC_Language->get('manufacturer') . ' ' .$osC_Product->getManufacturer(false);
      else
      	$manufacturer = "";
?>

    </td>
    <td valign="top"><?php echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword()), '<b><u>' . $osC_Product->getTitle() . '</u></b>') . '<br />' . $osC_Language->get('date_added') . ' ' . osC_DateTime::getLong($osC_Product->getDateAdded()) . $manufacturer . '<br /><br />' . $osC_Language->get('price') . '<br />' . $osC_Product->getPriceFormated(true); ?></td>
    <td class="submitFormButtons" align="right" valign="middle"><?php echo $osC_Template->osc_draw_image_jquery_button(array('href' => osc_href_link(FILENAME_PRODUCTS, $osC_Product->getKeyword() . '&action=cart_add'), 'icon' => 'cart', 'title' => $osC_Language->get('button_add_to_cart'))); ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

<?php
    }
  } else {
?>

  <tr>
    <td><?php echo $osC_Language->get('no_new_products'); ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

<?php
  }
?>

</table>

<div class="listingPageLinks">
  <span style="float: right;"><?php echo $Qproducts->getBatchPageLinks('page', 'new'); ?></span>

  <?php echo $Qproducts->getBatchTotalPages($osC_Language->get('result_set_number_of_products')); ?>
</div>
