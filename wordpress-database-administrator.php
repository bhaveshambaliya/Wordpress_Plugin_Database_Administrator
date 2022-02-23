<?php
/**
* Plugin Name: Database Administrator
* Plugin URI: 
* Description: This plugin create for manage database
* Version: 1.0
* Author: Parimal Dhimmar 
* Author URI: 
* License: 
*/

function WDA_admin_css_all_page() {    
    wp_register_style($handle = 'WDA_admin-css-all', $src = plugins_url('css/WDA_style.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('WDA_admin-css-all');
}
add_action('admin_print_styles', 'WDA_admin_css_all_page');


function WDA_admin_SCRIPT_all_page(){
	wp_enqueue_script('WDA_admin-SCRIPT-all', plugins_url('js/WDA_js.js', __FILE__), array('jquery'));
	wp_localize_script( 'WDA_admin-SCRIPT-all', 'WDA_ajax_post_ajax', array( 'WDA_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'WDA_admin_SCRIPT_all_page' );

add_action('admin_menu', 'WDA_Menu_Pages');
function WDA_Menu_Pages(){
    add_menu_page(
		'Wordpress Database Administrator',
		'WDA',
		'manage_options',
		'WDA-Administrator',
		'WDA_main_page' );
    
	add_submenu_page(
        'WDA-Administrator',
        'WDA SQL QUERY',
        'WDA SQL QUERY',
        'manage_options',
        'WDA_SQL_QUERY',
        'WDA_SQL_QUERY'
    );
	
	add_submenu_page(
		'WDA_browse_table',
		__( 'Page title',
		'Browse' ),
		'',
		'manage_options',
		'WDA_browse_table',
		'WDA_browse_table'
	);
	
	add_submenu_page(
		'WDA_structure_table',
		__( 'Page title',
		'Structure' ),
		'',
		'manage_options',
		'WDA_structure_table',
		'WDA_structure_table'
	);
	
	add_submenu_page(
		'WDA_search_in_column_table',
		__( 'Page title',
		'Structure' ),
		'',
		'manage_options',
		'WDA_search_in_column_table',
		'WDA_search_in_column_table'
	);
	
	add_submenu_page(
		'WDA_insert_in_table',
		__( 'Page title',
		'Structure' ),
		'',
		'manage_options',
		'WDA_insert_in_table',
		'WDA_insert_in_table'
	);
	
	add_submenu_page(
		'WDA_Create_New_table',
		__( 'Page title',
		'Structure' ),
		'',
		'manage_options',
		'WDA_Create_New_table',
		'WDA_Create_New_table'
	);
	
	add_submenu_page(
		'WDA_Edit_in_table',
		__( 'Page title',
		'Structure' ),
		'',
		'manage_options',
		'WDA_Edit_in_table',
		'WDA_Edit_in_table'
	);
	
	
}

require_once 'WDA_function.php';

function WDA_main_page(){
	require_once 'WDA_popup.php';
	require_once 'WDA_main_page.php';
}

function WDA_browse_table(){
	require_once 'WDA_popup.php';
	require_once 'WDA_browse_table.php';
}

function WDA_structure_table(){
	require_once 'WDA_popup.php';
	require_once 'WDA_structure_table.php';
}

function WDA_search_in_column_table(){
	require_once 'WDA_popup.php';
	require_once 'WDA_search_in_column_table.php';
}

function WDA_insert_in_table(){
	require_once 'WDA_insert_in_table.php';
}

function WDA_SQL_QUERY(){
	require_once 'WDA_SQL_QUERY.php';	
}

function WDA_Create_New_table(){
	require_once 'WDA_popup.php';
	require_once 'WDA_Create_New_table.php';
}

function WDA_Edit_in_table(){
	require_once 'WDA_popup.php';
	require_once 'WDA_Edit_in_table.php';
}

?>