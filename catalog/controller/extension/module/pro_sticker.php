<?php
class ControllerExtensionModuleProSticker extends Controller {
	public function index() {
		$data['settings'] = $this->getSettings();
		
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = '';
		}
		
		$data['url_module'] = 'index.php?route=extension/module/pro_sticker/ProStickerLoad';
		return $this->load->view('extension/module/pro_sticker', $data);	
		
	}
	public function ProStickerLoad() {
		$lang_id = $this->config->get('config_language_id');
		$settings = $this->getSettings();
		
		$products_id = $products_url_alias = $stikers_ajax_product = array ();
		
		if (isset($this->request->post['prod_id_ajax']) && is_array($this->request->post['prod_id_ajax'])) {
			foreach ($this->request->post['prod_id_ajax'] as $key => $value) {
				$products_id[$key] = (int)$value;
			}
		}
		
		if (isset($this->request->post['url_product']) && is_array($this->request->post['url_product'])) {
			foreach ($this->request->post['url_product'] as $key => $value) {
				$products_url_alias[$key] = $this->db->escape(utf8_strtolower(urldecode($value)));
			}
		}
		
		if ($products_url_alias) {
			$query = $this->db->query('SELECT query, LCASE(keyword) AS keyword FROM ' . DB_PREFIX . 'seo_url WHERE keyword IN ("' . implode ('","', $products_url_alias) . '") AND query LIKE "product_id=%" AND language_id = ' . (int)$this->config->get('config_language_id') . '');
			
			foreach ($query->rows as $data) {
				foreach ($products_url_alias as $index=>$keyword) {
					if ($keyword == $data['keyword']) {
						$products_id[$index] = (int) str_replace ('product_id=', '', $data['query']);
						unset ($products_url_alias[$index]);
					}
				}
			}
		}
		
		if ($products_id) {
			if ($this->customer->isLogged()) {
			  $customer_group_id = $this->customer->getGroupId();
			} else {
			  $customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$query = $this->db->query('SELECT p.product_id AS product_id, p.viewed AS viewed, p.date_available AS date_available, p.quantity AS quantity, p.date_start_sticker AS date_start_sticker, p.date_end_sticker AS date_end_sticker, sl.images AS images,sl.text_label AS text_label, m.image AS manufacturer, ps.price AS special FROM ' . DB_PREFIX . 'product p LEFT JOIN ' . DB_PREFIX . 'stickers_list sl ON (sl.sticker_id = p.sticker_id) LEFT JOIN ' . DB_PREFIX . 'manufacturer m ON (m.manufacturer_id = p.manufacturer_id) LEFT JOIN ' . DB_PREFIX . 'product_special ps ON (ps.product_id = p.product_id AND ps.customer_group_id = "' . (int) $customer_group_id . '" AND ((ps.date_start = "0000-00-00" OR ps.date_start < NOW()) AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW()))) WHERE p.product_id IN (' . implode (',', array_unique ($products_id)) . ') AND p.date_available <= NOW() AND p.status = "1" ORDER BY ps.priority DESC, ps.price DESC');
			
			$results = array ();
			
			foreach ($query->rows as $value) {
				$results[$value['product_id']] = array (
					'images'         => $value['images'],
					'text_label'     => $value['text_label'],
					'quantity'       => $value['quantity'],
					'manufacturer'   => $value['manufacturer'],
					'special'        => $value['special'],
					'date_available' => $value['date_available'],
					'viewed' 		=> $value['viewed'],
					'date_start_sticker'     => $value['date_start_sticker'],
					'date_end_sticker'       => $value['date_end_sticker']
				);
			}
			
			if ($results) {
				$positions = array(
					'topleft' => 'top 5px left',
					'topcenter' => 'top 5px center',
					'topright' => 'top 5px right',
					'centerleft' => 'center left',
					'centercenter' => 'center center',
					'centerright' => 'center right',
					'bottomleft' => 'bottom left',
					'bottomcenter' => 'bottom center',
					'bottomright' => 'bottom right'
				);
				
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				
				foreach ($products_id as $index=>$product_id) {
					$astickers = '';
					
					if (isset ($results[$product_id])) {
						$positions_status = array ('topleft' => 1, 'topcenter' => 1, 'topright' => 1, 'centerleft' => 1, 'centercenter' => 1, 'centerright' => 1, 'bottomleft' => 1, 'bottomcenter' => 1, 'bottomright' => 1);
						
						if ($settings['special_status'] && $results[$product_id]['special'] && file_exists (DIR_IMAGE . $settings['special_image'])) {
							$astickers .= '<div class="pro_sticker" style="background:url(' . 'image/' . $settings['special_image'] . ') ' . $positions[$settings['special_position']] . ' no-repeat"></div>';
							$positions_status[$settings['special_position']] = 0;
						}
						
						if ($settings['new_status'] && file_exists (DIR_IMAGE . $settings['new_image'])) {
							$time = explode ('-', $results[$product_id]['date_available']);
							
							if (time () < (mktime (0, 0, 0, $time[1], $time[2], $time[0]) + ($settings['days_new'] * 86400))) {
								$astickers .= '<div class="pro_sticker" style="background:url(' . 'image/' . $settings['new_image'] . ') ' . $positions[$settings['new_position']] . ' no-repeat"></div>';
								$positions_status[$settings['new_position']] = 0;
							}
						}
						
						if ($settings['bestseller_status'] && file_exists (DIR_IMAGE . $settings['bestseller_image'])) {
							$bestsellers_total = $this->getTopSeller($product_id);
							
							if(($bestsellers_total >= $settings['limit_order'])) { 
								$astickers .= '<div class="pro_sticker" style="background:url(' . 'image/' . $settings['bestseller_image'] . ') ' . $positions[$settings['bestseller_position']] . ' no-repeat"></div>';
								$positions_status[$settings['bestseller_position']] = 0;
							}
						}
						
						
						if ($settings['popular_status'] && file_exists (DIR_IMAGE . $settings['popular_image'])) {
							$limit_viewed = $results[$product_id]['viewed'];
							if(($limit_viewed >= $settings['limit_viewed'])) { 
								$astickers .= '<div class="pro_sticker" style="background:url(' . 'image/' . $settings['popular_image'] . ') ' . $positions[$settings['popular_position']] . ' no-repeat"></div>';
								$positions_status[$settings['popular_position']] = 0;
							}
						}
					
						
					
						if ($settings['quantity_status'] && $settings['quantity']) {
							foreach ($settings['quantity'] as $quantity) {
								if (($results[$product_id]['quantity'] >= $quantity['min']) && ($results[$product_id]['quantity'] <= $quantity['max'])) {
									$astickers .= '<div class="pro_sticker" style="background:url(' . 'image/' . $quantity['image'] . ') ' . $positions[$settings['quantity_position']] . ' no-repeat"></div>';
									$positions_status[$settings['quantity_position']] = 0;
									
									break;
								}
							}
						}
						
						if ($settings['manufacturer_status'] && file_exists (DIR_IMAGE . $results[$product_id]['manufacturer'])) {
							$astickers .= '<div class="pro_sticker" style="background:url(' . $this->model_tool_image->resize($results[$product_id]['manufacturer'], $settings['width'], $settings['height']) . ') ' . $positions[$settings['manufacturer_position']] . ' no-repeat"></div>';
							$positions_status[$settings['manufacturer_position']] = 0;
						}
						
						
						if ((date ('Y-m-d') >= $results[$product_id]['date_start_sticker']) && (date ('Y-m-d') <= $results[$product_id]['date_end_sticker']) || (($results[$product_id]['date_start_sticker'] == '0000-00-00') && ($results[$product_id]['date_end_sticker'] == '0000-00-00'))) {
							
							$images = json_decode($results[$product_id]['images'], true);
							$text_label = json_decode($results[$product_id]['text_label'], true);
							if (isset($text_label[$lang_id])) {
								foreach ($text_label[$lang_id] as $label) {
									$astickers .= '<div class="sticker_label '. $label['position'] .'" style="background:#'. $label['bg_color'] .'; color:#'. $label['text_color'] .'">'. $label['text'] .'</div>';	
									$positions_status[$label['position']] = 0;
								}
							}
							
							if ($images) {
								foreach ($positions as $position=>$value) {
									if ($positions_status[$position] && !empty($images[$lang_id][$position]) && file_exists (DIR_IMAGE . $images[$lang_id][$position])) {
										$astickers .= '<div class="pro_sticker" style="background:url(' . '../image/' . $images[$lang_id][$position] . ') ' . $value . ' no-repeat"></div>';
									}
								}
							}
						}
					}
					
					$stikers_ajax_product[$index] = $astickers;
				}
			}
		}
		
		header ('Content-type: text/html; charset=utf-8');
		
		echo json_encode($stikers_ajax_product);
	}
	private function getTopSeller($product_id) {
		$query = $this->db->query("SELECT SUM(quantity) AS total FROM " . DB_PREFIX . "order_product op WHERE op.product_id = '". (int)$product_id ."'");
		return ($query->row['total'] ? $query->row['total'] : 0);
	}
	private function getSettings() {
		$settings = $this->config->get('module_pro_sticker_settings');
		$default = array (
			'class' => '.image',
			'class_main_image' => '.thumbnails li:first-child a.thumbnail',
			'min_width' => 40,
			'min_height' => 40,
			'hide_hover' => 1,
			'z_index' => 0,
			'width' => 40,
			'height' => 40,
			'days_new' => 30,
			'popular_status' => 0,
			'popular_position' => 'bottomleft',
			'popular_image' => '',
			'limit_viewed' => 15,
			'bestseller_status' => 0,
			'bestseller_position' => 'topright',
			'bestseller_image' => '',
			'limit_order' => 10,
			'special_status' => 0,
			'special_position' => 'topleft',
			'special_image' => '',
			'new_status' => 0,
			'new_position' => 'bottomright',
			'new_image' => '',
			'quantity_status' => 0,
			'quantity_position' => 'topright',
			'manufacturer_status' => 0,
			'manufacturer_position' => 'bottomleft',
			'quantity' => array(array('min' => -10, 'max' => 0, 'image' => ''), array ('min' => 1, 'max' => 50, 'image' => ''), array ('min' => 51, 'max' => 100, 'image' => ''), array ('min' => 101, 'max' => 150, 'image' => ''), array ('min' => 151, 'max' => 200, 'image' => ''), array ('min' => 201, 'max' => 1000, 'image' => '')));
		
		foreach ($default as $setting=>$value) {
			if (!isset($settings[$setting])) {
				$settings[$setting] = $value;
			}
		}
		
		return $settings;
	}
}