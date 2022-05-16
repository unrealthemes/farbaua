<?php
class ModelExtensionModuleQuestionAnswer extends Model {
	public function getQuestionAnswer($data = array()) {
		$sql = "SELECT * FROM ". DB_PREFIX ."question_answer GROUP BY qa_id ORDER BY date_added DESC";

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
	public function getTotalQuestionAnswer() {
		$sql = "SELECT COUNT(*) AS total FROM ". DB_PREFIX ."question_answer";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getTotalNewQuestionAnswer() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ". DB_PREFIX ."question_answer WHERE status_id = '0'");
		
		return $query->row['total'];
	}
	public function delSelectId($qa_id) {
		$this->db->query("DELETE FROM `".DB_PREFIX."question_answer` WHERE qa_id = '".(int)$qa_id."'");
	}
	public function ChangeStatusQuestionAnswer($qa_id) {
      	$this->db->query("UPDATE " . DB_PREFIX . "question_answer SET status_id = '1', date_modified = NOW() WHERE qa_id = '" . (int)$qa_id . "'");
	}	
	public function saveCommentManager($qa_id,$comment_manager) {
      	$this->db->query("UPDATE " . DB_PREFIX . "question_answer SET comment_manager = '". $this->db->escape($comment_manager) ."', date_modified = NOW() WHERE qa_id = '" . (int)$qa_id . "'");
	}	
	public function installDB() {
		$this->db->query("CREATE TABLE IF NOT EXISTS  " . DB_PREFIX ."question_answer(
			`qa_id` int(11) NOT NULL primary key AUTO_INCREMENT,
			`name_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`telephone_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`comment_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`email_field` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`qa_product_id` int(11) NOT NULL,
			`comment_manager` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
			`status_id` int(11) NOT NULL,
			`date_added` datetime NOT NULL DEFAULT '0000-00-00',
			`date_modified` datetime NOT NULL DEFAULT '0000-00-00')");
	}

	public function uninstallDB() {
		$this->db->query("DROP TABLE IF EXISTS ". DB_PREFIX ."question_answer");
	}
}