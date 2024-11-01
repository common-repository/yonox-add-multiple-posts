<?php
/**
Plugin Name: Yonox Add Multiple Posts
Plugin URI: https://www.cursonix.com/yonox-add-multiple-posts/
Description: Add multiple posts or pages to edit later.
Version: 1.4
Author: Yonox
Author URI: https://www.cursonix.com/yonox-add-multiple-posts/
License: GPLv2 or later
*/

if ( !defined( 'ABSPATH' ) ) exit;

if (!defined('YNXADMP_LOCATION')) { define('YNXADMP_LOCATION', WP_PLUGIN_URL . '/'.basename(dirname(__FILE__))); }
if (!defined('YNXADMP_PATH')) { define('YNXADMP_PATH', plugin_dir_path( __FILE__ )); }

class YnxAddMultiplePosts{
  
	private $ynx_add_posts_screen_name;
	private static $instance;

	static function YnxadmpGetInstance() {
	  
	  if (!isset(self::$instance)) {
		  self::$instance = new self();
	  }
	  return self::$instance;
	}

	public function YNXadmpPluginMenu() {
		$this->ynx_add_posts_screen_name = add_menu_page(__('Yonox Add Multiple Posts','ynxadmp'),__('Yonox Add Posts','ynxadmp'), 'manage_options','ynxadmp-panel', array($this, 'YnxRenderPage'), 'dashicons-admin-page');
	}

	public function ynxadmp_admin_styles( $hook ) {
		if ( $hook === 'toplevel_page_ynxadmp-panel' ) {
			wp_enqueue_style( 'ynxadmp-admin-styles', YNXADMP_LOCATION . '/css/ynxadmp-admin-style.css' );
			wp_deregister_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui', YNXADMP_LOCATION . '/js/jquery-ui.min.js', array( 'jquery') );
			wp_enqueue_script( 'ynxadmp-ajax-admin', YNXADMP_LOCATION . '/js/ynxadmp-ajax-admin.js', array( 'jquery' ) );  
			wp_localize_script( 'ynxadmp-ajax-admin', 'YnxadmpAdminAjax', array( 
				'adminynxadmpajax'	=> admin_url( 'admin-ajax.php' ),
				'ynxadmpNonce'		=> wp_create_nonce( 'ynxadmp-send-secur' ),
				'processingText'	=> __( 'Processing...', 'ynxadmp' ),
				'postsCreatedText'	=> __( 'posts created', 'ynxadmp' ),
				'processFinishText'	=> __( 'Process finished OK with', 'ynxadmp' ),
				'finishedWithText'	=> __( 'Finished with', 'ynxadmp' )
			));
		}
	}
      
	public function YnxRenderPage(){
		require_once('ynxadmp-admin-panel.php');
	}
	
	public function ynxadmp_langinit() {
		load_plugin_textdomain( 'ynxadmp', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
	public function ynxadmpCreatePosts() {

		$ynxadmpnonce = $_POST['sendlocNonce']; 	
		if ( ! wp_verify_nonce( $ynxadmpnonce, 'ynxadmp-send-secur' ) )
			die ( __('Security Failed!','ynxadmp') );

		$typeEntry = $_POST['typeEntry'];
		$statusEntry = $_POST['statusEntry'];
		$ynxadmpTitle = $_POST['ynxadmpTitle'];
		$ynxadmpContent = $_POST['ynxadmpContent'];
		$ynxadmpCategory = $_POST['ynxadmpCategory'];
		$ynxadmpParent = $_POST['ynxadmpParent'];
		$ynxadmpAuthor = $_POST['ynxadmpAuthor'];
		
		global $wpdb;
		
		if (get_magic_quotes_gpc()) {
			// Yes? Strip the added slashes
			$ynxadmpTitle = stripslashes($ynxadmpTitle);
		}
		
		$author_id = 1;
		$user_query = "SELECT ID, user_login, display_name, user_email FROM $wpdb->users WHERE ID = ".intval($ynxadmpAuthor) . " LIMIT 1";
		$users = $wpdb->get_results($user_query);
		foreach ($users as $row) {
			$author_id = $row->ID;
		}
		
		$postTitle = trim($ynxadmpTitle);

		if ( $typeEntry == 'ynxadmp_type_post' ) {
			$ynxadmpPost = array ();
			$ynxadmpPost['post_title'] = $postTitle;
			$ynxadmpPost['post_type'] = 'post';
			$ynxadmpPost['post_content'] = $ynxadmpContent;
			$ynxadmpPost['post_status'] = $statusEntry;
			$ynxadmpPost['post_author'] = $author_id;
			$ynxadmpPost['post_category'] = array($ynxadmpCategory);
			wp_insert_post($ynxadmpPost);
		} elseif ( $typeEntry == 'ynxadmp_type_page' ) {
			$ynxadmpPage = array ();
			$ynxadmpPage['post_title'] = $postTitle;
			$ynxadmpPage['post_type'] = 'page';
			$ynxadmpPage['post_content'] = $ynxadmpContent;
			$ynxadmpPage['post_status'] = $statusEntry;
			$ynxadmpPage['post_author'] = $author_id;
			$ynxadmpPage['post_parent'] = $ynxadmpParent;
			wp_insert_post($ynxadmpPage);
		}

		exit;	
	}

	public function InitYnxadmpPlugin()
	{
	   add_action( 'admin_menu', array($this, 'YNXadmpPluginMenu') );
	   add_action( 'plugins_loaded', array($this, 'ynxadmp_langinit') );
	   add_action( 'admin_enqueue_scripts', array($this, 'ynxadmp_admin_styles') );
	   add_action( 'wp_ajax_addposts_ajaxfunc', array($this, 'ynxadmpCreatePosts') );
	}
  
}

if ( is_admin() ) {
	$YnxAddMultiplePosts = YnxAddMultiplePosts::YnxadmpGetInstance();
	$YnxAddMultiplePosts->InitYnxadmpPlugin();
}
?>