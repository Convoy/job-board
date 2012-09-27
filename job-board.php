<?php
/*
Plugin Name: Job Board
Plugin URI: http://weareconvoy.com
Description: A plugin for displaying and managing available jobs, developed for UFCW.
Version: 0.1
Author: Convoy
Author URI: http://weareconvoy.com
Requires at least: 3.0.0
Tested up to: 3.4

Copyright 2010-2012 by Convoy http://weareconvoy.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License,or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not,write to the Free Software
Foundation,Inc.,51 Franklin St,Fifth Floor,Boston,MA 02110-1301 USA
*/
?>
<?php

// don't allow direct access of this file

if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) die();

// require base objects and do instantiation

if ( !class_exists( 'JobBoardClass' ) ) {
	require_once( plugin_dir_path(__FILE__) . 'classes/job-board-class.php' );
}
$job_board = new JobBoardClass();

// define plugin file path

$job_board->set_plugin_file( __FILE__ ); 

// define directory name of plugin

$job_board->set_plugin_dir( plugin_basename(__FILE__) );

// path to this plugin

$job_board->set_plugin_path( plugin_dir_path(__FILE__) );

// URL to plugin

$job_board->set_plugin_url( plugin_dir_url(__FILE__) );

// call init

$job_board->init();

?>
