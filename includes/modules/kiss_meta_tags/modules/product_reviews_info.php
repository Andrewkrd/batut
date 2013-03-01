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
* @lastmod $Date: 2012/10/19 19:53:00 $:  Date of last commit
* @version $Rev:: 59                                                  $:  Revision of last commit
* @Id $Id: product_reviews_info.php,v 1.3 2012/10/19 19:53:00 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

    private $products_query;
    protected $noindex_follow = array();
    
    public function __construct() {
      $this->products_query = "SELECT p.products_model, pd.products_name, m.manufacturers_name, left(r.reviews_text, 100) AS reviews_text, r.customers_name FROM " . TABLE_REVIEWS . " r  INNER JOIN " . TABLE_PRODUCTS . " p ON p.products_id = r.products_id INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id = p.products_id AND pd.language_id = :languages_id LEFT JOIN " . TABLE_MANUFACTURERS . " m ON m.manufacturers_id = p.manufacturers_id WHERE r.reviews_id = :reviews_id AND r.languages_id = :languages_id";
    } // end constructor
    
    public function process() {

	  global $osC_Product;

      // set basename to what it actually is and not the module file name
      $base = new ArrayIterator( array( 'SCRIPT_NAME', 'PHP_SELF' ) );
      while ( $base->valid() ) {
        if ( array_key_exists(  $base->current(), $_SERVER ) && !empty(  $_SERVER[$base->current()] ) ) {
          if ( false !== strpos( $_SERVER[$base->current()], '.php' ) ) {
            preg_match( '@[a-z0-9_]+\.php@i', $_SERVER[$base->current()], $matches );
            if ( is_array( $matches ) && ( array_key_exists( 0, $matches ) )
                                      && ( substr( $matches[0], -4, 4 ) == '.php' ) ) {
	  			KissMT::init()->basename = $matches[0];
            } else { KissMT::init()->basename = str_replace('/','',$_SERVER['SCRIPT_NAME']); }
          } 
        }
        $base->next();
      }

      $this->get_value = $_GET['reviews'];
      $this->cache_suffix = (int)$_GET['reviews'];      
      $this->original_get = (int)$_GET['reviews'];
      $this->cache_name = $this->setCacheString( __FILE__, 'reviews_id', $this->cache_suffix );
      if ( false !== $this->retrieve( $this->cache_name ) ) {
        $reviews_string = '&' . $osC_Product->getKeyword();
        KissMT::init()->setCanonical( $this->checkCanonical( 'reviews=', $reviews_string ) );
        return;
      } 
      $query_replacements = array( ':reviews_id' => (int)$this->get_value, ':languages_id' => (int)KissMT::init()->retrieve( 'languages_id' ) );
      $query = str_replace( array_keys( $query_replacements ), array_values( $query_replacements ), $this->products_query );
      $result = KissMT::init()->query( $query );
      $product_results = $result->toArray();
      $result->freeResult();

	  foreach ($product_results as $key => $value) {
		 $product_results_decode[$key] = strip_tags($value);
		 $product_results_decode[$key] = html_entity_decode($value);
	  }

	  $breadcrumb = array_flip( KissMT::init()->retrieve( 'breadcrumb' ) ); 
	  if ( array_key_exists( $product_results_decode['products_model'], $breadcrumb ) ) {
		unset( $breadcrumb[$product_results_decode['products_model']] );
	  }
	  if ( array_key_exists( $product_results_decode['products_name'], $breadcrumb ) ) {
		unset( $breadcrumb[$product_results_decode['products_name']] );
	  }

      $breadcrumb = array_flip( $breadcrumb );
      $leading_values = KissMT::init()->entities( trim( $product_results['products_name'] ), $decode = true );
      if(!empty($product_results['products_model']))    $products_model = $product_results['products_model'];
      else    $products_model = "zxcvbnm";
      if ( !is_null( $product_results['products_model']) && strpos($product_results['products_name'], $products_model) == false)  {  
         $leading_values .= '[-separator-]' . trim( $product_results['products_model'] );
      } 

 //     $leading_values .= ' ' . implode( '[-separator-]', $breadcrumb );
      /*if ( !is_null( $product_results['manufacturers_name'] ) ) { 
        $leading_values .= '[-separator-]' . sprintf( KISSMT_BRAND_TEXT, trim( $product_results['manufacturers_name'] ) );
      }*/
      if ( !is_null( $product_results['reviews_text'] ) ) {
      }

      $reviews_string = '&' . $osC_Product->getKeyword();
      KissMT::init()->setCanonical( $this->checkCanonical( 'reviews=', $reviews_string ) );
      $this->parse( KissMT::init()->entities( sprintf( KISSMT_PRODUCT_REVIEWS_INFO_TEXT, $product_results['customers_name'] ), $decode = true ) . '[-separator-]' . KissMT::init()->entities( $leading_values, htmlentities($product_results['reviews_text']), $decode = true ) );
    } // end method

  } // End class
?>
