<?php
/*
  $Id: shopping_cart.php,v 1.3 2012/07/19 19:39:13 ujirafika.ujirafika Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>


      <div class="span9">
      	<div class="cart">
         <h1><i class="icon-shopping-cart"></i><?php echo $osC_Template->getPageTitle(); ?></h1>
			<br>
			
			<?php if ($osC_ShoppingCart->hasContents()) {?>
			<form name="shopping_cart" action="<?php echo osc_href_link(FILENAME_CHECKOUT, 'action=cart_update', 'SSL', false, false); ?>" method="post">
              <table class="table table-striped tcart">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Изображение</th>
                    <th>Количество</th>
                    <th>Цена за шт.</th>
                    <th>Всего</th>
                  </tr>
                </thead>
                <tbody>
                <?php
			    //$_cart_date_added = null; //if ($products['date_added'] != $_cart_date_added) {			        $_cart_date_added = $products['date_added'];echo sprintf($osC_Language->get('date_added_to_shopping_cart'), $products['date_added']);
				$row = 0;
			    foreach ($osC_ShoppingCart->getProducts() as $products) {
			    	$row =+ 1; ?>
                  <tr>
                    <td><?php echo $row;?></td>
                    <td><?php echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $products['keyword']), '<b>' . $products['name'] . '</b>');
				      if ( (STOCK_CHECK == '1') && ($osC_ShoppingCart->isInStock($products['item_id']) === false) ) {
				        echo '<span class="label label-important pull-left">' . 'товара нет в наличии' . '</span>'; 
				      }?>
      				</td>
                    <td><?php echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $products['keyword']), $osC_Image->show($products['image'], $products['name'], '', 'Mini'));?></td>
                    <td>
                      <div class="input-append cart-quantity">
                        <?php echo osc_draw_input_field('products[' . $products['item_id'] . ']', $products['quantity']); ?>
                        <button class="btn" type="submit"><i class="icon-refresh"></i></button>
                        <button class="btn btn-inverse" type="button" onclick="document.location.href='/checkout.php?action=cart_remove&item=<?php echo $products['item_id'];?>';"><i class="icon-remove"></i></button>       
                      </div>
                    </td>
                    <td><?php echo $osC_Currencies->displayPrice($products['price'], $products['tax_class_id'])?></td>
                    <td><?php echo $osC_Currencies->displayPrice($products['price'], $products['tax_class_id'], $products['quantity'])?></td>
                  </tr>
                <?php }?>                     
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th> <span class="pull-right">Итого:</span></th>
                    <th>
                    <?php foreach ($osC_ShoppingCart->getOrderTotals() as $module) if(strpos($module['title'], "Итого") !== false)
				        echo '<div class="totalPrice">' . $module['text'] . "</div>";
                    ?></th>
                  </tr>
                  
                </tbody>
              </table>              


              <div class="pull-right">
                    <button class="btn" type="submit">Пересчитать</button>
                    <?php if ($osC_Customer->isLoggedOn()) {?><button class="btn btn-danger" type="button" onclick="document.location.href='checkout.php/shipping'">Оформить</button><?php }?>
              </div>
              <div class="clearfix"></div>

         </form>
                               

<?php 
if (!$osC_Customer->isLoggedOn()) {

	if ($osC_MessageStack->size('error') > 0)
	echo $osC_MessageStack->get('error');
?> 

 <div class="form form-small">
  <form name="login" action="<?php echo osc_href_link(FILENAME_CHECKOUT, '?cart&unauth_order', 'SSL', false, false); ?>" method="post" class="form-horizontal">
    <h6 class="title">Быстрый заказ</h6>

    <p>Обязательными для заполнения являются все поля помеченные <em>*</em></p>

		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_first_name'), 'firstname', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('firstname', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_last_name'), 'lastname', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('lastname', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label('Номер телефона:', 'phone', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('phone', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('email_address', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Адрес:", 'street_address', null, true, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_input_field('street_address', null, "class=input-large"); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Подписаться на рассылку:", 'newsletter', null, false, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_checkbox_field('newsletter', '1'); ?>
		</div></div>
		
		<div class="control-group">
     	<?php echo osc_draw_label("Комментарии к заказу:", 'comment', null, false, "control-label minwidth");?>
			<div class="controls">	
			<?php echo osc_draw_textarea_field('comment', null, "class=input-large"); ?>
		</div></div>

	<button class="btn btn-danger" type="submit">Оформить заказ</button>
   
	</form>
 </div>

		<?php
			}
		
		
		} else {
		?>
		
		<p><?php echo $osC_Language->get('shopping_cart_empty'); ?></p>

		<?php
		  }
		?>  
 </div>
</div>   

