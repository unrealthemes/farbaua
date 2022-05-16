<?php
class ModelCatalogProductTabs extends Model {
	public function getProductExtraTabs($tabs_ns_id) {
          $query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_tabs_ns WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "' GROUP BY product_id");
			$all_product_this_tabs = array();
			foreach($query->rows as $key => $value){
				$comma_separated[] = implode(",", $value);
			}
          return $comma_separated;
    }
	public function changePrDescription($data, $product_id) { 
			
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_tabs_ns WHERE product_id = '" . (int)$product_id . "' AND tabs_ns_id = '" . (int)$data['tabs_ns_id'] . "'");
			
          if (!empty($data['product_extra_tab'])) {
                foreach ($data['product_extra_tab'] as $language_id => $value) {
                  $this->db->query("INSERT INTO " . DB_PREFIX . "product_tabs_ns SET product_id = '" . (int)$product_id . "', tabs_ns_id = '" . (int)$data['tabs_ns_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($value['product_extra_tab_description']) . "'");
                }
          }
        
    }
	public function changePrTabsStatus($tabs_ns_id, $value){
		$this->db->query("UPDATE " . DB_PREFIX . "tabs_ns SET status = '" . (int)$value . "' WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");
	}
	public function changePrTabsShowEmpty($tabs_ns_id, $value){
		$this->db->query("UPDATE " . DB_PREFIX . "tabs_ns SET show_empty_tab = '" . (int)$value . "' WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");
	}
	public function addTab($data) {
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "tabs_ns SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', show_empty_tab = '" . (int)$data['show_empty_tab'] . "', icon_tabs = '" .  $this->db->escape($data['icon_tabs']) . "'");

		$tabs_ns_id = $this->db->getLastId();
		
		foreach ($data['product_tabs_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tabs_description_ns SET tabs_ns_id = '" . (int)$tabs_ns_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}

		return $tabs_ns_id;
	}

	public function editTab($tabs_ns_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tabs_ns SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', show_empty_tab = '" . (int)$data['show_empty_tab'] . "', icon_tabs = '" . $this->db->escape($data['icon_tabs']) . "' WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "tabs_description_ns WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");

		foreach ($data['product_tabs_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tabs_description_ns SET tabs_ns_id = '" . (int)$tabs_ns_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
		}
	}

	

	public function getTab($tabs_ns_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tabs_ns b LEFT JOIN " . DB_PREFIX . "tabs_description_ns bd ON (b.tabs_ns_id = bd.tabs_ns_id) WHERE b.tabs_ns_id = '" . (int)$tabs_ns_id . "' AND bd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getTabs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tabs_ns b LEFT JOIN " . DB_PREFIX . "tabs_description_ns bd ON (b.tabs_ns_id = bd.tabs_ns_id) WHERE bd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND bd.title LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND b.status = '" . (int)$data['filter_status'] . "'";
		}
		if (isset($data['filter_show_empty']) && !is_null($data['filter_show_empty'])) {
			$sql .= " AND b.show_empty_tab = '" . (int)$data['filter_show_empty'] . "'";
		}
		$sql .= " GROUP BY b.tabs_ns_id";
		
		$sort_data = array(
			'bd.title',
			'b.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY bd.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTabsDescriptions($tabs_ns_id) {
		$product_tabs_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tabs_description_ns WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");

		foreach ($query->rows as $result) {
			$product_tabs_description_data[$result['language_id']] = array(
				'title' => $result['title']
			);
		}

		return $product_tabs_description_data;
	}
	public function delTab($tabs_ns_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tabs_ns WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tabs_description_ns WHERE tabs_ns_id = '" . (int)$tabs_ns_id . "'");
	}
	public function getTotalTabs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tabs_ns");

		return $query->row['total'];
	}
}