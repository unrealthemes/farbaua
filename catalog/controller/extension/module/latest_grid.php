<?php
class ControllerExtensionModuleLatestGrid extends Controller {
	public function getNextPage() {
		if (isset($this->request->post['setting'])) {
			$setting = unserialize(base64_decode($this->request->post['setting']));
		}
		$result_html = $this->index($setting);


		$this->response->setOutput($result_html);
	}
	public function index($setting) {
		$detect = new Mobile_Detect();
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		if($deviceType == 'phone') {
			$setting['limit'] = $setting['limit_mob'];
		}
		if($deviceType == 'tablet') {
			$setting['limit'] = $setting['limit_tablet'];
		}
		if($deviceType == 'computer') {
			$setting['limit'] = $setting['limit'];
		}
		$limit_max = isset($setting['limit_max']) ? $setting['limit_max'] : 10;
		$data['status_showmore'] = isset($setting['status_showmore']) ? $setting['status_showmore'] : 0;
		$setting['filter_sub_category'] = isset($setting['filter_sub_category']) ? $setting['filter_sub_category'] : 0;
		$setting['category_id'] = isset($setting['category_id']) ? $setting['category_id'] : 0;
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
		static $module = 0;
		$this->load->language('extension/module/latest_grid');
		$this->load->model('ns/moduleonoff');

		$this->load->language('ns/newstorelang');
		$data['required_text_option'] = $this->language->get('required_text_option');
		$data['text_showmore'] = $this->language->get('text_showmore');
		$data['lang_id'] = $this->config->get('config_language_id');
		$title_module = isset($setting['title_module']) ? $setting['title_module'] : 0;
		if(!empty($title_module[$data['lang_id']])){
			$data['heading_title'] = $title_module[$data['lang_id']];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}
		$data['ns_on_off_latest_model_product'] = $this->config->get('config_on_off_latest_model_product');
		$data['ns_on_off_latest_description'] = $this->config->get('config_on_off_latest_description');
		$data['ns_on_off_latest_slider_additional_image'] = $this->config->get('config_on_off_latest_slider_additional_image');
		$data['ns_select_latest_additional_animate_method'] = $this->config->get('config_select_latest_additional_animate_method');
		$data['ns_on_off_latest_rating'] = $this->config->get('config_on_off_latest_rating');
		$data['ns_on_off_latest_quantity_reviews'] = $this->config->get('config_on_off_latest_quantity_reviews');
		$data['ns_on_off_latest_fastorder'] = $this->config->get('config_on_off_latest_fastorder');
		$data['ns_on_off_latest_wishlist'] = $this->config->get('config_on_off_latest_wishlist');
		$data['ns_on_off_latest_compare'] = $this->config->get('config_on_off_latest_compare');
		$data['config_on_off_latest_quickview'] = $this->config->get('config_on_off_latest_quickview');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['button_cart'] = $this->language->get('button_cart');
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
		$data['required_text_option'] = $this->language->get('required_text_option');
		$data['change_text_cart_button_out_of_stock'] = $this->config->get('config_change_text_cart_button_out_of_stock');
		$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special');
		$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct');
		$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular');
		$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller');
		$this->language->load('product/product');
		$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
		$data['color_button_open_form_send_order'] = $this->config->get('config_color_button_open_form_send_order');
		$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
		$data['text_reviews_title'] = $this->language->get('text_reviews_title');
		$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
		$data['show_stock_status'] = $this->config->get('config_show_stock_status');
		$config_disable_cart_button_text = $this->config->get('config_disable_cart_button_text');
		if(!empty($config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'])){
			$data['disable_cart_button_text'] = $config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'];
		} else {
			$data['disable_cart_button_text'] = $this->language->get('disable_cart_button_text');
		}
		$data['show_options'] = $this->config->get('config_show_options_module');
		$show_options_required_featured = $this->config->get('config_show_options_required_module');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if(isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		if ($setting['filter_sub_category'] == 1) {
			$filter_sub_category = $setting['filter_sub_category'];
		} else {
			$filter_sub_category = null;
		}
		if ($setting['category_id']) {
			$filter_category_id = $setting['category_id'];
		} else {
			$filter_category_id = null;
		}

		$filter_data = array(
			'filter_sub_category' => $filter_sub_category,
			'filter_category_id' => $filter_category_id,
			'start' => ($page - 1) * $setting['limit'],
			'limit' => $setting['limit'],
			'limit_max' => $limit_max
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		if ($product_total > $limit_max) {
			$product_total = $limit_max;
		}
		$this->load->model('ns/newstore');
		$results = $this->model_ns_newstore->getLatest($filter_data);

		if ($results) {
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if (isset($product_info)) {
						$result = $product_info;
					} else {
						$result = $result;
					}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
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


					$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
					$additional_img = array();
					if($data['ns_on_off_latest_slider_additional_image'] =='2'){
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

					if($data['ns_on_off_latest_slider_additional_image'] =='2'){
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

				$data['products'][] = array(
					'sticker_new_prod'  => $sticker_new_prod,
					'product_quantity' 		=> $product_quantity,
					'stock_status' 			=> $stock_status,
					'additional_img' 		=> $additional_img,
					'additional_image_hover' => $additional_image_hover,
					'reviews'    			=> sprintf((int)$result['reviews']),
					'skidka'     			=> round($skidka),
					'model'     			=> $result['model'],
					'date_available'		=> $result['date_available'],
					'viewed'	 			=> $result['viewed'],
					'top_bestsellers'	 	=> $top_bestsellers['total'],
					'currency'	   			=> $this->session->data['currency'],
					'availability' 			=> $result['quantity'] ? true : false,
					'reviews'      			=> (int) $result['reviews'],
					'options'	  => $options,
					'date_end'			=> $special_date_end,
					'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


			$data['last_page'] = ceil($product_total / $setting['limit']);
			$data['nextPage'] = false;
			if ($page == 1) {
				if ($page == $data['last_page']) {
					$data['nextPage'] = false;
				} else {
					$data['nextPage'] = $page + 1;
				}
			} elseif ($page == $data['last_page']) {
				$data['nextPage'] = false;
			} else {
				$data['nextPage'] = $page +1;
			}
			if(isset($this->request->post['module'])) {
				$data['module'] = $this->request->post['module'];
			} else {
				$data['module'] = $module++;
			}

			$setting['title_module'] = '';
			$setting['name'] = '';
			$data['setting'] = base64_encode(serialize($setting));
			$data['all_prod'] = $this->model_ns_newstore->multi_implode(",", $data['products']);
			return $this->load->view('extension/module/latest_grid', $data);
		}
	}
}
