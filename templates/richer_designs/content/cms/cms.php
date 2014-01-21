<?php
/*
  $Id: cms.php,v 1.1 2011/08/31 20:02:21 ujirafika.ujirafika Exp $
  
  author Dave Howarth
  copyright 2008
  web http://www.box25.net
  email sales@box25.net
 
  Filename cms.php
  Desc Basic CMS system for osCommerce V3.0A5
  Modify by Gergely T�th
  http://oscommerce-extra.hu
*/


  // define the max number of articles to display
  $max_articles = MAX_DISPLAY_CMS_ARTICLES;
  // grab a list of cms items
  $QcmsList = osC_Cms::getListing();

?>

<?php if (file_exists('images/' . $osC_Template->getPageImage()) == true) {
echo osc_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"');
} else {}
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>


<?php
if (isset($_GET['cmskeyword'])) {;
  $QcmsList = osC_Cms::getsearchDetail($_GET['cmskeyword']);
}
?>

<!-- start of wrapper module -->
<div class="moduleBox">

<?php

	while ($QcmsList->next()) {
    	echo '<h3><a href="' . osc_href_link("articles/" . $QcmsList->value("cms_url")) . '"> ' . $QcmsList->value("cms_name") . '</a></h3>

				  <div class="rowfluid">
				    <p>' . $QcmsList->value("cms_short_text") . ' ' . $osC_Language->get('cms_text_more') . '</p>
				    <button class="btn btn-danger pull-left" type="button" onclick="document.location.href=\'' . osc_href_link("articles/" . $QcmsList->value("cms_url")) . '\'">Читать</button>
                                  </div><div style="clear: both">';
    }

?>
</div>
<!-- end of wrapper module -->

<!-- start of wrapper table -->

<div class="listingPageLinks">
  <span style="float: right;"><?php echo $QcmsList->getBatchPageLinks('page', null, true); ?></span>

  <?php echo $QcmsList->getBatchTotalPages($osC_Language->get('result_set_number_of_cms')); ?>
</div>

<!-- end of wrapper table -->