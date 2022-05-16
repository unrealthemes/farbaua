<?php
class ControllerCommonMenuns extends Controller {
	public function index() {

		$data['config_fixed_panel_top'] = $this->config->get('config_fixed_panel_top');
		$config_main_menu_selection = $this->config->get('config_main_menu_selection');
		$data['type_mobile_menu'] = $this->config->get('type_mobile_menu');
		$data['main_menu_fix_mobile'] = $this->config->get('main_menu_fix_mobile');
		$data['main_menu_mask'] = $this->config->get('main_menu_mask');
		$this->load->language('ns/newstorelang');
		$data['text_info_mob'] = $this->language->get('text_info_mob');
		$this->load->language('common/menu');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$data['main_menu'] = $this->config->get('config_main_menu_selection');
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['desc_info_mob'] = html_entity_decode($this->config->get('language_description_info_mob')[$data['lang_id']]['text'], ENT_QUOTES, 'UTF-8');
		$this->load->model('extension/module/nsmenu');
		$data['hmenu_type'] = $this->config->get('horizontal_menu_width_setting');
		$data['items'] = array();
		$data['additional'] = array();
		$menu_items_cache = $this->cache->get('newstoremenu.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));
		if (!empty($menu_items_cache)) {
				$data['items'] = $menu_items_cache;
				$config_menu_item = $this->config->get('config_menu_item');
				if(!empty($config_menu_item)) {
					$menu_items = $this->config->get('config_menu_item');
				} else {
					$menu_items = array();
				}
				foreach($menu_items as $datamenu){
					if($datamenu['additional_menu']=="additional" && $datamenu['status'] !='0')	{
						$data['additional'][] = 'additional';
					}
				}
		} else {
			$config_menu_item = $this->config->get('config_menu_item');
			if(!empty($config_menu_item)) {
				$menu_items = $this->config->get('config_menu_item');
			} else {
				$menu_items = array();
			}
			if (!empty($menu_items)){
				foreach ($menu_items as $key => $value) {
					$sort_menu[$key] = $value['sort_menu'];
				}
				array_multisort($sort_menu, SORT_ASC, $menu_items);
			}

			if(count($menu_items)){
				foreach($menu_items as $datamenu){
					if($datamenu['menu_type']=="link" && $datamenu['status'] !='0')	{
						$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeLink($datamenu);
					}
					if($datamenu['additional_menu']=="additional" && $datamenu['status'] !='0')	{
						$data['additional'][] = 'additional';
					}
					if($datamenu['menu_type']=="information" && $datamenu['status'] !='0')	{
						$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeInformation($datamenu);
					}
					if($datamenu['menu_type']=="manufacturer" && $datamenu['status'] !='0')	{
						$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeManufacturer($datamenu);
					}
					if($datamenu['menu_type']=="product" && $datamenu['status'] !='0'){
						if(!empty($datamenu['products_list'])){
							$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeProduct($datamenu);
						}
					}
					if($datamenu['menu_type']=="category" && $datamenu['status'] !='0')	{
						$data['items'][] = $this->model_extension_module_nsmenu->MegaMenuTypeCategory($datamenu);
					}
					if($datamenu['menu_type']=="html" && $datamenu['status'] !='0')	{
						$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeHtml($datamenu);
					}
					if($datamenu['menu_type']=="freelink" && $datamenu['status'] !='0')	{
						$data['items'][]=$this->model_extension_module_nsmenu->MegaMenuTypeFreeLink($datamenu);
					}
				}
			}

			$menu_items_cache = $data['items'];
			$this->cache->set('newstoremenu.' . (int)$this->config->get('config_language_id') . '.'. (int)$this->config->get('config_store_id'), $menu_items_cache);
		}
		if($config_main_menu_selection =='0') {
			return $this->load->view('common/menu_h', $data);
		}
		if($config_main_menu_selection =='1') {
			return $this->load->view('common/menu_v', $data);
		}
	}
}
