<?php
class ModelNsModuleonoff extends Model {		
	public function getCallbackModuleModification() {
		$query_status = $this->db->query("SELECT status FROM " . DB_PREFIX . "modification WHERE `code`='NS > CALLBACK'");
		if($query_status->num_rows > 0) { 
			return $query_status->row['status'];
		} else {
			return 0;
		}
	}
	public function getQuickOrderModuleModification() {
		$query_status = $this->db->query("SELECT status FROM " . DB_PREFIX . "modification WHERE `code`='NS->QUICK-ORDER'");
		if($query_status->num_rows > 0) { 
			return $query_status->row['status'];
		} else {
			return 1;
		}
	}
}
?>
