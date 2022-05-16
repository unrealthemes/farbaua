<?php
class ControllerExtensionModuleProductviewed extends Controller {
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
		$this->load->language('extension/module/productviewed');
		
		/*on_off*/
		$this->load->model('ns/moduleonoff');
		$data['ns_on_off_module_quick_order'] = $this->model_ns_moduleonoff->getQuickOrderModuleModification();
		$this->load->language('ns/newstorelang');
		$data['required_text_option'] = $this->language->get('required_text_option');
		$data['ns_on_off_featured_model_product'] = $this->config->get('config_on_off_featured_model_product'); 
		$data['ns_on_off_featured_description'] = $this->config->get('config_on_off_featured_description'); 
		$data['ns_on_off_featured_slider_additional_image'] = $this->config->get('config_on_off_featured_slider_additional_image'); 
		$data['ns_select_featured_additional_animate_method'] = $this->config->get('config_select_featured_additional_animate_method'); 
		$data['ns_on_off_featured_rating'] = $this->config->get('config_on_off_featured_rating'); 
		$data['ns_on_off_featured_quantity_reviews'] = $this->config->get('config_on_off_featured_quantity_reviews'); 
		$data['ns_on_off_featured_fastorder'] = $this->config->get('config_on_off_featured_fastorder'); 	
		$data['ns_on_off_featured_wishlist'] = $this->config->get('config_on_off_featured_wishlist');
		$data['ns_on_off_featured_compare'] = $this->config->get('config_on_off_featured_compare');
		$data['config_on_off_featured_quickview'] = $this->config->get('config_on_off_featured_quickview');
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special'); 
		$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct'); 
		$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular'); 
		$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller'); 
		$this->language->load('product/product');
		$data['icon_send_fastorder'] = $this->config->get('config_icon_send_fastorder');
		$data['background_button_send_fastorder'] = $this->config->get('config_background_button_send_fastorder');
		$data['background_button_open_form_send_order_hover'] = $this->config->get('config_background_button_open_form_send_order_hover');
		$data['background_button_send_fastorder_hover'] = $this->config->get('config_background_button_send_fastorder_hover');
		$data['background_button_open_form_send_order'] = $this->config->get('config_background_button_open_form_send_order');
		$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
		$data['icon_open_form_send_order_size'] = $this->config->get('config_icon_open_form_send_order_size');
		$data['color_button_open_form_send_order'] = $this->config->get('config_color_button_open_form_send_order');
		$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['text_reviews_title'] = $this->language->get('text_reviews_title');
		$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
		$data['change_text_cart_button_out_of_stock'] = $this->config->get('config_change_text_cart_button_out_of_stock');	
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
		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
		if (!empty($this->session->data['productviewed']) && count($this->session->data['productviewed']) > 1) {
	
			krsort($this->session->data['productviewed']);	
			$productviewed = array_unique($this->session->data['productviewed']);
			$products = array_slice($productviewed, 0, $setting['limit']);
			$this->load->model('ns/newstore');
			$data['all_prod'] = $this->model_ns_newstore->multi_implode(",", $products);
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
					$results_img = $this->model_catalog_product->getProductImages($product_info['product_id']);
					$additional_img = array();
					if($data['ns_on_off_featured_slider_additional_image'] ==''){
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
					
					if($data['ns_on_off_featured_slider_additional_image'] =='2'){
						foreach ($results_img as $key => $result_img) {
							if ($key < 1) {
								$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);								
							}
						}
					}
			if ((float)$product_info['special']) {
				$price2 = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				$special2 = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				$skidka = $special2/($price2/100)-100;
			} else {
				$skidka = "";
			}
			$top_bestsellers = $this->model_catalog_product->getTopSeller($product_id);
			
			$product_quantity = $product_info['quantity'];
			$stock_status = $product_info['stock_status'];
		
					if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
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
						$price_no_format = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
					} else {
						$price_no_format = false;
					}

					if ((float)$product_info['special']) {
						$special_no_format = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
					} else {
						$special_no_format = false;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					$data['products'][] = array(
						'date_end'			=> $special_date_end, 	
						'options'	  		=> $options,
						'additional_image_hover' => $additional_image_hover,
						'product_quantity' 	=> $product_quantity,
						'stock_status' 		=> $stock_status,
						'additional_img' 	=> $additional_img,
						'reviews'    		=> sprintf((int)$product_info['reviews']),
						'skidka'     		=> round((int)$skidka),
						'model'     		=> $product_info['model'],
						'date_available'	=> $product_info['date_available'],
						'viewed'	 		=> $product_info['viewed'],
						'top_bestsellers'	=> $top_bestsellers['total'],
						'minimum'     		=> ($product_info['minimum'] > 0) ? $product_info['minimum'] : 1,
						'price_no_format' 	=> $price_no_format,
						'special_no_format' => $special_no_format,	
						'product_id'  		=> $product_info['product_id'],
						'thumb'       		=> $image,
						'name'        		=> $product_info['name'],
						'description' 		=> utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       		=> $price,
						'special'     		=> $special,
						'tax'         		=> $tax,
						'rating'      		=> $rating,
						'href'        		=> $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/productviewed', $data);
		}
	}
}
