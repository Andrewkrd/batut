<?php
/*
  $Id: view.php,v 1.1 2011/08/31 20:02:18 ujirafika.ujirafika Exp $
  
  author Dave Howarth
  copyright 2008
  web http://www.box25.net
  email sales@box25.net
 
  Filename view.php
  Desc Basic CMS system for osCommerce V3.0A5
  Modify by Gergely T�th
  http://oscommerce-extra.hu
*/

  // grab the item requested
  $QcmsDetails = osC_Cms::getDetails();
  
?>
<div class="page-header"><h1><?php echo $osC_Template->getPageTitle(); ?></h1></div>
<?php
    echo '<div class="row-fluid">
    		<div class="span12">' . nl2br($QcmsDetails->value("cms_description")) . '</div>
		  </div>';
			  
?>

<div class="submitFormButtons" style="text-align: right;">
  <?php echo $osC_Template->osc_draw_image_jquery_button(array('href' => osc_href_link(FILENAME_CMS), 'icon' => 'triangle-1-e', 'title' => $osC_Language->get('button_continue'))); ?>
</div>

