<?php
/*
  $Id: richer_designs.php,v 1.23 2012/10/16 19:36:49 ujirafika.ujirafika Exp $
*/
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $osC_Language->getCharacterSet(); ?>" />
<meta charset="utf-8">
	<?php
	// Begin Dynamic Meta Tag Code
	if (file_exists('includes/modules/kiss_meta_tags/kiss_meta_tags.php'))
	{
	  include_once('includes/modules/kiss_meta_tags/kiss_meta_tags.php');
	}
	// End Dynamic Meta Tag Code
	?>	
<link rel="shortcut icon" href="<?php echo osc_href_link(null, null, 'AUTO', false) . 'favicon.ico'; ?>" />
<base href="<?php echo osc_href_link(null, null, 'AUTO', false); ?>" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/flexslider.css" rel="stylesheet">
<link href="/css/prettyPhoto.css" rel="stylesheet">
<link rel="stylesheet" href="/css/font-awesome.css"> 
<link href="/css/sidebar-nav.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">
<link href="/css/red.css" rel="stylesheet">	
<link href="/css/bootstrap-responsive.css" rel="stylesheet">
<script src="/js/jquery.js"></script>

<?php
   if ($osC_Template->hasJavascript()) {
    $osC_Template->getJavascript();
  }
?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/img/favicon.ico">
    
    <script type="text/javascript">
    var _gaq = _gaq || [];_gaq.push(['_setAccount', 'UA-35383379-1']);_gaq.push(['_trackPageview']);(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
    </script>

</head>
<body>
<!-- boot 
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Батуты Happy Hop</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li <?php if($_SERVER["REQUEST_URI"] == "/" || $_SERVER["REQUEST_URI"] == "/index.php") echo 'class="active"';?>></i> <a href="/"><i class="icon-home"></i> Главная</a></li>
              <li><a href="#about">Продукция</a></li>
              <li <?php if(strpos($_SERVER["REQUEST_URI"], "/info/contact") !== false) echo 'class="active"';?>><a href="/info/contact">Контакты</a></li>
            </ul>
          </div>

		<form class="navbar-search pull-right" action="search.php">
			<input type="text" class="search-query span2" placeholder="Поиск по сайту" name="keywords">
		</form>
        </div>
      </div>
    </div>
 -->

<!-- boot 
<div class="container">
	<div class="row fluid"> -->

<?php
	if ($osC_Template->hasPageHeader()) {
?>
<!-- header //-->
<!-- Header starts -->
  <header>
    <div class="container">
      <div class="row">

        <div class="span4">
          <!-- Logo. Use class "color" to add color to the text. -->
          <div class="logo">
            <h1><a href="/">Батуты&nbsp;<span class="color bold">Happy Hop</span></a></h1>
            <p class="meta" style="font-size: 16px;">+7 (861) 290-44-42</p>
          </div>
        </div>
       
        
         <div class="span5 pull-right">          
          <!-- Search form -->            
           <form class="form-search" action="/search.php">
            <div class="input-append">
              <input class="span3" id="appendedInputButton" type="text" placeholder="Введите фразу для поиска" name="keywords" />
              <button class="btn" type="submit">Поиск</button>
			</div>
           </form>            
   	
          <div class="hlinks">
            <span>
              <!-- item details with price -->
            <?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'), $osC_Language->get('cart_contents')); ?> 
            <i class="icon-shopping-cart"></i>
			<?php //echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'),  osc_image('/templates/' . $osC_Template->getCode() . '/images/header-links-basket.png', $osC_Language->get('cart_contents'))); ?>
			<?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'),  $osC_ShoppingCart->numberOfItems() . ' ' . $osC_Language->get('items') . ' - ' . $osC_Currencies->format($osC_ShoppingCart->getTotal())); ?>
            </span>

              <!-- Login and Register link -->
              <span class="lr"><?php if ($osC_Customer->isLoggedOn()) {?><a href="/account.php?logoff">выйти</a><?php }else{ ?><a href="/account.php?login">войти</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/account.php?create">зарегистрироваться</a><?php }?></span>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Header ends -->

<?php
	} // hasPageHeader
?>

 <!-- Navigation -->
          <div class="navbar">
           <div class="navbar-inner">
             <div class="container">
               <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
               </a>
               <div class="nav-collapse collapse">
                 <ul class="nav">
                   <li <?php if($_SERVER["REQUEST_URI"] == "/" || $_SERVER["REQUEST_URI"] == "/index.php") echo 'class="active"';?>><a href="/"><i class="icon-home"></i></a></li>
                   <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Аккаунт<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="/account.php">Мой Аккаунт</a></li>
                        <li><a href="/checkout.php">Корзина</a></li>
						<?php if ($osC_Customer->isLoggedOn()) {?> 
                        <li><a href="/account.php/orders">История заказов</a></li>
                        <li><a href="/account.php/edit">Редактировать профиль</a></li>
                        <li><a href="/account.php/password">Изменить пароль</a></li>
                        <li><a href="/account.php/logoff">Выйти</a></li>
                        <?php }?>
                      </ul>
                   </li>                   
                   <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Категории<b class="caret"></b></a>
                      <ul class="dropdown-menu">					
						<?php 
						foreach ($osC_Template->getBoxModules('right') as $box) {
							$box;
						}						
						$osC_Box = new osC_Boxes_categories_root();
						$osC_Box->initialize();
						include('templates/' . $osC_Template->getCode() . '/modules/boxes/categories_root.php'); 
						unset($osC_Box);
						?>						
                      </ul>
                   </li>
                   <li><a href="/articles/">Статьи</a></li>
                   <li><a href="/articles/foto-i-video">Фото и Видео</a></li>
                   <li><a href="/info/dostavka-i-oplata">Доставка и оплата</a></li>
                   <li><a href="/info/contact">Контакты</a></li>
                 </ul>
               </div>
              </div>
           </div>
         </div>
		
	
<?php if($_SERVER["REQUEST_URI"] == "/" || $_SERVER["REQUEST_URI"] == "/index.php") {?>

<!-- Flex Slider starts -->

<div class="container flex-main">
  <div class="row">
    <div class="span12">
            
            <div class="flex-image flexslider">
              <ul class="slides">
                 <li>
                  <a href="/category/naduvnyie_batutyi"><img src="img/photos/slide-main.jpg" /></a>
                </li>
                
               <li>
                  <a href="/magazin/naduvnoj-batut-s-gorkoj-formula-1-happy-hop-9026"><img src="img/photos/slide9026.jpg" />
                  <div class="flex-caption">
                   <h3>Батут с горкой cупер Формула 1 - <span class="color">47 990 руб.</span></h3>
                   <p>Игровой батут выполнен в форме болида формулы 1! Для будущих гонщиков и любителям автомобилей с открытыми колёсами.</p>
                     <div class="button">
                      <a href="/magazin/naduvnoj-batut-s-gorkoj-formula-1-happy-hop-9026?action=cart_add">Купить прямо сейчас!</a>
                     </div>
                  </div>                  
                </li>
                
                <li>
                  <a href="/magazin/detskij-batut-shhenki-dalmatinca-9109"><img src="img/photos/slider2.jpg" /></a>
                  <div class="flex-caption">
                     <!-- Title -->
                     <h3>ХИТ Продаж! Надувной батут Далматинец - <span class="color">45 500 руб.</span></h3>
                     <!-- Para -->
                     <p>Уникальный дизайнерский ход! Батут в виде забавных щенков далматинцев, не оставят равнодушными Ваших детей, имеет в составе игровую прыжковую комнату, огороженную защитной сетью и большую надувную горку.</p>
                     <div class="button">
                      <a href="/magazin/detskij-batut-shhenki-dalmatinca-9109?action=cart_add">Купить прямо сейчас!</a>
                     </div>
                  </div>                  
                </li>
                
                <li>
                  <a href="/magazin/Batut-akvapark-s-bassejnom-Happy-Hop-9045"><img src="img/photos/slider3.jpg" />
                  <div class="flex-caption">
                     <!-- Title -->
                     <h3>Мини аквапарк у вас дома за - <span class="color">52 200 руб.</span></h3>
                     <!-- Para -->
                     <p>3 горки для катания, габариты изделия позволяют играть в нем одновренемнно 5-ти детям. Подключите садовый шланг и превратите отличный игровой центр в настоящий аквапарк!</p>
                     <div class="button">
                      <a href="/magazin/batut-akvapark-s-bassejnom-happy-hop-9045?action=cart_add">Купить прямо сейчас!</a>
                     </div>
                  </div> 
                  </a>
                </li>
                
              </ul>
            </div>

    </div>
  </div>
</div>

<!-- Flex slider ends -->

<!-- Promo box starts -->

<div class="promo">
  <div class="container">
    <div class="row">

      <!-- Red color promo box -->
      <div class="span4">
        <div class="pbox rcolor">
          <div class="pcol-left">
            <a href="/category/malyie_batutyi"><img src="img/photos/promo-1.png" alt="" /></a>
          </div>
          <div class="pcol-right">
            <p class="pmed"><a href="/category/malyie_batutyi">Малые</a></p>
            <p class="psmall"><a href="/category/malyie_batutyi">Предназначены для 2-х детей. Общий вес которых не превышает 91 кг.</a></p>
          </div>
          <div cass="clearfix"></div>
        </div>
      </div>


      <!-- Blue color promo box -->
      <div class="span4">
        <div class="pbox bcolor">
          <div class="pcol-left">
            <a href="/category/srednie_batutyi"><img src="img/photos/promo-2.png" alt="" /></a>
          </div>
          <div class="pcol-right">
            <p class="pmed"><a href="/category/srednie_batutyi">Средние</a></p>
            <p class="psmall"><a href="/category/srednie_batutyi">Допустимое количество детей - 3 ребенка, общий вес которых не превышает 135 кг.</a></p>
          </div>
          <div cass="clearfix"></div>
        </div>
      </div>

      <!-- Green color promo box -->
      <div class="span4">
        <div class="pbox gcolor">
          <div class="pcol-left">
            <a href="/category/bolshie_batutyi"><img src="img/photos/promo-3.png" alt="" /></a>
          </div>
          <div class="pcol-right">
            <p class="pmed"><a href="/category/bolshie_batutyi">Большие</a></p>
            <p class="psmall"><a href="/category/bolshie_batutyi">Для игры одновременно 4-х 5-ти детей, общий вес которых не более 226 кг.</a></p>
          </div>
          <div cass="clearfix"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Promo box ends -->	
	

<!-- Promo text start -->	   
<div class="container newsletter">
  <div class="row">
    <div class="span12">
      <div class="well">
        <h1>Детские надувные батуты Happy Hop в Краснодаре!</h1>
        <p>Официальный дилер Happy Hop на юге России. <strong>Купить батут</strong> оптом и в розницу в Краснодаре. Весь модельный ряд детских надувных батутов в наличии в Краснодаре. Swiftech Company Ltd - ведущий производитель надувных батутов известный на рынке под брендом <strong>Happy Hop</strong>. 
        Бренд Хэппи Хоп включает в себя полный спектр надувных изделий батуты, горки, полосы препятствий, игровые центры и водные горки. 
        Более 60 видов изделий выполнены по уникальным технологиям, имеют привлекательный дизайн и были запатентованы.</p>
      </div>
    </div>
  </div>
</div>
<!-- Promo text end -->	  

      
<div class="items"> 
<div class="container">

<?php 
foreach ($osC_Template->getContentModules('after') as $box) {
	$box;
}

$osC_Box = new osC_Content_popular_products();
$osC_Box->initialize();
include('templates/' . $osC_Template->getCode() . '/modules/content/popular_products.php'); ?>

</div>
</div>
<?php }else {?>

<div class="items">
  <div class="container">
    <div class="row">

<?php
	$content_left = '';

	if ($osC_Template->hasPageBoxModules()) {
	  ob_start();

	  foreach ($osC_Template->getBoxModules('left') as $box) {
	    $osC_Box = new $box();
	    $osC_Box->initialize();

	    if ($osC_Box->hasContent()) {
	      if ($osC_Template->getCode() == DEFAULT_TEMPLATE) {
	        include('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php');
	      } else {
	        if (file_exists('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php')) {
	          include('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php');
	        } else {
	          include('templates/' . DEFAULT_TEMPLATE . '/modules/boxes/' . $osC_Box->getCode() . '.php');
	        }
	      }
	    }

	    unset($osC_Box);
	  }

	  $content_left = ob_get_contents();
	  ob_end_clean();

  	if (!empty($content_left)) {
?>

<!-- left_column //-->

    <div class="span3 hidden-phone">      
      <?php echo $content_left; ?>			
    </div>

<!-- end_left_column -->

<?php
		}
	}
?>

<!-- main_column -->

<div class="span9">
      
<div xmlns:v="http://rdf.data-vocabulary.org/#">
			<ul class="breadcrumb"><?php
    if ($osC_Services->isStarted('breadcrumb')) {
			$bread_crumb = $osC_Breadcrumb->getArray();
			$num_tabs = sizeof($bread_crumb)-1;
			foreach( $bread_crumb as $key => $value ) {
				echo "\t" . '<li typeof="v:Breadcrumb"' . ($key == $num_tabs ? ' class="active"' : '') . '> ' . $value . ' </li> ' . ($key !== $num_tabs ? ' / ' : '') . "\n";
			}
    }
?>
			</ul>
</div>

<?php
  if ($osC_MessageStack->size('header') > 0) {
    echo $osC_MessageStack->get('header');
  }

  if ($osC_Template->hasPageContentModules()) {
    foreach ($osC_Services->getCallBeforePageContent() as $service) {
      $$service[0]->$service[1]();
    }

    foreach ($osC_Template->getContentModules('before') as $box) {
      $osC_Box = new $box();
      $osC_Box->initialize();

      if ($osC_Box->hasContent()) {
        if ($osC_Template->getCode() == DEFAULT_TEMPLATE) {
          include('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php');
        } else {
          if (file_exists('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php')) {
            include('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php');
          } else {
            include('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $osC_Box->getCode() . '.php');
          }
        }
      }

      unset($osC_Box);
    }
  }

  if ($osC_Template->getCode() == DEFAULT_TEMPLATE) {
    include('templates/' . $osC_Template->getCode() . '/content/' . $osC_Template->getGroup() . '/' . $osC_Template->getPageContentsFilename());
  } else {
    if (file_exists('templates/' . $osC_Template->getCode() . '/content/' . $osC_Template->getGroup() . '/' . $osC_Template->getPageContentsFilename())) {
      include('templates/' . $osC_Template->getCode() . '/content/' . $osC_Template->getGroup() . '/' . $osC_Template->getPageContentsFilename());
    } else {
      include('templates/' . DEFAULT_TEMPLATE . '/content/' . $osC_Template->getGroup() . '/' . $osC_Template->getPageContentsFilename());
    }
  }
?>

<div class="clearfix"></div>

<?php
  if ($osC_Template->hasPageContentModules()) {
    foreach ($osC_Services->getCallAfterPageContent() as $service) {
      $$service[0]->$service[1]();
    }

    foreach ($osC_Template->getContentModules('after') as $box) {
      $osC_Box = new $box();
      $osC_Box->initialize();

      if ($osC_Box->hasContent()) {
        if ($osC_Template->getCode() == DEFAULT_TEMPLATE) {
          include('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php');
        } else {
          if (file_exists('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php')) {
            include('templates/' . $osC_Template->getCode() . '/modules/content/' . $osC_Box->getCode() . '.php');
          } else {
            include('templates/' . DEFAULT_TEMPLATE . '/modules/content/' . $osC_Box->getCode() . '.php');
          }
        }
      }

      unset($osC_Box);
    }
  }
?>
</div>
<!-- eof_main_column //-->

<?php
  $content_right = '';

  if ($osC_Template->hasPageBoxModules()) {
    ob_start();

    foreach ($osC_Template->getBoxModules('right') as $box) {
      $osC_Box = new $box();
      $osC_Box->initialize();

      if ($osC_Box->hasContent()) {
        if ($osC_Template->getCode() == DEFAULT_TEMPLATE) {
          include('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php');
        } else {
          if (file_exists('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php')) {
            include('templates/' . $osC_Template->getCode() . '/modules/boxes/' . $osC_Box->getCode() . '.php');
          } else {
          }
        }
      }

      unset($osC_Box);
    }

    $content_right = ob_get_contents();
    ob_end_clean();
  }

  if (!empty($content_right)) {
?>

<!-- right_column //-->
<!-- 	<div id="right-column">  -->	

<?php
   // echo $content_right;
?>

<!-- 		</div> -->
<!-- eof_right_column //-->

<?php
	}
?>

</div>
</div>
</div>

</div>

<?php }?>

<?php if ($osC_Template->hasPageFooter()) {?> 

<div class="container newsletter">
  <div class="row">
    <div class="span12">
      <div class="well">
               <h5><i class="icon-envelope-alt"></i> Рассылка - Скидки, акции и многое другое!!!</h5>
               <p>Будь в курсе событий, только самая актуальная и важная информация.</p>
               <form class="form-inline" action="/info/subscribe" method="post">
                  <div class="controls controls-row">
                    <input name="email_address" class="span3" type="text" placeholder="Введите свой email для подписки">
                    <button type="submit" class="btn">Подписаться</button>
                  </div>
               </form>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container">
    <div class="row">
      <div class="span12">

            <div class="row">

              <div class="span4">
                <div class="widget">
                  <h5>Контакты</h5>
                  <hr>
                    <div class="social">
                      <a href="#"><i class="icon-facebook facebook"></i></a>
                      <a href="#"><i class="icon-twitter twitter"></i></a>
                      <a href="#"><i class="icon-linkedin linkedin"></i></a>
                      <a href="#"><i class="icon-google-plus google-plus"></i></a> 
                    </div>
                  <hr>
                  <i class="icon-home"></i> &nbsp; г. Краснодар, ул. Сормовская, 34, магазин Жирафик</p>
                  <hr>
                  <i class="icon-phone"></i> &nbsp; +7 (861) 290-44-42, пн-сб 9 до 19, вс до 18.
                  <hr>
                  <i class="icon-envelope-alt"></i> &nbsp; <a href="mailto:%20info@batut-krasnodar.ru?subject=Вопрос%20на%20сайте!" style="font-size: 14px">info@batut-krasnodar.ru</a>                 
                </div>
              </div>

              <div class="span4">
							<div class="widget">
								<h5>Преимущества</h5>
								<hr>
								<div>
									<ul>
										<li class="advantages">
												<i class="icon-circle-arrow-right"></i>
												Доставка любого батута по г. Краснодар, Новроссийск, Анапа, Витязево, Геленджик, Туапсе, Сочи, Адлер осуществляется бесплатно. Оплата курьеру при получении наличными.</span>
										</li>
										<li class="advantages">
												<i class="icon-circle-arrow-right"></i>
												Отправим батут в любой город России транспортной компанией, доставка за наш счет.
										</li>
										<li class="advantages">
												<i class="icon-circle-arrow-right"></i>
                                                                                                <a href="/articles/kak-ustanovit-batut-Happy-hop">Установка батута</a> - бесплатно! При доставке наш курьер установит, надует купленный батут и ответит на все Ваши вопросы.
										<li>
												<i class="icon-circle-arrow-right"></i>
												<a href="/info/batutyi-optom">Батуты Happy hop оптом</a> со склада в Краснодаре. Большой товарный запас, все модели в наличии.
									</ul>
								</div>
							</div>
						</div>

              <div class="span4">
                <div class="widget">
                  <h5>Разделы</h5>
                  <hr>
                  <div class="two-col">
                    <div class="col-left">
                      <ul>
                        <?php 
						foreach ($osC_Template->getBoxModules('right') as $box) {
							$box;
						}						
						$osC_Box = new osC_Boxes_categories_root();
						$osC_Box->initialize();
						include('templates/' . $osC_Template->getCode() . '/modules/boxes/categories_root.php'); 
						unset($osC_Box);
						?>	
                      </ul>
                    </div>
                    <div class="col-right">
                      <ul>
                        <li><a href="/articles/kak-ustanovit-batut-Happy-hop">Установка батута</a></li>
                        <li><?php if ($osC_Customer->isLoggedOn()) {?><a href="/account.php">Мой аккаунт</a><?php }else{ ?><a href="/account.php/login">Войти</a><?php }?></a></li>
                        <li><a href="/checkout.php">Корзина</a></li>
                        <li><a href="/info/dostavka-i-oplata">Доставка и оплата</a></li>
                        <li><a href="/info/batutyi-optom">Батуты оптом</a></li>
                        <li><a href="/info/sitemap">Карта сайта</a></li>
                        <li><a href="/info/contact">Контакты</a></li>
                      </ul>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
              
            </div>

            <hr>
            <!-- Copyright info -->
            <p class="copy">&copy; 2015 - Интернет-магазин батутов Краснодар | <a href="/">Главная</a> | <a href="/info/dostavka-i-oplata">Доставка и оплата</a> | <a href="/checkout.php">Корзина</a> | <a href="/info/contact">Контакты</a></p>
      </div>
    </div>
  <div class="clearfix"></div>
  </div>
</footer>
<?php }?>
<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span> 
<!-- JS -->

<script src="/js/bootstrap.js"></script> <!-- Bootstrap -->
<script src="/js/jquery.prettyPhoto.js"></script> <!-- Pretty Photo -->
<script src="/js/jquery.tweet.js"></script> <!-- Twitter -->
<script src="/js/jquery.flexslider-min.js"></script> <!-- Flex slider -->
<script src="/js/nav.js"></script> <!-- Sidebar navigation -->
<script src="/js/filter.js"></script> <!-- Filter for support page -->
<script src="/js/jquery.carouFredSel-6.1.0-packed.js"></script> <!-- Carousel for recent posts -->
<script src="/js/custom.js"></script> <!-- Custom codes -->

<script>
(function(){
var widget_id = 722123;
_shcp =[{widget_id : widget_id}];
var lang =(navigator.language || navigator.systemLanguage 
|| navigator.userLanguage ||"en")
.substr(0,2).toLowerCase();
var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
var hcc = document.createElement("script");
hcc.type ="text/javascript";
hcc.async =true;
hcc.src =("https:"== document.location.protocol ?"https":"http")
+"://"+ url;
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hcc, s.nextSibling);
})();
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter17546242 = new Ya.Metrika({id:17546242, enableAll: true, trackHash:true, webvisor:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/17546242" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>