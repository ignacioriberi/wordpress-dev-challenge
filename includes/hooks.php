<?php
/**
 * Activate plugin
 */
function activate() {
	global $wpdb;

	// Storage broken links in database
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}bl_links(
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `bl_post_id` BIGINT(20) NULL,
	  `bl_url` TEXT(100) NULL,
	  `bl_status` VARCHAR(100) NULL,
	  `bl_origin` TEXT(200) NULL,
	  `bl_checked` INT(1) NULL DEFAULT 1,
	  PRIMARY KEY (`ID`)
    )";

	$wpdb->query($sql);
}

/**
 * Deactivate plugin
 */
function deactivate() {

}