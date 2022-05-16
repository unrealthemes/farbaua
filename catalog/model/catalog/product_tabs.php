<?php
class ModelCatalogProductTabs extends Model {
	public function getTabs($product_id) {
    $product_tabs = array();
    $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_tabs_ns ptn LEFT JOIN " . DB_PREFIX . "tabs_ns tn ON (tn.tabs_ns_id = ptn.tabs_ns_id) LEFT JOIN " . DB_PREFIX . "tabs_description_ns tdn ON (tdn.tabs_ns_id = ptn.tabs_ns_id) WHERE ptn.product_id = '" . (int)$product_id . "' AND ptn.language_id = '" . (int)$this->config->get('config_language_id') . "' AND tdn.language_id = '" . (int)$this->config->get('config_language_id') . "' AND tn.status = '1'");
    if ($query->rows) {
      foreach ($query->rows as $result) {
		
		if($result['show_empty_tab'] == '1'){
			$product_tabs[] = array(
				'title' => $result['title'],
				'text' 	=> $result['text'],
				'icon_tabs' => $result['icon_tabs'],
			);
		} else {
			$tab_text_info = strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'));
			if($tab_text_info !=''){
				$product_tabs[] = array(
					'title' => $result['title'],
					'text' 	=> $result['text'],
					'icon_tabs' => $result['icon_tabs'],
				);
			}
		}
        
      }
    }
    
    return $product_tabs;
  }
}