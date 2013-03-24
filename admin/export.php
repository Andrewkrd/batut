<?php
/**************************************************
* Скрипт yandex.php для выгрузки товаров в формате YML для сайта на движке OSCommerce

//  Внимание! Вы можете бесплатно использовать скрипт на свой страх и риск. За любые ошибки разработчики отвественности не несут.
**************************************************/


header('Content-type: application/xml');
header("Content-Type: text/xml; charset=UTF-8");

require('../includes/configure.php');
require('../includes/database_tables.php');
require('../includes/classes/database.php');

$ctt = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_DATABASE) or die("Ошибка базы данных");

mysql_query("SET NAMES UTF8");
	
#########################
#ТУТ НАЧИНАЮ ГЕНЕРИТЬ XML
# переменные для заголовка
$cdate = date("Y-m-d H:i",time());
$csite = "http://ujirafika.ru/";//Вписать свой адрес магазина
$cname = "Жирафик";
$csite2 = $csite;
$cname2 = $cname;
$cdesc = "Детский интернет-магазин в Краснодаре";
#----------------------------------------------
$yandex=<<<END
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="$cdate">

<shop>
<name>$cname</name>
<company>$cdesc</company>
<url>$csite</url>

<currencies>
    <currency id="RUR" rate="1"/>
</currencies>
END;

$arr_cats=array();
#----------------------------------------------
    # для яндекса вывожу все подкатегории
	$yandex .= "\n\n<categories>\n";
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
		#экранируем спецсимволы
		$rezzzz['categories_name']=htmlspecialchars($rezzzz['categories_name']);
		$fftt = "    <category id=\"".$rezzzz['categories_id']."\"";
		if($rezzzz['parent_id']>0)	$fftt .= " parentId=\"".$rezzzz['parent_id']."\"";
		$fftt.= ">".$rezzzz['categories_name']."</category>\n";
		$yandex .= $fftt;

		$arr_cats[$rezzzz['categories_id']]=$rezzzz;
	}
	$yandex .= "</categories>\n";
	
$yandex .= "<local_delivery_cost>200</local_delivery_cost>\n";
#----------------------------------------------
$yandex .="\n<offers>\n";

#----- YANDEX ------

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
	ORDER BY pd.products_name";

$res = mysql_query($tmp);
$arr_names=array();

#список категорий, которые следует игнорировать и не выводить в Яндекс.Маркет
/*$arr_cats=array(
	2, 3, 4, 5, 66, 50, 57, 35, 8, 14, 15, 18
);*/

$last_offer = 0;	// id последнего товара

while ($tovar = mysql_fetch_array($res)) {
	// скрыть товар из определенных категорий
	/*if (!in_array($tovar[categoryID], $arr_cats)){
		continue;
	}*/
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
		
	$description=htmlspecialchars(strip_tags($tovar['products_description']));
	$description = mb_substr($description, 0, 510, 'UTF-8');
	
	$tovar['products_name'] = htmlspecialchars($tovar['products_name']);
	
	#picture
	if ($tovar['image'] != "") {
		$ppy = "<picture>{$csite}images/products/large/".$tovar['image']."</picture>
      ";
		
	} else {
		$ppy = "<picture></picture>";
	}
	
	$tmp="select
		DISTINCT pi.image		
	FROM		
		" . DB_TABLE_PREFIX . "products_images as pi
	WHERE
		pi.default_flag=0 AND
		pi.products_id = " . $tovar['products_id'];
	$res_pic = mysql_query($tmp);
	while ($pic = mysql_fetch_array($res_pic)) {		
		$ppy .= "<picture>{$csite}images/products/large/".$pic['image']."</picture>
      ";
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
		$vendorCode = "<vendorCode>". $tovar['products_model'] ."</vendorCode>
     ";		
	} else {
		$tmp="SELECT products_model
			  FROM " . DB_TABLE_PREFIX . "products
			  WHERE parent_id=" . $tovar['products_id'] . "
			  LIMIT 1";
		$res3 = mysql_query($tmp);
		if(mysql_num_rows($res3) > 0)	{
			$products_model = mysql_result($res3, 0, 0);
			$vendorCode = "<vendorCode>". $products_model ."</vendorCode>
      ";
		} else 
			$vendorCode = "<vendorCode></vendorCode>";
	}
	//$vendorCode = ""; $vendor = "";
	
	#local_delivery_cost
	 if($price < 1500)
		$ldc = "<local_delivery_cost>200</local_delivery_cost>
     ";
	else
		$ldc = "<local_delivery_cost>0</local_delivery_cost>
     ";
		
	#$available 0 - отсутствует, 1 - в наличии, 2 - под заказ, 3 - доставка около недели
	if ($tovar["available"] == 1)
		$available = "true";
	else
		$available = "false";
#----------------------------------------------
$keyword = mb_strtolower($tovar["products_keyword"]);
$yandex.=<<<END
    
    <offer id="$tovar[products_id]" available="$available">
      <url>{$csite}products.php/$keyword</url>
      <price>$price</price>
      <currencyId>$valuta</currencyId>
      <categoryId>$tovar[categories_id]</categoryId>
      $ppy <delivery>true</delivery>
      <name>$tovar[products_name]</name>
      $vendor $vendorCode $ldc <description>$description</description>      
    </offer>
END;
	
	$last_offer = $tovar['products_id'];
}	

$yandex .= "</offers>\n</shop>\n</yml_catalog>\n";


#выводим, что нагенерили
echo $yandex;
//$handle = fopen("/home/u302429/ujirafika.ru/www/admin/test.txt", "a");
//fwrite($handle, $yandex);
#для отладки
#echo '<pre>';
#echo htmlspecialchars($yandex);
#echo '</pre>';
?>