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
* @Id $Id: generic_module.php,v 1.1 2011/08/30 21:12:53 ujirafika.ujirafika Exp $:  Full Details   
*/

  final class KissMT_Module extends KissMT_Modules {
    
    protected $noindex_follow = array();
    
    public function __construct() {

    } // end constructor
    
    public function process() {
    
    global $osC_Session;
    
	  // filename less the .php
	  $short_filename = substr( KissMT::init()->retrieve( 'basename' ), 0, strlen( KissMT::init()->retrieve( 'basename' ) )-4 );

	  $file_title = ucfirst( strtolower( str_replace( '_', ' ', $short_filename ) ) ); 


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

	  /**
	  * Do we have a languages define for this page?
	  */
     
	  $poss_title_define = 'KISSMT_' . strtoupper( $short_filename ) . '_TITLE_TEXT';
	  $poss_description_define = 'KISSMT_' . strtoupper( $short_filename ) . '_DESCRIPTION_TEXT';
	  // If we have language defines for this page then we use these
	  if ( defined( $poss_title_define ) && defined( $poss_description_define ) ) { 
		$leading_value = sprintf( constant( $poss_title_define ),  $file_title . '[-separator-]' );
		$description = constant( $poss_description_define );
	  // No language defines so we use what the script found .. either HEADING_TITLE or the filename
	  } else {
		$leading_value = $file_title;
		$description = false;
	  }

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
      KissMT::init()->basename = substr($_SERVER["REQUEST_URI"], 1);
      KissMT::init()->setCanonical( $this->checkCanonical('',$link) );
      $this->parse( KissMT::init()->entities( $leading_value, $decode = true ), KissMT::init()->entities( $description, $decode = true ) );
    } // end method

  } // End class  
?>
