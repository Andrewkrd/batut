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
* @Id $Id: products_new.php,v 1.2 2012/01/30 20:23:22 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {

		private $productClass;
		private $new_products_query;
		protected $noindex_follow = array();

		public function __construct() {
				

		} // end constructor

		public function process() {
			
		global $osC_Database;

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
                KissMT::init()->basename = "articles/";
		$this->get_value = '';
		$this->original_get = "";
		
		$this->cache_name = $this->setCacheString( __FILE__, 'listing', 'general' . $this->cache_suffix );
		if ( false !== $this->retrieve( $this->cache_name ) ) {
		KissMT::init()->setCanonical( $this->checkCanonical('',$page_string) );
		return;
		} 
		
		$Qkeyword = $osC_Database->query('select cms_keyword, cms_short_text from :table_cms where cms_url = :cms_url');
		$Qkeyword->bindTable(':table_cms', TABLE_CMS);
                $url = $_SERVER["REQUEST_URI"];
                if(strpos($url, "articles")) 
                  $request_url = substr($url, strpos($url, "articles/")+9);
                else
                  $request_url = "";
		$Qkeyword->bindValue(':cms_url', $request_url);
		$Qkeyword->execute();

                $breadcrumb = KissMT::init()->retrieve( 'breadcrumb' );	
                
		$this->cache_suffix =  (isset($_GET['page'])?'&page=' . $_GET['page'] : '' );
                
		KissMT::init()->setCanonical( osc_href_link( KissMT::init()->retrieve( 'basename' )) . $request_url ); 
		
		KissMT::init()->title = $breadcrumb[0];
		
                KissMT::init()->description = $Qkeyword->value('cms_short_text');
                if(empty(KissMT::init()->description))
                   KissMT::init()->description = "Статьи и описание батуты Happy Hop. Полезные советы и характеристики";
		
		KissMT::init()->keywords = $Qkeyword->value('cms_keyword');
                
		if(empty(KissMT::init()->keywords))
                    KissMT::init()->keywords = "статьи описание батуты Happy Hop советы характеристики";

		
    } // end method

  } // End class
?>
