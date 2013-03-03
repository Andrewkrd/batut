<?php
/*
  $Id: edit.php,v 1.4 2012/09/11 20:06:42 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  $osC_ObjectInfo = new osC_ObjectInfo(osC_Categories_Admin::get($_GET['cID']));

  $categories_array = array(array('id' => '0',
                                  'text' => $osC_Language->get('top_category')));

  foreach ( $osC_CategoryTree->getArray() as $value ) {
    $categories_array[] = array('id' => $value['id'],
                                'text' => $value['title']);
  }
?>

<h1><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ( $osC_MessageStack->exists($osC_Template->getModule()) ) {
    echo $osC_MessageStack->get($osC_Template->getModule());
  }
?>

<div class="infoBoxHeading"><?php echo osc_icon('edit.png') . ' ' . $osC_ObjectInfo->getProtected('categories_name'); ?></div>
<div class="infoBoxContent">
  <form name="cEdit" class="dataForm" action="<?php echo osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()] . '&cID=' . $osC_ObjectInfo->getInt('categories_id') . '&action=save'); ?>" method="post" enctype="multipart/form-data">

  <p><?php echo $osC_Language->get('introduction_edit_category'); ?></p>

  <fieldset>
    <div><label><?php echo $osC_Language->get('field_name'); ?></label>

<?php
  foreach ( $osC_Language->getAll() as $l ) {
    echo '<p>' . $osC_Language->showImage($l['code']) . '&nbsp;' . $l['name'] . '<br />' . osc_draw_input_field('categories_name[' . $l['id'] . ']', osC_Categories_Admin::get($osC_ObjectInfo->getInt('categories_id'), $l['id'], 'categories_name')) . '</p>';
  }
?>

    </div>

<?php
  if ( !osc_empty($osC_ObjectInfo->get('categories_image')) ) {
?>

    <div><p><?php echo osc_image('../' . DIR_WS_IMAGES . 'categories/' . $osC_ObjectInfo->get('categories_image'), $osC_ObjectInfo->get('categories_name'), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . DIR_WS_CATALOG . 'images/categories/' . $osC_ObjectInfo->getProtected('categories_image'); ?></p></div>

<?php
  }
?>

    <div><label for="categories_image"><?php echo $osC_Language->get('field_image'); ?></label><?php echo osc_draw_file_field('categories_image', true); ?></div>
    <div><label for="sort_order"><?php echo $osC_Language->get('field_sort_order'); ?></label><?php echo osc_draw_input_field('sort_order', $osC_ObjectInfo->get('sort_order')); ?></div>
    <div><label for="categories_text_top">SEO текст категории верх</label><?php echo osc_draw_textarea_field('categories_text_top', $osC_ObjectInfo->get('categories_text_top')); ?></div>
    <div><label for="categories_text">SEO текст категории подвал</label><?php echo osc_draw_textarea_field('categories_text', $osC_ObjectInfo->get('categories_text')); ?></div>
    <div><label for="categories_title">Title категории</label><?php echo osc_draw_textarea_field('categories_title', $osC_ObjectInfo->get('categories_title')); ?></div>
    <div><label for="kissmt_categories_description">Description категории</label><?php echo osc_draw_textarea_field('kissmt_categories_description', $osC_ObjectInfo->get('kissmt_categories_description')); ?></div>
  </fieldset>

  <p align="center"><?php echo osc_draw_hidden_field('subaction', 'confirm') . '<input type="submit" value="' . $osC_Language->get('button_save') . '" class="operationButton" /> <input type="button" value="' . $osC_Language->get('button_cancel') . '" onclick="document.location.href=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()]) . '\';" class="operationButton" />'; ?></p>

  </form>
</div>
