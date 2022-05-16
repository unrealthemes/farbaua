<?php
class ModelExtensionModuleFoundCheaperproduct extends Model {
	public function getFoundCheapers($data = array()) {
		$sql = "SELECT * FROM ". DB_PREFIX ."found_cheaper GROUP BY id_found_cheaper ORDER BY date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 4;
			}

			$sql .= " LIMIT ".(int)$data['start'].",".(int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalFoundCheaper() {
		$sql = "SELECT COUNT(*) AS total FROM ". DB_PREFIX ."found_cheaper";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getTotalNewFoundCheaper() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ". DB_PREFIX ."found_cheaper WHERE status_id = '0'");
		
		return $query->row['total'];
	}
	public function delSelectId($id_found_cheaper) {
		$this->db->query("DELETE FROM `".DB_PREFIX."found_cheaper` WHERE id_found_cheaper = '".(int)$id_found_cheaper."'");
	}
	public function ChangeStatusFoundCheaper($id_found_cheaper) {
      	$this->db->query("UPDATE " . DB_PREFIX . "found_cheaper SET status_id = '1', date_modified = NOW() WHERE id_found_cheaper = '" . (int)$id_found_cheaper . "'");
	}	
	public function saveCommentManager($id_found_cheaper,$comment_manager) {
      	$this->db->query("UPDATE " . DB_PREFIX . "found_cheaper SET comment_manager = '". $this->db->escape($comment_manager) ."', date_modified = NOW() WHERE id_found_cheaper = '" . (int)$id_found_cheaper . "'");
	}	
	public function installDB() {
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX ."found_cheaper(
			`id_found_cheaper` int(11) NOT NULL primary key AUTO_INCREMENT,
			`name_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`telephone_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`link_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`comment_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`email_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`fcp_product_id` int(11) NOT NULL,
			`comment_manager` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			 `status_id` int(11) NOT NULL,
			`date_added` datetime NOT NULL,
			`date_modified` datetime NOT NULL)");
	}

	public function uninstallDB() {
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."found_cheaper");
	}
}