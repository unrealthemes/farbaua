<?php
class ModelExtensionModuleReviewsStore extends Model {
	public function installDB() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "reviews_store` (
			`reviews_store_id` int(11) NOT NULL AUTO_INCREMENT,
			`author` varchar(64) NOT NULL,
			`description` text NOT NULL,
			`admin_response` text NOT NULL,
			`rating` int(1) NOT NULL,
			`like` tinyint(11) NOT NULL DEFAULT '0',
			`dislike` tinyint(11) NOT NULL DEFAULT '0',
			`status` tinyint(1) NOT NULL DEFAULT '0',
			`status_check` tinyint(1) NOT NULL DEFAULT '0',
			`date_added` datetime NOT NULL,
			`date_modified` datetime NOT NULL,
			PRIMARY KEY (`reviews_store_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "reviews_store_rating` (
			`reviews_store_id` int(11) NOT NULL,
			  `reviews_store_theme_id` int(11) NOT NULL,
			  `rating` int(1) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "reviews_store_theme` (
			`reviews_store_theme_id` int(11) NOT NULL AUTO_INCREMENT,
			`status` tinyint(1) NOT NULL DEFAULT '0',
			`sort_order` int(3) NOT NULL,
			PRIMARY KEY (`reviews_store_theme_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "reviews_store_theme_desc` (
			`reviews_store_theme_id` int(11) NOT NULL AUTO_INCREMENT,
			`language_id` int(11) NOT NULL,
			`theme_text` text NOT NULL,
			PRIMARY KEY (`reviews_store_theme_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		
		
	}
}
