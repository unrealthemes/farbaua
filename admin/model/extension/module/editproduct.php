<?php
class ModelExtensionModuleEditproduct extends Model {		
	public function editSettingEditprod($data) {
		$store_id = 0;			
		$code = 'editproduct';					
		if (version_compare(VERSION, '2.0.0.0', '=')) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($code) . "'");

			foreach ($data as $key => $value) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
				}	
			}
		} else {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."'");
			foreach ($data as $key => $value) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					if (VERSION < 2.1) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}
				}	
			}
		}
	}
	
	public function getUserGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group");
		$user_group = array();
		foreach($query->rows as $result){
			$user_group[] = array(
				'user_group_id' 	=> $result['user_group_id'],
				'name'       		=> $result['name'],
				'editor_permission' => json_decode($result['editor_permission'], true)
			);
		}
		return $user_group;
	}
	public function updateUsergroupEditProdView($data) {
			
    	  	foreach($data as $user_group_all) {
				if(empty($user_group_all['description_edit'])){
					$user_group_all['description_edit'] = 'N';
				}
				if(empty($user_group_all['category_edit'])){
					$user_group_all['category_edit'] = 'N';
				}
				if(empty($user_group_all['image_edit'])){
					$user_group_all['image_edit'] = 'N';
				}
				if(empty($user_group_all['image_url_edit'])){
					$user_group_all['image_url_edit'] = 'N';
				}
				if(empty($user_group_all['image_google_edit'])){
					$user_group_all['image_google_edit'] = 'N';
				}
				if(empty($user_group_all['price_edit'])){
					$user_group_all['price_edit'] = 'N';
				}
				if(empty($user_group_all['manual_price_edit'])){
					$user_group_all['manual_price_edit'] = 'N';
				}
				if(empty($user_group_all['bprice_edit'])){
					$user_group_all['bprice_edit'] = 'N';
				}
				if(empty($user_group_all['special_edit'])){
					$user_group_all['special_edit'] = 'N';
				}
				if(empty($user_group_all['related_edit'])){
					$user_group_all['related_edit'] = 'N';
				}
				if(empty($user_group_all['code_edit'])){
					$user_group_all['code_edit'] = 'N';
				}
				if(empty($user_group_all['attribute_edit'])){
					$user_group_all['attribute_edit'] = 'N';
				}
				if(empty($user_group_all['option_edit'])){
					$user_group_all['option_edit'] = 'N';
				}
				if(empty($user_group_all['link_product_admin'])){
					$user_group_all['link_product_admin'] = 'N';
				}
				if(empty($user_group_all['link_module_edit_admin'])){
					$user_group_all['link_module_edit_admin'] = 'N';
				}
				if(empty($user_group_all['group_editor'])){
					$user_group_all['group_editor'] = 'N';
				}
				
				$this->db->query("UPDATE " . DB_PREFIX . "user_group SET  
				editor_permission = '" . $this->db->escape(json_encode($user_group_all)) . "'
				WHERE user_group_id = '". (int)$user_group_all['user_group_id'] ."' ");
			}		
	}
	
	public function modificationTableUserGroup() {
    	$query = $this->db->query("DESC ".DB_PREFIX."user_group status");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `status`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` description_edit");
			if ($query->num_rows) { 
			    $this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `description_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` category_edit");
			if ($query->num_rows) { 
			    $this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `category_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` image_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `image_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` image_url_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `image_url_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` image_google_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `image_google_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` price_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `price_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` manual_price_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `manual_price_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` bprice_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `bprice_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` special_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `special_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` related_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `related_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` code_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `code_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` attribute_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `attribute_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` option_edit");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `option_edit`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` link_module_edit_admin");
			if ($query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `link_module_edit_admin`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` link_product_admin");
			if ($query->num_rows) { 
			    $this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` DROP `link_product_admin`");
			}
		$query = $this->db->query("DESC `".DB_PREFIX."user_group` editor_permission");
			if (!$query->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "user_group` ADD `editor_permission` text default ''");
			}
 
	}
	
	
	public function installDB() {
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS  ". DB_PREFIX ."edit_key_product (
		`key` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		license_key text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL)");
	
		$license_key = $this->db->query("SELECT `key` FROM ". DB_PREFIX ."edit_key_product WHERE `key`='local_key' LIMIT 1");
		if ($license_key->num_rows <= 0) { $this->db->query("INSERT INTO ". DB_PREFIX ."edit_key_product (`key`, `license_key`) VALUES( 'local_key', '');");}
				
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_price(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`price_manual` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`price_changes` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`close_price` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_manual_price(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`manual_price` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_end` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_username_join_product(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_additional_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_additional_model` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_additional_sku` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_additional_price` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_join` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_manual_price_save_special(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`priority` int(11) NOT NULL,
			`customer_group_id` int(11) NOT NULL,
			`price` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_start` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_end` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_price_product(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_model` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_sku` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`quantity` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_join_sku(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_model` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_sku` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`price` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`bprice` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`quantity` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_special(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`special_changes` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_discount(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`discount_changes` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_related(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`related_changes` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`close_related` text CHARACTER SET utf8 COLLATE utf8_general_ci)");		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX ."edit_options(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`options` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("DESC `" . DB_PREFIX . "edit_options` close_options");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_image_google(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`general_image` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`additional_image` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_image(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`general_image` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`additional_image` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`sort_addtitional_image` text CHARACTER SET utf8 COLLATE utf8_general_ci)");						
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_image_url(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`general_image` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`additional_image` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX ."edit_code(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`model_product` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`sku_product` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`upc_product` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`location` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`tax_class` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`quantity` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`minimum` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`subtract` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`stock_status` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`lwh` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`length_class` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`weight` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`weight_class` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_description(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`product_h1` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`seo_title` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`meta_keyword` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`meta_description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`tag` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_category(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`manufacture` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`category` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`subcategory` text CHARACTER SET utf8 COLLATE utf8_general_ci)");				
	
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX ."edit_attributes(`id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`user_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_modified` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`attributes` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "suppler_sku_description (nom_id int(11) AUTO_INCREMENT, sku_id int(11), sku varchar(64), store_id int(2), PRIMARY  KEY (nom_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "suppler_sku (nom_id int(11) AUTO_INCREMENT, sku_id int(11), product_id int(11), PRIMARY  KEY (nom_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "suppler_base_price (nom_id int(11) AUTO_INCREMENT, product_id int(11), model varchar(64), bprice decimal(12,4), bpack int(11), brate decimal(12,4), bsuppler varchar(2), bdisc decimal(12,4), bmin decimal(12,4), bav decimal(12,4), bmax decimal(12,4), optimal  decimal(12,4), market_percent_to_price decimal(6,3), market_percent_to_bprice decimal(6,3),  market_percent_to_bdprice decimal(6,3), PRIMARY  KEY (nom_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		

		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_seo_description(
			`seo_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,				
			`selected_id` int(11) NOT NULL,				
			`name_seo_template` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_added` datetime NOT NULL,				
			`data_seo_description` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_seo_title(
			`seo_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,				
			`selected_id` int(11) NOT NULL,				
			`name_seo_template` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_added` datetime NOT NULL,				
			`data_seo_title` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_seo_h1(
			`seo_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,				
			`selected_id` int(11) NOT NULL,				
			`name_seo_template` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_added` datetime NOT NULL,				
			`data_seo_h1` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_seo_keyword(
			`seo_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,				
			`selected_id` int(11) NOT NULL,				
			`name_seo_template` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_added` datetime NOT NULL,				
			`data_seo_keyword` text CHARACTER SET utf8 COLLATE utf8_general_ci)");				
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "edit_seo_tag(
			`seo_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,				
			`selected_id` int(11) NOT NULL,				
			`name_seo_template` text CHARACTER SET utf8 COLLATE utf8_general_ci,
			`date_added` datetime NOT NULL,				
			`data_seo_tag` text CHARACTER SET utf8 COLLATE utf8_general_ci)");
		$product_description_meta_h1 = $this->db->query("DESC `".DB_PREFIX."product_description` meta_h1");
			if (!$product_description_meta_h1->num_rows) { 
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `meta_h1` varchar(255) NOT NULL");
			}
	}
	
	public function uninstallDB() {
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_seo_tag");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_seo_keyword");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_seo_h1");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_seo_title");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_seo_description");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_attributes");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_description");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_category");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_code");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_image_url");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_image");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_image_google");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_options");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_related");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_discount");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_special");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_manual_price");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_price");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_price_product");
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."edit_manual_price_save_special");
	}
	
}
?>
