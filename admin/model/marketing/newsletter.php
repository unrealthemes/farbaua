<?php
class ModelMarketingNewsletter extends Model {
	public function deleteNewsletter($newsletter_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE id = '" . (int)$newsletter_id . "'");
	}
	public function getNewsletters($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "newsletter n";
		$sql .= " ORDER BY n.date_added";

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

	public function getTotalNewsletters() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter";

		$implode = array();

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}