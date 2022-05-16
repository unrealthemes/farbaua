<?php
class ModelSaleNewfastorder extends Model {

	public function editNewfastorder($newfastorder_id,$data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "newfastorder SET
		name = '" . $this->db->escape($data['name']) . "',
		telephone = '" . $this->db->escape($data['telephone']) . "',
		email_buyer = '" . $this->db->escape($data['email_buyer']) . "',
		comment = '" . $this->db->escape($data['comment']) . "',
		username = '" . $this->db->escape($data['username']) . "',
		status_id = '" . (int)$data['status_id'] . "',
		date_modified = NOW() WHERE fast_id = '" . (int)$newfastorder_id . "'");
	}


	public function editNewfastorders($newfastorder_id) {
      	$this->db->query("UPDATE " . DB_PREFIX . "newfastorder SET status_id = '1', date_modified = NOW() WHERE fast_id = '" . (int)$newfastorder_id . "'");


	}

	public function deleteNewfastorder($newfastorder_id) {
	$del_prod_fast = $this->db->query("SELECT order_id FROM ". DB_PREFIX ."newfastorder WHERE fast_id = '". $newfastorder_id ."'");
			foreach($del_prod_fast->rows as $del_prod){
				$this->db->query("DELETE FROM " . DB_PREFIX . "newfastorder_product WHERE order_id = '" . $del_prod['order_id'] . "'");
			}
		$this->db->query("DELETE FROM " . DB_PREFIX . "newfastorder WHERE fast_id = '" . (int)$newfastorder_id . "'");


		$this->cache->delete('newfastorder');
	}

	public function getNewfastorder($newfastorder_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newfastorder WHERE fast_id = '" . (int)$newfastorder_id . "'");

		return $query->row;
	}
	public function getCustomers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "newfastorder` nfo";
		$sql .= " WHERE nfo.fast_id > '0'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(nfo.name, ' ', nfo.telephone, ' ', nfo.email_buyer) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'fast_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	public function getNewfastorders($data = array()) {

		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "newfastorder` nfo";
			$sql .= " WHERE nfo.fast_id > '0'";

			if (!empty($data['searh_info_user'])) {
				$sql .= " AND CONCAT(nfo.name, ' ', nfo.telephone, ' ', nfo.email_buyer) LIKE '%" . $this->db->escape($data['searh_info_user']) . "%'";
			}
			if (!empty($data['filter_url'])) {
				$sql .= " AND CONCAT(nfo.newfastorder_url) LIKE '%" . $this->db->escape($data['filter_url']) . "%'";
			}
			if (!empty($data['filter_manager'])) {
				$sql .= " AND CONCAT(nfo.username) LIKE '%" . $this->db->escape($data['filter_manager']) . "%'";
			}
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND CONCAT(nfo.date_added) LIKE '%" . $this->db->escape($data['filter_date_added']) . "%'";
			}
			if (!empty($data['filter_date_modified'])) {
				$sql .= " AND CONCAT(nfo.date_modified) LIKE '%" . $this->db->escape($data['filter_date_modified']) . "%'";
			}
			if (!empty($data['filter_status']) || ($data['filter_status'] =='0')) {
				$sql .= " AND nfo.status_id = " . (float)$data['filter_status'] . "";
			}


			$sort_data = array(
				'fast_id',
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY fast_id";
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
		} else {
			$newfastorder_data = $this->cache->get('newfastorder');

			if (!$newfastorder_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newfastorder ORDER BY fast_id");

				$newfastorder_data = $query->rows;

				$this->cache->set('newfastorder', $newfastorder_data);
			}

			return $newfastorder_data;
		}
	}

	public function getFastOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."newfastorder_product WHERE order_id = ". $order_id ."");
		return $query->rows;
	}

	public function getFastOrderProductOptions($data) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."newfastorder_product_option WHERE order_id = ". $data['order_id'] ." AND order_product_id = ". $data['product_id'] ."");
		return $query->rows;
	}
	public function countTotalNewFastorder() {
		$query = $this->db->query("SELECT COUNT(status_id) AS countfastorder FROM `".DB_PREFIX."newfastorder` WHERE status_id = '0'");
		return $query->row['countfastorder'];
	}

	public function getTotalNewfastorders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newfastorder");
		return $query->row['total'];
	}
	public function addDbQuickorder() {}
	public function saveSettingNewFastOrder($data) {
		$store_id = 0;
		$code = 'fastorder';
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."'");

		foreach ($this->request->post as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
			}
		}
	}
}
?>
