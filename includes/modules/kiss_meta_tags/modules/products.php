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
* @lastmod $Date: 2012/09/20 06:53:31 $:  Date of last commit
* @version $Rev:: 59                                                  $:  Revision of last commit
* @Id $Id: products.php,v 1.3 2012/09/20 06:53:31 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

    private  $noindex_follow = array();

    public function __construct() {

    } // end constructor

    public function process() {

		global $osC_Product, $osC_Currencies, $osC_Session;

			  $this->noindex_follow = array();
			  $leading_values = '';
			  $prodName = '';
			  $shortProductTitle = '';
			  $fullProductTitle = '';

			  if (isset($_GET['manufacturers'])) {
				$this->cache_suffix = $osC_Product->_data['keyword'] .  '_' . $_GET['manufacturers'];
			  	$product_string = '&manufacturers=' . $_GET['manufacturers'];
      		    $this->original_get = $osC_Product->_data['keyword'];			  								  
			  } elseif (isset($_GET['images'])) {

			  	  $this->cache_suffix = $osC_Product->_data['keyword'] . '_image_'; 
			  	  $product_string = '';

				  $link = '';
				  $iii = 1;	  
				  foreach ($_GET as $key => $value) {   
					if ($key != $osC_Session->getName() ) {
						if ($iii==1) {
							$link .= $key . (!empty($value)?'='.$value:'');
						} else {
							$link .= '&' . $key . (!empty($value)?'='.$value:'');
						}
					}
					$iii++;			
				  }          
				  $this->original_get = $link;   
			  	  $leading_values = 'Изображение ';
	  	  				   
		  
			  }	else {
			  	$this->cache_suffix = $osC_Product->_data['keyword'] . ((isset($_GET['cPath']) && is_numeric($osC_Product->_data['category_id'])) ? '_' . $osC_Product->_data['category_id'] : ''); 
			  	
                                $product_string =  ""; 	
      		    $this->original_get = $osC_Product->_data['keyword'];
			  }

			  $this->cache_name = $this->setCacheString( __FILE__, 'products_keyword', $this->cache_suffix );

			  if ( false !== $this->retrieve( $this->cache_name ) ) {
				KissMT::init()->setCanonical( osc_href_link("magazin/" . mb_strtolower($osC_Product->_data['keyword'] )) );
				return;
			  } 

			  $productModel = html_entity_decode(strip_tags($osC_Product->_data['model']));
			  $productName  = html_entity_decode(strip_tags($osC_Product->_data['name']));


			  $breadcrumb = array_flip( KissMT::init()->retrieve( 'breadcrumb' ) ); 

			  if ( array_key_exists( $productName, $breadcrumb ) ) {
				unset( $breadcrumb[$productName] );
			  }
			  $breadcrumb = array_flip( $breadcrumb );

			  $currencyCode = $osC_Currencies->getCode();

			  $price = str_replace( $osC_Currencies->currencies[$currencyCode]['symbol_left'], $osC_Currencies->getCode() , $osC_Product->getPriceFormated());

			  

		    $wordelements = explode (' ',$osC_Product->_data['name']);
		    // shorten title		    
		    if ((count($wordelements) >= KISS_MT_META_TITLE_NUMWORDS) && (KISS_MT_META_TITLE_LIMIT_NUMWORDS === true)) {
		       	for ($i=0 ;$i <= (KISS_MT_META_TITLE_NUMWORDS-1);$i++ ) {
		       		if ($i==(KISS_MT_META_TITLE_NUMWORDS-1) && in_array(strtolower($wordelements[$i]),KissMT::init()->stopwords) )  {
		       		    // do nothing
		       		} elseif (!empty($wordelements[$i])) {
		       		   $prodName .= $wordelements[$i] . ' '; 
		       		} 			       
		       	}
			   	$shortProductTitle = KissMT::init()->entities( rtrim( str_replace( array( '[--replaced--],', '[--replaced--]' ), '',  $prodName ), ',' ), $decode = true );			       
		       	$fullProductTitle  = KissMT::init()->entities( trim( $osC_Product->_data['name'] ), $decode = true );	

		    }  else {
		  		$fullProductTitle = KissMT::init()->entities( trim( $osC_Product->_data['name'] ), $decode = true );			    
		    }


/*			  if (isset($_GET['cPath']) && is_numeric($osC_Product->_data['category_id']) ) {

			   	  // get category name
				  $osC_Category = new osC_Category($osC_Product->_data['category_id']);
		          $list_string = $osC_Category->getTitle();

				  if ( isset($list_string) && ! empty($list_string)) { 
					$leading_values .= '[-separator-]' . sprintf( KISSMT_CAT_TEXT, trim( $list_string ) );
				  }			  

			  } else {

				  if ( isset( $_GET['manufacturers'] ) ) { 
				  		if( !is_null( $osC_Product->getManufacturer()  ) )  { 
							$leading_values .= '[-separator-]' . sprintf( KISSMT_BRAND_TEXT, trim( $osC_Product->getManufacturer() ) );							
						}
				  }	elseif (strpos($leading_values,$osC_Product->getManufacturer()) === false && $osC_Product->getManufacturer() !== "none") {	
						$leading_values .= '[-separator-]' . sprintf( trim( $osC_Product->getManufacturer()) );
				  }			  
			  }
*/

			 /* if ($osC_Product->hasVariants()) {
				  $variant = $osC_Product->_data['variants'];			  
				  foreach ( $osC_Product->_data['variants'] as $variant ) {
			  
					  if ( !is_null( $variant['data']['model'] ) ) {  
						 $leading_values .= '[-separator-]' . trim( $variant['data']['model'] );
					  } 
				  }
				  					  
			  } else {
				  if ( !is_null( $osC_Product->_data['model'] ) ) {  
					 $leading_values .= '[-separator-]' . trim( $osC_Product->_data['model'] );
				  } 
    		  }*/
			 /* if (strpos($leading_values,$osC_Product->_data['keyword']) === false ) { 
			  	  $leading_values .= '[-separator-]' .  $osC_Product->_data['keyword'];
			  }*/
//			  $leading_values .= '[-separator-]' . $breadcrumb?implode( '[-separator-]', $breadcrumb ):'';			  

			  KissMT::init()->setCanonical( osc_href_link("magazin/" . mb_strtolower($osC_Product->_data['keyword'] ))  );

			  $database_keywords = KissMT::init()->entities( trim( $osC_Product->_data['tags'] ), $decode = true );
			  $database_keywords = $database_keywords?( str_replace(',','[-separator-]',$database_keywords)) :'';

			  $this->parse( KissMT::init()->entities(  $leading_values , $decode = true ), KissMT::init()->entities(  htmlentities($osC_Product->_data['description'],null,"UTF-8",false), $decode = true ), $price , ($database_keywords?$database_keywords:''),$shortProductTitle,$fullProductTitle, false);

	}

  } // End class
?>
