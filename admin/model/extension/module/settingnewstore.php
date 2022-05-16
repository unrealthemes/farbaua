<?php
class ModelExtensionModuleSettingnewstore extends Model {
	public function getWallCategories($parent_id = 0) {
		
	}	
	private $land;
    
    public function __construct($registry) {
		parent::__construct($registry);
        $this->land = $this->land();
    }
    
    private function land() {
        return (int)$this->config->get('config_language_id');
    }
	public function getCategorys($category) {
		if($category){
			$query = $this->db->query("SELECT c.`category_id`, cd2.`name` FROM " . DB_PREFIX . "category c 
			LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) 
			WHERE c.`category_id` IN (".implode(',',$category).") AND cd2.language_id = " . $this->land . "");
			return $query->rows;
		}
	}	
	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT c.`category_id`, cd2.`name` FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.`category_id` = cd2.`category_id`) WHERE c.`category_id` = " . (int)$category_id . " AND cd2.language_id = " . $this->land . "");

		return $query->row;
	}
	public function getInformations() {
			$sql = "SELECT i.`information_id`,id.`title` FROM " . DB_PREFIX . "information i 
			LEFT JOIN " . DB_PREFIX . "information_description id ON (i.`information_id` = id.`information_id`) 
			WHERE id.language_id = " . $this->land . "";

			$query = $this->db->query($sql);

			return $query->rows;
		
	}
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT p.`product_id`, pd.`name` FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = " . $this->land . "");

		return $query->row;
	}
	
	public function saveSetting($data) {
		$store_id = 0;			
		$code = 'settingnewstore';					
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."'");
		
		foreach ($data as $key => $value) {
			if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					if (VERSION < 2.1) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}
				}	
			}
	}
	
	public function saveSettingMenu($data) {
		$store_id = 0;			
		$code = 'settingnewstore';					
		$config_menu_item = 'config_menu_item';					
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '". $store_id ."' AND `code` = '". $code ."' AND `key`='". $config_menu_item ."'");
		
		foreach ($data as $key => $value) {
			if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					if (VERSION < 2.1) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', serialized = '1'");
					}
				}	
			}
	}
	public function generateCss($data){
		if(!isset($data['csseditor']))
		{
			return;
		}
		$file = DIR_CATALOG."view/theme/newstore/stylesheet/csseditor.css";
		$dataStr = "";
		$catalog = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;
		
		if(isset($data['nst_data']['bg_mode_pos_2']) && ($data['nst_data']['bg_mode_pos_2'] == '1') && ($data['nst_data']['img_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 {background-image:url(' . $catalog . 'image/'.$data['nst_data']['img_pos_2'].');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_2']) && ($data['nst_data']['bg_mode_pos_2'] == '2') && ($data['nst_data']['bg_mode_color_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 {background-color:'.$data['nst_data']['bg_mode_color_pos_2'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_2']) && ($data['nst_data']['title_color_pos_2'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_2 .title-module span, .bg_mode_pos_2 h3{color:'.$data['nst_data']['title_color_pos_2'].' !important;}}';
		}
		
		if(isset($data['nst_data']['bg_mode_pos_22']) && ($data['nst_data']['bg_mode_pos_22'] == '1') && ($data['nst_data']['img_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 {background-image:url(' . $catalog . 'image/'.$data['nst_data']['img_pos_22'].');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_22']) && ($data['nst_data']['bg_mode_pos_22'] == '2') && ($data['nst_data']['bg_mode_color_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 {background-color:'.$data['nst_data']['bg_mode_color_pos_22'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_22']) && ($data['nst_data']['title_color_pos_22'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_22 .title-module span, .bg_mode_pos_22 h3{color:'.$data['nst_data']['title_color_pos_22'].' !important;}}';
		}
		
		if(isset($data['nst_data']['bg_mode_pos_11']) && ($data['nst_data']['bg_mode_pos_11'] == '1') && ($data['nst_data']['img_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 {background-image:url(' . $catalog . 'image/'.$data['nst_data']['img_pos_11'].');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_11']) && ($data['nst_data']['bg_mode_pos_11'] == '2') && ($data['nst_data']['bg_mode_color_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 {background-color:'.$data['nst_data']['bg_mode_color_pos_11'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_11']) && ($data['nst_data']['title_color_pos_11'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_11 .title-module span, .bg_mode_pos_11 h3{color:'.$data['nst_data']['title_color_pos_11'].' !important;}}';
		}
		
		if(isset($data['nst_data']['bg_mode_pos_15']) && ($data['nst_data']['bg_mode_pos_15'] == '1') && ($data['nst_data']['img_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 {background-image:url(' . $catalog . 'image/'.$data['nst_data']['img_pos_15'].');}}';
		}
		if(isset($data['nst_data']['bg_mode_pos_15']) && ($data['nst_data']['bg_mode_pos_15'] == '2') && ($data['nst_data']['bg_mode_color_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 {background-color:'.$data['nst_data']['bg_mode_color_pos_15'].';}}';
		}
		if(isset($data['nst_data']['title_color_pos_15']) && ($data['nst_data']['title_color_pos_15'] !='')){
			$dataStr .= '@media (min-width: 768px){.bg_mode_pos_15 .title-module span, .bg_mode_pos_15 h3{color:'.$data['nst_data']['title_color_pos_15'].' !important;}}';
		}
		foreach($data['csseditor'] as $name => $item){
			switch($name){
				case 'top_background':
				if($item['value']){
					$dataStr .= '#top{background-color:'.$item['value'].'; border-color:'.$item['value'].'} #top .container{background-color:'. $item['value'] .';}';
					break;
				}
				case 'top_color_link':
				if($item['value']){
					$dataStr .= '#top .btn-link, #top-links li, #top-links a {color:'.$item['value'].'}';
					break;
				}
				case 'top_color_link_hover':
				if($item['value']){
					$dataStr .= '#top .btn-link:hover, #top-links a:hover{color:'.$item['value'].'}';
					break;
				}
				case 'header_search_category_select_background':
				if($item['value']){
					$dataStr .= '.btn-search-select{background-color:'. $item['value'].'}';
					break;
				}
				case 'header_search_category_select_border_color':
				if($item['value']){
					$dataStr .= '.btn-search-select{border:2px solid '. $item['value'].'; box-shadow:none;}';
					break;
				}
				case 'header_search_category_select_color_text':
				if($item['value']){
					$dataStr .= '.btn-search-select{color:'. $item['value'].'}';
					break;
				}
				case 'header_search_category_select_color_text_hover':
				if($item['value']){
					$dataStr .= '.btn-search-select:hover{color:'. $item['value'].'}';
					break;
				}
				case 'header_search_btn_background':
				if($item['value']){
					$dataStr .= '.btn-search{border-color:'.$item['value'].'; background-color:'. $item['value'].';}';
					break;
				}
				case 'header_search_btn_color':
				if($item['value']){
					$dataStr .= '.btn-search{color:'.$item['value'].'}';
					break;
				}
				case 'header_search_btn_color_hover':
				if($item['value']){
					$dataStr .= '.btn-search:hover, .btn-search:focus,.btn-search.active{color:'.$item['value'].';}';
					break;
				}
				case 'header_search_btn_border_color':
				if($item['value']){
					$dataStr .= '.btn-search{box-shadow:none;}';										
					$dataStr .= '#search .button_search{border:2px solid '.$item['value'].'}';
					$dataStr .= '.btn-search:hover, .btn-search:active {border-color: '.$item['value'].'; box-shadow:none;}';					
					break;
				}
				case 'header_search_input_border_color':
				if($item['value']){
					$dataStr .= '#search .form-control:focus {border-color:'.$item['value'].'} #search .input-lg{border:2px solid '.$item['value'].'}';
					break;
				}
				
				
				case 'header_cart_background':
				if($item['value']){
					$dataStr .= '#cart > .btn{background:'.$item['value'].'}';
					break;
				}
				case 'header_cart_background_hover':
				if($item['value']){
					$dataStr .= '#cart > .btn:hover, #cart > .btn:active, #cart > .btn.active, #cart > .btn.disabled, #cart > .btn[disabled],#cart.open > .btn {background:'.$item['value'].';border: 2px solid '.$item['value'].'}';
					break;
				}
				case 'header_cart_border_color':
				if($item['value']){
					$dataStr .= '#cart > .btn{border: 2px solid '.$item['value'].'}';
					break;
				}
				case 'header_cart_color_text':
				if($item['value']){
					$dataStr .= '#cart > .btn .cart-total{color:'.$item['value'].'}';
					$dataStr .= '#cart > .btn > .car-down{color:'.$item['value'].'}';
					$dataStr .= '#cart > .btn > .shop-bag{color:'.$item['value'].'}';
					$dataStr .= '#cart > .btn .cart-total b{color:'.$item['value'].'}';
					break;
				}
				case 'header_cart_color_text_hover':
				if($item['value']){
					$dataStr .= '#cart > .btn:hover,.#cart > .btn:focus,#cart.open > .btn{color:'.$item['value'].'}';
					break;
				}
				
				case 'link_pagemenu_background':
				if($item['value']){
					$dataStr .= '#additional-menu{background-color:'.$item['value'].'}';
					break;
				}
				case 'link_pagemenu_border_color':
				if($item['value']){
					$dataStr .= '#additional-menu{border-color:'.$item['value'].' !important}';
					break;
				}
				case 'link_pagemenu_color_text':
				if($item['value']){
					$dataStr .= '#additional-menu .nav > li > a{color:'.$item['value'].'}';
					break;
				}
				
				case 'general_menu_background':
				if($item['value']){
					$dataStr .= '.menu-general-ns .box-heading,.btn-menu{background-color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu{background-color:'.$item['value'].'}';
					break;
				}
				case 'general_menu_background_hover':
				if($item['value']){
					$dataStr .= '.menu-general-ns .box-heading:hover,.btn-menu:hover,.btn-menu:focus{background-color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu .nav > li:hover > a, #horizontal-menu .nav > li.open > a {background-color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu .menu-static-width > .dropdown-menu{border-top:3px solid '.$item['value'].'}';
					$dataStr .= '#horizontal-menu .menu-full-width > .dropdown-menu{border-top:3px solid '.$item['value'].'}';
					break;
				}
				case 'general_menu_border_color':
				if($item['value']){
					$dataStr .= '.menu-general-ns .box-heading,.btn-menu{border-color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu{border-top: 1px solid '.$item['value'].' !important; border-bottom:1px solid '.$item['value'].' !important;}';
					break;
				}
				case 'general_menu_border_color_hover':
				if($item['value']){
					$dataStr .= '.menu-general-ns .box-heading:hover,.btn-menu:hover, .btn-menu:focus{border-color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu:hover{border-top: 1px solid '.$item['value'].' !important; border-bottom:1px solid '.$item['value'].' !important;}';
					break;
				}
				case 'general_menu_color_text':
				if($item['value']){
					$dataStr .= '.menu-general-ns .box-heading,#menu .btn{color:'.$item['value'].'}';
					
					break;
				}
				case 'general_menu_link_color_text':
				if($item['value']){
					$dataStr .= '#menu a{color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu .nav > li > a{color:'.$item['value'].'}';
					break;
				}
				case 'general_menu_link_color_text_hover':
				if($item['value']){
					$dataStr .= '#menu #menu-list  > li > a.dropdown-toggle:hover{color:'.$item['value'].'}';
					$dataStr .= '#menu #menu-list  > li > a.parent-link.hover{color:'.$item['value'].'}';
					$dataStr .= '#menu #menu-list > li .dropdown-menu-simple .nsmenu-haschild > li > a:hover,#menu #menu-list > li .dropdown-menu-simple .nsmenu-haschild > li > a.hover{color:'.$item['value'].'}';
					$dataStr .= '#menu #menu-list > li  .dropdown-menu-information .dropdown-inner .nsmenu-haschild li > a:hover,#menu #menu-list > li  .dropdown-menu-information .dropdown-inner .nsmenu-haschild li > a.hover{color:'.$item['value'].'}';
					$dataStr .= '.container-accordion-menu .parent-link:hover,.container-accordion-menu .parent-link:hover .arrow,.container-accordion-menu .parent-link.hover,.container-accordion-menu .parent-link.hover .arrow{color:'.$item['value'].'}';
					$dataStr .= '.container-accordion-menu .sub-category-link:hover,.container-accordion-menu .sub-category-link:hover .arrow,.container-accordion-menu .sub-category-link.hover,.container-accordion-menu .sub-category-link.hover .arrow{color:'.$item['value'].'}';
					$dataStr .= '.container-accordion-menu .child_children_link > a:hover{color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu .dropdown-inner .child a:hover{color:'.$item['value'].'}';
					$dataStr .= '#horizontal-menu .nav > li:hover > a, #horizontal-menu .nav > li.open > a{color:'.$item['value'].'}';
					break;
				}
				
				case 'btn_carousel_background':
				if($item['value']){
					$dataStr .= '.btn-carousel-module{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_carousel_background_hover':
				if($item['value']){
					$dataStr .= '.btn-carousel-module:hover{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_carousel_color_text':
				if($item['value']){
					$dataStr .= '.btn-carousel-module{color:'.$item['value'].' !important;}';
					break;
				}
				case 'btn_carousel_color_text_hover':
				if($item['value']){
					$dataStr .= '.btn-carousel-module:hover{color:'.$item['value'].' !important;}';
					break;
				}
				case 'btn_carousel_border_color':
				if($item['value']){
					$dataStr .= '.btn-carousel-module{border:1px solid '.$item['value'].'}';
					break;
				}
				
				case 'box_heading_module_background':
				if($item['value']){
					$dataStr .= '.container-module .title-module:before, .categorywall-container .title-module:before{border-bottom:2px solid '.$item['value'].'}';					
					break;
				}
				case 'box_heading_module_background_right':
				if($item['value']){
					$dataStr .= '.container-module .title-module:after, .categorywall-container .title-module:after{border-bottom:2px solid '.$item['value'].'}';
					break;
				}
				case 'box_heading_module_color_text':
				if($item['value']){
					$dataStr .= '.container-module .title-module span{color:'.$item['value'].'}';
					break;
				}
				//GENERAL BUTTON
				case 'btn_general_background':
				if($item['value']){
					$dataStr .= '.btn-general{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_background_hover_block':
				if($item['value']){
					$dataStr .= '.product-list .product-thumb:hover .btn-general,.product-grid .product-thumb:hover .btn-general,.container-module-productany .product-thumb:hover .btn-general,.container-module .product-thumb:hover .btn-general {background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_background_hover_button':
				if($item['value']){
					$dataStr .= '.btn-general:hover, .btn-general:active{background-color:'.$item['value'].' !important}';
					break;
				}
				case 'btn_general_border_color':
				if($item['value']){
					$dataStr .= '.btn-general{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_border_color_hover_block':
				if($item['value']){
					$dataStr .= '.product-list .product-thumb:hover .btn-general,.product-grid .product-thumb:hover .btn-general,.container-module-productany .product-thumb:hover .btn-general,.container-module .product-thumb:hover .btn-general {border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_border_color_hover_button':
				if($item['value']){
					$dataStr .= '.btn-general:hover, .btn-general:active{border-color:'.$item['value'].' !important}';
					break;
				}
				case 'btn_general_color':
				if($item['value']){
					$dataStr .= '.btn-general{color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_color_hover_block':
				if($item['value']){
					$dataStr .= '.product-list .product-thumb:hover .btn-general,.product-grid .product-thumb:hover .btn-general,.container-module-productany .product-thumb:hover .btn-general,.container-module .product-thumb:hover .btn-general {color:'.$item['value'].'}';
					break;
				}
				case 'btn_general_color_hover_button':
				if($item['value']){
					$dataStr .= '.btn-general:hover, .btn-general:active{color:'.$item['value'].' !important}';
					break;
				}
				
				case 'btn_fastorder_background':
				if($item['value']){
					$dataStr .= '.btn-fastorder{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_fastorder_background_hover':
				if($item['value']){
					$dataStr .= '.btn-fastorder:hover, .btn-fastorder:focus{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_fastorder_border_color':
				if($item['value']){
					$dataStr .= '.btn-fastorder{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_fastorder_border_color_hover':
				if($item['value']){
					$dataStr .= '.btn-fastorder:hover, .btn-fastorder:focus{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_fastorder_color_text':
				if($item['value']){
					$dataStr .= '.btn-fastorder{color:'.$item['value'].'}';
					break;
				}
				case 'btn_fastorder_color_text_hover':
				if($item['value']){
					$dataStr .= '.btn-fastorder:hover, .btn-fastorder:focus{color:'.$item['value'].'}';
					break;
				}
				case 'btn_wishlist_background':
				if($item['value']){
					$dataStr .= '.btn-wishlist{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_wishlist_background_hover':
				if($item['value']){
					$dataStr .= '.btn-wishlist:hover, .btn-wishlist:focus{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_wishlist_border_color':
				if($item['value']){
					$dataStr .= '.btn-wishlist{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_wishlist_border_color_hover':
				if($item['value']){
					$dataStr .= '.btn-wishlist:hover, .btn-wishlist:focus{border-color:'.$item['value'].'}';
					break;
				}
				
				case 'btn_wishlist_color_text':
				if($item['value']){
					$dataStr .= '.btn-wishlist{color:'.$item['value'].'}';
					break;
				}
				case 'btn_wishlist_color_text_hover':
				if($item['value']){
					$dataStr .= '.btn-wishlist:hover, .btn-wishlist:focus{color:'.$item['value'].'}';
					break;
				}
				
				case 'btn_compare_background':
				if($item['value']){
					$dataStr .= '.btn-compare{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_compare_background_hover':
				if($item['value']){
					$dataStr .= '.btn-compare:hover, .btn-compare:focus{background-color:'.$item['value'].'}';
					break;
				}
				case 'btn_compare_border_color':
				if($item['value']){
					$dataStr .= '.btn-compare{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_compare_border_color_hover':
				if($item['value']){
					$dataStr .= '.btn-compare:hover, .btn-compare:focus{border-color:'.$item['value'].'}';
					break;
				}
				case 'btn_compare_color_text':
				if($item['value']){
					$dataStr .= '.btn-compare{color:'.$item['value'].'}';
					break;
				}
				case 'btn_compare_color_text_hover':
				if($item['value']){
					$dataStr .= '.btn-compare:hover, .btn-compare:focus{color:'.$item['value'].'}';
					break;
				}
				case 'footer_background':
				if($item['value']){
					$dataStr .= 'footer .footer-top,footer .footer-center,footer .footer-bottom{background-color:'.$item['value'].'}';
					break;
				}
				case 'footer_border_top':
				if($item['value']){
					$dataStr .= 'footer{border-top:3px solid '.$item['value'].'}';
					break;
				}
				case 'footer_color_h5_title_block':
				if($item['value']){
					$dataStr .= 'footer h3{color:'.$item['value'].'}';
					break;
				}
				case 'footer_color_link':
				if($item['value']){
					$dataStr .= 'footer a, footer{color: '.$item['value'].'}';
					break;
				}
				case 'footer_color_link_hover':
				if($item['value']){
					$dataStr .= 'footer a:hover{color: '.$item['value'].'}';
					break;
				}
			}
		}
		file_put_contents($file,$dataStr);
	}
	
	
}