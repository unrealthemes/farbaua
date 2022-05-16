<?php
class ControllerExtensionFeedGoogleMerchantXml extends Controller {
	
	public function index() {
		$this->load->language('extension/feed/google_merchant_xml');

		$this->document->setTitle($this->language->get('heading_title'));

		if (version_compare(VERSION,'3.0.0.0', '>=')) {
			$token = 'user_token=' . $this->session->data['user_token'];
			$extension = 'marketplace/extension';
		} else {
			$token = 'token=' . $this->session->data['token'];
			$extension = 'extension/extension';
		}

		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_merchant_xml', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link($extension, $token . '&type=feed', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_google_merchant_xml'] = $this->language->get('text_google_merchant_xml');
		$data['text_latest'] = $this->language->get('text_latest'); 
		$data['text_bestseller'] = $this->language->get('text_bestseller'); 
		$data['text_min_price'] = $this->language->get('text_min_price'); 
		$data['text_max_price'] = $this->language->get('text_max_price'); 
		$data['text_viewed'] = $this->language->get('text_viewed'); 
		$data['text_price_from_to'] = $this->language->get('text_price_from_to'); 
		$data['entry_status'] = $this->language->get('entry_status'); 
		$data['entry_limit'] = $this->language->get('entry_limit'); 
		$data['entry_title'] = $this->language->get('entry_title'); 
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_google_merchant_xml_original_image_status'] = $this->language->get('entry_google_merchant_xml_original_image_status');
		$data['entry_google_merchant_xml_key'] = $this->language->get('entry_google_merchant_xml_key');
		$data['entry_google_merchant_xml_additional_images'] = $this->language->get('entry_google_merchant_xml_additional_images');
		$data['entry_google_merchant_xml_multiplier'] = $this->language->get('entry_google_merchant_xml_multiplier');
		$data['entry_google_merchant_xml_condition'] = $this->language->get('entry_google_merchant_xml_condition');
		$data['entry_google_merchant_xml_utm'] = $this->language->get('entry_google_merchant_xml_utm');
		$data['entry_google_merchant_xml_currency'] = $this->language->get('entry_google_merchant_xml_currency');
		$data['entry_google_merchant_xml_special'] = $this->language->get('entry_google_merchant_xml_special');
		$data['entry_google_merchant_xml_min_price'] = $this->language->get('entry_google_merchant_xml_min_price');
		$data['entry_google_merchant_xml_max_price'] = $this->language->get('entry_google_merchant_xml_max_price');
		$data['entry_google_merchant_xml_zero_quantity'] = $this->language->get('entry_google_merchant_xml_zero_quantity');
		$data['entry_google_merchant_xml_gtin'] = $this->language->get('entry_google_merchant_xml_gtin');
		$data['entry_google_merchant_xml_mpn'] = $this->language->get('entry_google_merchant_xml_mpn');
		$data['entry_google_merchant_xml_original_description'] = $this->language->get('entry_google_merchant_xml_original_description');
		$data['entry_google_merchant_xml_custom_sql'] = $this->language->get('entry_google_merchant_xml_custom_sql');
		$data['entry_google_merchant_xml_links'] = $this->language->get('entry_google_merchant_xml_links');
		$data['link_merchant'] = HTTPS_CATALOG . 'index.php?route=extension/feed/google_merchant_xml';
		$data['link_facebook'] = HTTPS_CATALOG . 'index.php?route=extension/feed/google_merchant_xml&target=facebook';
		$data['link_cron_merchant'] = HTTPS_CATALOG . 'xml_feed/google_merchant_xml.xml';
		$data['link_cron_facebook'] = HTTPS_CATALOG . 'xml_feed/google_merchant_xml_facebook.xml';
		$data['link_cron'] = realpath (DIR_APPLICATION . '../xml_feed/google_merchant_xml.php');
		$data['text_help_link'] = $this->language->get('text_help_link');
		$data['text_feed_cron'] = $this->language->get('text_feed_cron');
		$data['text_not_selected'] = $this->language->get('text_not_selected');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_category_google'] = $this->language->get('text_category_google');
		$data['text_feed_merchant'] = $this->language->get('text_feed_merchant');
		$data['text_feed_facebook'] = $this->language->get('text_feed_facebook');
		$data['text_feed_help'] = $this->language->get('text_feed_help');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_id'] = $this->language->get('text_id');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_identifier'] = $this->language->get('text_identifier');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['text_id'] = $this->language->get('text_id');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_identifier'] = $this->language->get('text_identifier');
		$data['text_credits'] = $this->language->get('text_credits');

		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($extension, $token . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/google_merchant_xml', $token, true)
		);

		$data['action'] = $this->url->link('extension/feed/google_merchant_xml', $token, true);

		$data['cancel'] = $this->url->link($extension, $token . '&type=module', true);

		$this->load->model('localisation/currency');
		
		$currencies = $this->model_localisation_currency->getCurrencies();
		
		$data['currencies'] = $currencies;

		// Categories
		$this->load->model('catalog/category');

		$filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);

		$data['categories'] = $this->model_catalog_category->getCategories($filter_data);
		
		// Manufacturers
		$this->load->model('catalog/manufacturer');
		
		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC',
		);

		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers($filter_data);
		
		if (isset($this->request->post['google_merchant_xml_status'])) {
			$data['google_merchant_xml_status'] = $this->request->post['google_merchant_xml_status'];
		} else {
			$data['google_merchant_xml_status'] = $this->config->get('google_merchant_xml_status');
		}

		if (isset($this->request->post['google_merchant_xml_currency'])) {
			$data['google_merchant_xml_currency'] = $this->request->post['google_merchant_xml_currency'];
		} else {
			$data['google_merchant_xml_currency'] = $this->config->get('google_merchant_xml_currency');
		}

		if (isset($this->request->post['google_merchant_xml_identifier'])) {
			$data['google_merchant_xml_identifier'] = $this->request->post['google_merchant_xml_identifier'];
		} else {
			$data['google_merchant_xml_identifier'] = $this->config->get('google_merchant_xml_identifier');
		}

		if (isset($this->request->post['google_merchant_xml_category'])) {
			$data['google_merchant_xml_category'] = $this->request->post['google_merchant_xml_category'];
		} else if($this->config->get('google_merchant_xml_category')) {
			$data['google_merchant_xml_category'] = $this->config->get('google_merchant_xml_category');
		} else {
			$data['google_merchant_xml_category'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_manufacturer'])) {
			$data['google_merchant_xml_manufacturer'] = $this->request->post['google_merchant_xml_manufacturer'];
		} else if($this->config->get('google_merchant_xml_manufacturer')) {
			$data['google_merchant_xml_manufacturer'] = $this->config->get('google_merchant_xml_manufacturer');
		} else {
			$data['google_merchant_xml_manufacturer'] = [];
		}
		
		$this->load->model('customer/customer_group');
		
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['google_merchant_xml_customer_group'])) {
			$data['google_merchant_xml_customer_group'] = $this->request->post['google_merchant_xml_customer_group'];
		} else {
			$data['google_merchant_xml_customer_group'] = $this->config->get('google_merchant_xml_customer_group');
		}

		if (isset($this->request->post['google_merchant_xml_key'])) {
			$data['google_merchant_xml_key'] = $this->request->post['google_merchant_xml_key'];
		} else {
			$data['google_merchant_xml_key'] = $this->config->get('google_merchant_xml_key');
		}

		if (isset($this->request->post['google_merchant_xml_condition'])) {
			$data['google_merchant_xml_condition'] = $this->request->post['google_merchant_xml_condition'];
		} elseif ($this->config->get('google_merchant_xml_condition')) {
			$data['google_merchant_xml_condition'] = $this->config->get('google_merchant_xml_condition');
		} else {
			$data['google_merchant_xml_condition'] = 'new';
		}

		if (isset($this->request->post['google_merchant_xml_gtin'])) {
			$data['google_merchant_xml_gtin'] = $this->request->post['google_merchant_xml_gtin'];
		} else {
			$data['google_merchant_xml_gtin'] = $this->config->get('google_merchant_xml_gtin');
		}

		if (isset($this->request->post['google_merchant_xml_mpn'])) {
			$data['google_merchant_xml_mpn'] = $this->request->post['google_merchant_xml_mpn'];
		} else {
			$data['google_merchant_xml_mpn'] = $this->config->get('google_merchant_xml_mpn');
		}

		if (isset($this->request->post['google_merchant_xml_special'])) {
			$data['google_merchant_xml_special'] = $this->request->post['google_merchant_xml_special'];
		} else {
			$data['google_merchant_xml_special'] = $this->config->get('google_merchant_xml_special');
		}

		if (isset($this->request->post['google_merchant_xml_min_price'])) {
			$data['google_merchant_xml_min_price'] = $this->request->post['google_merchant_xml_min_price'];
		} else {
			$data['google_merchant_xml_min_price'] = $this->config->get('google_merchant_xml_min_price');
		}

		if (isset($this->request->post['google_merchant_xml_max_price'])) {
			$data['google_merchant_xml_max_price'] = $this->request->post['google_merchant_xml_max_price'];
		} else {
			$data['google_merchant_xml_max_price'] = $this->config->get('google_merchant_xml_max_price');
		}

		if (isset($this->request->post['google_merchant_xml_zero_quantity'])) {
			$data['google_merchant_xml_zero_quantity'] = $this->request->post['google_merchant_xml_zero_quantity'];
		} else {
			$data['google_merchant_xml_zero_quantity'] = $this->config->get('google_merchant_xml_zero_quantity');
		}

		if (isset($this->request->post['google_merchant_xml_original_description'])) {
			$data['google_merchant_xml_original_description'] = $this->request->post['google_merchant_xml_original_description'];
		} else {
			$data['google_merchant_xml_original_description'] = $this->config->get('google_merchant_xml_original_description');
		}

		if (isset($this->request->post['google_merchant_xml_multiplier'])) {
			$data['google_merchant_xml_multiplier'] = $this->request->post['google_merchant_xml_multiplier'];
		} elseif ($this->config->get('google_merchant_xml_multiplier')) {
			$data['google_merchant_xml_multiplier'] = $this->config->get('google_merchant_xml_multiplier');
		} else {
			$data['google_merchant_xml_multiplier'] = 1;
		}

		if (isset($this->request->post['google_merchant_xml_original_image_status'])) {
			$data['google_merchant_xml_original_image_status'] = $this->request->post['google_merchant_xml_original_image_status'];
		} else {
			$data['google_merchant_xml_original_image_status'] = $this->config->get('google_merchant_xml_original_image_status');
		}

		if (isset($this->request->post['google_merchant_xml_additional_images'])) {
			$data['google_merchant_xml_additional_images'] = $this->request->post['google_merchant_xml_additional_images'];
		} else {
			$data['google_merchant_xml_additional_images'] = $this->config->get('google_merchant_xml_additional_images');
		}
 
		if (isset($this->request->post['google_merchant_xml_utm'])) {
			$data['google_merchant_xml_utm'] = $this->request->post['google_merchant_xml_utm'];
		} else {
			$data['google_merchant_xml_utm'] = $this->config->get('google_merchant_xml_utm');
		}

		if (isset($this->request->post['google_merchant_xml_custom_sql'])) {
			$data['google_merchant_xml_custom_sql'] = $this->request->post['google_merchant_xml_custom_sql'];
		} else {
			$data['google_merchant_xml_custom_sql'] = $this->config->get('google_merchant_xml_custom_sql');
		}

		if (isset($this->request->post['google_merchant_xml_category_google_category'])) {
			$data['google_merchant_xml_category_google_category'] = $this->request->post['google_merchant_xml_category_google_category'];
		} elseif ($this->config->get('google_merchant_xml_category_google_category')) {
			$data['google_merchant_xml_category_google_category'] = $this->config->get('google_merchant_xml_category_google_category');
		} else {
			$data['google_merchant_xml_category_google_category'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_product_type'])) {
			$data['google_merchant_xml_category_product_type'] = $this->request->post['google_merchant_xml_category_product_type'];
		} elseif ($this->config->get('google_merchant_xml_category_product_type')) {
			$data['google_merchant_xml_category_product_type'] = $this->config->get('google_merchant_xml_category_product_type');
		} else {
			$data['google_merchant_xml_category_product_type'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_condition'])) {
			$data['google_merchant_xml_category_condition'] = $this->request->post['google_merchant_xml_category_condition'];
		} elseif ($this->config->get('google_merchant_xml_category_condition')) {
			$data['google_merchant_xml_category_condition'] = $this->config->get('google_merchant_xml_category_condition');
		} else {
			$data['google_merchant_xml_category_condition'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_custom_label_0'])) {
			$data['google_merchant_xml_category_custom_label_0'] = $this->request->post['google_merchant_xml_category_custom_label_0'];
		} elseif ($this->config->get('google_merchant_xml_category_custom_label_0')) {
			$data['google_merchant_xml_category_custom_label_0'] = $this->config->get('google_merchant_xml_category_custom_label_0');
		} else {
			$data['google_merchant_xml_category_custom_label_0'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_custom_label_1'])) {
			$data['google_merchant_xml_category_custom_label_1'] = $this->request->post['google_merchant_xml_category_custom_label_1'];
		} elseif ($this->config->get('google_merchant_xml_category_custom_label_1')) {
			$data['google_merchant_xml_category_custom_label_1'] = $this->config->get('google_merchant_xml_category_custom_label_1');
		} else {
			$data['google_merchant_xml_category_custom_label_1'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_custom_label_2'])) {
			$data['google_merchant_xml_category_custom_label_2'] = $this->request->post['google_merchant_xml_category_custom_label_2'];
		} elseif ($this->config->get('google_merchant_xml_category_custom_label_2')) {
			$data['google_merchant_xml_category_custom_label_2'] = $this->config->get('google_merchant_xml_category_custom_label_2');
		} else {
			$data['google_merchant_xml_category_custom_label_2'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_custom_label_3'])) {
			$data['google_merchant_xml_category_custom_label_3'] = $this->request->post['google_merchant_xml_category_custom_label_3'];
		} elseif ($this->config->get('google_merchant_xml_category_custom_label_3')) {
			$data['google_merchant_xml_category_custom_label_3'] = $this->config->get('google_merchant_xml_category_custom_label_3');
		} else {
			$data['google_merchant_xml_category_custom_label_3'] = [];
		}
		
		if (isset($this->request->post['google_merchant_xml_category_custom_label_4'])) {
			$data['google_merchant_xml_category_custom_label_4'] = $this->request->post['google_merchant_xml_category_custom_label_4'];
		} elseif ($this->config->get('google_merchant_xml_category_custom_label_4')) {
			$data['google_merchant_xml_category_custom_label_4'] = $this->config->get('google_merchant_xml_category_custom_label_4');
		} else {
			$data['google_merchant_xml_category_custom_label_4'] = [];
		}

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/google_merchant_xml', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/google_merchant_xml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}