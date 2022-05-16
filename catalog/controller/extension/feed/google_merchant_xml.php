<?php
class ControllerExtensionFeedGoogleMerchantXml extends Controller {
	
	private $categories = array();
	private $eol = ''; 
	private $part_size = 7000;
	private	$sleep_time = 2;
	private	$product_images = [];
	private	$override_language = false;
	private	$override_currency = false;

	public function index() {
		
		if ($this->config->get('google_merchant_xml_status')) {
			$key = $this->config->get('google_merchant_xml_key');
		
			if (!empty($key) && (!isset($this->request->get['key']) || $this->request->get['key'] != $key)) {
				die;
			}
					
			$this->generateFeed();
		
		}
	}
	
	public function cron() {
		$dir = realpath (DIR_APPLICATION . '../');
		$filename = $dir . '/xml_feed/google_merchant_xml.xml';
		$filename_fb = $dir . '/xml_feed/google_merchant_xml_facebook.xml';
		
		$fp = fopen($filename, 'w');
		$fp_fb = fopen($filename_fb, 'w');
		if ($this->config->get('google_merchant_xml_status')) {
		$head  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
		$head .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . $this->eol;
		$head .= '<channel>' . $this->eol; 
		$head .= '<title>' . $this->config->get('config_name') . '</title>' . $this->eol ;
		$head .= '<link>' . HTTPS_SERVER . '</link>' . $this->eol ;
		$head .= '<description>' . $this->config->get('config_name') . '</description>' . $this->eol ;

		fwrite($fp, $head);
		fwrite($fp_fb, $head);
		
		if ($this->config->get('google_merchant_xml_additional_images')) {
			$this->product_images = $this->getImages();
		}
		
			$page = 0;
			$feed_part = $this->getXml($page);
			
			while ($feed_part != 'EMPTY') {
				fwrite($fp, $feed_part);
				fwrite($fp_fb, str_replace(['in_stock', 'out_of_stock'], ['in stock', 'out of stock'], $feed_part));
				unset($feed_part);
				sleep($this->sleep_time); 
				$page++;
				$feed_part = $this->getXml($page);
			}
			fwrite($fp, '</channel></rss>');
			fwrite($fp_fb, '</channel></rss>');
		}
		fclose($fp);
		fclose($fp_fb);
	}
	
	private function generateFeed() {
		
		header('Content-Type: application/xml');
		
		$this->load->model('localisation/currency');
		
		$this->eol = "\n";
		
		if (isset($this->request->get['currency'])) {
			$query = $this->db->query("SELECT currency_id FROM `" . DB_PREFIX . "currency` WHERE code = '" . $this->db->escape($this->request->get['currency']) . "' AND status = '1'"); 
			if ($query->num_rows) {
				$this->session->data['currency'] = $this->request->get['currency'];
				$this->override_currency = $this->request->get['currency'];
			} else {
				die;
			}
		}
		
		if (isset($this->request->get['language'])) {
			$query = $this->db->query("SELECT language_id FROM `" . DB_PREFIX . "language` WHERE code='" . $this->db->escape($this->request->get['language']) . "' AND status = '1'"); 
			if ($query->num_rows) {
				$this->session->data['language'] = $this->request->get['language'];
				$this->config->set('config_language_id', $query->row['language_id']);
				$this->config->set('config_language', $this->request->get['language']);
			} else {
				die;
			}
		}
		
		$head  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
		$head .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . $this->eol;
		$head .= '<channel>' . $this->eol;
		$head .= '<title>' . $this->config->get('config_name') . '</title>' . $this->eol ;
		$head .= '<link>' . HTTPS_SERVER . '</link>' . $this->eol ;
		$head .= '<description>' . $this->config->get('config_name') . '</description>' . $this->eol ;
		
		echo $head;
		
		if ($this->config->get('google_merchant_xml_additional_images')) {
			$this->product_images = $this->getImages();
		}
		
		$page = 0;
		$feed_part = $this->getXml($page);
		while ($feed_part != 'EMPTY') {
			echo $feed_part;
			unset($feed_part);
			sleep($this->sleep_time);
			$page++;
			$feed_part = $this->getXml($page);
		}
		
		echo '</channel></rss>';
	}

	private function getXml($page = 0) {
		$xml = '';
		
		$original_image_dir = HTTPS_SERVER . 'image/';
		
		$this->load->model('tool/image');

		$start = $page * $this->part_size;
		$limit = $this->part_size;
		
		$items_currency = $this->config->get('google_merchant_xml_currency');
		
		if ($this->override_currency) {
			$items_currency = $this->override_currency;
		}
		
		$shop_currency = $this->config->get('config_currency');
		$decimal = (int)$this->currency->getDecimalPlace($items_currency);
		
		$google_merchant_xml_category_google_category = $this->config->get('google_merchant_xml_category_google_category');
		$google_merchant_xml_category_product_type = $this->config->get('google_merchant_xml_category_product_type');
		$google_merchant_xml_category_condition = $this->config->get('google_merchant_xml_category_condition');
		$google_merchant_xml_category_custom_label_0 = $this->config->get('google_merchant_xml_category_custom_label_0');
		$google_merchant_xml_category_custom_label_1 = $this->config->get('google_merchant_xml_category_custom_label_1');
		$google_merchant_xml_category_custom_label_2 = $this->config->get('google_merchant_xml_category_custom_label_2');
		$google_merchant_xml_category_custom_label_3 = $this->config->get('google_merchant_xml_category_custom_label_3');
		$google_merchant_xml_category_custom_label_4 = $this->config->get('google_merchant_xml_category_custom_label_4');
		
		$id = $this->config->get('google_merchant_xml_identifier');
		$gtin = $this->config->get('google_merchant_xml_gtin');
		$mpn = $this->config->get('google_merchant_xml_mpn');
		$condition = $this->config->get('google_merchant_xml_condition');
		$utm = $this->config->get('google_merchant_xml_utm');
		$original_description = $this->config->get('google_merchant_xml_original_description');
		$original_images = $this->config->get('google_merchant_xml_original_image_status');
		$special = $this->config->get('google_merchant_xml_special');
		$multiplier = (float)$this->config->get('google_merchant_xml_multiplier') > 0 ? (float)$this->config->get('google_merchant_xml_multiplier') : 1;
		
		$facebook = !empty($this->request->get['target']) && $this->request->get['target'] == 'facebook';
		
		if (!empty($this->config->get('google_merchant_xml_category'))) {
			$categories = implode(',', $this->config->get('google_merchant_xml_category'));
		} else {
			$categories = false;
		}
		
		if (!empty($this->config->get('google_merchant_xml_manufacturer'))) {
			$manufacturers = implode(',', $this->config->get('google_merchant_xml_manufacturer'));
		} else {
			$manufacturers = false;
		}
		
		$empty = true;
		
		$xml = '';
		
		foreach ($this->getProducts($categories, $manufacturers, $special, $start, $limit) as $product) {
			$empty = false;

			$xml .= '<item>'. $this->eol; 
			$xml .= '<g:id>' . $product[$id] . '</g:id>' . $this->eol;
			$xml .= '<g:title>' . $this->prepareField($product['name']) . '</g:title>' . $this->eol;
			
			if ($original_description) {
				$xml .= '<g:description><![CDATA[' . html_entity_decode($product['description']) . ']]></g:description>' . $this->eol; 
			} else {
				$xml .= '<g:description><![CDATA[' . $this->prepareField($product['description']) . ']]></g:description>' . $this->eol; 
			}
			
			$link = $this->url->link('product/product', 'product_id=' . $product['product_id']);
			
			if ($utm) {
				$utm_to_link = str_replace(['{product_id}' , '{product_model}'], [$product['product_id'], $product['model']], $utm);
				$link .= (strpos($link, '?') == false ? '?' : '&') . $utm_to_link;
			}
			
			$xml .= '<g:link>' . $this->prepareField($link) . '</g:link>' . $this->eol;
			
			if ($original_images) {
				$parts = explode('/', $product['image']);
				$image_url = implode('/', array_map('rawurlencode', $parts));		
				$xml .= '<g:image_link>' . $original_image_dir . $image_url . '</g:image_link>' . $this->eol;
			} else {
				if (version_compare(VERSION,'3.0.0.0', '>=')) {
					$xml .= '<g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')) . '</g:image_link>' . $this->eol;
				} else {
					$xml .= '<g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . '</g:image_link>' . $this->eol;
				}
			}
			
			if (!empty($this->product_images[$product['product_id']])) {
				foreach ($this->product_images[$product['product_id']] as $image) {
					if ($original_images) {
						$parts = explode('/', $image);
						$image_url = implode('/', array_map('rawurlencode', $parts));		
						$xml .= '<g:additional_image_link>' . $original_image_dir . $image_url . '</g:additional_image_link>' . $this->eol;
					} else {
						if (version_compare(VERSION,'3.0.0.0', '>=')) {
							$xml .= '<g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')) . '</g:image_link>' . $this->eol;
						} else {
							$xml .= '<g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . '</g:image_link>' . $this->eol;
						}
					}
				}
			}
			
			if (!$facebook) {
				$xml .= '<g:availability>' . ($product['quantity'] > 0 ? 'in_stock' : 'out_of_stock') . '</g:availability>' . $this->eol;
			} else {
				$xml .= '<g:availability>' . ($product['quantity'] > 0 ? 'in stock' : 'out of stock') . '</g:availability>' . $this->eol;
			}
			
			if ($product['special'] && $product['special'] < $product['price']) {
				$xml .= '<g:price>' . number_format($this->currency->convert($this->tax->calculate($product['price'] * $multiplier, $product['tax_class_id']), $shop_currency, $items_currency), $decimal, '.', '') . ' ' . $items_currency . '</g:price>' . $this->eol;
				$xml .= '<g:sale_price>' . number_format($this->currency->convert($this->tax->calculate($product['special'] * $multiplier, $product['tax_class_id']), $shop_currency, $items_currency), $decimal, '.', '') . ' ' . $items_currency . '</g:sale_price>' . $this->eol;
			}	else {
				$xml .= '<g:price>' . number_format($this->currency->convert($this->tax->calculate($product['price'] * $multiplier, $product['tax_class_id']), $shop_currency, $items_currency), $decimal, '.', '') . ' ' . $items_currency . '</g:price>' . $this->eol;
			}
			
			if (!empty($google_merchant_xml_category_google_category[$product['category_id']])) {
				$xml .= '<g:google_product_category>' . $google_merchant_xml_category_google_category[$product['category_id']] . '</g:google_product_category>' . $this->eol;
			}
			
			if (!empty($google_merchant_xml_category_product_type[$product['category_id']])) {
				$xml .= '<g:product_type>' . $google_merchant_xml_category_product_type[$product['category_id']] . '</g:product_type>' . $this->eol;
			}
			
			$identifier = false;
			
			if (!empty($product['manufacturer'])) {
				$xml .= '<g:brand>' . $this->prepareField($product['manufacturer']) . '</g:brand>';
				$identifier = true;
			}
			
			if (!empty($product[$gtin])) {
				$xml .= '<g:gtin>' . $product[$gtin] . '</g:gtin>';
				$identifier = true;
			}
			
			if (!empty($product[$mpn])) {
				$xml .= '<g:mpn>' . $product[$mpn] . '</g:mpn>';
				$identifier = true;
			}
			
			if (!$identifier) {
				$xml .= '<g:identifier_exists>no</g:identifier_exists>';
			}
			
			if (!empty($google_merchant_xml_category_condition[$product['category_id']])) {
				$xml .= '<g:condition>' . $google_merchant_xml_category_condition[$product['category_id']] . '</g:condition>' . $this->eol;
			} else {
				$xml .= '<g:condition>' . $condition . '</g:condition>' . $this->eol;
			}
			
			if (!empty($google_merchant_xml_category_custom_label_0[$product['category_id']])) {
				$xml .= '<g:custom_label_0>' . $google_merchant_xml_category_custom_label_0[$product['category_id']] . '</g:custom_label_0>' . $this->eol;
			} 

			if (!empty($google_merchant_xml_category_custom_label_1[$product['category_id']])) {
				$xml .= '<g:custom_label_1>' . $google_merchant_xml_category_custom_label_1[$product['category_id']] . '</g:custom_label_1>' . $this->eol;
			} 

			if (!empty($google_merchant_xml_category_custom_label_2[$product['category_id']])) {
				$xml .= '<g:custom_label_2>' . $google_merchant_xml_category_custom_label_2[$product['category_id']] . '</g:custom_label_2>' . $this->eol;
			} 

			if (!empty($google_merchant_xml_category_custom_label_3[$product['category_id']])) {
				$xml .= '<g:custom_label_3>' . $google_merchant_xml_category_custom_label_3[$product['category_id']] . '</g:custom_label_3>' . $this->eol;
			} 

			if (!empty($google_merchant_xml_category_custom_label_0[$product['category_id']])) {
				$xml .= '<g:custom_label_4>' . $google_merchant_xml_category_custom_label_4[$product['category_id']] . '</g:custom_label_4>' . $this->eol;
			} 
			
			$xml .= '</item>'. $this->eol;
		}
		
		if ($empty) {
			return 'EMPTY';
		}
		return $xml;
	}

	private function prepareField($field) {

		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);
		
		$from = array('"', '&', '>', '<', '°', '\''); 
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&#176;', '&apos;');
		$field = str_replace($from, $to, $field);
		
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}
	
	private function getProducts($categories, $manufacturers, $special = false, $start = 0, $limit = 3000) {

		$customer_group = (int)$this->config->get('google_merchant_xml_customer_group');		
		$zero_quantity = $this->config->get('google_merchant_xml_zero_quantity');
		
		$min_price = (int)$this->config->get('google_merchant_xml_min_price');		
		$max_price = (int)$this->config->get('google_merchant_xml_max_price');
		$custom_xml = $this->config->get('google_merchant_xml_custom_sql');
	
		$sql = "SELECT p.*, pd.*, m.name AS manufacturer, " . (!$categories ? " NULL AS category_id" : " p2c.category_id ") . ", " . ($special ? " ps.price " : " NULL ") . " AS special FROM " . DB_PREFIX . "product p " . ($categories ? " JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id) " : " ") . " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) " . ($special ? " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id AND ps.customer_group_id = '" . $customer_group . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) " : " " ). " WHERE 1 " . ($categories ? " AND p2c.category_id IN (" . $this->db->escape($categories) . ")" : "")	. ($manufacturers ? " AND p.manufacturer_id IN (" . $this->db->escape($manufacturers) . ")" : "") . " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1'" . (!$zero_quantity ? " AND p.quantity > 0 " : "") . ($min_price > 0 ? " AND p.price >= " . $min_price . " " : "") .  ($max_price > 0 ? " AND p.price <= " . $max_price . " " : "") . (!empty($custom_xml) ? ' ' . $custom_xml . ' ' : "") . " AND p.price > 0 GROUP BY p.product_id ORDER BY p.product_id LIMIT " . $start . ", " . $limit;
		
		$result = $this->db->query($sql);
		
		return $result->rows;
	}
	
	private function getImages($limit = 10) {
		$images = [];
		$query = $this->db->query("SELECT pi.product_id, pi.image FROM `" . DB_PREFIX . "product_image` pi ORDER BY pi.product_id, pi.sort_order");
		
		foreach ($query->rows as $row) {
			if (empty($images[$row['product_id']])) {
				$images[$row['product_id']] = [];
			}
			if (count($images[$row['product_id']]) < $limit) $images[$row['product_id']][] = $row['image'];
		}
		
		return $images;
	}
}