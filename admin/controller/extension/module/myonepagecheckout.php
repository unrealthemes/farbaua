<?php
class ControllerExtensionModuleMyonepagecheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/myonepagecheckout');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/module');

		$this->load->model('extension/module/myonepagecheckout');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_module_myonepagecheckout->editSettingMyonepagecheckout('module_myonepagecheckout', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
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
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/myonepagecheckout', 'user_token=' . $this->session->data['user_token'], true)
		);	

		$data['action'] = $this->url->link('extension/module/myonepagecheckout', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['token'] = $this->session->data['user_token'];
		$data['text_tab_details'] = $this->language->get('text_tab_details');
		$data['text_tab_address'] = $this->language->get('text_tab_address');
		$data['text_tab_delivery'] = $this->language->get('text_tab_delivery');
		$data['text_tab_shipping_method'] = $this->language->get('text_tab_shipping_method');
		$data['text_tab_payment_method'] = $this->language->get('text_tab_payment_method');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled_required'] = $this->language->get('text_enabled_required');
		$data['text_enabled_no_required'] = $this->language->get('text_enabled_no_required');
		$data['text_details_last_name'] = $this->language->get('text_details_last_name');
		$data['text_details_payment_email'] = $this->language->get('text_details_payment_email');
		$data['text_details_telephone'] = $this->language->get('text_details_telephone');
		$data['text_details_payment_fax'] = $this->language->get('text_details_payment_fax');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_status_description'] = $this->language->get('text_status_description');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');		
		
		if (isset($this->request->post['module_myonepagecheckout_status'])) {
			$data['module_myonepagecheckout_status'] = $this->request->post['module_myonepagecheckout_status'];
		} else {
			$data['module_myonepagecheckout_status'] = $this->config->get('module_myonepagecheckout_status');
		}
		if (isset($this->request->post['details_last_name'])) {
			$data['details_last_name'] = $this->request->post['details_last_name'];
		} else {
			$data['details_last_name'] = $this->config->get('details_last_name');
		}
		if (isset($this->request->post['details_payment_email'])) {
			$data['details_payment_email'] = $this->request->post['details_payment_email'];
		} else {
			$data['details_payment_email'] = $this->config->get('details_payment_email');
		}
		if (isset($this->request->post['details_telephone'])) {
			$data['details_telephone'] = $this->request->post['details_telephone'];
		} else {
			$data['details_telephone'] = $this->config->get('details_telephone');
		}
		if (isset($this->request->post['details_payment_fax'])) {
			$data['details_payment_fax'] = $this->request->post['details_payment_fax'];
		} else {
			$data['details_payment_fax'] = $this->config->get('details_payment_fax');
		}
		
		$data['text_address_company'] = $this->language->get('text_address_company');
		$data['text_address_address_1'] = $this->language->get('text_address_address_1');
		$data['text_address_address_2'] = $this->language->get('text_address_address_2');
		$data['text_address_city'] = $this->language->get('text_address_city');
		$data['text_address_postcode'] = $this->language->get('text_address_postcode');
		
		if (isset($this->request->post['address_payment_company'])) {
			$data['address_payment_company'] = $this->request->post['address_payment_company'];
		} else {
			$data['address_payment_company'] = $this->config->get('address_payment_company');
		}
		if (isset($this->request->post['address_payment_address_1'])) {
			$data['address_payment_address_1'] = $this->request->post['address_payment_address_1'];
		} else {
			$data['address_payment_address_1'] = $this->config->get('address_payment_address_1');
		}
		if (isset($this->request->post['address_payment_address_2'])) {
			$data['address_payment_address_2'] = $this->request->post['address_payment_address_2'];
		} else {
			$data['address_payment_address_2'] = $this->config->get('address_payment_address_2');
		}
		if (isset($this->request->post['address_payment_city'])) {
			$data['address_payment_city'] = $this->request->post['address_payment_city'];
		} else {
			$data['address_payment_city'] = $this->config->get('address_payment_city');
		}
		if (isset($this->request->post['address_payment_postcode'])) {
			$data['address_payment_postcode'] = $this->request->post['address_payment_postcode'];
		} else {
			$data['address_payment_postcode'] = $this->config->get('address_payment_postcode');
		}
		
		if (isset($this->request->post['address_shipping_address'])) {
			$data['address_shipping_address'] = $this->request->post['address_shipping_address'];
		} else {
			$data['address_shipping_address'] = $this->config->get('address_shipping_address');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/myonepagecheckout', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/myonepagecheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function uninstall() {
		$code = 'myonepagecheckout';
		$store_id = 0;
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}
}