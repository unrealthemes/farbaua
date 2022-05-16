<?php
class ModelCatalogReviewsStore extends Model {
	public function changeStatus($reviews_store_id, $value){
		$this->db->query("UPDATE " . DB_PREFIX . "reviews_store SET status = '" . (int)$value . "' WHERE reviews_store_id = '" . (int)$reviews_store_id . "'");
	}
	public function changeStatusCheck($reviews_store_id, $value){
		$this->db->query("UPDATE " . DB_PREFIX . "reviews_store SET status_check = '" . (int)$value . "' WHERE reviews_store_id = '" . (int)$reviews_store_id . "'");
	}
	public function deleteReview($reviews_store_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store WHERE reviews_store_id = '" . (int)$reviews_store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_rating WHERE reviews_store_id = '" . (int)$reviews_store_id . "'");
	}
	public function editTheme($reviews_store_id,$data) {
		$this->db->query("UPDATE " . DB_PREFIX . "reviews_store SET 
		author = '" . $this->db->escape($data['author']) . "', 
		description = '" . $this->db->escape($data['description']) . "', 
		admin_response = '" .$this->db->escape($data['admin_response']). "', 
		`like` = '" . (int)$data['like'] . "',
		dislike = '" . (int)$data['dislike'] . "',
		status = '" . (int)$data['status'] . "',
		date_added = '" . $this->db->escape($data['date_added']) . "',
		date_modified = NOW() WHERE reviews_store_id = '" . (int)$reviews_store_id . "'");
		
		if (isset($data['rating'])) {
			foreach ($data['rating'] as $reviews_store_theme_id => $value) {
				$this->db->query("UPDATE " . DB_PREFIX . "reviews_store_rating SET rating = '" . (int)$value . "' WHERE reviews_store_theme_id = '" . (int)$reviews_store_theme_id . "' AND reviews_store_id = '" . (int)$reviews_store_id . "'");				
			}
		}
	}
	public function getInfoReview($reviews_store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "reviews_store WHERE reviews_store_id='". (int)$reviews_store_id ."'");

		return $query->row;
	}
	public function getInfoThemeRating($reviews_store_id) {
		$query = $this->db->query("SELECT rsr.reviews_store_theme_id,rstd.theme_text,rsr.rating FROM " . DB_PREFIX . "reviews_store_theme_desc rstd 
			LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rstd.reviews_store_theme_id = rsr.reviews_store_theme_id) WHERE 
			rsr.reviews_store_id = '". (int)$reviews_store_id ."' AND rstd.language_id='". (int)$this->config->get('config_language_id') ."'");

		return $query->rows;
	}
	public function getRatingTheme($reviews_store_id) {
		$query = $this->db->query("SELECT rsr.rating FROM " . DB_PREFIX . "reviews_store_theme rst
			LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rst.reviews_store_theme_id = rsr.reviews_store_theme_id) WHERE rsr.reviews_store_id='" . (int)$reviews_store_id . "' 
			ORDER BY rst.sort_order ASC");

		return $query->rows;
	}
	
	public function getAllReviews($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "reviews_store rs ";
		
		if (!empty($data['filter_author'])) {
			$sql .= " WHERE rs.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_author'])) {
				$sql .= " AND";
			} else {
				$sql .= " WHERE";
			}
			$sql .= " rs.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			if (!empty($data['filter_author']) || (isset($data['filter_status']) && !is_null($data['filter_status']))) {
				$sql .= " AND";
			} else {
				$sql .= " WHERE";
			}
			$sql .= " DATE(rs.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		$sort_data = array(
			'date_added_asc',
			'date_added_desc'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'date_added_asc') {
				$sql .= " ORDER BY rs.date_added ASC";
			} else {
				$sql .= " ORDER BY rs.date_added DESC";
			}
		} else {
			$sql .= " ORDER BY rs.date_added DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			
			if (!isset($data['start']) || $data['start'] < 0) {
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
	public function getAvgRatingCustomer($reviews_store_id) {
		$sql = "SELECT TRUNCATE(AVG(rsr.rating),1) as avg_customer_rating
		FROM " . DB_PREFIX . "reviews_store rs 
		LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rs.reviews_store_id = rsr.reviews_store_id) AND rs.reviews_store_id='" . (int)$reviews_store_id . "'";

		$query = $this->db->query($sql);
	
		return $query->row['avg_customer_rating'];
	}
	public function getThemeList() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "reviews_store_theme rst
		LEFT JOIN " . DB_PREFIX . "reviews_store_theme_desc rstd ON (rst.reviews_store_theme_id = rstd.reviews_store_theme_id) 
		WHERE rstd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rst.sort_order ASC");

		return $query->rows;
	}
	
	
	
	public function getTotalReviewsStore($data = array()) {
		$sql = "SELECT COUNT(reviews_store_id) as total_r FROM " . DB_PREFIX . "reviews_store";
		if (!empty($data['filter_author'])) {
			$sql .= " WHERE author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			if (!empty($data['filter_author'])) {
				$sql .= " AND";
			} else {
				$sql .= " WHERE";
			}
			$sql .= " status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			if (!empty($data['filter_author']) || (isset($data['filter_status']) && !is_null($data['filter_status']))) {
				$sql .= " AND";
			} else {
				$sql .= " WHERE";
			}
			$sql .= " DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		$query = $this->db->query($sql);
			
		return $query->row['total_r'];
	}
	public function countTotalStatusOff() {
		$bd_select = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "reviews_store'");
		if ($bd_select->num_rows == 0) {
			$this->installDB();
			return 0;		
		} else {
			$sql = "SELECT COUNT(reviews_store_id) as total_reviews FROM " . DB_PREFIX . "reviews_store WHERE status_check = '0'";

			$query = $this->db->query($sql);
			
			return $query->row['total_reviews'];
		}
	}
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
	
	public function editSettingReviewsStore($code, $data, $store_id = 0) {
		$data = array('reviews_store_setting' => $data);
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					if (VERSION < 2.1) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}	
				}
		
		}
		
	}
	public function editSettingTheme($data) {
		if(!isset($data['reviews_theme_item'])){
			$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_theme");
			$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_theme_desc");
		}
		
		if(isset($data['reviews_theme_item'])){
			$reviews_store_theme_query = $this->db->query("SELECT reviews_store_theme_id FROM " . DB_PREFIX . "reviews_store_theme");
			$item_in_bd=array();
			
			foreach($reviews_store_theme_query->rows as $result){
				$item_in_bd[]=$result['reviews_store_theme_id'];
			}
			$item_send=array();
			
				foreach($data['reviews_theme_item'] as $result2){
					$item_send[]=$result2['reviews_store_theme_id'];
				}
			
			$del_theme=array_diff($item_in_bd, $item_send);
			if(count($del_theme)){
				$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_theme WHERE reviews_store_theme_id IN ('" . join(',', $del_theme) . "')");
				$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_theme_desc WHERE reviews_store_theme_id IN ('" . join(',', $del_theme) . "')");
			}
			foreach($data['reviews_theme_item'] as $res_theme){
				$this->db->query("UPDATE " . DB_PREFIX . "reviews_store_theme SET 
				sort_order = '" . (int)$res_theme['sort_order'] . "',
				status = '" . (int)$res_theme['status'] . "'
				WHERE reviews_store_theme_id = '" . (int)$res_theme['reviews_store_theme_id'] . "'");

				$this->db->query("DELETE FROM " . DB_PREFIX . "reviews_store_theme_desc WHERE reviews_store_theme_id = '" . (int)$res_theme['reviews_store_theme_id'] . "'");

				foreach ($res_theme['theme_text'] as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "reviews_store_theme_desc SET
					reviews_store_theme_id = '" . (int)$res_theme['reviews_store_theme_id'] . "',
					theme_text = '" . $this->db->escape($value) . "', 
					language_id = '" . (int)$language_id . "'");
				}
				
			}
		}
		
		if(isset($data['reviews_theme_item_new'])){
			foreach($data['reviews_theme_item_new'] as $res_theme_new){
				$this->db->query("INSERT INTO " . DB_PREFIX . "reviews_store_theme SET 
				sort_order = '" . (int)$res_theme_new['sort_order'] . "', 
				status = '" . (int)$res_theme_new['status'] . "'");
				$reviews_store_theme_id = $this->db->getLastId();
				
				foreach ($res_theme_new['theme_text'] as $language_id => $value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "reviews_store_theme_desc SET
					reviews_store_theme_id = '" . (int)$reviews_store_theme_id . "',
					theme_text = '" . $this->db->escape($value) . "', 
					language_id = '" . (int)$language_id . "'");
				}
			}
		}
		
	}
	public function getReviewsTheme() {
		$reviews_store_theme_data = array();
		$reviews_store_theme_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "reviews_store_theme ORDER BY sort_order ASC");
		
		foreach ($reviews_store_theme_query->rows as $reviews_store_theme) {
			$reviews_store_theme_desc_data = array();

			$reviews_store_theme_desc_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "reviews_store_theme_desc 
			WHERE reviews_store_theme_id = '" . (int)$reviews_store_theme['reviews_store_theme_id'] . "'");

			foreach ($reviews_store_theme_desc_query->rows as $reviews_store_theme_desc) {
				$reviews_store_theme_desc_data[$reviews_store_theme_desc['language_id']] = $reviews_store_theme_desc['theme_text'];
			}

			$reviews_store_theme_data[] = array(
				'theme_text' 			   => $reviews_store_theme_desc_data,
				'reviews_store_theme_id'   => $reviews_store_theme['reviews_store_theme_id'],
				'status'                   => $reviews_store_theme['status'],
				'sort_order'               => $reviews_store_theme['sort_order']
			);
		}
		
		return $reviews_store_theme_data;
		
	}
	
	
	
	
}