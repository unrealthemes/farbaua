<?php
class ModelExtensionModuleNsmenu extends Model {
	private $lang_id;
    
    public function __construct($registry) {
		parent::__construct($registry);
        $this->lang_id = $this->lang_id();
    }
    private function lang_id() {
        return (int)$this->config->get('config_language_id');
    }
	public function MegaMenuTypeFreeLink($data){
		
		$this->load->model('tool/image');
		$type_link_data['result_freelink'] = array();
		$widthfreelink =((int)$data['sfl']['freelink_img_width']>0)?(int)$data['sfl']['freelink_img_width']:50;
		$heightfreelink =((int)$data['sfl']['freelink_img_height']>0)?(int)$data['sfl']['freelink_img_height']:50;
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['use_add_html']){
			$add_html = html_entity_decode($data['add_html'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		} else { 
			$add_html = "";
		}
		$result['freelink_item'] = array();
		if(!empty($data['banner_item'][$this->lang_id])){		
			foreach($data['banner_item'][$this->lang_id] as $freelink_item){
				
				$thumb="";
				if($data['sfl']['variant_category']=="full_image"){
					if (is_file(DIR_IMAGE . $freelink_item['image'])) {
						$thumb = $this->model_tool_image->resize($freelink_item['image'], $widthfreelink, $heightfreelink);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $widthfreelink, $heightfreelink);			
					}
				}
				if(isset($freelink_item['subcat'])){
					$subcat = $freelink_item['subcat'];
				} else {
					$subcat = array();
				}
				
				$result['freelink_item'][]=array(
					'thumb'  => $thumb,
					'name'  => $freelink_item['title'],
					'href'   => $freelink_item['link'],
					'children'   => $subcat,
					'sort'   => $freelink_item['sort'],
					
					
				);	
				
			}
		}
		if(!empty($result['freelink_item'])){
			foreach ($result['freelink_item'] as $key => $value) {
				$sort_freelink[$key] = $value['sort'];
			}
			array_multisort($sort_freelink, SORT_ASC, $result['freelink_item']);
		}
		
		$type_freelink_data['result_freelink'] = array(
			'type' 				=> "freelink",
			'thumb' 			=> $thumb_menu,
			'add_html' 			=> $add_html,
			'children' 			=> $result['freelink_item'],
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'subtype' 			=> $data['sfl']['variant_category'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);	
		
        return $type_freelink_data['result_freelink'];
	}
	public function MegaMenuTypeLink($data){
		$this->load->model('tool/image');
		$result_menu_link = array();
		if($data['image']){
			$thumb = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb = "";
		}
		
		$type_link_data['result_menu_link'] = array(
			'type' 				=> "link",
			'thumb' 			=> $thumb,
			'children' 			=> false,
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);		
			return $type_link_data['result_menu_link'];     
	}
	public function MegaMenuTypeInformation($data){
		$this->load->model('tool/image');
		$this->load->model('catalog/information');		
		$result_menu_information=array();
		if($data['image']){
			$thumb = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb = "";
		}
		if($data['use_add_html']){
			$add_html = html_entity_decode($data['add_html'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		} else { 
			$add_html = "";
		}
		$result['result_information'] = array();
		if(!empty($data['informations_list'])){
			foreach($data['informations_list'] as $information_id){
				$information = $this->model_catalog_information->getInformation($information_id);
				if($information){
					$result['result_information'][]=array(
						'sort_order' => $information['sort_order'],
						'name'  => $information['title'],
						'href'  => $this->url->link('information/information', 'information_id=' . $information['information_id']),
					);	
				}
			}
		}
		if(!empty($result['result_information'])){
			foreach ($result['result_information'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $result['result_information']);
		}
		
		
		$type_link_data['result_menu_information'] = array(
			'type' 				=> "information",
			'thumb' 			=> $thumb,
			'children' 			=> $result['result_information'],
			'add_html' 			=> $add_html,
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);	
	
		
        return $type_link_data['result_menu_information'];	
	}
	
	public function MegaMenuTypeManufacturer($data){
		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');
		$result_menu_manufacturer=array();
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['use_add_html']) {
			$add_html = html_entity_decode($data['add_html'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		} else { 
			$add_html = "";
		}
		$data['result_manufacturer']=array();
		$data['result_manufacturer_a'] = array();
		if(!empty($data['manufacturers_list'])){
			foreach($data['manufacturers_list'] as $manufacturer_id){
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				if($manufacturer){
					$thumb = "";
					if (is_file(DIR_IMAGE . $manufacturer['image'])) {
						$thumb = $this->model_tool_image->resize($manufacturer['image'], 50, 50);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', 50, 50);			
					}
					$name_m = $manufacturer['name'];

					if (is_numeric(utf8_substr($name_m, 0, 1))) {
						$key = '0 - 9';
					} else {
						$key = utf8_substr(utf8_strtoupper($name_m), 0, 1);
					}
					if (!isset($data['result_manufacturer_a'][$key])) {
						$data['result_manufacturer_a'][$key]['name'] = $key;
					}
					$data['result_manufacturer_a'][$key]['manufacturer'][] = array(
						'name' => $name_m,
						'thumb'	=>	$thumb,
						'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
					);
					$data['result_manufacturer'][] = array(
						'name'  => 	$manufacturer['name'],
						'href'  => 	$this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
						'thumb'	=>	$thumb
					);	
				}
			}
		}
		
		$type_link_data['result_menu_manufacturer'] = array(
			'type' 				=> "manufacturer",
			'thumb' 			=> $thumb_menu,
			'result_manufacturer_a' 			=> $data['result_manufacturer_a'],
			'children' 			=> $data['result_manufacturer'],
			'add_html' 			=> $add_html,
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'type_manuf' 	=> $data['type_manuf'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);	
		
	
		return $type_link_data['result_menu_manufacturer'];		
	}
	public function MegaMenuTypeProduct($data){		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$width = ((int)$data['product_width']>0)?(int)$data['product_width']:50;
		$height = ((int)$data['product_height']>0)?(int)$data['product_height']:50;
		$result_menu_product=array();
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['use_add_html']) {
			$add_html = html_entity_decode($data['add_html'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		} else { 
			$add_html = "";
		}
		$data['result_product']=array();
		if(is_array($data['products_list'])){
			foreach($data['products_list'] as $product_id){
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if($product_info){
					$thumb = "";				
					if (is_file(DIR_IMAGE . $product_info['image'])) {
						$thumb = $this->model_tool_image->resize($product_info['image'], $width, $height);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $width, $height);			
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $currency);
					} else {
						$data['price'] = false;
					}
									
					if ((float)$product_info['special']) {
						$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $currency);
					} else {
						$data['special'] = false;
					}	
					$data['result_product'][]=array(
						'name'  => $product_info['name'],
						'href'  => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])	,
						'thumb'	=> $thumb,
						'price'	=> $data['price'],
						'special'=>$data['special']
					);	
				}	
			}
		}	
		
		$type_link_data['result_menu_product'] = array(
			'type' 				=> "product",
			'children' 			=> $data['result_product'],
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'add_html' 			=> $add_html,
			'thumb' 			=> $thumb_menu,
		);
					
		return $type_link_data['result_menu_product'];
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
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT c.`category_id`,c.`image`, cd2.`name` FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.`category_id` = cd2.`category_id`) WHERE c.`category_id` = " . (int)$category_id . " AND cd2.language_id = " . $this->lang_id . "");
		return $query->row;
	}
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT c.`category_id`, cd.`name` FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
		WHERE c.parent_id = '" . (int)$parent_id . "' 
		AND cd.language_id = '" . $this->lang_id . "' 
		AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  
		AND c.status = '1' ORDER BY c.sort_order, cd.name ASC");
		return $query->rows;
	}
	public function MegaMenuTypeCategory($data){
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		
		$width=((int)$data['category_img_width']>0)?(int)$data['category_img_width']:50;
		$height=((int)$data['category_img_height']>0)?(int)$data['category_img_height']:50;
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['use_add_html']) {
			$add_html = html_entity_decode($data['add_html'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		} else { 
			$add_html = "";
		}
		
		$result_category=array();
		if(!empty($data['category_list'])){
			$data_categories_list = $data['category_list'];
		} else {
			$data_categories_list = '';
		}
		
		if (!empty($data_categories_list)){
			foreach ($data_categories_list as $key => $value) {
				$sort_menu[$key] = $data['sort_category'][$value];
			} 
			array_multisort($sort_menu, SORT_ASC, $data_categories_list);
		}
		
		if(is_array($data_categories_list)){
			$category_list = array();
			foreach($data_categories_list as $cat){
				$category = $this->getCategory($cat);
				if($category){
					$category_list[]=$category;
				}

			}
			
			foreach($category_list as $category){
				if($category){
					$thumb="";
					if($data['variant_category']=="full_image"){
						if (is_file(DIR_IMAGE . $category['image'])) {
						$thumb = $this->model_tool_image->resize($category['image'], $width, $height);
						} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $width, $height);			
						}
					}		

					$children_data=array();
					if($data['show_sub_category']){
						$children = $this->getCategories($category['category_id']);
						if($children){
							foreach ($children as $child) {
								
								$child_4level_data=array();
								$child_4level = $this->getCategories($child['category_id']);
								if($child_4level){
									foreach ($child_4level as $c4level) {
										$path_4level = $this->getCategoryPath($c4level['category_id']);		
										
										$child_4level_data[] = array(
											'name'  => $c4level['name'],
											'href'  => $this->url->link('product/category', 'path=' . $path_4level)
										);
									}
								}	
								$path=$this->getCategoryPath($child['category_id']);		
								
								if(!empty($data['sticker'][$child['category_id']])) {
									$sticker_category = $data['sticker'][$child['category_id']];
								} else {
									$sticker_category = '0';
								}
								
								$children_data[] = array(
									'child_4level_data'  => $child_4level_data,
									'sticker_category'  => $sticker_category,
									'name'  => $child['name'],
									'href'  => $this->url->link('product/category', 'path=' . $path)
								);
							}	
							
						}
					}
					
					$path = $this->getCategoryPath($category['category_id']);

					if(isset($data['sticker'][$category['category_id']])){
						$sticker_category = $data['sticker'][$category['category_id']];
					} else {
						$sticker_category = '0';
					}
					$result_category[]=array(
						'name'  			=> $category['name'],
						'sticker_category'  => $sticker_category,
						'href'  			=> $this->url->link('product/category', 'path=' . $path),
						'children'  		=> $children_data,
						'thumb'				=> $thumb
					);	
				}
			}
		}
		
		
		$result_menu_category = array();
		$type_link_data['result_menu_category'] = array(
			'type' 				=> "category",
			'children' 			=> $result_category,
			'subtype' 			=> $data['variant_category'],
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'add_html' 			=> $add_html,
			'thumb' 			=> $thumb_menu,
		);
		return $type_link_data['result_menu_category'];
		
	}
	public function MegaMenuTypeHtml($data){
		$this->load->model('tool/image');
		$result_menu_html = array();
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		$html_block = html_entity_decode($data['html_block'][$this->lang_id], ENT_QUOTES, 'UTF-8');
		$type_link_data['result_menu_html'] = array(
			'type' 				=> "html",
			'children'			=> true,
			'href' 				=> (trim($data['link'][$this->lang_id]))?$data['link'][$this->lang_id]:"javascript:void(0);",
			'name' 				=> $data['namemenu'],
			'sticker_parent' 	=> $data['sticker_parent'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'html' 				=> $html_block,
			'thumb' 			=> $thumb_menu,
		);
			return $type_link_data['result_menu_html'];
	}
 
}