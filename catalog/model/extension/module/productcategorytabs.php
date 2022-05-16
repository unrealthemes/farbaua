<?php
class ModelExtensionModuleProductcategorytabs extends Model {
	public function getBestSellerProducts($data = array()){
      $customer_group_id = $this->config->get('config_customer_group_id');    
      $product_data = $this->cache->get('product.bestseller.cat_id.' . $data['filter_category_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);
      if (!$product_data) {
         $product_data = array();
         
         $sql = "SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op 
		 LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) 
		 LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) 
		 LEFT JOIN " . DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id)
		 LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		 LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
		 WHERE o.order_status_id > '0' 
		 AND p.status = '1'
		 AND p.date_available <= NOW() 
		 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		 AND p2c.category_id = '". $data['filter_category_id'] ."'  
		 GROUP BY op.product_id ";

		 if (isset($data['sort'])){
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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
        foreach ($query->rows as $result) {       
            $product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
        }
         
         $this->cache->set('product.bestseller.cat_id.' . (int)$data['filter_category_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $product_data);
      }
      
      return $product_data;
    }
	
	
	public function getProductSpecials($data = array()) {
		
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) 
		FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id 
		AND r1.status = '1' GROUP BY r1.product_id) AS rating,
		(SELECT category_id FROM ". DB_PREFIX ."product_to_category WHERE product_id = ps.product_id AND category_id = '" .$data['filter_category_id']. "' GROUP BY category_id) as category 
		FROM " . DB_PREFIX . "product_special ps 
		LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE p.status = '1' 
		AND p.date_available <= NOW() 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
		AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
		AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) 
		GROUP BY ps.product_id ,category HAVING category='".$data['filter_category_id']."'";
		
		
		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}
	public function getProductMostViewed($data = array()) {
		
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)
		WHERE p.status = '1' 
		AND p.date_available <= NOW()
		AND p.viewed > 0 	
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
		AND p2c.category_id = '". $data['filter_category_id']. "' GROUP BY p.viewed ";
		
		if (isset($data['sort'])){
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
}