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
* @lastmod $Date: 2011/08/30 21:12:53 $:  Date of last commit
* @version $Rev:: 56                                                  $:  Revision of last commit
* @Id $Id: product_reviews.php,v 1.1 2011/08/30 21:12:53 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

    private $products_query;
    protected $noindex_follow = array( 'page' );
    
    public function __construct() {
      $this->products_query = "SELECT p.products_model, pd.products_name, m.manufacturers_name, left(r.reviews_text, 100) AS reviews_text FROM " . TABLE_PRODUCTS . " p INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id = p.products_id AND pd.language_id = :languages_id LEFT JOIN " . TABLE_MANUFACTURERS . " m ON m.manufacturers_id = p.manufacturers_id LEFT JOIN " . TABLE_REVIEWS . " r ON r.products_id = p.products_id  AND r.languages_id = :languages_id WHERE p.products_id = :products_id LIMIT " . (int)KISSMT_CATEGORIES_PRODUCTS_LIMIT . "";
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

      $this->get_value = $osC_Product->getID();
      $this->original_get = '';
      $this->cache_suffix = $osC_Product->getID();      
      $this->cache_name = $this->setCacheString( __FILE__, 'reviews_id', $this->cache_suffix );
      if ( false !== $this->retrieve( $this->cache_name ) ) {
        $reviews_string = '&' . $osC_Product->getKeyword();  
        KissMT::init()->setCanonical( $this->checkCanonical( 'reviews=new', $reviews_string ) );
        return;
      } 
      $query_replacements = array( ':products_id' => (int)$this->get_value, ':languages_id' => (int)KissMT::init()->retrieve( 'languages_id' ) );
      $query = str_replace( array_keys( $query_replacements ), array_values( $query_replacements ), $this->products_query );
      $result = KissMT::init()->query( $query );
      $reviews_text = '';
      $products_model = '';
      $products_name = '';
      $manufacturers_name = '';

      while ( $product_results = $result->next() ) {
        if ( !is_null( $product_results['products_model'] ) ) {
        $products_model = trim( $product_results['products_model'] );
		$product_results_decode['products_model'] = html_entity_decode(strip_tags($products_model));
        }
        if ( !is_null( $product_results['products_name'] ) ) {
        $products_name =  trim( $product_results['products_name'] );
		$product_results_decode['products_name'] = html_entity_decode(strip_tags($products_name));
        }
        if ( !is_null( $product_results['manufacturers_name'] ) ) {
        $manufacturers_name = trim( $product_results['manufacturers_name'] );
        }
        if ( !is_null( $product_results['reviews_text'] ) ) {
          $reviews_text .= trim( rtrim( $product_results['reviews_text'], '.' ) . '.' ) . '[-separator-]';
        }
      }

      $result->freeResult();

	  $breadcrumb = array_flip( KissMT::init()->retrieve( 'breadcrumb' ) ); 
	  if ( isset($product_results_decode['products_model']) && array_key_exists( $product_results_decode['products_model'], $breadcrumb ) ) {
		unset( $breadcrumb[$product_results_decode['products_model']] );
	  }
	  if ( isset($product_results_decode['products_name']) && array_key_exists( $product_results_decode['products_name'], $breadcrumb ) ) {
		unset( $breadcrumb[$product_results_decode['products_name']] );
	  }

      $breadcrumb = array_flip( $breadcrumb );
      $leading_values = $products_name;
      if ( !is_null( $products_model ) ) {  
         $leading_values .= '[-separator-]' . $products_model;
      } 
      $leading_values .= '[-separator-]' . implode( '[-separator-]', $breadcrumb );
      if ( !is_null( $manufacturers_name ) ) { 
        $leading_values .= '[-separator-]' . sprintf( KISSMT_BRAND_TEXT, $manufacturers_name );
      }

      $reviews_string = '&' . $osC_Product->getKeyword();  
      KissMT::init()->setCanonical( $this->checkCanonical( 'reviews=new', $reviews_string ) );
      $this->parse( KissMT::init()->entities( sprintf( KISSMT_PRODUCT_REVIEWS_TEXT, $leading_values ), $decode = true ), KissMT::init()->entities( $reviews_text, $decode = true )  );
    } // end method

  } // End class
?>
