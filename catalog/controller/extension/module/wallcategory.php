<?php
class ControllerExtensionModuleWallcategory extends Controller {
	public function index($setting) {
		$this->document->addScript('catalog/view/theme/newstore/js/jquery.scrollpanel-0.5.0.min.js');
		$this->document->addScript('catalog/view/theme/newstore/js/jquery.mousewheel-3.1.3.js');
		if(isset($setting['width']) && !empty($setting['width'])){
			$width = $setting['width'];
		} else {
			$width = 150;
		}
		if(isset($setting['height']) && !empty($setting['height'])){
			$height = $setting['height'];
		} else {
			$height = 150;
		}
		static $module = 0;
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$limit_sub_category = $setting['limit'];
		
		$wall_cache = $this->cache->get('wallcategory.'. $module .'.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));
	if (!empty($wall_cache)) {
		$data['categories'] = $wall_cache;
	} else {
		if (isset($setting['wall_category'])) {
			$categories = $setting['wall_category'];
		} else {
			$categories = array();
		}
		if (!empty($categories)){
			foreach ($categories as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order, SORT_ASC, $categories);
		}

		$data['categories'] = array();
		foreach($categories as $category){
            $category_info = $this->model_catalog_category->getCategory($category['category']);

			
			$data['subcategories'] = array();
			$subcategories = $this->model_catalog_category->getCategories($category['category']);
			if($subcategories){
				$subcategories = array_slice($subcategories, 0, $limit_sub_category);
			
				foreach($subcategories as $subcategory){					
					$path = $this->getCategoryPath($subcategory['category_id']);		
					$data['subcategories'][] = array(
						'category_id' 	=> $subcategory['category_id'],
						'name'        	=> $subcategory['name'],
						'href'  	    => $this->url->link('product/category', 'path=' . $path),	
					);
				}
			}
			if ($category['image']) {
				$image_category = $this->model_tool_image->resize($category['image'], $width, $height);
			} else {
				$image_category = $this->model_tool_image->resize('placeholder.png', $width, $height);
			}	
			$path = $this->getCategoryPath($category['category']);	
			$data['categories'][] = array(
				'subcategories' => $data['subcategories'],
				'category_id' => $category_info['category_id'],
				'name' 		  => $category_info['name'],						
				'href'  	  => $this->url->link('product/category', 'path=' . $path),			
				'image' 	  => $image_category,				
			);			
               
        }
		$wall_cache = $data['categories'];	
		$this->cache->set('wallcategory.'. $module .'.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'), $wall_cache);	
	}
	
	
		$this->load->model('catalog/manufacturer');
	$manufacturer_cache = $this->cache->get('manufacturer.'. $module .'.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));
	if (!empty($manufacturer_cache)) {
		$data['manufacturers'] = $manufacturer_cache;
	} else {
		if (isset($setting['wall_manufactures'])) {
			$wall_manufactures = $setting['wall_manufactures'];
		} else {
			$wall_manufactures = '';
		}
		if (!empty($wall_manufactures)){
			foreach ($wall_manufactures as $key => $value) {
				$sort_order_manufactures[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order_manufactures, SORT_ASC, $wall_manufactures);
		}
		$data['manufacturers'] = array();

		if($wall_manufactures) {
			foreach ($wall_manufactures as $manufacturer) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer['manufacturer_id']);

				if($manufacturer_info) {
					$data['manufacturers'][] = array(
						'manufacturer_id' => $manufacturer_info['manufacturer_id'],
						'name'            => $manufacturer_info['name'],
						'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id']),
						'thumb'           => $this->model_tool_image->resize(($manufacturer['image']=='' ? 'no_image.jpg' : $manufacturer['image']), $width, $height)
						);
				}
			}
		}
		$manufacturer_cache = $data['manufacturers'];	
		$this->cache->set('manufacturer.'. $module .'.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'), $manufacturer_cache);	
	}
		$data['module'] = $module++;
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['heading_title'] = $setting['title_name'];	
		return $this->load->view('extension/module/wallcategory', $data);
	}
	public function getCategoryPath($category_id){
		$path = '';
		$category = $this->db->query("SELECT c.`category_id`,c.`parent_id` FROM " . DB_PREFIX . "category c WHERE c.`category_id` = " .(int)$category_id."");
		if($category->row['parent_id'] != 0){
			$path .= $this->getCategoryPath($category->row['parent_id']) . '_';
		}
		$path .= $category->row['category_id'];
 
		return $path;
	}
	 
}