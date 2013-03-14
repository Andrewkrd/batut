<?php
###############################

// Sitemap для OSCommerce 3.0 
// @Author Кузнецов Андрей labinsk@inbox.ru

###############################

header('Content-type: application/xml');
header("Content-Type: text/xml; charset=UTF-8");
/*
require('includes/configure.php');
require('includes/database_tables.php');
require('includes/classes/database.php');
require('includes/classes/category_tree.php');*/
$_SERVER["REQUEST_URI"] = "/sitemap.php";

require('includes/application_top.php');

$ctt = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_DATABASE) or die("Ошибка базы данных");

mysql_query("SET NAMES UTF8");

$csite = "http://batut-krasnodar.ru/";//Вписать свой адрес магазина

$now = date('Y-m-d');

$map=<<<END
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
      <loc>{$csite}</loc>
      <lastmod>$now</lastmod>
      <changefreq>always</changefreq>      
    </url>
END;


 $tmp="
	select
		p.products_id, pd.products_keyword, p.products_last_modified, p.products_date_added, p.manufacturers_id, m.manufacturers_sef
	FROM
		" . DB_TABLE_PREFIX . "products AS p,
		" . DB_TABLE_PREFIX . "products_description as pd,
		" . DB_TABLE_PREFIX . "manufacturers as m
	WHERE
		p.products_status=1 AND
		p.products_id=pd.products_id AND
		(p.manufacturers_id=m.manufacturers_id)AND 
		p.parent_id=0 
	ORDER BY p.products_id";

$res = mysql_query($tmp);
$tovar_num = mysql_num_rows($res);

while ($tovar = mysql_fetch_array($res)) {
	
	if ($tovar['products_last_modified'] != "NULL")
		$lastmod = date('Y-m-d', strtotime($tovar['products_last_modified']));
	else 
		$lastmod = date('Y-m-d', strtotime($tovar['products_date_added']));

	/*$tmp_url ="
		select
			cd.category_url, cd.categories_id 
		FROM
			" . DB_TABLE_PREFIX . "products_to_categories AS c,
			" . DB_TABLE_PREFIX . "categories_description AS cd			 
		WHERE
			cd.categories_id=c.categories_id AND
			c.products_id="	. 	$tovar["products_id"] . "
		ORDER BY categories_id";
	$res_url = mysql_query($tmp_url);
	$cpath = "cPath,";
	$cat_url = "";
	$sum = 0;
	while ($cat = mysql_fetch_array($res_url)) {		
		if( strlen($cpath) > 6 )
			$cpath .= "_";
		
			$cpath .= $cat["categories_id"];
		
		
		if($sum < 2) {	
			$cat_url .= "/" . $cat["category_url"];
			$sum += 1;
		}
	}*/
		
#----------------------------------------------
$keyword = mb_strtolower($tovar["products_keyword"]);
$map.=<<<END
    
    <url>
      <loc>{$csite}magazin/$keyword</loc>
      <lastmod>$lastmod</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
/*
if($tovar["manufacturers_id"] > 0) {
	$map.=<<<END
	    
   <url>
      <loc>{$csite}products.php/$tovar[products_keyword]/manufacturers,$tovar[manufacturers_sef]</loc>
      <lastmod>$lastmod</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
}*/
}

// Новые товары

/*$page = 1;
for($i=1; $i<$tovar_num; $i=$i+10)	{
	
$map.=<<<END
    
    <url>
      <loc>{$csite}products.php/new/page,$page</loc>
      <lastmod>$now</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;

$page = $page+1;
}
*/

// Специальная цена
/*
$tmp="
	select
		count(*)
	FROM
		" . DB_TABLE_PREFIX . "specials
	WHERE
		expires_date IS NULL or expires_date < NOW()";

$res_spec = mysql_query($tmp);
$cnt_spec = mysql_result($res_spec, 0, 0);
$page = 1;

for($i=1; $i<$cnt_spec; $i=$i+10)	{

$map.=<<<END
    
    <url>
      <loc>{$csite}products.php/specials/page,$page</loc>
      <lastmod>$now</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
$page = $page+1;
}
*/



// Производители
/*
$manuf_sql="
	select
		manufacturers_id, manufacturers_sef
	FROM
		" . DB_TABLE_PREFIX . "manufacturers";

$res_manuf = mysql_query($manuf_sql);
while ($manuf = mysql_fetch_array($res_manuf)) {
if($manuf["manufacturers_id"] > 0)
$map.=<<<END
    
    <url>
      <loc>{$csite}index.php/manufacturers,$manuf[manufacturers_sef]</loc>
      <lastmod>$now</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
}
*/



// Раздел Информация 

$tmp="
	select
		info_url, last_modified
	FROM
		" . DB_TABLE_PREFIX . "info
	WHERE
		active = 1
	ORDER BY info_id";

$res2 = mysql_query($tmp);

while ($info = mysql_fetch_array($res2)) {

$lastmod = date('Y-m-d', strtotime($info['last_modified']));

$map.=<<<END
    
    <url>
      <loc>{$csite}info/$info[info_url]</loc>
      <lastmod>$lastmod</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
}

$map.=<<<END
    
    <url>
      <loc>{$csite}info/contact</loc>
      <lastmod>2012-01-22</lastmod>
      <changefreq>daily</changefreq>      
    </url>
    <url>
      <loc>{$csite}info/sitemap</loc>
      <lastmod>2012-01-22</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;




$tree = new osC_CategoryTree();
$tarr = $tree->buildBranchMap(0);

$links = array();

foreach($tarr as $val1)	{
	if(array_key_exists('link', $val1))
		$links[] = $val1['link'];
	else
		foreach($val1 as $val2) {
			if(array_key_exists('link', $val2))
				$links[] = $val2['link'];		
			else
				foreach($val1 as $val2) {
					if(array_key_exists('link', $val2))
						$links[] = $val2['link'];
				}
				
		}
	
}

$lastmod_cat = date('Y-m-d', strtotime("-2 day"));
$links = str_replace("/index.php/", "/category/", $links);
foreach ($links as $link) {
$map.=<<<END
    
    <url>
      <loc>$link</loc>
      <lastmod>$lastmod_cat</lastmod>
      <changefreq>daily</changefreq>      
    </url>
END;
}

$map .= "\n</urlset>";

//echo $map;

file_put_contents("sitemap.tmp", $map);
copy("sitemap.tmp", "sitemap.xml");

#для отладки
#echo '<pre>';
#echo htmlspecialchars($map);
#echo '</pre>';
?>