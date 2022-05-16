<?php
class ControllerExtensionModuleAutosearch extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/autosearch');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('extension/module/autosearch');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_module_autosearch->editSettingAutoSearch('autosearch', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/autosearch', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/autosearch', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/autosearch', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/autosearch', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['entry_on_off_autosearch'] = $this->language->get('entry_on_off_autosearch');
		$data['text_search_model'] = $this->language->get('text_search_model');
		$data['text_search_tag'] = $this->language->get('text_search_tag');
		$data['text_search_manufacturer'] = $this->language->get('text_search_manufacturer');
		$data['text_search_upc'] = $this->language->get('text_search_upc');
		$data['text_search_sku'] = $this->language->get('text_search_sku');
		$data['text_display_image'] = $this->language->get('text_display_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['text_display_model'] = $this->language->get('text_display_model');
		$data['text_display_manufacturer'] = $this->language->get('text_display_manufacturer');
		$data['text_display_price'] = $this->language->get('text_display_price');
		$data['text_display_rating'] = $this->language->get('text_display_rating');
		$data['text_display_stock'] = $this->language->get('text_display_stock');
		
		if (isset($this->request->post['autosearch_on_off'])) {
			$data['autosearch_on_off'] = $this->request->post['autosearch_on_off'];
		} else {
			$data['autosearch_on_off'] = $this->config->get('autosearch_on_off');
		}
		if (isset($this->request->post['search_model_on_off'])) {
			$data['search_model_on_off'] = $this->request->post['search_model_on_off'];
		} else {
			$data['search_model_on_off'] = $this->config->get('search_model_on_off');
		}
		if (isset($this->request->post['search_tag_on_off'])) {
			$data['search_tag_on_off'] = $this->request->post['search_tag_on_off'];
		} else {
			$data['search_tag_on_off'] = $this->config->get('search_tag_on_off');
		}
		if (isset($this->request->post['search_manufacturer_on_off'])) {
			$data['search_manufacturer_on_off'] = $this->request->post['search_manufacturer_on_off'];
		} else {
			$data['search_manufacturer_on_off'] = $this->config->get('search_manufacturer_on_off');
		}
		if (isset($this->request->post['search_upc_on_off'])) {
			$data['search_upc_on_off'] = $this->request->post['search_upc_on_off'];
		} else {
			$data['search_upc_on_off'] = $this->config->get('search_upc_on_off');
		}
		if (isset($this->request->post['search_sku_on_off'])) {
			$data['search_sku_on_off'] = $this->request->post['search_sku_on_off'];
		} else {
			$data['search_sku_on_off'] = $this->config->get('search_sku_on_off');
		}
		
		if (isset($this->request->post['display_image_on_off'])) {
			$data['display_image_on_off'] = $this->request->post['display_image_on_off'];
		} else {
			$data['display_image_on_off'] = $this->config->get('display_image_on_off');
		}
		if (isset($this->request->post['image_search_width'])) {
			$data['image_search_width'] = $this->request->post['image_search_width'];
		}  else {
			$data['image_search_width'] = $this->config->get('image_search_width');
		}
		if (isset($this->request->post['image_search_height'])) {
			$data['image_search_height'] = $this->request->post['image_search_height'];
		} else {
			$data['image_search_height'] = $this->config->get('image_search_height');
		}
		if (isset($this->request->post['display_model_on_off'])) {
			$data['display_model_on_off'] = $this->request->post['display_model_on_off'];
		} else {
			$data['display_model_on_off'] = $this->config->get('display_model_on_off');
		}
		if (isset($this->request->post['display_manufacturer_on_off'])) {
			$data['display_manufacturer_on_off'] = $this->request->post['display_manufacturer_on_off'];
		} else {
			$data['display_manufacturer_on_off'] = $this->config->get('display_manufacturer_on_off');
		}
		if (isset($this->request->post['display_price_on_off'])) {
			$data['display_price_on_off'] = $this->request->post['display_price_on_off'];
		} else {
			$data['display_price_on_off'] = $this->config->get('display_price_on_off');
		}
		if (isset($this->request->post['display_rating_on_off'])) {
			$data['display_rating_on_off'] = $this->request->post['display_rating_on_off'];
		} else {
			$data['display_rating_on_off'] = $this->config->get('display_rating_on_off');
		}
		if (isset($this->request->post['display_stock_on_off'])) {
			$data['display_stock_on_off'] = $this->request->post['display_stock_on_off'];
		} else {
			$data['display_stock_on_off'] = $this->config->get('display_stock_on_off');
		}
	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/autosearch', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/autosearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function uninstall() {
		$code = 'autosearch';
		$store_id = 0;
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}
}