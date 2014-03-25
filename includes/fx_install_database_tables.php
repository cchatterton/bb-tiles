<?php

function fx_install_database_tables(){

	 global $wpdb;
	 $wpdb->show_errors();

	 // create table bb_tile_types
	 $tableName = $wpdb->prefix . 'bb_tiles';
	 $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
	     `tile_id` bigint(20) unsigned NOT NULL auto_increment,
 	     `filename` varchar(255) default NULL,		
	     `name` varchar(255) default NULL,
	     `version` varchar(255) default NULL,
	     `variation` varchar(255) default NULL,
	     `description` varchar(255) default NULL,
 	     `instructions` varchar(255) default NULL,
 	     `status` varchar(255) default NULL,
	     PRIMARY KEY  (`tile_id`)
	     )ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;");

	 // create table bb_tile_meta
	 $tableName = $wpdb->prefix . 'bb_tile_meta';
	 $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
	     `meta_id` bigint(20) unsigned NOT NULL auto_increment,
	     `tile_id` bigint(20) NOT NULL default '0',
	     `name` varchar(255) default NULL,
	     `type` varchar(255) default NULL,
	     `size` varchar(255) default NULL,
	     `default` varchar(255) default NULL,
	     PRIMARY KEY  (`meta_id`)
	     )ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;");

	 $wpdb->hide_errors();
	
}

?>