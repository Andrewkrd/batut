<?php
/*
  $Id: info.php,v 1.4 2012/07/31 20:34:36 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>
<div itemscope="itemscope" itemtype="http://schema.org/Product">

<div class="product-main">
          <div class="row">
            <div class="span3">
            
<?php if(in_array($osC_Product->getAvailable(), array(1,2,3,4))) { 

	$availability = "InStock";
		
	if($osC_Product->getAvailable() == 4) {
		$avail = '<span class="label label-inverse">Отсутствует</span>';
		$availability = "OutOfStock";
	}
	if($osC_Product->getAvailable() == 1)	{
		$avail = '<span class="label label-success">В наличии</span>';
		$availability = "InStock";
	}
	if($osC_Product->getAvailable() == 2) {
		$availability = "PreOrder";
		$avail = '<span class="label">Под заказ</span>';
	}
	if($osC_Product->getAvailable() == 3) {
		$availability = "PreOrder";
		$avail = '<span class="label">Под заказ 10-14 дней</span>';
	}
}
?>   
            
<?php
  if ($osC_Product->hasImage()) {
?>
              <!-- Image -->
                 <div style="float: left; text-align: center; padding: 0 10px 10px 0; width: auto;">
    <?php $group_id = $osC_Image->getID('large');
    $lightboxcaption = $osC_Language->get('download_image');
		
    echo osc_link_object(osc_href_link(DIR_WS_IMAGES.'products/'.$osC_Image->getCode($group_id).'/'.$osC_Product->getImage()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle(), null, 'product_info'),'rel="lightbox-tour" title="'.$lightboxcaption.'"');
		
	  	if ($osC_Product->numberOfImages() > 1) {
	echo '<div id="counterst">';
  		foreach ($osC_Product->getImages() as $images) {
  			if ($osC_Product->getImage() != $images['image']) echo osc_link_object(osc_href_link(DIR_WS_IMAGES. 'products/' . $osC_Image->getCode($group_id) . '/' . $images['image']), $osC_Image->show($images['image'], $osC_Product->getTitle(), null, 'mini'), 'rel="lightbox-tour" title="'.$lightboxcaption.'"'); echo '&nbsp;';
    	}
		echo '</div>';
    }
			
  	if ($osC_Product->numberOfImages() > 1) {
  		echo "\n<div id=\"gallerydiv\" style=\"display:none;\">";
  		foreach ($osC_Product->getImages() as $images) {
  			if ($osC_Product->getImage() != $images['image']) echo osc_link_object(osc_href_link(DIR_WS_IMAGES. 'products/' . $osC_Image->getCode($group_id) . '/' . $images['image']),  $osC_Image->show($images['image'], $osC_Product->getTitle(), null, 'product_info'), 'rel="lightbox-tour2" title="'.$lightboxcaption.'"');
    	}
    	echo "</div>";
    }
	
	?>
  </div>

<?php
  }
?>

            </div>
            
	<div temprop="offers" itemscope itemtype="http://schema.org/Offer">
            <div class="span5">
                <h1 class="title-h1" itemprop="name"><?php echo $osC_Template->getPageTitle(); ?></h1>

				 <table border="0">
			      <tr>
			        <td class="productInfoKey"><h5><?php echo $osC_Language->get('listing_price_heading') ?>:</h5></td>
			        <td>&nbsp;</td>
			        <td class="productInfoValue"> <h5><?php echo $osC_Product->getPriceFormated(true);?></h5></td>
			      </tr>
				</tbody></table>
                <p>Доставка: <a href="/info/dostavka-i-oplata">Бесплатно!</a></p>
                <?php if ( $osC_Product->hasAttribute('manufacturers')) { 
				  echo '<p>Производитель: ' . $osC_Product->getAttribute('manufacturers') . '</p>';
                } ?>
                <?php if ( $osC_Product->hasAttribute('manufacturers') ) { 
				  echo '<p>Модель: ' . $osC_Product->getModel() . '</p>';
                } ?>	
                <p>Наличие: <?php echo $avail;?></p>

			<div class="input-append cart-quantity">
				<div class="button pull-left"><a href="<?php echo osc_href_link("magazin/", $osC_Product->getKeyword() . '?action=cart_add'); ?>"><i class="icon-shopping-cart icon-large"></i> <span style="color: white;">Купить</span></a></div>      
			</div>
			<div class="clearfix"></div>
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style" style="width: 300px; margin-top: 20px;">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
			</div>
			<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dfc7ad23d6465f6"></script>
			<!-- AddThis Button END -->

            </div>
          </div>
        </div>

 
    <link itemprop="availability" href="http://schema.org/<?php echo $availability; ?>" />

	<span itemprop="price" style="visibility: hidden;"><?php echo $rest = substr($osC_Product->getClearMinPrice(), 0, strlen($osC_Product->getClearMinPrice()) - 2);  ?></span>

	<meta itemprop="priceCurrency" content="RUR " />

	
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Описание</a></li>
		<li class=""><a href="#tab2" data-toggle="tab">Характеристики</a></li>
<!-- 	<li class=""><a href="#tab3" data-toggle="tab">Отзывы <?php echo osC_Reviews::getTotal(osc_get_product_id($osC_Product->getID()));?></a></li> -->
	</ul>
	
	
	<div class="tab-content">
          <!-- Description -->
          <div class="tab-pane active" id="tab1">
            <div itemprop="description">
  				<?php echo $osC_Product->getDescription(); ?>
			</div>
          </div>

          <!-- Sepcs -->
          <div class="tab-pane" id="tab2">
            
            <h5 class="title">Спецификация</h5>
            <?php if(strlen($osC_Product->getParams()) > 1) {?>
            
 			<table class="table table-striped tcart">
              <tbody>
            <?php 
            $params = explode(";", $osC_Product->getParams());      
            array_pop($params);   
            foreach($params as $param) 
            	echo '<tr>
                  <td width="35%"><strong>' . substr($param, 0, strpos($param, ':')) . '</strong></td>
                  <td>' . substr($param, strpos($param, ':') + 1) . '</td>
                </tr>'; ?>
                                                                                               
              </tbody>
            </table>
            <h5 class="title">Характеристики материала</h5>            
            
 			<table class="table table-striped tcart">
              <tbody>
				<tr>
					<td width="35%"><strong>Материал батута:</strong></td>
					<td>ПВХ ламинированная ткань Оксфорд (laminated PVC);</td>
				</tr>
				<tr>
					<td><strong>Застежки:</strong></td>
					<td>Лавсан</td>
				</tr>
				<tr>
					<td><strong>Предел прочности на растяжение:</strong></td>
					<td>до 136 кг</td>
				</tr>
				<tr>
					<td><strong>Прочность материала на разрыв:</strong></td>
					<td>до 14 кг;</td>
				</tr>
				<tr>
					<td><strong>Рабочий диапазон температур:</strong></td>
					<td>от - 10 до + 40 °C;</td>
				</tr>
              </tbody>
            </table>
            
			<?php }else{?>
			<p>Информация скоро будет.</p>
			<?php }?>
          </div>

          <!-- Review -->
 <!--          <div class="tab-pane" id="tab3">

	<h5 itemprop="itemreviewed">Отзывы <?php echo $osC_Template->getPageTitle()?></h5>
		<div class="item-review">    
            
   <?php 
   $Qreviews = osC_Reviews::getListing();
   while ($Qreviews->next()) { ?>
		<div itemscope itemtype="http://data-vocabulary.org/Review">
		<p>Автор: <span itemprop="reviewer"> <?php echo $Qreviews->value('customers_name'); ?></span></p>
		<p>Дата: <time itemprop="dtreviewed" datetime="<?php echo date("o-m-d", strtotime($Qreviews->value('date_added'))); ?>"><?php echo $Qreviews->value('date_added'); ?></time></p>
		<p>Оценка: <?php echo osc_image('/templates/' . $osC_Template->getCode() . '/images/stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($osC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . ' <span itemprop="rating" class="color">' . $Qreviews->valueInt('reviews_rating') ?></span></p>
		<span itemprop="description"><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 10000)); ?></span>
		<hr>
        </div>         
          <?php }?>  

            <h5 class="title">Написать отзыв</h5>

                                  <div class="form form-small">

                                      <form class="form-horizontal" action="<?php echo osc_href_link("magazin/", 'reviews=new&' . $osC_Product->getID() . '&action=process', 'NONSSL', false, false); ?>" method="post" onsubmit="return checkForm(this);">                                         

                                          <div class="control-group">
                                            <label class="control-label" for="name">Имя пользователя:</label>
                                            <div class="controls">
                                              <?php echo osc_draw_input_field('customer_name', null, 'class="input-large"');?>
                                            </div>
                                          </div>
                                          <div class="control-group">
                                            <label class="control-label" for="name">Email (не публикуется):</label>
                                            <div class="controls">
                                              <?php echo osc_draw_input_field('customer_email_address', null, 'class="input-large"');?>
                                            </div>
                                          </div>

                                          <div class="control-group">
                                            <div class="controls">  
                                            <?php echo $osC_Language->get('field_review_rating') . '' . $osC_Language->get('review_lowest_rating_title') . ' ' . osc_draw_radio_field('rating', array('1', '2', '3', '4', '5')) . ' ' . $osC_Language->get('review_highest_rating_title'); ?>
                                            </div>
                                          </div>


                                          <div class="control-group">
                                            <label class="control-label" for="name">Ваш отзыв о товаре:</label>
                                            <div class="controls">
                                               <?php echo osc_draw_textarea_field('review', null, null, 15, 'class="input-large"'); ?>
                                            </div>
                                          </div>

                                          <div class="form-actions">

                                            <button type="submit" class="btn">Отправить</button>
                                            <button type="reset" class="btn">Очистить</button>
                                          </div>
                                      </form>
                                    </div> 
          </div>
        </div> -->
	


</div>
</div>