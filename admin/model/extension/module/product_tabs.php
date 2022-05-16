<?php
class ModelExtensionModuleProductTabs extends Model {
	public function installDB() {
	 $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_tabs_ns` (
		`product_id` int(11) NOT NULL, 
		`tabs_ns_id` int(11) NOT NULL, 
		`language_id` int(11) NOT NULL, 
		`text` text NOT NULL, 
		PRIMARY KEY (`product_id`,`tabs_ns_id`,`language_id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
	$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tabs_ns` (
		`tabs_ns_id` int(11) NOT NULL AUTO_INCREMENT, 
		`sort_order` int(3) NOT NULL DEFAULT '0', 
		`status` tinyint(1) NOT NULL DEFAULT '1', 
		`show_empty_tab` tinyint(1) NOT NULL DEFAULT '0', 
		`icon_tabs` varchar(255) NOT NULL DEFAULT '', 
		PRIMARY KEY (`tabs_ns_id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");		
	$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tabs_description_ns` (
		`tabs_ns_id` int(11) NOT NULL, 
	    `language_id` int(11) NOT NULL, 
		`title` text NOT NULL, 
		PRIMARY KEY (`tabs_ns_id`,`language_id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

   
  }

  public function deleteDB() {
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "tabs_ns`;");   
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "tabs_description_ns`;");  
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_tabs_ns`;");
  }
}
