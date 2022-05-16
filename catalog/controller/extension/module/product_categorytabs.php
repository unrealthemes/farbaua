<?php
class ControllerExtensionModuleProductCategorytabs extends Controller {
	public function index($setting) {
		$data['nst_data'] = $this->config->get('nst_data');
		if(isset($data['nst_data']['lazyload_module']) && ($data['nst_data']['lazyload_module'] == 1)){
			$data['lazyload_module'] = true;
			if (isset($data['nst_data']['lazyload_image']) && ($data['nst_data']['lazyload_image'] !='')) {
				$data['lazy_image'] = 'image/' . $data['nst_data']['lazyload_image'];
			} else {
				$data['lazy_image'] = 'image/catalog/lazyload/lazyload.jpg';
			}
		} else {
			$data['lazyload_module'] = false;
		}
		$data['text_select'] = $this->language->get('text_select');	
		$data['config_additional_settings_newstore'] = $this->config->get('config_additional_settings_newstore');
		static $module = 0;
		$this->load->language('extension/module/product_categorytabs');
		$data['show_special_timer_module'] = $this->config->get('config_show_special_timer_module');
		$data['on_off_sticker_special'] = $this->config->get('on_off_sticker_special');
		$data['config_change_icon_sticker_special'] = $this->config->get('config_change_icon_sticker_special');
		$data['on_off_sticker_topbestseller'] = $this->config->get('on_off_sticker_topbestseller');
		$data['config_limit_order_product_topbestseller'] = $this->config->get('config_limit_order_product_topbestseller');
		$data['config_change_icon_sticker_topbestseller'] = $this->config->get('config_change_icon_sticker_topbestseller');
		$data['on_off_sticker_popular'] = $this->config->get('on_off_sticker_popular');
		$data['config_min_quantity_popular'] = $this->config->get('config_min_quantity_popular');
		$data['config_change_icon_sticker_popular'] = $this->config->get('config_change_icon_sticker_popular');
		$data['on_off_sticker_newproduct'] = $this->config->get('on_off_sticker_newproduct');
		$data['config_limit_day_newproduct'] = $this->config->get('config_limit_day_newproduct');
		$data['config_change_icon_sticker_newproduct'] = $this->config->get('config_change_icon_sticker_newproduct');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['category_tab_featured'] = $this->language->get('category_tab_featured');
		$data['category_tab_latest'] = $this->language->get('category_tab_latest');
		$data['category_tab_bestseller'] = $this->language->get('category_tab_bestseller');
		$data['category_tab_special'] = $this->language->get('category_tab_special');
		$data['category_tab_popular'] = $this->language->get('category_tab_popular');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		$data['show_only_featured_product'] = $setting['show_only_featured_product'];
		$data['link_image'] = $setting['link_image'];
		$data['open_popup'] = $setting['link_image_open_popup'];
		$data['status_banner'] = $setting['status_banner'];
		$data['position_banner'] = $setting['position_banner'];
		$banner_to_category = $setting['banner_to_category'];
		if ($banner_to_category && is_file(DIR_IMAGE . $banner_to_category)) {
			$data['banner_to_category'] = $this->model_tool_image->resize($banner_to_category, $setting['width_banner'], $setting['height_banner']);
		} else {
			$data['banner_to_category'] = '';
		}
			$category_info = $this->model_catalog_category->getCategory($setting['category_id']);
		
			$data['heading_title'] = $category_info['name'];
		
			$this->load->model('ns/moduleonoff');
			$data['ns_on_off_module_quick_order'] = $this->model_ns_moduleonoff->getQuickOrderModuleModification();
			$this->load->language('ns/newstorelang');
			$data['required_text_option'] = $this->language->get('required_text_option');
			$data['lang_id'] = $this->config->get('config_language_id');
			$data['ns_on_off_module_quick_order'] = $this->model_ns_moduleonoff->getQuickOrderModuleModification();
			$data['change_text_cart_button_out_of_stock'] = $this->config->get('config_change_text_cart_button_out_of_stock');	
			$data['ns_on_off_tab_featured_model_product'] = $this->config->get('config_on_off_tab_featured_model_product'); 	
			$data['ns_on_off_tab_featured_description'] = $this->config->get('config_on_off_tab_featured_description'); 	
			$data['ns_on_off_tab_featured_slider_additional_image'] = $this->config->get('config_on_off_tab_featured_slider_additional_image'); 	
			$data['ns_select_tab_featured_additional_animate_method'] = $this->config->get('config_select_tab_featured_additional_animate_method'); 	
			$data['ns_on_off_tab_featured_rating'] = $this->config->get('config_on_off_tab_featured_rating'); 	
			$data['ns_on_off_tab_featured_quantity_reviews'] = $this->config->get('config_on_off_tab_featured_quantity_reviews'); 	
			$data['ns_on_off_tab_featured_fastorder'] = $this->config->get('config_on_off_tab_featured_fastorder'); 	
			$data['ns_on_off_tab_featured_wishlist'] = $this->config->get('config_on_off_tab_featured_wishlist'); 	
			$data['ns_on_off_tab_featured_compare'] = $this->config->get('config_on_off_tab_featured_compare'); 				
			$data['ns_on_off_tab_latest_model_product'] = $this->config->get('config_on_off_tab_latest_model_product'); 	
			$data['ns_on_off_tab_latest_description'] = $this->config->get('config_on_off_tab_latest_description'); 	
			$data['ns_on_off_tab_latest_slider_additional_image'] = $this->config->get('config_on_off_tab_latest_slider_additional_image'); 	
			$data['ns_select_tab_latest_additional_animate_method'] = $this->config->get('config_select_tab_latest_additional_animate_method'); 	
			$data['ns_on_off_tab_latest_rating'] = $this->config->get('config_on_off_tab_latest_rating'); 	
			$data['ns_on_off_tab_latest_quantity_reviews'] = $this->config->get('config_on_off_tab_latest_quantity_reviews'); 	
			$data['ns_on_off_tab_latest_fastorder'] = $this->config->get('config_on_off_tab_latest_fastorder'); 	
			$data['ns_on_off_tab_latest_wishlist'] = $this->config->get('config_on_off_tab_latest_wishlist'); 	
			$data['ns_on_off_tab_latest_compare'] = $this->config->get('config_on_off_tab_latest_compare'); 	
			$data['ns_on_off_tab_bestseller_model_product'] = $this->config->get('config_on_off_tab_bestseller_model_product'); 	
			$data['ns_on_off_tab_bestseller_description'] = $this->config->get('config_on_off_tab_bestseller_description'); 	
			$data['ns_on_off_tab_bestseller_slider_additional_image'] = $this->config->get('config_on_off_tab_bestseller_slider_additional_image'); 	
			$data['ns_select_tab_bestseller_additional_animate_method'] = $this->config->get('config_select_tab_bestseller_additional_animate_method'); 	
			$data['ns_on_off_tab_bestseller_rating'] = $this->config->get('config_on_off_tab_bestseller_rating'); 	
			$data['ns_on_off_tab_bestseller_quantity_reviews'] = $this->config->get('config_on_off_tab_bestseller_quantity_reviews'); 	
			$data['ns_on_off_tab_bestseller_fastorder'] = $this->config->get('config_on_off_tab_bestseller_fastorder'); 	
			$data['ns_on_off_tab_bestseller_wishlist'] = $this->config->get('config_on_off_tab_bestseller_wishlist'); 	
			$data['ns_on_off_tab_bestseller_compare'] = $this->config->get('config_on_off_tab_bestseller_compare'); 	
			$data['ns_on_off_tab_special_model_product'] = $this->config->get('config_on_off_tab_special_model_product'); 	
			$data['ns_on_off_tab_special_description'] = $this->config->get('config_on_off_tab_special_description'); 	
			$data['ns_on_off_tab_special_slider_additional_image'] = $this->config->get('config_on_off_tab_special_slider_additional_image'); 	
			$data['ns_select_tab_special_additional_animate_method'] = $this->config->get('config_select_tab_special_additional_animate_method'); 	
			$data['ns_on_off_tab_special_rating'] = $this->config->get('config_on_off_tab_special_rating'); 	
			$data['ns_on_off_tab_special_quantity_reviews'] = $this->config->get('config_on_off_tab_special_quantity_reviews'); 	
			$data['ns_on_off_tab_special_fastorder'] = $this->config->get('config_on_off_tab_special_fastorder'); 	
			$data['ns_on_off_tab_special_wishlist'] = $this->config->get('config_on_off_tab_special_wishlist'); 	
			$data['ns_on_off_tab_special_compare'] = $this->config->get('config_on_off_tab_special_compare'); 	
			$data['ns_on_off_tab_popular_model_product'] = $this->config->get('config_on_off_tab_popular_model_product'); 	
			$data['ns_on_off_tab_popular_description'] = $this->config->get('config_on_off_tab_popular_description'); 	
			$data['ns_on_off_tab_popular_slider_additional_image'] = $this->config->get('config_on_off_tab_popular_slider_additional_image'); 	
			$data['ns_select_tab_popular_additional_animate_method'] = $this->config->get('config_select_tab_popular_additional_animate_method'); 	
			$data['ns_on_off_tab_popular_rating'] = $this->config->get('config_on_off_tab_popular_rating'); 	
			$data['ns_on_off_tab_popular_quantity_reviews'] = $this->config->get('config_on_off_tab_popular_quantity_reviews'); 	
			$data['ns_on_off_tab_popular_fastorder'] = $this->config->get('config_on_off_tab_popular_fastorder'); 	
			$data['ns_on_off_tab_popular_wishlist'] = $this->config->get('config_on_off_tab_popular_wishlist'); 	
			$data['ns_on_off_tab_popular_compare'] = $this->config->get('config_on_off_tab_popular_compare'); 	
			$data['disable_cart_button'] = $this->config->get('config_disable_cart_button'); 
			$data['disable_fastorder_button'] = $this->config->get('config_disable_fastorder_button');
			$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
			$data['config_on_off_latest_quickview'] = $this->config->get('config_on_off_latest_quickview');
			$data['config_on_off_special_quickview'] = $this->config->get('config_on_off_special_quickview');
			$data['config_on_off_bestseller_quickview'] = $this->config->get('config_on_off_bestseller_quickview');
			$data['config_on_off_featured_quickview'] = $this->config->get('config_on_off_featured_quickview');
			$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
			$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
			$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special'); 
			$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct'); 
			$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular'); 
			$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller'); 
			$data['text_reviews_title'] = $this->language->get('text_reviews_title'); 
			$data['background_button_send_fastorder'] = $this->config->get('config_background_button_send_fastorder');
			$data['background_button_open_form_send_order_hover'] = $this->config->get('config_background_button_open_form_send_order_hover');
			$data['background_button_send_fastorder_hover'] = $this->config->get('config_background_button_send_fastorder_hover');
			$data['background_button_open_form_send_order'] = $this->config->get('config_background_button_open_form_send_order');
			$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
			$data['icon_open_form_send_order_size'] = $this->config->get('config_icon_open_form_send_order_size');
			$data['color_button_open_form_send_order'] = $this->config->get('config_color_button_open_form_send_order');
			$data['show_stock_status'] = $this->config->get('config_show_stock_status');
			$config_disable_cart_button_text = $this->config->get('config_disable_cart_button_text');
			if(!empty($config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'])){
				$data['disable_cart_button_text'] = $config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'];
			} else {
				$data['disable_cart_button_text'] = $this->language->get('disable_cart_button_text');
			}
			$data['disable_cart_button'] = $this->config->get('config_disable_cart_button'); 
			$data['disable_fastorder_button'] = $this->config->get('config_disable_fastorder_button');
			$data['show_options'] = $this->config->get('config_show_options_module');
			$show_options_required_featured = $this->config->get('config_show_options_required_module');
				
		
		$this->load->model('extension/module/productcategorytabs');
		$this->load->model('catalog/product');

		$sort_order = explode('-',$setting['sorts_product']);
		
		$filter_data = array(
			'sort' 				 	=> $sort_order[0],
			'filter_category_id'	=> $setting['category_id'],
			'order' 				=> $sort_order[1],
			'start' 				=> 0,
			'limit' 				=> $setting['limit']
		);
			
		$results_latest = $this->model_catalog_product->getProducts($filter_data);
		$results_bestseller = $this->model_extension_module_productcategorytabs->getBestSellerProducts($filter_data);
		$results_special = $this->model_extension_module_productcategorytabs->getProductSpecials($filter_data);
		$results_most_viewed = $this->model_extension_module_productcategorytabs->getProductMostViewed($filter_data);
		
		$data['featured_products'] = array();
		
	if (!empty($setting['product'])) {
		$products = array_slice($setting['product'], 0, (int)$setting['limit']);
		foreach ($products as $product_id) {
			$result = $this->model_catalog_product->getProduct($product_id);
			if ($result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
				$additional_img = array();
				if($data['ns_on_off_tab_featured_slider_additional_image'] =='1'){
				foreach ($results_img as $result_img) {
					if ($result_img['image']) {	
						$image_additional = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
					} else {
						$image_additional = false;
					}
				$additional_img[] = $image_additional;
				}
				}
				$additional_image_hover = '';
				
				if($data['ns_on_off_tab_featured_slider_additional_image'] =='2'){
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);		
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					
					$options = array();
					
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}
					
				$data['featured_products'][] = array(	
					'date_end'			=> $special_date_end, 
					'sticker_new_prod'  => $sticker_new_prod,					
					'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,
					'product_id'  		=> $result['product_id'],
					'options'	  		=> $options,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'additional_img' 	=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    		=> sprintf((int)$result['reviews']),
					'skidka'     		=> round($skidka),
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'thumb'       		=> $image,
					'name'        		=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $price,
					'special'     		=> $special,
					'tax'         		=> $tax,
					'rating'      		=> $rating,
					'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
				}
			}
		}
		$this->load->model('ns/newstore');
		$data['all_prod_featured'] = $this->model_ns_newstore->multi_implode(",", $data['featured_products']);
		$data['all_prod_latest'] = $this->model_ns_newstore->multi_implode(",", $results_latest);
		$data['all_prod_special'] = $this->model_ns_newstore->multi_implode(",", $results_special);
		$data['all_prod_bestseller'] = $this->model_ns_newstore->multi_implode(",", $results_bestseller);
		$data['all_prod_popular'] = $this->model_ns_newstore->multi_implode(",", $results_most_viewed);
		$data['latest_products'] = array();
		if ($results_latest) {
			foreach ($results_latest as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
				$additional_img = array();
				if($data['ns_on_off_tab_latest_slider_additional_image'] =='1'){
				foreach ($results_img as $result_img) {
					if ($result_img['image']) {	
						$image_additional = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
					} else {
						$image_additional = false;
					}
				$additional_img[] = $image_additional;
				}
				}
				$additional_image_hover = '';
				
				if($data['ns_on_off_tab_latest_slider_additional_image'] =='2'){
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);		
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					
					$options = array();
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}
				$data['latest_products'][] = array(		
					'date_end'			=> $special_date_end, 
					'sticker_new_prod'  => $sticker_new_prod,
					'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,		
					'product_id'  		=> $result['product_id'],
					'options'	  		=> $options,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'additional_img' 	=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    		=> sprintf((int)$result['reviews']),
					'skidka'     		=> round($skidka),
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'thumb'       		=> $image,
					'name'        		=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $price,
					'special'     		=> $special,
					'tax'         		=> $tax,
					'rating'      		=> $rating,
					'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
		}
		
		
		$data['bestseller_products'] = array();
		if ($results_bestseller) {
			foreach ($results_bestseller as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
				$additional_img = array();
				if($data['ns_on_off_tab_bestseller_slider_additional_image'] =='1'){
				foreach ($results_img as $result_img) {
					if ($result_img['image']) {	
						$image_additional = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
					} else {
						$image_additional = false;
					}
				$additional_img[] = $image_additional;
				}
				}
				$additional_image_hover = '';
				if($data['ns_on_off_tab_bestseller_slider_additional_image'] =='2'){
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);		
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					
					$options = array();
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}
				$data['bestseller_products'][] = array(
					'date_end'			=> $special_date_end, 
					'sticker_new_prod'  => $sticker_new_prod,
					'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,	
					'product_id'  		=> $result['product_id'],
					'options'	  		=> $options,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'additional_img' 	=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    		=> sprintf((int)$result['reviews']),
					'skidka'     		=> round($skidka),
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'thumb'       		=> $image,
					'name'        		=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $price,
					'special'     		=> $special,
					'tax'         		=> $tax,
					'rating'      		=> $rating,
					'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
		}	
		
		$data['special_products'] = array();
		if ($results_special) {
			foreach ($results_special as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
				$additional_img = array();
				if($data['ns_on_off_tab_special_slider_additional_image'] =='1'){
				foreach ($results_img as $result_img) {
					if ($result_img['image']) {	
						$image_additional = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
					} else {
						$image_additional = false;
					}
				$additional_img[] = $image_additional;
				}
				}
				$additional_image_hover = '';
				if($data['ns_on_off_tab_special_slider_additional_image'] =='2'){
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);		
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					
					$options = array();
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}
				$data['special_products'][] = array(
					'date_end'			=> $special_date_end, 
					'sticker_new_prod'  => $sticker_new_prod,
					'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,	
					'product_id'  		=> $result['product_id'],
					'options'	  		=> $options,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'additional_img' 	=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    		=> sprintf((int)$result['reviews']),
					'skidka'     		=> $skidka,
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'thumb'       		=> $image,
					'name'        		=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $price,
					'special'     		=> $special,
					'tax'         		=> $tax,
					'rating'      		=> $rating,
					'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
		}	
		$data['popular_products'] = array();
		if ($results_most_viewed) {
			foreach ($results_most_viewed as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
				$additional_img = array();
				if($data['ns_on_off_tab_popular_slider_additional_image'] =='1'){
				foreach ($results_img as $result_img) {
					if ($result_img['image']) {	
						$image_additional = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
					} else {
						$image_additional = false;
					}
				$additional_img[] = $image_additional;
				}
				}
				$additional_image_hover = '';
				if($data['ns_on_off_tab_popular_slider_additional_image'] =='2'){
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);		
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					
					$options = array();
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}
				$data['popular_products'][] = array(
					'date_end'			=> $special_date_end, 
					'sticker_new_prod'  => $sticker_new_prod,	
					'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,
					'product_id'  		=> $result['product_id'],
					'options'	  		=> $options,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'additional_img' 	=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    		=> sprintf((int)$result['reviews']),
					'skidka'     		=> round($skidka),
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'thumb'       		=> $image,
					'name'        		=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $price,
					'special'     		=> $special,
					'tax'         		=> $tax,
					'rating'      		=> $rating,
					'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
		}			
			
			$data['module'] = $module++;
			return $this->load->view('extension/module/product_categorytabs', $data);
	}
}