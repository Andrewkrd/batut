<?php 
/**
*
* KISS Dynamic SEO Meta Tags
* KISS = (Keep It Simple Stupid!) 
* 
* @package KISS Meta Tags SEO v1.0
* @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
* @link http://www.fwrmedia.co.uk
* @copyright Copyright 2008-2009 FWR Media
* @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk 
* @lastdev $Author: ujirafika.ujirafika $:  Author of last commit
* @lastmod $Date: 2012/10/16 21:17:37 $:  Date of last commit
* @version $Rev:: 39                                                  $:  Revision of last commit
* @Id $Id: kissmt.php,v 1.7 2012/10/16 21:17:37 ujirafika.ujirafika Exp $:  Full Details   
*/

  /**
  * General
  * Default text to add to meta titles that are too short. Leave blank if not needed. you can inlude %s to place your shop name in there.
  */

  /**
  * Homepage
  * Note: The %s is where your shop name will go in the text
  */

  define( 'KISSMT_TITLE_PADDING', 'Happy Hop Краснодар', true);

  // padding для главной (переопределяется вполседствии)
  define( 'KISSMT_TITLE_HOME_RE_PADDING', '' );
  
  define( 'KISSMT_HOMEPAGE_TITLE', 'Детские надувные батуты Happy Hop Краснодар' );
  
  define( 'KISSMT_HOMEPAGE_DESCRIPTION', 'Детские надувные батуты, купить батуты Happy Hop в Краснодар в интернет-магазине' );

  // Brand text ( manufacturer )
  define( 'KISSMT_BRAND_TEXT', ' %s ' );

  // Category text ( category )
  define( 'KISSMT_CAT_TEXT', ' %s ' );  
  
  // Manufacturers page (index.php)
  define( 'KISSMT_MANUFACTURERS_TEXT', 'Купить %s в Краснодаре' );

  // specials.php
  define( 'KISSMT_SPECIALS_TEXT', '%s в ' . STORE_NAME );
  // products_new.php
  define( 'KISSMT_PRODUCTS_NEW_TEXT', '%s в ' . STORE_NAME );
  // reviews.php
  define( 'KISSMT_REVIEWS_TEXT', '%s от покупателей в ' . STORE_NAME );
  // product_reviews.php  
  define( 'KISSMT_PRODUCT_REVIEWS_TEXT', 'Отзывы покупателей %s' );
  // product_reviews_info.php
  define( 'KISSMT_PRODUCT_REVIEWS_INFO_TEXT', 'Отзыв от %s' );
  
  /**
  * Generic pages (php file may or may not exist)
  * Here you can build meta tags for any of the peripheral pages simply by creating two defines
  * KISSMT_XXXXX_TITLE_TEXT and KISSMT_XXXXX_DESCRIPTION_TEXT
  * the XXXXX must be the name of the file ( less the .php ) in capitals
  * If there is a %s in the KISSMT_XXXXX_TITLE_TEXT this is replaced with the page HEADING_TITLE
  */
  // information.php (general infomation about the below)
  define( 'KISSMT_INFORMATION_TITLE_TEXT', 'Информация, доставка, оплата, скидки, контакты');
  define( 'KISSMT_INFORMATION_DESCRIPTION_TEXT', 'Полезная информация о интернет-магазине Жирафик' );
 
  define( 'KISSMT_CHECKOUT_TITLE_TEXT', 'Корзина - ' );
  define( 'KISSMT_CHECKOUT_DESCRIPTION_TEXT', 'Здесь отображены все товары, которые вы положили в корзину' );
  
  define( 'KISSMT_SEARCH_TITLE_TEXT', 'Поиск по сайту - ' );
  define( 'KISSMT_SEARCH_DESCRIPTION_TEXT', 'Поиск по сайту. Если не нашли нужный товар в каталоге, воспользуйтесь поиском или расширенным поиском по сайту' );
  
  define( 'KISSMT_ACCOUNT_TITLE_TEXT', 'Аккаунт - ' );
  define( 'KISSMT_ACCOUNT_DESCRIPTION_TEXT', 'Создание аккаунта на сайте или вход под своим именем' );
  
  // privacy.php
  define( 'KISSMT_PRIVACY_TITLE_TEXT', '%s privacy statement at ' . STORE_NAME );
  define( 'KISSMT_PRIVACY_DESCRIPTION_TEXT', 'We take all possible measures to ensure the safety of your privacy and information' );
  // conditions.php
  define( 'KISSMT_CONDITIONS_TITLE_TEXT', '%s conditions statement at ' . STORE_NAME );
  define( 'KISSMT_CONDITIONS_DESCRIPTION_TEXT', 'information about conditions of usage.' );
  // shipping.php
  define( 'KISSMT_SHIPPING_TITLE_TEXT', '%s information at ' . STORE_NAME );
  define( 'KISSMT_SHIPPING_DESCRIPTION_TEXT', 'Shipping, packaging and handling.' );
 // contact.php
  define( 'KISSMT_CONTACT_TITLE_TEXT', 'Контакты - ' );
  define( 'KISSMT_CONTACT_DESCRIPTION_TEXT', 'Воспользуйтесь удобным способом для связи с нами' );
  // sitemap.php
  define( 'KISSMT_SITEMAP_TITLE_TEXT', 'Карта сайта - ' );
  define( 'KISSMT_SITEMAP_DESCRIPTION_TEXT', 'Воспользуйтесь удобным инструментом - Карта сайта для исследования нашего интернет-магазина');
?>
