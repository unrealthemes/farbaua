<?php
class ModelExtensionModuleProSticker extends Model {
	public function addSticker($data) {
		if(isset($data['text_label'])){
			$text_label = $data['text_label'];
		} else {
			$text_label = '';
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "stickers_list SET 
		name = '" . $this->db->escape($data['name']) . "', 
		images = '" . $this->db->escape(json_encode($data['images'], true)) . "',
		text_label = '" . $this->db->escape(json_encode($text_label, true)) . "',
		sort_order = '" . (int) $data['sort_order'] . "'");
		
		$this->cache->delete('stickers_list');
	}
	
	public function editSticker($sticker_id, $data) {
		if(isset($data['text_label'])){
			$text_label = $data['text_label'];
		} else {
			$text_label = '';
		}
		$this->db->query("UPDATE " . DB_PREFIX . "stickers_list SET 
		name = '" . $this->db->escape($data['name']) . "', 
		images = '" . $this->db->escape(json_encode($data['images'], true)) . "', 
		text_label = '" . $this->db->escape(json_encode($text_label, true)) . "', 
		sort_order = '" . (int)$data['sort_order'] . "' 
		WHERE sticker_id = '" . (int)$sticker_id . "'");
		
		$this->cache->delete('stickers_list');
	}
	
	public function deleteSticker($sticker_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stickers_list WHERE sticker_id = '" . (int) $sticker_id . "'");
		
		$this->cache->delete('stickers_list');
	}
	
	public function getStickerById($sticker_id) {
		return $this->db->query("SELECT * FROM " . DB_PREFIX . "stickers_list WHERE sticker_id = '" . (int) $sticker_id . "'")->row;
	}
	
	public function getAllStickers() {
		$stickers_list = $this->cache->get('stickers_list');
		
		if (!$stickers_list) {
			$stickers_list = $this->db->query("SELECT sticker_id, name FROM " . DB_PREFIX . "stickers_list")->rows;
			
			$this->cache->set('stickers_list', $stickers_list);
		}
		
		return $stickers_list;
	}
	
	public function getStickerList($data = array ()) {
		$sort_data = array('sl.name', 'sl.sort_order');
		
		$sql = "SELECT * FROM " . DB_PREFIX . "stickers_list sl";
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sl.name";
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ($data['start']) || isset ($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			
			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}
		
		return $this->db->query($sql)->rows;
	}
	
	public function getProductsByProStickerId($sticker_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE sticker_id = '" . (int) $sticker_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalStickerList($data = array ()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stickers_list");
		
		return $query->row['total'];
	}
	
	public function getProducts($data = array ()) {
		$sort_data = array ('p.image', 'pd.name', 'p.sort_order', 'p.price', 'sl.name', 'p.date_start_sticker', 'p.date_end_sticker');
		
		$sql = "SELECT p.product_id AS product_id, p.image AS image, pd.name AS name, p.price AS price, p.sort_order AS sort_order, p.date_available AS date_available, sl.name AS sticker_name, sl.images AS sticker_images, p.date_start_sticker AS date_start_sticker, p.date_end_sticker AS date_end_sticker FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "stickers_list sl ON (p.sticker_id = sl.sticker_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		if ($data['filter_category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		
		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		
		$sql .= $this->getFilterSql($data);
		
		$sql .= " GROUP BY p.product_id";
		
		if (isset ($data['sort']) && in_array ($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}
		
		if (isset ($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ($data['start']) || isset ($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			
			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}
		
		return $this->db->query($sql)->rows;
	}
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		if ($data['filter_category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}
		
		if ($data['filter_sticker_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "stickers_list sl ON (p.sticker_id = sl.sticker_id)";
		}
		
		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		
		$sql .= $this->getFilterSql($data);
		
		return $this->db->query($sql)->row['total'];
	}
	
	public function editProducts($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET sticker_id = '" . (int) $data['sticker_id'] . "', date_start_sticker = '" . $data['date_start_sticker'] . "', date_end_sticker = '" . $data['date_end_sticker'] . "' WHERE product_id IN (" . implode (',', $data['selected']) . ")");
	}
	
	private function getFilterSql($data) {
		$sql = '';
		
		if ($data['filter_product_name']) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_product_name'])) . "%'";
		}
		
		if ($data['filter_category_id']) {
			$sql .= " AND p2c.category_id IN (" . implode (',', $data['filter_category_id']) . ")";
		}
		
		if ($data['filter_sticker_id']) {
			$sql .= " AND sl.sticker_id IN (" . implode (',', $data['filter_sticker_id']) . ")";
		}
		
		if ($data['filter_date_start_sticker']) {
			$sql .= " AND p.date_start_sticker = '" . $this->db->escape($data['filter_date_start_sticker']) . "'";
		}
		
		if ($data['filter_date_end_sticker']) {
			$sql .= " AND p.date_end_sticker = '" . $this->db->escape($data['filter_date_end_sticker']) . "'";
		}
		
		return $sql;
	}
}
?>