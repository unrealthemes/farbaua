<?php
class ModelExtensionDesignWallcategory extends Model {
	public function getCategory($wall_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wallcategory_image WHERE banner_id = '". (int)$wall_id ."'");
		
		return $query->rows;
	}
}