<?php
class ModelSaleCallback extends Model {
	public function editCallback($callback_id,$data) {
		$this->db->query("UPDATE " . DB_PREFIX . "callback SET name = '" . $this->db->escape($data['name']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', comment = '" . $this->db->escape($data['comment']) . "', email_buyer = '" . $this->db->escape($data['email_buyer']) . "', username = '" . $this->db->escape($data['username']) . "', status_id = '" . (int)$data['status_id'] . "', date_modified = NOW() WHERE call_id = '" . (int)$callback_id . "'");
	}
	public function editCallbacks($callback_id) {
      	$this->db->query("UPDATE " . DB_PREFIX . "callback SET status_id = '1', date_modified = NOW() WHERE call_id = '" . (int)$callback_id . "'");
	}
	public function deleteCallback($callback_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "callback WHERE call_id = '" . (int)$callback_id . "'");
		$this->cache->delete('callback');
	}
	public function getCallback($callback_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "callback WHERE call_id = '" . (int)$callback_id . "'");
		return $query->row;
	}
	public function getCallbacks($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "callback";

			if (isset($data['filter_status'])) {
				$sql .= " WHERE status_id = '" . $data['filter_status'] . "'";
			} else {
				$sql .= " WHERE status_id >= '0'";
			}
			if (!empty($data['filter_callback_id'])) {
				$sql .= " AND call_id = '" . $data['filter_callback_id'] . "'";
			}
			if (!empty($data['filter_fio'])) {
				$sql .= " AND name LIKE '" . $this->db->escape($data['filter_fio']) . "%'";
			}
			if (!empty($data['filter_phone'])) {
				$sql .= " AND telephone LIKE '" . $this->db->escape($data['filter_phone']) . "%'";
			}
			if (!empty($data['filter_email'])) {
				$sql .= " AND email_buyer LIKE '" . $this->db->escape($data['filter_email']) . "%'";
			}
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			$sort_data = array(
				'call_id',
				'name',
				'telephone'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY call_id";
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
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "callback ORDER BY call_id");
			$callback_data = $query->rows;
			return $callback_data;
		}
	}
	public function getTotalCallbacks($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "callback";
		if (isset($data['filter_status'])) {
			$sql .= " WHERE status_id = '" . $data['filter_status'] . "'";
		} else {
			$sql .= " WHERE status_id >= '0'";
		}
		if (!empty($data['filter_callback_id'])) {
			$sql .= " AND call_id = '" . $data['filter_callback_id'] . "'";
		}
		if (!empty($data['filter_fio'])) {
			$sql .= " AND name LIKE '" . $this->db->escape($data['filter_fio']) . "%'";
		}
		if (!empty($data['filter_phone'])) {
			$sql .= " AND telephone LIKE '" . $this->db->escape($data['filter_phone']) . "%'";
		}
		if (!empty($data['filter_email'])) {
				$sql .= " AND email_buyer LIKE '" . $this->db->escape($data['filter_email']) . "%'";
			}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function addDbCallback(){}
	public function CreateTableNewstore(){}
	public function countTotal() {
		$countcallback = $this->db->query("SELECT COUNT(status_id) AS countcallback FROM `".DB_PREFIX."callback` WHERE status_id = '0'");
		return $countcallback->row['countcallback'];
	}
	public function countTotalAll() {
		$countcallback_all = $this->db->query("SELECT COUNT(status_id) AS countcallback_all FROM `".DB_PREFIX."callback`");
		return $countcallback_all->row['countcallback_all'];
	}
	public function countTotalFinal() {
		$countcallback_final = $this->db->query("SELECT COUNT(status_id) AS countcallback_final FROM `".DB_PREFIX."callback` WHERE status_id = '1'");
		return $countcallback_final->row['countcallback_final'];
	}
}
?>
