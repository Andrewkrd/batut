<?php
/**************************************************
* Скрипт yandex.php для выгрузки товаров в формате YML для сайта на движке OSCommerce

//  Внимание! Вы можете бесплатно использовать скрипт на свой страх и риск. За любые ошибки разработчики отвественности не несут.
**************************************************/

//header("Content-type: text/html; charset=UTF-8"); 

header("Content-type: text/csv; charset=UTF-8"); 
header("Content-Disposition: attachment; filename=export.csv"); 

require('../includes/configure.php');
require('../includes/database_tables.php');
require('../includes/classes/database.php');

$ctt = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_DATABASE) or die("Ошибка базы данных");

mysql_query("SET NAMES UTF8");
	
$yandex = "Группа^Подгруппа^Артикул^Ид^Товар^Полное описание^Розничная цена^Валюта^Картинка
";

$arr_cats=array();
#----------------------------------------------
    # для яндекса вывожу все подкатегории

	$tmp="
		select
			c.categories_id, cd.categories_name, c.parent_id
		FROM
			" . DB_TABLE_PREFIX . "categories as c, " . DB_TABLE_PREFIX . "categories_description as cd
		WHERE
			c.categories_id=cd.categories_id
		ORDER BY
			c.parent_id, cd.categories_name";

	$res = mysql_query($tmp);
	while ($rezzzz = mysql_fetch_array($res)){		

		$arr_cats[$rezzzz['categories_id']]=$rezzzz;
	}
	
//print_r($arr_cats);
 $tmp="
	select
		p.products_id, pc.categories_id, pd.products_name,
		p.products_price, pd.products_description, pi.image, 
		p.products_quantity, pd.products_keyword, p.manufacturers_id,
		p.products_model, p.available
	FROM
		" . DB_TABLE_PREFIX . "products AS p,
		" . DB_TABLE_PREFIX . "products_description as pd,
		" . DB_TABLE_PREFIX . "products_images as pi, 
		" . DB_TABLE_PREFIX . "products_to_categories as pc	 
	WHERE
		p.products_status=1 AND
		p.products_id=pd.products_id AND
		p.products_id=pi.products_id AND
		p.products_id=pc.products_id AND
		pi.default_flag=1 AND
		p.parent_id=0
	ORDER BY pd.products_name
	";

$res = mysql_query($tmp);
$arr_names=array();



$last_offer = 0;	// id последнего товара

while ($tovar = mysql_fetch_array($res)) {

	if( $last_offer == $tovar['products_id'] ) {
		continue;
	}
	
	$valuta="RUR";
	$price=round($tovar['products_price']);
	
	if(1) {
		$tmp="SELECT specials_new_products_price
			  FROM " . DB_TABLE_PREFIX . "specials
			  WHERE 
			  		products_id=" . $tovar['products_id'] . " AND
			  		status = 1 AND
			  		(UNIX_TIMESTAMP(expires_date) > UNIX_TIMESTAMP() OR expires_date IS NULL)
		
			  ";
		$result = mysql_query($tmp);
		if(mysql_numrows($result) > 0)
			$price = round(mysql_result($result, 0, 0));		
	}
	
	
	if($price <= 0) {
		$tmp="SELECT products_price
			  FROM " . DB_TABLE_PREFIX . "products
			  WHERE parent_id=" . $tovar['products_id'] . "
			  LIMIT 1";
		$res2 = mysql_query($tmp);
		$price = round(mysql_result($res2, 0, 0));
	}
		
	if( $price <= 0 || $price > 1000000000 ) {
		echo "price error";
		continue;
	}
		
	$description = str_replace("\r\n", "", nl2br($tovar['products_description'])); // addslashes(htmlspecialchars(strip_tags($tovar['products_description'])));
	//$description = mb_substr($description, 0, 510, 'UTF-8');
	
	$tovar['products_name'] = htmlspecialchars($tovar['products_name']);
	
	#picture
	if ($tovar['image'] != "") {
		$ppy = "<picture>{$csite}images/products/large/".$tovar['image']."</picture>
     ";
		
	} else {
		$ppy = "<picture></picture>";
	}
	
	#Vendor
	if($tovar['manufacturers_id'] > 0) {		
		$res1 = mysql_query("SELECT manufacturers_name FROM " . DB_TABLE_PREFIX . "manufacturers WHERE manufacturers_id=".$tovar['manufacturers_id']);
		$manufacturers_name = mysql_result($res1, 0, "manufacturers_name");		
		$vendor = "<vendor>" . $manufacturers_name . "</vendor>
     ";					
	} else {
		$vendor = "<vendor></vendor>";
	}
	
	#Vendorcode
	if ($tovar['products_model'] != "") {
		$vendorCode =  $tovar['products_model'];		
	} else {
		$tmp="SELECT products_model
			  FROM " . DB_TABLE_PREFIX . "products
			  WHERE parent_id=" . $tovar['products_id'] . "
			  LIMIT 1";
		$res3 = mysql_query($tmp);
		$products_model = @mysql_result($res3, 0, 0);	
		if ($products_model != "")
			$vendorCode = $products_model;
		else 
			$vendorCode = "";
	}		
	
	
	#Category
	$res_category = mysql_query("SELECT categories_id FROM " . DB_TABLE_PREFIX . "products_to_categories WHERE products_id=".$tovar['products_id']);
	$arr_category = mysql_fetch_array($res_category);
	$catf =  mysql_result($res_category, 0, "categories_id");	

	$cat_name = $arr_cats[$catf]["categories_name"];
			
	
//$yandex = "Группа#Подгруппа#Артикул#Ид#Товар#Полное описание#Розничная цена#Картинка\n";
	
#----------------------------------------------
$yandex.= "Импорт^" . $cat_name . "^" . $vendorCode . "^" . "0" . $tovar['products_id'] . "^" . $tovar['products_name'] . "^\"" . $description . "\"^" . $price . ".00" . "^RUB^" . $tovar['image'] . "
";

	
	$last_offer = $tovar['products_id'];
}	



#выводим, что нагенерили
echo $yandex;
//$handle = fopen("/home/u302429/ujirafika.ru/www/admin/test.txt", "a");
//fwrite($handle, $yandex);
#для отладки
#echo '<pre>';
#echo htmlspecialchars($yandex);
#echo '</pre>';
?>