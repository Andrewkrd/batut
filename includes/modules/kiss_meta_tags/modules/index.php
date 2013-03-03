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
* @lastmod $Date: 2012/09/21 07:56:20 $:  Date of last commit
* @version $Rev:: 56                                                  $:  Revision of last commit
* @Id $Id: index.php,v 1.12 2012/09/21 07:56:20 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

	private $productClass;
    private $descriptions_extension = false;
    private $description = false;
    protected $noindex_follow = array();
    
    public function __construct() {
 		
    			require_once('includes/classes/products.php');  
    			require_once('includes/classes/product.php');
   				$this->productClass = new osC_Products();

    } // end constructor
    
    public function process() {

      global $osC_Database, $osC_Language, $osC_Category, $osC_Manufacturer, $osC_Session;

      $this->description = false;
      $this->descriptions_extension = false;
      
      $list_string = '';
      $list_string_first ='';
      $metakeywords = '';   
      
      if(strpos($_SERVER["REQUEST_URI"], "?") === false)
        KissMT::init()->basename = substr($_SERVER["REQUEST_URI"], 1);
      else
        KissMT::init()->basename = substr($_SERVER["REQUEST_URI"], 1, strpos($_SERVER["REQUEST_URI"], "?"));

      switch ( true ) {
        /**
        * A nested category or category products listing query
        */
        case (strpos($_SERVER["REQUEST_URI"], 'category') !== false && isset($osC_Category)):

          $this->get_value = $osC_Category->getID();
          $this->descriptionsExtension( 'category' );
          $this->cache_suffix = $osC_Category->getID();
        
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
        
          $this->cache_name = $this->setCacheString( __FILE__, 'cPath', $this->cache_suffix . (isset($_GET['sort'])?str_replace('|','_',$_GET['sort']) : '' ) . (isset($_GET['page'])?$_GET['page'] : '' ));
          if ( false !== $this->retrieve( $this->cache_name ) ) {
            KissMT::init()->setCanonical( $this->checkCanonical(''));
            return;
          } 


          if ( false === $this->descriptions_extension ) {

			  if (isset($osC_Category) && is_numeric($this->get_value)) {
			  	  $Atr = array("price"=>"по цене", "name"=>"по наименованию");
			  	  if(isset($_GET['sort']))
   				 	$sort_name = strtr(str_replace('|d','',$_GET['sort']),$Atr);
		  		  $leading_values = trim( $osC_Category->getTitle() ) . (isset($_GET['sort'])?' сортировка ' . $sort_name : '' ) . ((isset($_GET['sort'])&&stripos($_GET['sort'],'d'))?' по убыванию ' : '' ) . (isset($_GET['page'])?' страница ' . $_GET['page'] : '' ) ;
			  }

		   	  	// get products in this category based on product listing sort
				$this->productClass->setCategory($osC_Category->getID());
				if (isset($_GET['sort'])) $this->productClass->setSortBy(str_replace('|d','',$_GET['sort']),((stripos($_GET['sort'],'d'))?'-' : '+' ));
				$res = $this->productClass->execute();

				if ($res->numberOfRows() > 0) {
					if (is_numeric(KISSMT_CATEGORIES_PRODUCTS_LIMIT) && KISSMT_CATEGORIES_PRODUCTS_LIMIT > 0)	{
						$i=1;				

						while (($res->next()) && ($i <= KISSMT_CATEGORIES_PRODUCTS_LIMIT)) {
							$prodc = new osC_Product($res->valueInt('products_id'));
							$metakeywords .= trim( $prodc->_data['tags'] . '[-separator-]');													
							if ($i==1) {
								$list_string_first = trim( $prodc->_data['name'] );
							} else {
								$list_string .= trim( $prodc->_data['name'] ) . '[-separator-]';	
							}
							$i++;
						}
							
				    }  
		        }
				$res->freeResult(); 
							
          } else {
            $list_string = '';
            $list_string .= trim( $this->description['kissmt_categories_description'] );
          }

		  if (!isset($leading_values)) {
		  	  $leading_values_arr = array();
		  	  $leading_values = "";
		      $breadcrumb = array_flip( KissMT::init()->retrieve( 'breadcrumb' ) );
		      //$leading_values = implode( '[-separator-]', KissMT::init()->retrieve( 'breadcrumb' ) );
		      foreach ( KissMT::init()->retrieve( 'breadcrumb' ) as $val)
		      	if(stripos ($leading_values, $val) === false) {
		      		$leading_values_arr[] = $val;
		      		$leading_values .= $val ;
		      	}		      	
		      $leading_values = implode( '[-separator-]', $leading_values_arr );
		  }

		$query = "SELECT categories_title, category_url  FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = " . (int)$this->get_value . " AND language_id = " . (int)KissMT::init()->retrieve( 'languages_id' ) . "";
		$result = $osC_Database->query(':sqlraw');
		$result->bindRaw(':sqlraw',$query);
		$result->execute();
		$categories_title = $result->value('categories_title');
		if(!empty($categories_title))	
                    $leading_values .= '[-separator-]' . $categories_title; 
                
                

          KissMT::init()->setCanonical( $this->checkCanonical(''));
          $this->parse( KissMT::init()->entities( $leading_values, $decode = true ), KissMT::init()->entities( $leading_values . ' в Краснодаре, ' . $list_string, $decode = true ),null,null,$list_string_first,$metakeywords);
          break;
        /**
        * A manufacturers query
        */
        case (array_key_exists( 'manufacturers', $_GET ) && isset($osC_Manufacturer)): 

        	$sort = "";
         if (!is_numeric($_GET["manufacturers"]))  {
	      	$man_sef = $_GET["manufacturers"];
			$Qman = $osC_Database->query('select manufacturers_id from :table_manufacturers where manufacturers_sef = :manufacturers_sef');
			$Qman->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
			$Qman->bindValue(':manufacturers_sef', $man_sef);
			$Qman->execute();
			$this->get_value = $Qman->value('manufacturers_id');   
         }	
        else
          $this->get_value = $this->parsePath( $_GET['manufacturers'] );
          
          
          $this->descriptionsExtension( 'manufacturer' );
          $this->original_get = $_GET['manufacturers'];
          $this->cache_suffix = $_GET['manufacturers'];
          $this->cache_name = $this->setCacheString( __FILE__, 'manufacturers_id', $this->cache_suffix . (isset($_GET['sort'])?str_replace('|','_',$_GET['sort']) : '' ) . (isset($_GET['page'])?$_GET['page'] : '' ));
          if ( false !== $this->retrieve( $this->cache_name ) ) {
            KissMT::init()->setCanonical( $this->checkCanonical( 'manufacturers=', (isset($_GET['sort'])?'&sort=' . $_GET['sort'] : '' ) . (isset($_GET['page'])?'&page=' . $_GET['page'] : '' ) ) );
            return;
          }
          $list_string = '';
          if ( false === $this->descriptions_extension ) {

	   	    // get first product(s) by this manufacturer based on product listing sort
			$this->productClass->setManufacturer((int)$this->get_value);
			if (isset($_GET['sort'])) $this->productClass->setSortBy(str_replace('|d','',$_GET['sort']),((stripos($_GET['sort'],'d'))?'-' : '+' ));			
			$res = $this->productClass->execute();
			
            $manu_products = '';
            if ($res->numberOfRows() > 0 && is_numeric(KISSMT_CATEGORIES_PRODUCTS_LIMIT) && KISSMT_CATEGORIES_PRODUCTS_LIMIT > 0 ) {
				$i=1;            
				while (($res->next()) && ($i <= KISSMT_CATEGORIES_PRODUCTS_LIMIT)) {

				  $prodc = new osC_Product($res->valueInt('products_id'));	
				  $metakeywords .= trim( $prodc->_data['keyword'] . '[-separator-]');	
				  			      				  
				  if ($i==1) {  			  	        	
				      if ( !isset( $manu_name ) && !is_null( $osC_Manufacturer->_data['name'] ) ) { 
				      	$sort_title = "";
				      	if(isset($_GET['sort'])) {
				      		if(strpos($_GET['sort'], "price") !== false)
				      			$sort_title = "цене";
				      		if(strpos($_GET['sort'], "name") !== false)
				      			$sort_title = "названию";
				      	}
				      		
				      	if(!empty($osC_Manufacturer->_data['text']))
				      		$manufacturer_name = $osC_Manufacturer->_data['text'];
				      	else
				      		$manufacturer_name = $osC_Manufacturer->_data['name'];
				        //$manu_name = trim( $manufacturer_name ) . (isset($_GET['sort'])?' сортировка по ' . $sort_title : '' ) . ((isset($_GET['sort'])&&stripos($_GET['sort'],'d') !== false)?' по убыванию ' : '' ) . (isset($_GET['page'])?' страница ' . $_GET['page'] : '' );
						$manu_name = trim( $manufacturer_name );
				      	$sort = (isset($_GET['sort'])?' сортировка по ' . $sort_title : '' ) . ((isset($_GET['sort'])&&stripos($_GET['sort'],'d') !== false)?' по убыванию ' : '' ) . (isset($_GET['page'])?' страница ' . $_GET['page'] : '' );
				      }
    					
				      $list_string_first .= trim( $prodc->_data['name'] );
				  } else {
			          $list_string .= trim( $prodc->_data['name'] ) . '[-separator-]';	

				  }
				  $i++;
				}
				  
		    } else {$manu_name = '';}
		    
		    
            $res->freeResult();
          } else {
            $manu_name = trim( $this->description['manufacturers_name'] );
            $list_string = trim( $this->description['kissmt_manufacturers_description'] );
          }
          KissMT::init()->setCanonical( $this->checkCanonical( 'manufacturers=', (isset($_GET['sort'])?'&sort=' . $_GET['sort'] : '' ) . (isset($_GET['page'])?'&page=' . $_GET['page'] : '' ) ) );
          $this->parse( KissMT::init()->entities( sprintf( KISSMT_MANUFACTURERS_TEXT, $manu_name ) . $sort, $decode = true ), KissMT::init()->entities( $list_string, $decode = true ),null,null,$list_string_first,$metakeywords);
         
          break;
        /**
        * Root index page
        */
        default:
          KissMT::init()->setCanonical( $this->checkCanonical('') );
          define( 'KISSMT_TITLE_HOME_PADDING', KISSMT_TITLE_HOME_RE_PADDING);
          $this->parse( KissMT::init()->entities( sprintf( KISSMT_HOMEPAGE_TITLE, STORE_NAME ), $decode = true ), KissMT::init()->entities( sprintf( KISSMT_HOMEPAGE_DESCRIPTION, STORE_NAME ), $decode = true ), '' );
          break;
      }
    } // end method
    
    private function descriptionsExtension( $type = false ) {

      global $osC_Database;

      if ( false === ( defined( 'KISSMT_DESCRIPTIONS_EXTENSION_ENABLE' ) && ( KISSMT_DESCRIPTIONS_EXTENSION_ENABLE == 'true' ) ) ) {
        return false;
      } 
      if ( $type == 'category' ) {
        $query = "SELECT kissmt_categories_description FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories_id = " . (int)$this->get_value . " AND language_id = " . (int)KissMT::init()->retrieve( 'languages_id' ) . "";
	    $result = $osC_Database->query(':sqlraw');
	    $result->bindRaw(':sqlraw',$query);
	    $result->execute();

        $row = $result->next();
        $result->freeResult();
        if ( false !== ( ( false !== $row ) && !is_null( $row['kissmt_categories_description'] ) ) ) {
          $this->descriptions_extension = true;
          $this->description = array( 'kissmt_categories_description' => $row['kissmt_categories_description'] );
          return;
        }
      } elseif ( $type == 'manufacturer' ) {
        $query = "SELECT m.manufacturers_name, m.kissmt_manufacturers_description FROM " . TABLE_MANUFACTURERS . " m INNER JOIN " . TABLE_MANUFACTURERS_INFO . " mi ON mi.manufacturers_id = m.manufacturers_id AND mi.languages_id = " . (int)KissMT::init()->retrieve( 'languages_id' ) . " WHERE m.manufacturers_id = " . (int)$this->get_value . "";
	    $result = $osC_Database->query(':sqlraw');
	    $result->bindRaw(':sqlraw',$query);
	    $result->execute();

        $row = $result->next();
        $result->freeResult();
        if ( false !== ( ( false !== $row ) && !is_null( $row['kissmt_manufacturers_description'] ) ) ) {
          $this->descriptions_extension = true;
          $this->description = array( 'manufacturers_name' => $row['manufacturers_name'], 'kissmt_manufacturers_description' => $row['kissmt_manufacturers_description'] );
          return;
        }
      }
      $this->descriptions_extension = false;
      $this->description = false;
    }

  } // End class
?>
