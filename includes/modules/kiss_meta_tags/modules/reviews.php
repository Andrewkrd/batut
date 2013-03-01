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
* @lastmod $Date: 2012/01/30 20:23:22 $:  Date of last commit
* @version $Rev:: 56                                                  $:  Revision of last commit
* @Id $Id: reviews.php,v 1.2 2012/01/30 20:23:22 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

	protected $noindex_follow = array();

	public function __construct() {

		require_once('includes/classes/product.php');  

	} // end constructor
    
		public function process() {

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

		$this->get_value = '';
		$this->cache_suffix = (isset($_GET['page'])?$_GET['page'] : '' );      
		$this->original_get = 'reviews';
		$list_array = array();
		$list_string_first ='';
		$metakeywords = '';

		$page_string = (isset($_GET['page'])?'&page=' . $_GET['page'] : '' );		
	
		$this->cache_name = $this->setCacheString( __FILE__, 'listing', 'reviews' . $this->cache_suffix);
		if ( false !== $this->retrieve( $this->cache_name ) ) {
			KissMT::init()->setCanonical( $this->checkCanonical('',$page_string) );
		return;
		} 

		$res = osC_Reviews::getListing();

		if ($res->numberOfRows() > 0) {
			if (is_numeric(KISSMT_CATEGORIES_PRODUCTS_LIMIT) && KISSMT_CATEGORIES_PRODUCTS_LIMIT > 0)	{

				$i=1;				

				while (($res->next()) && ($i <= KISSMT_CATEGORIES_PRODUCTS_LIMIT)) {
					$prodc = new osC_Product($res->valueInt('products_id'));
					$metakeywords .= trim( $prodc->_data['keyword'] . '[-separator-]');					
					if ($i==1) {
				
						$list_string_first = trim( $prodc->_data['name'] );

					} else {
				
						$list_array[] = trim( $prodc->_data['name'] );	
					}
					$i++;
				}

			} 
		}

		$res->freeResult(); 

		$list_string = implode( '[-separator-]', $this->removeArrayDuplicates( $list_array ) );

		$list_string = !is_null( $list_string ) ? $list_string : false; 

		$leading_values = implode( '[-separator-]', KissMT::init()->retrieve( 'breadcrumb' ) ) . (isset($_GET['page'])?' страница ' . $_GET['page'] : '' );

		KissMT::init()->setCanonical( $this->checkCanonical('',$page_string) ); 
		$this->parse( KissMT::init()->entities( sprintf( KISSMT_REVIEWS_TEXT, $leading_values ), $decode = true ), KissMT::init()->entities( $list_string, $decode = true ),null,null,$list_string_first,$metakeywords );

    } // end method

  } // End class
?>
